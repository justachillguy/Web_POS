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
        // $productStocks = Product::when(request()->has("keyword"), function ($query) {
        //     $query->where(function (Builder $builder) {
        //         $keyword = request()->keyword;
        //         $builder->where("name", "LIKE", "%" . $keyword . "%");
        //     });
        // })
        //     ->when(request()->has("inStock"), function ($query) {
        //         $query->where("total_stock", ">", 30);
        //     })
        //     ->latest("id")
        //     ->paginate(4)
        //     ->withQueryString();

        // return response()->json(
        //     [
        //         "productStocks" => $productStocks,
        //     ]
        // );
    }
}
