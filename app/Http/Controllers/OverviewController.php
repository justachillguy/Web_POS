<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherResource;
use App\Models\DailySale;
use App\Models\MonthlySale;
use App\Models\Stock;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverViewController extends BaseController
{
    public function overViewPage()
    {
        $totalStock = Stock::all()->sum("quantity");
        $totalStaff = User::all()->count();

        $today = Carbon::today();
        $todaySales = Voucher::whereDate("created_at", $today)->paginate(5)->withQueryString();
        $todayVouchers = Voucher::whereDate("created_at", $today)->get();

        $totalVoucher = count($todayVouchers);
        $totalCash = array_sum($todayVouchers->pluck("total")->toArray());
        $totalTax = array_sum($todayVouchers->pluck("tax")->toArray());
        $totalNet = array_sum($todayVouchers->pluck("net_total")->toArray());

        $data = VoucherResource::collection($todaySales);
        return response()->json(
            [
                "total_stock" => $totalStock,
                "total_staff" => $totalStaff,
                "today_sales" => $data->resource,
                "total_voucher" => $totalVoucher,
                "total_cash" => $totalCash,
                "total_tax" => $totalTax,
                "total_net" => $totalNet,
            ],200);
    }

    public function weeklySaleOverview()
    {
        $weekly = $this->weeklySale()->original;

        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();

        $weeklySales = DailySale::whereBetween("created_at", [$currentWeekStart, $currentWeekEnd])->get();
        $totalExpenses = $weeklySales->sum("total_actual_price");
        $totalIncome = $weeklySales->sum("total");
        $totalProfit = $totalIncome - $totalExpenses;
        // return $totalExpenses;
        /*
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

        */

        return response()->json([
            'weeklySaleOverview' => $weekly,
            'totalProfit' => $totalProfit,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses
        ],200);
    }

    public function monthlySaleOverview()
    {
        $monthly = $this->monthlySale()->original;
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $monthlySales = DailySale::whereBetween("created_at", [$currentMonthStart, $currentMonthEnd])->get();
        $totalExpenses = $monthlySales->sum("total_actual_price");
        $totalIncome = $monthlySales->sum("total");
        $totalProfit = $totalIncome - $totalExpenses;
        // return response()->json([
        //     "a" => $totalExpenses,
        //     "b" => $totalIncome,
        //     "c" => $totalProfit,
        // ]);

        /*
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

        */

        return response()->json([
            'monthlySaleOverview' => $monthly,
            'totalProfit' => $totalProfit,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses
        ],200);
    }

    public function yearlySaleOverview()
    {
        $yearly = $this->yearlySale()->original;

        $thisYear = Carbon::now()->format("Y");

        $yearlySales = MonthlySale::whereYear("created_at", $thisYear)->get();
        $totalExpenses = $yearlySales->sum("total_actual_price");
        $totalIncome = $yearlySales->sum("total");
        $totalProfit = $totalIncome - $totalExpenses;

        /*
        // return $yearly;
        // $currentYearStart = now()->startOfYear();
        // $currentYearEnd = now()->endOfYear();

        // return $now;

        $voucherRecords = VoucherRecord::whereBetween('created_at', [$currentYearStart, $currentYearEnd])->get();

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

        $voucher = Voucher::whereBetween('created_at', [$currentYearStart, $currentYearEnd])->get();
        $totalIncome = $voucher->sum('total');
        // return $totalIncome;
        */
        return response()->json([
            'yearlySaleOverview' => $yearly,
            'totalProfit' => $totalProfit,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses
        ],200);
    }
}
