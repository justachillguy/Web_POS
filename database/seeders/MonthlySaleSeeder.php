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

        for ($i = 1; $i <=12; $i++) {
            $quan = random_int(300, 350);
            $total = rand(3000000, 4000000);
            $tax = rand(8000, 10000);
            $netTotal = rand(3008000, 4010000);
            $vouchers[] = [
                "vouchers" => $quan,
                "total" => $total,
                "tax" => $tax,
                "net_total" => $netTotal,
                "created_at" => Carbon::createFromDate("2022", $i),
                "updated_at" => Carbon::createFromDate("2022", $i),
            ];
        }

        MonthlySale::insert($vouchers);
    }
}
