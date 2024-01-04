<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product::factory(10)->create();

        foreach (["တူ", "နှစ်လက်မသံ", "သုံးလက်မသံ", "ငါးလက်မသံ", "လွှ", "ပေါက်ပြား", "ဘိလပ်မြေ", "မူလီ"] as $value) {
            $actual_price = rand(1000, 10000);
            $product = Product::create([
                "name" => $value,
                "actual_price" => $actual_price,
                "primary_unit_id" => rand(1, 8),
                "primary_price" => $actual_price + 300,
                "stock" => 100,
                "user_id" => 1,
            ]);

            $product->categories()->attach(rand(1, 2));
        }
    }
}
