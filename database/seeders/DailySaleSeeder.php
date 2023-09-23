<?php

namespace Database\Seeders;

use App\Models\DailySale;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailySaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $endDate = Carbon::today()->addDay();
        // $startDate = Carbon::today()->subYears(2)->subMonths(3);
        // $period = CarbonPeriod::create($startDate, $endDate);

        // foreach ($period as $date) {
        //     DB::table("daily_sale")->insert([
        //         "date" => $date,
        //         "vouchers" => 5,
        //         "cash" => 23234,
        //         "tax" => 4334,
        //         "total" => 3343343,
        //         "created_at" => now(),
        //         "updated_at" => now(),
        //     ]);
        // }

        $endDate = Carbon::now()->addMonths(3);
        $startDate = Carbon::now()->subYears(2);
        $period = CarbonPeriod::create($startDate, $endDate);
        $dailySales = [];

        foreach ($period as $date) {
            $total = random_int(35555, 74343);
            $tax = random_int(3557, 7435);
            $netTotal = $total + $tax;
            $dailySales[] = [
                "vouchers" => random_int(10, 20),
                "total" => $total,
                "tax" => $tax,
                "net_total" => $netTotal,
                "created_at" => $date,
                "updated_at" => $date,
            ];
        }
        DailySale::insert($dailySales);
    }
}
