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
            'phone'=>fake()->phoneNumber(),
            'voucher_number'=>fake()->uuid(),
            'total'=>fake()->numberBetween(100, 10000),
            'tax'=>fake()->numberBetween(0,1000),
            'net_total'=>fake()->numberBetween(100,10000),
            'user_id'=>rand(1,5)
        ];
    }
}
