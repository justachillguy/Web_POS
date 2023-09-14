<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class SaleReportController extends Controller
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

    public function todaySaleReport()
    {

        $today = Carbon::today()->format("Y-m-d H:i:s");
        $now = Carbon::today()->format("Y-m-d ") . "23:59:59";

        $vouchers = Voucher::whereBetween("created_at", [$today, $now])->get();
        // return $vouchers;
        $total = array_sum($vouchers->pluck("total")->toArray());
        $average = $vouchers->avg('net_total');
        // return $average;

        $maxSale = Voucher::whereBetween("created_at", [$today, $now])->orderByDesc('net_total')->first();
        // return $maxSale;
        $maxVoucherNumber = $maxSale->voucher_number;
        // return $maxVoucherNumber;
        $maxTotal = $maxSale->net_total;
        // return $maxTotal;
        $max=[
            "voucherNumber"=>$maxVoucherNumber,
            'total'=>$maxTotal
        ];

        $minSale = Voucher::whereBetween("created_at", [$today, $now])->orderBy('net_total')->first();
        // return $minSale;
        $minVoucherNumber = $minSale->voucher_number;
        // return $minVoucherNumber;
        $minTotal = $minSale->net_total;
        // return $minTotal;
        $min=[
            "voucherNumber"=>$minVoucherNumber,
            'total'=>$minTotal
        ];

        return response()->json([
            'todayTotalSales' => $total,
            'todayAverageSales'=>$average,
            'todayMaxSales'=> $max,
            'todayMinSales'=>$min
        ]);
    }

    public function weeklySaleReport()
    {

        $weeklySales = Voucher::select(
            DB::raw('DATE(created_at) as sale_date'),
            DB::raw('SUM(total) as total_sales')
        )
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->groupBy('sale_date')
            ->get();
        // return $weeklySales;
        // dd($weeklySales);

        // Calculate the best-selling day
        $bestSellingDay = $weeklySales->max('total_sales');

        // Find the date of the best-selling day
        $bestSellingDate = $weeklySales->where('total_sales', $bestSellingDay)->pluck('sale_date')->first();
        // return $bestSellingDate; //2023-09-09

        // Convert the date to a Carbon instance
        $bestSellingDateFormatted = Carbon::parse($bestSellingDate);

        // Get the day name (e.g., Monday, Tuesday)
        $bestSellingDayName = $bestSellingDateFormatted->formatLocalized('%A');
        // return $bestSellingDayName; //Saturday

        // Create an array to store day names and total sales for the entire week
        $daysOfWeek = [];

        // Loop through the week and get day names
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::parse($bestSellingDate)->startOfWeek()->addDays($i);
            $dayName = $day->formatLocalized('%A');
            // $date = $day->format("m/d/Y");

            // Calculate sales for the current day
            $daySales = $weeklySales->where('sale_date', $day->format('Y-m-d'))->first()?->total_sales ?? 0;
            // return $daySales;

            $daysOfWeek[] = [
                'dayName' => $dayName,
                'daySales' => $daySales,
                'date' => $day
            ];
        }
        // return $daysOfWeek;
        // dd($daysOfWeek);

        // dd(collect($daysOfWeek));

        $all = collect($daysOfWeek);


        $total = $weeklySales->sum('total_sales');
        // return $total;

        //Highest Sales
        $highest = $all->max('daySales');
        // return $highest;
        $highestSellingDate = $all->where('daySales', $highest)->pluck('date')->first();
        // return  $highestSellingDate;
        $highestSellingDateFormat = $highestSellingDate->format('m/d/Y');
        $highestDay = [
            "Highest Sale Amount" => $highest,
            "Highest Selling Date" => $highestSellingDateFormat
        ];

        // return $highestSellingDateFormat;
        // return $highestDay;

        //Lowest Sales
        $lowest = $all->min('daySales');
        // return $lowest;

        if ($lowest == 0) {
            $lowestSellingDays = $all->where('daySales', $lowest)->pluck('date');
            // return $lowestSellingDays;
            $lowestDays = [];
            foreach ($lowestSellingDays as $day) {
                $date = $day->format('m/d/Y');
                $lowestSaleAmount = $lowest;
                $lowestDays[] = [
                    'Lowest Sale Amount' => $lowestSaleAmount,
                    'Lowest Selling Day' => $date
                ];
            }
            // return $lowestDays;
        } else {
            $lowestSellingDate = $all->where('daySales', $lowest)->pluck('date')->first();
            // return  $lowestSellingDate;
            $lowestSellingDateFormat = $lowestSellingDate->format('m/d/Y');
            $lowestDays = [
                "lowest Sale Amount" => $lowest,
                "lowest Selling Date" => $lowestSellingDateFormat
            ];
        }

        // return $lowestDays;

        $average = $weeklySales->avg('total_sales');
        // return $average;

        return response()->json([
            "Weekly Sales" => $daysOfWeek,
            "Total Weekly Sales Amount" => $total,
            "Average Amount" => $average,
            "Highest Sale" => $highestDay,
            "Lowest Sale" => $lowestDays
        ]);
    }
}
