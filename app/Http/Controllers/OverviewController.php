<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OverviewController extends BaseController
{
    public function weeklySaleOverview()
    {
        $weekly = $this->weeklySale()->original;

        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();

        $voucherRecords = VoucherRecord::whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])->get();

        // return $voucherRecords;

        foreach ($voucherRecords as $voucherRecord) {
            // $cost = $voucherRecord->cost;
            // $actualPrice = $voucherRecord->product->actual_price;

            // $profit = $cost - $actualPrice;

            $totalProfit = $voucherRecords->sum(function ($voucherRecord) {
                $cost = $voucherRecord->cost;
                $actualPrice = $voucherRecord->product->actual_price;

                return $cost - $actualPrice;
            });

            $totalExpenses = $voucherRecords->sum(function ($voucherRecord) {
                return $voucherRecord->product->actual_price;
            });

            // return $totalExpenses;
        }

        // return $totalProfit;

        $voucher = Voucher::whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])->get();
        $totalIncome = $voucher->sum('total');
        // return $totalIncome;

        return response()->json([
            'weeklySaleOverview' => $weekly,
            'totalProfit' => $totalProfit,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses
        ]);
    }

    public function monthlySaleOverview()
    {
        $monthly = $this->monthlySale()->original;
        $currentMonthStart = now()->startOfYear();
        $currentMonthEnd = now()->endOfYear();

        $voucherRecords = VoucherRecord::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->get();

        // return $voucherRecords;

        foreach ($voucherRecords as $voucherRecord) {
            // $cost = $voucherRecord->cost;
            // $actualPrice = $voucherRecord->product->actual_price;

            // $profit = $cost - $actualPrice;

            $totalProfit = $voucherRecords->sum(function ($voucherRecord) {
                $cost = $voucherRecord->cost;
                $actualPrice = $voucherRecord->product->actual_price;

                return $cost - $actualPrice;
            });

            $totalExpenses = $voucherRecords->sum(function ($voucherRecord) {
                return $voucherRecord->product->actual_price;
            });

            // return $totalExpenses;
        }

        // return $totalProfit;

        $voucher = Voucher::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->get();
        $totalIncome = $voucher->sum('total');
        // return $totalIncome;

        return response()->json([
            'monthlySaleOverview' => $monthly,
            'totalProfit' => $totalProfit,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses
        ]);
    }

    public function yearlySaleOverview()
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
            'yearlySaleOverview'=>$yearly,
            'yearly'=>$yearlyProfits
        ]);
    }
}
