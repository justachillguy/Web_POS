<?php

namespace App\Http\Controllers;

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
}
