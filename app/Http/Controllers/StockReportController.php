<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    public function stockReport()
    {
        $totoalProducts = Product::all()->count();
        $totalBrands = Brand::all()->count();
        $productStocks = Product::select("*")
            ->when(request()->has("inStock"), function ($query) {
                $query->where("total_stock", ">", 20);
            })
            ->when(request()->has("outOfStock"), function ($query) {
                $query->where("total_stock", 0);
            })
            ->when(request()->has("lowStock"), function ($query) {
                $query->whereBetween("total_stock", [500, 100]);
            })
            ->paginate(5);

        // $products = Product::all();
        // if (request()->has("lowStock")) {
        //     $productStocks = $products->whereBetween("total_stock", [20,1])->paginate(5);
        // }
        return response()->json(
            [
                "total_products" => $totoalProducts,
                "total_brands" => $totalBrands,
                "productStocks" => $productStocks,
            ]
        );
    }
}
