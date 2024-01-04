<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = ["percentage", "amount"];
        $amount = ["10", "1000"];

        foreach (["နှစ်သစ် ပရိုမိုးရှင်း", "1. 11 ပရိုမိုးရှင်း"] as $value) {
            $type_id = rand(0, 1);
            $promotion = Promotion::create([
                "name" => $value,
                "type" => $type[$type_id],
                "amount" => $amount[$type_id],
                "started_at" => Carbon::now()->toDateString(),
                "expired_at" => Carbon::now()->addDays(7)->toDateString(),
                "user_id" => 1
            ]);
        }
    }
}
