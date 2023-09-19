<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductStockLevelResource;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Stock;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockReportController extends Controller
{
    public function stockLvlTable()
    {
        /* For stock report table  */
        $productStocks = Product::when(request()->has("keyword"), function ($query) {
            $query->where(function (Builder $builder) {
                $keyword = request()->keyword;
                $builder->where("name", "LIKE", "%" . $keyword . "%");
            });
        })
            ->when(request()->has('id'), function ($query) {
                $sortType = request()->id ?? 'asc';
                $query->orderBy("id", $sortType);
            })
            ->when(request()->has("in_stock"), function ($query) {
                $query->where("total_stock", ">", 30);
            })
            ->when(request()->has("low_stock"), function ($query) {
                $query->whereBetween("total_stock", [1, 30]);
            })
            ->when(request()->has("out_of_stock"), function ($query) {
                $query->where("total_stock", 0);
            })
            ->latest("id")
            ->paginate(5)
            ->withQueryString();

        return ProductStockLevelResource::collection($productStocks);
    }

    public function stockLvlBar()
    {
        /* Total product & brand report  */
        $totalProduct = Product::all()->count();
        $totalBrand = Brand::all()->count();

        /* For Stock Level Bar */
        $inStock = Product::where("total_stock", ">", 30)->count();
        $lowStock = Product::whereBetween("total_stock", [1, 30])->count();
        $outOfStock = $totalProduct - ($inStock + $lowStock);

        return response()->json(
            [
                "total_product" => $totalProduct,
                "total_brand" => $totalBrand,
                "stock_lvl_bar" => [
                    "in_stock" => [$inStock, round($inStock / $totalProduct, 4) * 100 . "%"],
                    "low_stock" => [$lowStock, round($lowStock / $totalProduct, 4) * 100 . "%"],
                    "out_of_stock" => [$outOfStock, round($outOfStock / $totalProduct, 4) * 100 . "%"],
                ]
            ]
        );
    }

    public function bestSellerBrands()
    {
        /* For weekly best seller brands  */
        $startDate = Carbon::now()->startOfWeek()->format("Y-m-d H:i:s");
        $endDate = Carbon::now()->endOfWeek()->format("Y-m-d H:i:s");

        $weeklyBestSellerProd = VoucherRecord::selectRaw("sum(quantity) as quantity, product_id")
            ->whereBetween("created_at", [$startDate, $endDate])
            ->groupBy("product_id")
            ->orderBy("quantity", "desc")
            ->limit(5)
            ->get()
            ->pluck("quantity", "product_id")
            ->toArray();

        $weeklyBestSellerBrands = [];
        foreach ($weeklyBestSellerProd as $prodID => $quantity) {
            $brandName = Brand::whereHas("products", function (Builder $query) use ($prodID) {
                $query->where("id", $prodID);
            })
                ->get()
                ->pluck("name")
                ->toArray();

            $weeklyBestSellerBrands[] = [
                "brand_name" => implode($brandName),
                "quantity" => $quantity,
            ];
        }

        /* Total cash for weekly best selling brands   */
        $weeklyBestSellerTotalCost = 0;
        foreach ($weeklyBestSellerProd as $prodID => $quantity) {
            $price = Product::where("id", $prodID)->value("sale_price");
            $cost = $price * $quantity;
            $weeklyBestSellerTotalCost += $cost;
        }

        return response()->json(
            [
                "weekly_best_seller_brands" => $weeklyBestSellerBrands,
                "weekly_total_cost" => $weeklyBestSellerTotalCost,
            ]
        );
    }
}
