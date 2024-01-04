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
        foreach (["သံ", "ထုရိုက်ပစ္စည်း"] as  $value) {
            $category = Category::create(["name" => $value]);
        }
    }
}
