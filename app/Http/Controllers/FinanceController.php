<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailySalesResource;
use App\Http\Resources\MonthlySalesResource;
use App\Http\Resources\YearlySalesResource;
use App\Models\DailySale;
use App\Models\MonthlySale;
use App\Models\Voucher;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function dailySales()
    {
        /* $dailySales to be passed thru API and shown in tables. */
        $dailySales = Voucher::where(function ($query) {
            $date = request()->date;
            $query->where("created_at", "LIKE", "%" . $date . "%");
        })
            ->when(request()->has("id"), function ($query) {
                $sortType = request()->id ?? "asc";
                $query->orderBy("id", $sortType);
            })
            ->paginate(5)
            ->withQueryString();

        /* dailySales2 to calculated the info about total vouchers, cost, tax and final cost. */
        $dailySales2 = Voucher::where(function ($query) {
            $date = request()->date;
            $query->where("created_at", "LIKE", "%" . $date . "%");
        })->get();

        /* Calculating total vouchers, total cost, total tax and total final cost. */
        $totalVocuhers = count($dailySales2);
        $total_cash = array_sum($dailySales2->pluck("total")->toArray());
        $total_tax = array_sum($dailySales2->pluck("tax")->toArray());
        $total = array_sum($dailySales2->pluck("net_total")->toArray());


        return response()->json(
            [
                "daily_sales" => DailySalesResource::collection($dailySales),
                "total_vouchers" => $totalVocuhers,
                "total_cash" => $total_cash,
                "total_tax" => $total_tax,
                "total" => $total,
            ]
        );
    }

    public function thisMonthSales()
    {

        $thisMonthSales = DailySale::where(function ($query) {
            $date = request()->date;
            $query->where("created_at", "LIKE", "%" . $date . "%");
        })
            ->when(request()->has("id"), function ($query) {
                $sortType = request()->id ?? "asc";
                $query->orderBy("id", $sortType);
            })
            ->paginate(5)
            ->withQueryString();


        $thisMonthSales2 = DailySale::where(function ($query) {
            $date = request()->date;
            $query->where("created_at", "LIKE", "%" . $date . "%");
        })->get();

        $totalDays = count($thisMonthSales2);
        $totalVocuhers = array_sum($thisMonthSales2->pluck("vouchers")->toArray());
        $total_cash = array_sum($thisMonthSales2->pluck("total")->toArray());
        $total_tax = array_sum($thisMonthSales2->pluck("tax")->toArray());
        $total = array_sum($thisMonthSales2->pluck("net_total")->toArray());

        return response()->json(
            [
                "this_month_sales" => MonthlySalesResource::collection($thisMonthSales),
                "total_days" => $totalDays,
                "total_vouchers" => $totalVocuhers,
                "total_cash" => $total_cash,
                "total_tax" => $total_tax,
                "total" => $total,
            ]
        );
    }

    public function thisYearSales()
    {
        $thisYearSales = MonthlySale::where(function ($query) {
            $year = request()->year;
            $query->where("created_at", "LIKE", "%" . $year . "%");
        })
            ->when(request()->has("id"), function ($query) {
                $sortType = request()->id ?? "asc";
                $query->orderBy("id", $sortType);
            })
            ->paginate(5)
            ->withQueryString();

        $thisYearSales2 = MonthlySale::where(function ($query) {
            $year = request()->year;
            $query->where("created_at", "LIKE", "%" . $year . "%");
        })->get();

        $totalMonths = count($thisYearSales2);
        $totalVocuhers = array_sum($thisYearSales2->pluck("vouchers")->toArray());
        $total_cash = array_sum($thisYearSales2->pluck("total")->toArray());
        $total_tax = array_sum($thisYearSales2->pluck("tax")->toArray());
        $total = array_sum($thisYearSales2->pluck("net_total")->toArray());

        return response()->json(
            [
                "yearly_sales" => YearlySalesResource::collection($thisYearSales),
                "total_months" => $totalMonths,
                "total_vouchers" => $totalVocuhers,
                "total_cash" => $total_cash,
                "total_tax" => $total_tax,
                "total" => $total,
            ]
        );
    }

    public function customSalesList()
    {
        $startDate = request()->startDate . " 00:00:00";
        $endDate = request()->endDate . " 23:59:59";

        $salesList = Voucher::select("*")
            ->whereBetween("created_at", [$startDate, $endDate])
            ->when(request()->has("id"), function ($query) {
                $sortType = request()->id ?? "asc";
                $query->orderBy("id", $sortType);
            })
            ->paginate(5)
            ->withQueryString();

        $salesList2 = Voucher::select("*")
            ->whereBetween("created_at", [$startDate, $endDate])->get();

        $totalVocuhers = count($salesList2);
        $total_cash = array_sum($salesList2->pluck("total")->toArray());
        $total_tax = array_sum($salesList2->pluck("tax")->toArray());
        $total = array_sum($salesList2->pluck("net_total")->toArray());


        return response()->json(
            [
                "daily_sales" => DailySalesResource::collection($salesList),
                "total_vouchers" => $totalVocuhers,
                "total_cash" => $total_cash,
                "total_tax" => $total_tax,
                "total" => $total,
            ]
        );
    }
}
