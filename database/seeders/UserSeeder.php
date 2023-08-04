<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            "name" => "Zwe Yan Naing",
            "email" => "zwe@gmail.com",
            "role" => "admin",
            "password" => Hash::make("thepassword"),

        ]);

        User::factory()->create([
            "name" => "Lin Pyae Pyae",
            "email" => "lin@gmail.com",
            "role" => "admin",
            "password" => Hash::make("thepassword"),

        ]);

        User::factory()->count(3)->create();
    }
}
