<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherResource;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OverviewController extends BaseController
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
        return response()->json(
            [
                "total_stock" => $totalStock,
                "total_staff" => $totalStaff,
                "today_sales" => VoucherResource::collection($todayVouchers),
                "total_voucher" => $totalVoucher,
                "total_cash" => $totalCash,
                "total_tax" => $totalTax,
                "net_total" => $totalNet,
            ]
        );
    }

    public function weeklySaleOverview()
    {
        $weekly = $this->weeklySale();

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
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

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
        $yearly = $this->yearlySale()->original;
        $currentYearStart = now()->startOfYear();
        $currentYearEnd = now()->endOfYear();

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

        return response()->json([
            'yearlySaleOverview' => $yearly,
            'totalProfit' => $totalProfit,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses
        ]);
    }


}
