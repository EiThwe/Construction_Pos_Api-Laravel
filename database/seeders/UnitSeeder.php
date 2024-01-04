<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (["ကျပ်သား", "ပိဿာ"] as $value) {
            Unit::insert([
                "name" => $value,
                "unit_type_id" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }

        foreach (["ပေ", "လက်မ", "ကိုက်"] as $value) {
            Unit::insert([
                "name" => $value,
                "unit_type_id" => 2,
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }

        foreach (["ချောင်း", "ခု", "အိတ်"] as $value) {
            Unit::insert([
                "name" => $value,
                "unit_type_id" => 3,
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
    }
}
