<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (["အလေးချိန်", "အလျား/အနံ", "အရေအတွက်"] as $value) {
            UnitType::insert([
                "name" => $value
            ]);
        }
    }
}
