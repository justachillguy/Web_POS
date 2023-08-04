<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "brand_id" => rand(1,20),
            "actual_price" => rand(1000, 3000),
            "sale_price" => rand(1200, 3300),
            "total_stock" => rand(1,50),
            "unit" => fake()->word(),
            "more_information" => fake()->sentence(10),
            "user_id" => rand(1,5),
            "photo" => fake()->sentence(),
        ];
    }
}
