<?php

namespace Database\Seeders;

use App\Models\VoucherRecord;
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
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subYears(2)->subMonths(3);
        $period = CarbonPeriod::create($startDate, $endDate);
        $daily_sale = [];

        foreach ($period as $date) {
            $cash = random_int(35555, 74343);
            $tax = random_int(3557, 7435);
            $daily_sale[]=[
                "date" => $date,
                "vouchers" => random_int(34, 56),
                "item count"=>rand(20,50),
                "cash" => $cash,
                "tax" => $tax,
                "total" => $cash + $tax,
                "created_at" => now(),
                "updated_at" => now(),
            ];

    }
        DB::table("daily_sale")->insert($daily_sale);
    }
}
