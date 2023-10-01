<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductReportResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class SaleReportController extends BaseController

{


    public function brandSale()
    {
        // $brand = Brand::find($brandId);
        // // return $brand;
        // $bestSellerBrand = $brand->bestSellerBrand;
        // // return $bestSellerBrand;
        // $brandName = $bestSellerBrand->first()->product->brand->name;
        // // return $brandName;
        // $count = count($bestSellerBrand);
        // //  return $count;
        // return response()->json([
        //     "brand's name" => $brandName,
        //     "Brand Counts" => $count
        // ]);


        $brands = Brand::withCount('brand')
            ->withSum('brand', 'cost')
            ->get();
        // return $brands;
        $brandInfo = [];

        foreach ($brands as $brand) {
            $brandName = $brand->name;
            $brandCount = $brand->brand_count;
            $brandSales = $brand->brand_sum_cost;

            $brandInfo[] = [
                'name' => $brandName,
                'count' => $brandCount,
                'sales' => $brandSales,
            ];
        }

        return response()->json([
            'brandsInfo' => $brandInfo
        ]);
    }

    public function productReport()
    {
        $product = Product::paginate(4)->withQueryString();
        $data = ProductReportResource::collection($product);
        return $data->resource;
    }

    public function todaySaleReport()
    {

        $today = Carbon::today()->format("Y-m-d H:i:s");
        $now = Carbon::today()->format("Y-m-d ") . "23:59:59";

        $vouchers = Voucher::whereBetween("created_at", [$today, $now])->get();
        // return $vouchers;
        $total = array_sum($vouchers->pluck("total")->toArray());
        $average = $vouchers->avg('net_total');
        $averageAmount = round($average,2);
        // return $average;

        $maxSale = Voucher::whereBetween("created_at", [$today, $now])->orderByDesc('net_total')->first();
        // return $maxSale;
        $maxVoucherNumber = $maxSale->voucher_number;
        // return $maxVoucherNumber;
        $maxTotal = $maxSale->net_total;
        // return $maxTotal;
        $max = [
            "voucherNumber" => $maxVoucherNumber,
            'total' => $maxTotal
        ];

        $minSale = Voucher::whereBetween("created_at", [$today, $now])->orderBy('net_total')->first();
        // return $minSale;
        $minVoucherNumber = $minSale->voucher_number;
        // return $minVoucherNumber;
        $minTotal = $minSale->net_total;
        // return $minTotal;
        $min = [
            "voucherNumber" => $minVoucherNumber,
            'total' => $minTotal
        ];

        return response()->json([
            'todayTotalSales' => $total,
            'todayAverageSales' => $averageAmount,
            'todayMaxSales' => $max,
            'todayMinSales' => $min
        ]);
    }

    public function weeklySaleReport()
    {

       return $this->weeklySale();

    }

    public function monthlySaleReport()
    {
       return $this->monthlySale();
    }

    public function yearlySaleReport()
    {
       return $this->yearlySale();

    }


}
