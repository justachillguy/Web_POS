<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $endDate = Carbon::now();
        $startDate = Carbon::now()->subYears(2)->subMonths(3);
        $period = CarbonPeriod::create($startDate, $endDate);
        // return response()->json(
        //     [
        //         "startDate" => $startDate,
        //         "endDate" => $endDate,
        //         $period
        //     ]
        // );

        // Get the current date
        $currentDate = Carbon::now();

        // Get the start of the month
        $startOfMonth = $currentDate->startOfMonth();

        // Get the end of the month
        $endOfMonth = $currentDate->endOfMonth();

        // Format the dates as needed
        // $startDateFormatted = $startOfMonth->toDateString();
        // $endDateFormatted = $endOfMonth->toDateString();

        // return $startOfMonth;
        // return $endOfMonth;
        // return $startDateFormatted;

        $firstVoucher = Voucher::orderBy('created_at')->first()->created_at;
        // return $firstVoucher;
        $lastVoucher = Voucher::orderBy('created_at', 'desc')->first()->created_at;
        // return $lastVoucher;
        $result = CarbonPeriod::create($firstVoucher, $lastVoucher);
        // return $result;

        // foreach ($result as $dt) {
        // }

        // $period = now()->subMonths(12)->monthsUntil(now());

        // $data = [];
        // foreach ($period as $date) {
        //     $data[] = [
        //         'month' => $date->shortMonthName,
        //         'year' => $date->year,
        //     ];
        // }


        // $data = array();
        // for ($i = 11; $i >= 0; $i--) {
        //     $month = Carbon::today()->startOfMonth()->subMonth($i);
        //     $year = Carbon::today()->startOfMonth()->subMonth($i)->format('Y');
        //     array_push($data, array(
        //         'month' => $month->shortMonthName,
        //         'year' => $year
        //     ));
        // }

        // return $data;



        // Retrieve distinct months and years from the vouchers
        // $distinctMonthsYears = Voucher::select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month'))
        //     ->groupBy('year', 'month')
        //     ->get();
        // // return $distinctMonthsYears;

        // // Loop through distinct months and years
        // foreach ($distinctMonthsYears as $distinctMonthYear) {
        //     $year = $distinctMonthYear->year;
        //     $month = $distinctMonthYear->month;

        //     // Get the first and last day of the current month and year
        //     $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        //     // return $startDate;
        //     $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        //     // return $endDate;

        //     // Retrieve vouchers for the current month
        //     $vouchersForMonth = Voucher::whereBetween('created_at', [$startDate, $endDate])->get();

        //     $cash = array_sum($vouchersForMonth->pluck("total")->toArray());
        //     $tax = array_sum($vouchersForMonth->pluck("tax")->toArray());
        //     $total = array_sum($vouchersForMonth->pluck("net_total")->toArray());

        //     /* Counting the number of voucher we've got so far for today. */
        //     $totalVouchers = Voucher::selectRaw("count(*) as vouchers")->whereBetween("created_at", [$startDate, $endDate])->get();
        //     // return $totalVouchers;
        //     $NOV = $totalVouchers->pluck("vouchers")->all();
        //     // return $NOV;
        //     $nov = intval(implode("", $NOV));
        //     // var_dump($nov);

        //     DB::table("monthly_sale")->insert(
        //         [
        //             "date" => Carbon::create($year,$month),
        //             "vouchers" => $nov,
        //             "cash" => $cash,
        //             "tax" => $tax,
        //             "total" => $total,
        //             "created_at" => now(),
        //             "updated_at" => now(),
        //         ]
        //     );
        // }

        // return response()->json([
        //     'message'=>'monthly done'
        // ]);

         // public function yearlySale()
    // {
    //     $distinctYears = Voucher::getDistinctYears();

    //     // return $distinctYears;
    //     // $years = $distinctYears->format("Y-m-d H:i:s");
    //     // $years = Carbon::create($distinctYears);
    // //    return $years;

    //     foreach($distinctYears as $year){
    //           // Get the first and last day of the current month and year
    //      $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
    //     //   return $startDate; //2021-01-01
    //       $endDate = Carbon::createFromDate($year, 12, 1)->endOfYear();
    //     //   return $endDate; //2021-12-31

    //      $vouchersForYear = Voucher::whereBetween('created_at', [$startDate, $endDate])->get();
    //     //  return $vouchersForYear;

    //      $cash = array_sum($vouchersForYear->pluck("total")->toArray());
    //     //  return $cash;
    //      $tax = array_sum($vouchersForYear->pluck("tax")->toArray());
    //      $total = array_sum($vouchersForYear->pluck("net_total")->toArray());

    //      $totalVouchers = Voucher::selectRaw("count(*) as vouchers")->whereBetween('created_at', [$startDate, $endDate])->get();
    //     //  return $totalVouchers;
    //      $NOV = $totalVouchers->pluck("vouchers")->all();
    //      // return $NOV;
    //      $nov = intval(implode("", $NOV));
    //      var_dump($nov);

    //      DB::table("yearly_sale")->insert(
    //          [
    //              "year" => $year,
    //              "vouchers" => $nov,
    //              "cash" => $cash,
    //              "tax" => $tax,
    //              "total" => $total,
    //              "created_at" => now(),
    //              "updated_at" => now(),
    //          ]
    //      );
    //  }

    //  return response()->json([
    //      'message'=>'yearly sale done'
    //  ]);

    // }



        // return $data;
    }
}
