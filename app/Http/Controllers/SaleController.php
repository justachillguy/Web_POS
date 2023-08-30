<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemsInVoucherResource;
use App\Http\Resources\VoucherRecordResource;
use App\Http\Resources\VoucherResource;
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

    public function list()
    {
        $vouchers = Voucher::when(request()->has("keyword"), function ($query) {

            $keyword = request()->keyword;

            $query->where("voucher_number", "LIKE", "%" . $keyword . "%");
            $query->orWhere("created_at", "LIKE", "%" . $keyword . "%");
        })
            ->latest("id")
            ->paginate(10)
            ->withQueryString();

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
            DB::table("sale_close")->where("id", 1)->update([
                "sale_close" => true,
            ]);

            $today = Carbon::today()->addDay()->format("Y-m-d H:i:s");
            $now = Carbon::today()->addDay()->format("Y-m-d ") . "23:59:59";

            // $today = Carbon::today()->format("Y-m-d H:i:s");
            // $now = Carbon::now()->format("Y-m-d ") . "23:59:59";

            // return response()->json([
            //     "today" => $today,
            //     "now" => $now,
            // ]);
            $vouchers = Voucher::whereBetween("created_at", [$today, $now])->get();
            $cash = array_sum($vouchers->pluck("total")->toArray());
            $tax = array_sum($vouchers->pluck("tax")->toArray());
            $total = array_sum($vouchers->pluck("net_total")->toArray());
            $totalVocuhers = Voucher::selectRaw("count(*) as vouchers")->whereBetween("created_at", [$today, $now])->get();
            $NOV = collect($totalVocuhers)->pluck("vouchers")->all();
            $nov = implode("", $NOV);
            // return $nov;

            DB::table("daily_sale")->insert(
                [
                    "date" => Carbon::today(),
                    "vouchers" => $nov,
                    "cash" => $cash,
                    "tax" => $tax,
                    "total" => $total,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]
                );

            return response()->json(
                [
                    "message" => "ရက်ချုပ်လို့ ပြီးပါပြီခင်ဗျ။"
                ]
            );
        }
    }
}
