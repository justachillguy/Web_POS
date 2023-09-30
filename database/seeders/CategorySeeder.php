<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = ["drink", "cake", "bread", "noodle", "ice-cream"];
        $categories = [];

        foreach ($titles as $title) {
            $categories[] = [
                "title" => $title,
                "user_id" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }
        Category::insert($categories);
    }
}
