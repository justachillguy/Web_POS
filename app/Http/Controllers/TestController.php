<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class TestController extends Controller
{
    public function test()
    {

        // // $date = Carbon::createFromDate("2023", "3", "23")->format("F jS Y");
        // $now = Carbon::now();
        // // $date = $now->addDays(20)->format("F jS Y");
        // $dates = [];
        // for ($i = 0; $i <= 30; $i++) {
        //     // $d = rand(1, 30);
        //     array_push($dates, Carbon::createFromDate("2023", "1", "1")->addDays($i));
        // }

        // $endDate = Carbon::now();
        // $startDate = Carbon::now()->subYears(2)->subMonths(3);
        // $period = CarbonPeriod::create($startDate, $endDate);


        // $info = DB::table("products")->selectRaw("sum(total_stock) as stock")->groupBy("brand_id")->orderBy("brand_id", "asc")->get();
        // $info = DB::table("voucher_records")->selectRaw("sum(quantity) as sales")->groupBy("product_id")->orderBy("product_id")->get();


        // $startDate = Carbon::now()->startOfWeek()->format("Y-m-d H:i:s");
        // $endDate = Carbon::now()->endOfWeek()->format("Y-m-d H:i:s");

        // $weeklyBestSellerProd = VoucherRecord::selectRaw("sum(quantity) as quantity, product_id")
        // ->whereBetween("created_at", [$startDate, $endDate])
        // ->groupBy("product_id")
        // ->orderBy("quantity", "desc")
        // ->limit(5)
        // ->get()
        // ->pluck("quantity", "product_id")
        // ->toArray();



        // $weeklyBestSellerBrands = [];
        // foreach ($weeklyBestSellerProd as $prodID => $quantity) {
        //     $brandName = Brand::whereHas("products", function (Builder $query) use ($prodID) {
        //         $query->where("id", $prodID);
        //     })
        //     ->get()
        //     ->pluck("name")
        //     ->toArray();

        //     $weeklyBestSellerBrands[] = [
        //         "brand_name" => implode($brandName),
        //         "quantity" => $quantity,
        //     ];
        // }

        // $weeklyBestSellerTotalCost = 0;

        // foreach ($weeklyBestSellerProd as $prodID => $quantity) {
        //     $price = Product::where("id", $prodID)->value("sale_price");
        //     $cost = $price * $quantity;
        //     $weeklyBestSellerTotalCost += $cost;
        // }

        // return response()->json(
        //     [
        //         "brands" => $weeklyBestSellerBrands,
        //         "total_cost" => $weeklyBestSellerTotalCost,
        //     ]
        // );


    }
}
