<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stocks = [];
        for($i=1; $i<=30; $i++){
            // $quantity = random_int(1100, 1200);
            $quantity = $i < 21 ? random_int(1100, 1200) : random_int(0,8);
            $stocks[] = [
                "user_id" => 1,
                "product_id" => $i,
                "quantity" => $quantity,
                // "more" => fake()->sentence(5),
            ];
            $product = Product::findOrFail($i);
            $product->total_stock += $quantity;
            $product->update();
        }
        Stock::insert($stocks);
        // Stock::factory()->count(20)->create();
    }
}
