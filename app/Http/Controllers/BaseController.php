<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public function weeklySale()
    {

        $weeklySales = Voucher::select(
            DB::raw('DATE(created_at) as sale_date'),
            DB::raw('CAST(SUM(total) AS SIGNED) as total_sales')
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

    public function monthlySale()
    {

        // Get the current month in Carbon format
        $currentDate = Carbon::parse('2023-08-01');
        // return $currentDate;
        $currentMonth = $currentDate->format('Y-m');
        // return $currentMonth;

        // Initialize an array to store the sum of sales for each day
        $salesByDay = array_fill(1, $currentDate->daysInMonth, 0);
        // return $salesByDay;

        // Initialize an array to store formatted dates
        $formattedDates = array_fill(1, $currentDate->daysInMonth, '');
        // return $formattedDates;

        // Retrieve sales data from the voucher model for the current month
        $vouchers = Voucher::whereYear('created_at', $currentDate->year)
            ->whereMonth('created_at', $currentDate->month)

            ->get();
            // return $vouchers;

        // Loop through the retrieved vouchers and calculate the total sales for each day
        foreach ($vouchers as $voucher) {
            $saleDate = Carbon::parse($voucher->created_at);
            $dayOfMonth = $saleDate->day;
            // return $dayOfMonth;
            $salesByDay[$dayOfMonth] += $voucher->total;
            // return $salesByDay;
            $formattedDates[$dayOfMonth] = $saleDate->format('d/m/Y');
            // return $formattedDates;
        }
        // return $dayOfMonth;
        // return $salesByDay;
        // return $formattedDates;

        for ($day = 1; $day <= $currentDate->daysInMonth; $day++) {
            $sales = $salesByDay[$day];
            $formattedDate = $formattedDates[$day];
            $monthlySales[]=[
                "date"=>$formattedDate,
                "total_sales"=>$sales
            ];
        }

        // return $monthlySales;
        $monthly = collect($monthlySales);

        $total = $monthly->sum('total_sales');
        // return $total;

        //Highest Sales
        $highest = $monthly->max('total_sales');
        // return $highest;
        $highestSellingDate = $monthly->where('total_sales', $highest)->pluck('date')->first();
        // return $highestSellingDate;

        $highestSellingDateOfMonth = [
            "highestSaleAmount" => $highest,
            "highestSellingDateOfMonth" => $highestSellingDate
        ];

        // return $highestSellingDateOfMonth;


        //Lowest Sales
        $lowest = $monthly->min('total_sales');
        // return $lowest;

        if ($lowest == 0) {
            $lowestSellingDates = $monthly->where('total_sales', $lowest)->pluck('date');
            // return $lowestSellingDates;
            $lowestDays = [];
            foreach ($lowestSellingDates as $day) {
                // $date = $day->format('m/d/Y');
                $lowestSaleAmount = $lowest;
                $lowestSellingDateOfMonth[] = [
                    'lowestSaleAmount' => $lowestSaleAmount,
                    'lowestSellingDateOfMonth' => $day
                ];
            }
            // return $lowestDays;
        } else {
            $lowestSellingDate = $monthly->where('total_sales', $lowest)->pluck('date')->first();
            // return  $lowestSellingDate;

            $lowestSellingDateOfMonth = [
                "lowestSaleAmount" => $lowest,
                "lowestSellingDateOfMonth" => $lowestSellingDate
            ];
        }

        // return $lowestDays;

        $average = $monthly->avg('total_sales');
        // return $average;
        return response()->json([
            "monthlySales" => $monthlySales,
            "totalMonthlySalesAmount" => $total,
            "averageAmount" => $average,
            "highestSale" => $highestSellingDateOfMonth,
            "lowestSale" => $lowestSellingDateOfMonth
        ]);
    }

    public function yearlySale()
    {

        $monthlySales = Voucher::select(
            DB::raw('MONTH(created_at) as sale_month'),
            DB::raw('CAST(SUM(total) AS SIGNED) as total_sales')
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

       // Format the sale_month as "YYYY-MM-DD"
        $monthlySales->transform(function ($item) use ($currentYear) {
            $formattedDate = Carbon::create($currentYear, $item->sale_month, 1)->format('Y-m-d');
            // Replace the sale_month with the formatted date
            $item->sale_month = $formattedDate;

            return $item;
        });

        // return $monthlySales;


        // Calculate the best-selling month
        $bestSellingMonth = $monthlySales->max('total_sales');

        // Find the date of the best-selling month
        $bestSellingDate = $monthlySales->where('total_sales', $bestSellingMonth)->pluck('sale_month')->first();
        // return $bestSellingDate; //2023-08-01

        $date = Carbon::createFromFormat('Y-m-d', $bestSellingDate);
        // return $date;
        $monthName = $date->format('F');
        // return $monthName;

        //Calculate start of month of current year
        $month = Carbon::parse($bestSellingDate)->startOfYear();
        $monthName = $month->format('F');
        // return $monthName; //January
        // return $month; //2023-01-01T00:00:00.000000Z

        // Create an array to store day names and total sales for the entire month
        $daysOfMonth = [];

        // Loop through the month and get month names
        for ($i = 0; $i < 12; $i++) {
            $month = Carbon::parse($bestSellingDate)->startOfYear()->addMonths($i);
            $monthName = $month->format('F');

            // Calculate sales for the current month
            $monthSales = $monthlySales->where('sale_month', $month->format('Y-m-d'))->first()?->total_sales ?? 0;
            // return $monthlySales;

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
            "yearlySales" => $daysOfMonth,
            "totalYearlySalesAmount" => $total,
            "averageAmount" => $average,
            "highestSale" => $highestMonth,
            "lowestSale" => $lowestMonths
        ]);

    }


}
