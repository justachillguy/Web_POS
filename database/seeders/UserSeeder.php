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
            "name" => "Admin",
            "email" => "admin@gmail.com",
            "role" => "admin",
            "password" => Hash::make("thepassword"),

        ]);

        User::factory()->create([
            "name" => "Staff",
            "email" => "staff@gmail.com",
            "role" => "staff",
            "password" => Hash::make("thepassword"),

        ]);

        // $users = [
        //     "admin"
        // ]

        // User::factory()->count(3)->create();
    }
}
