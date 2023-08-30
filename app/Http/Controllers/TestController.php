<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        // // $date = Carbon::createFromDate("2023", "3", "23")->format("F jS Y");
        // $now = Carbon::now();
        // // $date = $now->addDays(20)->format("F jS Y");
        $dates = [];
        for ($i = 0; $i <= 30; $i++) {
            // $d = rand(1, 30);
            array_push($dates, Carbon::createFromDate("2023", "1", "1")->addDays($i));
        }
        return $dates;

    }
}
