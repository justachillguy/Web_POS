<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoucherResource;
use App\Models\Stock;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverViewController extends Controller
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
                "today_sales" => VoucherResource::collection($todaySales),
                "total_voucher" => $totalVoucher,
                "total_cash" => $totalCash,
                "total_tax" => $totalTax,
                "total_net" => $totalNet,
            ]
        );
    }
}
