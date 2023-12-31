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
            "name" => "Doffy",
            "phone_number" => "09787878787",
            "email" => "admin2@gmail.com",
            "date_of_birth" => "2000-01-01",
            "gender" => "male",
            "address" => "ho nar d nar",
            "position" => "admin",
            "password" => Hash::make("aaaaaaaa"),
            // "photo" => "jokerj oekr ej ojskdffjsdoff",
        ]);

        User::factory()->create([
            "name" => "Nika",
            "phone_number" => "09787878787",
            "email" => "admin3@gmail.com",
            "date_of_birth" => "2000-01-01",
            "gender" => "male",
            "address" => "ho nar d nar",
            "position" => "admin",
            "password" => Hash::make("aaaaaaaa"),
            // "photo" => "jokerj oekr ej ojskdffjsdoff",
        ]);

        

    }
}
