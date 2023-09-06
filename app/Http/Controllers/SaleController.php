<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemsInVoucherResource;
use App\Http\Resources\VoucherRecordResource;
use App\Http\Resources\VoucherResource;
use App\Models\DailySale;
use App\Models\MonthlySale;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class SaleController extends Controller
{
    public function checkout(Request $request)
    {

        try {
            DB::beginTransaction();

            //collect([array])
            $productIds = collect($request->items)->pluck("product_id");
            // return $productIds; //[1,2,3]
            $products = Product::whereIn("id", $productIds)->get();
            // dd($products);

            $total = 0;

            foreach ($request->items as $item) {
                $total += $item["quantity"] * $products->find($item["product_id"])->sale_price;
            }

            $tax = $total * 0.05;
            $netTotal = $total + $tax;

            $voucher = Voucher::create([
                "voucher_number" => fake()->uuid(),
                "total" => $total,
                "tax" => $tax,
                "net_total" => $netTotal,
                "user_id" => auth()->id(),
            ]);

            $records = [];

            foreach ($request->items as $item) {

                $currentProduct = $products->find($item["product_id"]);
                $records[] = [
                    "voucher_id" => $voucher->id,
                    "product_id" => $item["product_id"],

                    "quantity" => $item["quantity"],
                    "cost" => $item["quantity"] * $currentProduct->sale_price,
                    "created_at" => now(),
                    "updated_at" => now()
                ];
                Product::where("id", $item["product_id"])->update([
                    "total_stock" => $currentProduct->total_stock - $item["quantity"]
                ]);
            }

            VoucherRecord::insert($records); // use database
            $voucherRecords = VoucherRecord::where("voucher_id", $voucher->id)->get();

            DB::commit();

            return response()->json(
                [
                    "items" => ItemsInVoucherResource::collection($voucherRecords),
                    "total" => $total,
                    "tax" => $tax,
                    "net_total" => $netTotal
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return response()->json(
                [
                    "message" => "Caught Exception: " . $th->getMessage() . "on " . $th->getLine(),
                ],
                500
            );
        }
    }

    public function recentList()
    {
        $start = Carbon::today();
        $end = Carbon::now();
        // $vouchers = DB::table("vouchers")
        //     ->whereBetween("created_at", [$start, $end])
        //     ->paginate(5);

        $vouchers = Voucher::select("*")
        ->whereBetween("created_at", [$start, $end])
        ->paginate(5)
        ->withQueryString();
        // return $vouchers;
        return VoucherResource::collection($vouchers);
    }

    public function saleClose()
    {
        $isSaleClose = DB::table("sale_close")->first()->sale_close;

        /* Checking if sale_close status is close or not  */
        if ($isSaleClose) {
            DB::table("sale_close")->where("id", 1)->update([
                "sale_close" => false,
            ]);
        } else {

            /* If sale has been closed, close it and collect the lists of vouchers for today. */
            DB::table("sale_close")->where("id", 1)->update([
                "sale_close" => true,
            ]);

            // $today = Carbon::today()->addDay()->format("Y-m-d H:i:s");
            // $now = Carbon::today()->addDay()->format("Y-m-d ") . "23:59:59";

            $today = Carbon::today()->format("Y-m-d H:i:s");
            $now = Carbon::today()->format("Y-m-d ") . "23:59:59";

            // return response()->json([
            //     "today" => $today,
            //     "now" => $now,
            // ]);

            $vouchers = Voucher::whereBetween("created_at", [$today, $now])->get();
            // return $vouchers;

            $total = array_sum($vouchers->pluck("total")->toArray());
            $tax = array_sum($vouchers->pluck("tax")->toArray());
            $netTotal = array_sum($vouchers->pluck("net_total")->toArray());

            /* Counting today's sales */
            $totalVocuhers = count($vouchers);

            DailySale::create(
                [
                    "vouchers" => $totalVocuhers,
                    "total" => $total,
                    "tax" => $tax,
                    "net_total" => $netTotal,
                ]
            );

            return response()->json(
                [
                    "message" => "ရက်ချုပ်လို့ ပြီးပါပြီခင်ဗျ။"
                ]
            );
        }
    }

    public function createMonthlySale()
    {
        $date = request()->date;
        $dailySales = DailySale::where("created_at", "LIKE", "%" . $date . "%")->get();
        // return $dailySales;

        $totalVocuhers = array_sum($dailySales->pluck("vouchers")->toArray());
        // return $totalVocuhers;
        $total = array_sum($dailySales->pluck("total")->toArray());
        $tax = array_sum($dailySales->pluck("tax")->toArray());
        $netTotal = array_sum($dailySales->pluck("net_total")->toArray());

        MonthlySale::create(
            [
                "vouchers" => $totalVocuhers,
                "total" => $total,
                "tax" => $tax,
                "net_total" => $netTotal,
            ]
        );

        return response()->json(
            [
                "message" => "လချုပ်လို့ ပြီးပါပြီခင်ဗျ။"
            ]
        );
    }
}
