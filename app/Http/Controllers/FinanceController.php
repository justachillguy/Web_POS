<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function dailyList()
    {
        $dailyLists = DB::table("daily_sale")->get();
        return response()->json(
            [
                "daily_list" => $dailyLists,
            ]
        );
    }

    public function monthlyList()
    {
        $monthlyLists = DB::table("monthly_sale")->latest('id')
        ->paginate(10);
        return response()->json(
            [
                "monthly_list" => $monthlyLists,
            ]
        );

        }

        public function yearlyList()
        {
            $yearlyLists = DB::table('yearly_sale')->latest('id')->paginate(10);
            return response()->json([
                'yearly_list' => $yearlyLists
            ]);

        }

    }

