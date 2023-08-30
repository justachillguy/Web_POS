<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* To gather all vouchers. */
        $vouchers = [];

        /* This for loop (i) is for dates. */
        for ($i = 1; $i <= 300; $i++) {

            /* This for loop (r) represents the total number of vouchers in a day. */
            for ($r = 1; $r <= 2; $r++) {

                /* Generate random cost. */
                $total = rand(3000, 10000);
                $tax = $total * 0.05;
                $netTotal = $total + $tax;

                $vouchers[] = [
                    'customer' => fake()->name(),
                    'phone' => fake()->phoneNumber(),
                    'voucher_number' => fake()->uuid(),
                    'total' => $total,
                    'tax' => $tax,
                    'net_total' => $netTotal,
                    'user_id' => 1,
                    "created_at" => Carbon::now()->addDays($i),
                    "updated_at" => Carbon::now()->addDays($i),
                ];
            }
        }
        Voucher::insert($vouchers);
    }
}
