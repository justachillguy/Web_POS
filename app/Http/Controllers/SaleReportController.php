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
            'todayAverageSales' => $average,
            'todayMaxSales' => $max,
            'todayMinSales' => $min
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
            "highestSaleAmount" => $highest,
            "highestSellingDate" => $highestSellingDateFormat
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
                    'lowestSaleAmount' => $lowestSaleAmount,
                    'lowestSellingDay' => $date
                ];
            }
            // return $lowestDays;
        } else {
            $lowestSellingDate = $all->where('daySales', $lowest)->pluck('date')->first();
            // return  $lowestSellingDate;
            $lowestSellingDateFormat = $lowestSellingDate->format('m/d/Y');
            $lowestDays = [
                "lowestSaleAmount" => $lowest,
                "lowestSellingDate" => $lowestSellingDateFormat
            ];
        }

        // return $lowestDays;

        $average = $weeklySales->avg('total_sales');
        // return $average;

        return response()->json([
            "weeklySales" => $daysOfWeek,
            "totalWeeklySalesAmount" => $total,
            "averageAmount" => $average,
            "highestSale" => $highestDay,
            "lowestSale" => $lowestDays
        ]);
    }

    public function monthlySaleReport()
    {

        $monthlySales = Voucher::select(
            DB::raw('MONTH(created_at) as sale_month'),
            DB::raw('SUM(total) as total_sales')
        )
            ->whereBetween('created_at', [
                now()->startOfYear(),
                now()->endOfYear()
            ])
            ->groupBy('sale_month')
            ->get();

            // return $monthlySales;

        // Get the current year
        $currentYear = now()->year;

        // Format the sale_month column in the result
        $monthlySales->transform(function ($item) use ($currentYear) {
            // Format the sale_month as "YYYY-MM-DD"
            $formattedDate = Carbon::create($currentYear, $item->sale_month, 1)->format('Y-m-d');

            // Replace the sale_month with the formatted date
            $item->sale_month = $formattedDate;

            return $item;
        });

        // return $monthlySales;



        // Calculate the best-selling month
        $bestSellingDay = $monthlySales->max('total_sales');

        // Find the date of the best-selling month
        $bestSellingDate = $monthlySales->where('total_sales', $bestSellingDay)->pluck('sale_month')->first();
        // return $bestSellingDate;

        // Convert the date to a Carbon instance
        // $bestSellingDateFormatted = Carbon::parse($bestSellingDate);

        $date = Carbon::createFromFormat('Y-m-d', $bestSellingDate);
        // return $date;
        $monthName = $date->format('F');
        // return $monthName;
        $month = Carbon::parse($bestSellingDate)->startOfYear();
        $monthName = $month->format('F');
        // return $monthName;
        // return $month;

        // Create an array to store day names and total sales for the entire month
        $daysOfMonth = [];

        // Loop through the month and get month names
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::parse($bestSellingDate)->startOfYear()->addMonths($i);
            $monthName = $month->format('F');

            // Calculate sales for the current month
            $monthSales = $monthlySales->where('sale_month', $month->format('Y-m-d'))->first()?->total_sales ?? 0;
            // return $monthSales;

            $daysOfMonth[] = [
                'monthName' => $monthName,
                'monthSales' => $monthSales,
                'date' => $month
            ];
        }
        // return $daysOfMonth;
        // dd($daysOfMonth);

        // dd(collect($daysOfMonth));

        $all = collect($daysOfMonth);


        $total = $monthlySales->sum('total_sales');
        // return $total;

        //Highest Sales
        $highest = $all->max('monthSales');
        // return $highest;
        $highestSellingMonth = $all->where('monthSales', $highest)->pluck('date')->first();
        // return  $highestSellingDate;
        $highestSellingDateFormat = $highestSellingMonth->format('m/d/Y');
        $highestMonth = [
            "highestSaleAmount" => $highest,
            "highestSellingMonth" => $highestSellingDateFormat
        ];

        // return $highestSellingDateFormat;
        // return $highestDay;

        //Lowest Sales
        $lowest = $all->min('monthSales');
        // return $lowest;

        if ($lowest == 0) {
            $lowestSellingMonths = $all->where('monthSales', $lowest)->pluck('date');
            // return $lowestSellingDays;
            $lowestDays = [];
            foreach ($lowestSellingMonths as $day) {
                $date = $day->format('m/d/Y');
                $lowestSaleAmount = $lowest;
                $lowestMonths[] = [
                    'lowestSaleAmount' => $lowestSaleAmount,
                    'lowestSellingMonth' => $date
                ];
            }
            // return $lowestDays;
        } else {
            $lowestSellingDate = $all->where('monthSales', $lowest)->pluck('date')->first();
            // return  $lowestSellingDate;
            $lowestSellingDateFormat = $lowestSellingDate->format('m/d/Y');
            $lowestMonths = [
                "lowestSaleAmount" => $lowest,
                "lowestSellingDate" => $lowestSellingDateFormat
            ];
        }

        // return $lowestDays;

        $average = $monthlySales->avg('total_sales');
        // return $average;

        return response()->json([
            "monthlySales" => $daysOfMonth,
            "totalMonthlySalesAmount" => $total,
            "averageAmount" => $average,
            "highestSale" => $highestMonth,
            "lowestSale" => $lowestMonths
        ]);
    }

    public function yearlySaleReport()
    {

        $yearlySales = Voucher::select(
            DB::raw('YEAR(created_at) as sale_year'),
            DB::raw('SUM(total) as total_sales')
        )

            ->groupBy('sale_year')
            ->get();

        // return $yearlySales;

        $total = $yearlySales->sum('total_sales');
        // return $total;
        $average = $yearlySales->avg('total_sales');
        // return $average;

        //Highest Sales
        $highestAmount = $yearlySales->max('total_sales');
        // return $highestAmount;

        $highestSellingYear = $yearlySales->where('total_sales', $highestAmount)->pluck('sale_year');
        // return $highestSellingYear;
        $highestYear = (int)$highestSellingYear[0];
        // return $highestYear;

        $highestSellingYear = [
            "highestSaleAmount" => $highestAmount,
            "highestSellingYear" => $highestYear
        ];


         //Lowest Sales
         $lowestAmount = $yearlySales->min('total_sales');
         // return $lowestAmount;

         $lowestSellingYear = $yearlySales->where('total_sales', $lowestAmount)->pluck('sale_year');
         // return $lowestSellingYear;
         $lowestYear = (int)$lowestSellingYear[0];

         $lowestSellingYear = [
             "lowestSaleAmount" => $lowestAmount,
             "lowestSellingYear" => $lowestYear
         ];

        return response()->json([
            "yearlySales" => $yearlySales,
            "totalYearlySalesAmount" => $total,
            "averageAmount" => $average,
            "highestSale" => $highestSellingYear,
            "lowestSale" => $lowestSellingYear
        ]);
    }
}
