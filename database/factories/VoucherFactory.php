<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //

            'customer' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'voucher_number' => fake()->uuid(),
            'total' => 0,
            'tax' => 0,
            'net_total' => 0,
            'user_id' => 1,

        ];
    }
}
