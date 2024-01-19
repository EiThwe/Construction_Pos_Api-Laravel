<?php

namespace Database\Seeders;

use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentDate = Carbon::now()->format("d") - 1;

        for ($i = 1; $i <= $currentDate; $i++) {
            $revenue = rand(10000, 1000000);
            $profit = rand(10000, 1000000);
            $expense = rand(10000, 1000000);

            Record::insert(
                [
                    "revenue" => $revenue,
                    "profit" => $profit,
                    "expense" => $expense,
                    "voucher_count" => rand(100, 1000),
                    "user_id" => 1,
                    "created_at" =>  Carbon::now()->startOfMonth()->day($i)->addHour(14),
                    "updated_at" =>  Carbon::now()->startOfMonth()->day($i)->addHour(14),
                ]
            );
        }
    }
}
