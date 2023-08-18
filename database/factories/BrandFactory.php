<?php

namespace Database\Factories;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->domainWord(),
            "company" => fake()->word(),
            "information" => fake()->sentence(10),
            "agent" => fake()->name(),
            "phone_number" => fake()->phoneNumber(),
            "user_id" => 1,
            // "photo" => fake()->sentence(),
        ];
    }
}
