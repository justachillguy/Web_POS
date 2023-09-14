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
    // public function stockReport()
    // {
    //     $productStocks = Product::when(request()->has("keyword"), function ($query) {
    //         $query->where(function (Builder $builder) {
    //             $keyword = request()->keyword;
    //             $builder->where("name", "LIKE", "%" . $keyword . "%");
    //         });
    //     })
    //         ->when(request()->has("inStock"), function ($query) {
    //             $query->where("total_stock", ">", 30);
    //         })
    //         ->when(request()->has("lowStock"), function ($query) {
    //             $query->whereBetween("total_stock", [1, 30]);
    //         })
    //         ->when(request()->has("outOfStock"), function ($query) {
    //             $query->where("total_stock", 0);
    //         })
    //         ->latest("id")
    //         ->paginate(4)
    //         ->withQueryString();

    //     $totalProduct = Product::all()->count();
    //     $totalBrand = Brand::all()->count();

    //     return response()->json(
    //         [
    //             "productStocks" => $productStocks,
    //             "total_product" => $totalProduct,
    //             "total_brand" => $totalBrand,
    //         ]
    //     );
    // }
}
