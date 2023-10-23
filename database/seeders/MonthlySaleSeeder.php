<?php

namespace Database\Seeders;

use App\Models\MonthlySale;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonthlySaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vouchers = [];

        $years = ["2021", "2022", "2023"];

        foreach ($years as $year) {

            for ($month = 1; $month <= 12; $month++) {
                $quan = random_int(300, 350);
                $total = rand(3000000, 4000000);
                $rand = random_int(500000,800000);
                $tax = rand(8000, 10000);
                $netTotal = rand(3008000, 4010000);
                $vouchers[] = [
                    "vouchers" => $quan,
                    "total_actual_price" => $total - $rand,
                    "total" => $total,
                    "tax" => $tax,
                    "net_total" => $netTotal,
                    "created_at" => Carbon::createFromDate($year, $month),
                    "updated_at" => Carbon::createFromDate($year, $month),
                ];
            }
        }

        MonthlySale::insert($vouchers);
    }
}
