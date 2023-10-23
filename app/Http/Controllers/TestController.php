<?php

namespace App\Http\Controllers;


use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{


    public function testMonth()
    {

        // Get the current month in Carbon format
        $currentDate = Carbon::now();
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
            $salesByDay[$dayOfMonth] += $voucher->total;
            $formattedDates[$dayOfMonth] = $saleDate->format('d/m/Y'); // Format date as "day, month, year".
        }

        // Output the result
        for ($day = 1; $day <= $currentDate->daysInMonth; $day++) {
            $sales = $salesByDay[$day];
            $formattedDate = $formattedDates[$day];
            $monthly[] = [
                "date" => $formattedDate,
                "total_sales" => $sales
            ];
        }

        return $monthly;
    }

    public function yearlySale()
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

    public function eachYearlySale()
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

    public function eachYearlySaleOverview()
    {
        $yearly =  $this->yearlySale()->original;

        $yearlyProfits = DB::table('voucher_records')
            ->selectRaw('YEAR(voucher_records.created_at) as year')
            ->selectRaw('SUM( voucher_records.cost - products.actual_price ) as total_profit')
            ->selectRaw('CAST(SUM(vouchers.total) AS SIGNED) as total_income')
            ->selectRaw('SUM(products.actual_price) as total_expenses')
            ->join('products', 'voucher_records.product_id', '=', 'products.id')
            ->join('vouchers', 'voucher_records.voucher_id', '=', 'vouchers.id')
            ->groupBy('year')
            ->get();

        // return $yearlyProfits;

        // foreach ($yearlyProfits as $profit) {
        //     $year = $profit->year;
        //     $totalProfit = $profit->total_profit;
        //     $totalCost = $profit->total_cost;
        //     $totalActualPrice = $profit->total_actual_price;
        // }

        return response()->json([
            'yearlySaleOverview' => $yearly,
            'yearly' => $yearlyProfits
        ]);
    }

    public function monthlySaleTest()
    {

        $currentDate = Carbon::now();
        // return $currentDate;
        $startOfMonth = $currentDate->startOfMonth();
        // return $startOfMonth;

        if ($currentDate->isLastOfMonth()) {
            // If today is the last day of the current month, retrieve sales for the entire month.
            $endOfMonth = $currentDate->endOfMonth();

        } else {
            // If today is not the last day of the current month, set the end date to today.
            $endOfMonth = Carbon::now();

        }

        // return $endOfMonth;

        $salesData = DB::table('vouchers')
            ->select(DB::raw("DATE_FORMAT(created_at, '%d/%m/%Y') as date"), DB::raw('CAST(SUM(total) AS SIGNED) as total_sales'))
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d/%m/%Y')"))
            ->get();

        return $salesData;
    }

    public function monthlySale()
    {

        // Get the current month in Carbon format
        $currentDate = Carbon::now();
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
}
