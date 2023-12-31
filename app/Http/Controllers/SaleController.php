<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemsInVoucherResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VoucherRecordResource;
use App\Http\Resources\VoucherResource;
use App\Models\DailySale;
use App\Models\MonthlySale;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use App\Models\YearlySale;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class SaleController extends Controller
{

    public function productsList()
    {
        $products = Product::when(request()->has("keyword"), function ($query) {
            $query->where(function (Builder $builder) {
                $keyword = request()->keyword;

                $builder->where("name", "LIKE", "%" . $keyword . "%");
            });
        })
            ->when(request()->has("brandID"), function ($query) {
                $brandID = request()->brandID;
                $query->where("brand_id", $brandID);
            })
            ->when(request()->has("id"), function ($query) {
                $sortType = request()->id ?? "desc";
                $query->orderBy("id", $sortType);
            })
            ->paginate(10)
            ->withQueryString();

        $isSaleClose = DB::table("sale_close")->first()->sale_close;

        if ($products->isEmpty()) {
            return response()->json([
                "message" => "There is no product to sell yet."
            ],404);
        }

        return response()->json(
            [
                "products" => ProductResource::collection($products)->resource,
                "is_sale_close" => $isSaleClose,
            ],200);
    }

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
                ],500);
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
            ->when(request()->has("id"), function ($query) {
                $sortType = request()->id ?? "asc";
                $query->orderBy("id", $sortType);
            })
            ->paginate(5)
            ->withQueryString();
        // return $vouchers;
        $data = VoucherResource::collection($vouchers);
        return response()->json([
            "vouchers"=> $data->resource
        ],200);
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

            /* Getting today's 24 hours to get today's vouchers */
            $today = Carbon::today()->format("Y-m-d H:i:s");
            $now = Carbon::today()->format("Y-m-d ") . "23:59:59";

            /* Get today's vouchers */
            $vouchers = Voucher::whereBetween("created_at", [$today, $now])->get();
            $prodIDs = VoucherRecord::whereBetween("created_at", [$today, $now])->get()->pluck("product_id")->toArray();

            /* Calculating total of cost, tax and net total. */
            $total_actual_price = Product::whereIn("id", $prodIDs)->sum("actual_price");
            $total = array_sum($vouchers->pluck("total")->toArray());
            $tax = array_sum($vouchers->pluck("tax")->toArray());
            $netTotal = array_sum($vouchers->pluck("net_total")->toArray());

            /* Counting today's sales */
            $totalVocuhers = count($vouchers);

            DailySale::create(
                [
                    "vouchers" => $totalVocuhers,
                    "total_actual_price" => $total_actual_price,
                    "total" => $total,
                    "tax" => $tax,
                    "net_total" => $netTotal,
                ]
            );

            return response()->json(
                [
                    "message" => "ရက်ချုပ်လို့ ပြီးပါပြီခင်ဗျ။"
                ],200);
        }
    }

    public function createMonthlySale()
    {
        /* လချုပ်ချုပ်ဖို့ date  */
        $date = request()->date;
        /* Getting the daily sales between selected date to create monthly sals. */
        $dailySales = DailySale::where("created_at", "LIKE", "%" . $date . "%")->get();
        // return $dailySales;

        /* Calculating total vouchers, total cost, total tax and total final cost. */
        $totalVocuhers = array_sum($dailySales->pluck("vouchers")->toArray());
        $total_actual_price = array_sum($dailySales->pluck("total_actual_price")->toArray());
        $total = array_sum($dailySales->pluck("total")->toArray());
        $tax = array_sum($dailySales->pluck("tax")->toArray());
        $netTotal = array_sum($dailySales->pluck("net_total")->toArray());

        MonthlySale::create(
            [
                "vouchers" => $totalVocuhers,
                "total_actual_price" => $total_actual_price,
                "total" => $total,
                "tax" => $tax,
                "net_total" => $netTotal,
            ]
        );

        return response()->json(
            [
                "message" => "လချုပ်လို့ ပြီးပါပြီခင်ဗျ။"
            ],200);
    }

    public function createYearlySale()
    {
        $year = request()->year;
        $monthlySales = MonthlySale::where("created_at", "like", "%" . $year . "%")->get();

        $totalVocuhers = array_sum($monthlySales->pluck("vouchers")->toArray());
        $total_actual_price = array_sum($monthlySales->pluck("total_actual_price")->toArray());
        $total = array_sum($monthlySales->pluck("total")->toArray());
        $tax = array_sum($monthlySales->pluck("tax")->toArray());
        $netTotal = array_sum($monthlySales->pluck("net_total")->toArray());


        YearlySale::create(
            [
                "vouchers" => $totalVocuhers,
                "total_actual_price" => $total_actual_price,
                "total" => $total,
                "tax" => $tax,
                "net_total" => $netTotal,
            ]
        );

        return response()->json(
            [
                "message" => "နှစ်ချုပ်လို့ ပြီးပါပြီခင်ဗျ။"
            ],200);
    }
}
