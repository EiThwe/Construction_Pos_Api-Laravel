<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardHelperController extends Controller
{
    public static function calculateDateRange($type)
    {
        $currentDate = Carbon::now();
        $startDate = '';
        $endDate = '';

        if ($type == "weekly") {
            $startDate = $currentDate->copy()->startOfWeek();
            $endDate =  $currentDate->copy()->endOfWeek();
        } else if ($type == "monthly") {
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate =  $currentDate->copy()->endOfMonth();
        } else if ($type == "yearly") {
            $startDate = $currentDate->copy()->startOfYear();
            $endDate =  $currentDate->copy()->endOfYear();
        } else {
            abort(400, "weekly or monthly or yearly is required");
        }

        return [$startDate, $endDate];
    }

    static public  function getRecords($dates, $status, $type, $key)
    {
        return Record::whereBetween("created_at", $dates)
            ->where("status", $status)
            ->select($key, "created_at")
            ->get()
            ->map(function ($record) use ($type, $key) {
                $date = Carbon::parse($record["created_at"])->format(
                    $type === "monthly" ? "d" : ($type === "yearly" ? "m-d" : "l")
                );

                return [
                    "amount" => $record[$key],
                    "created_at" => $record["created_at"],
                    "date" => $type === "monthly" ? "Day - $date" : $date
                ];
            })->toArray();
    }

    static public function generateAdditionalRecords($records, $type)
    {
        $lastRecord = end($records);

        $currentDate = Carbon::parse($lastRecord ? $lastRecord["created_at"] : Carbon::now()->subDay())->format("d");

        $endDay = ($type === "monthly") ? Carbon::now()->endOfMonth()->format("d") : Carbon::now()->endOfWeek()->format("d");

        $additionalRecords = [];

        for ($i = 1; $i <= $endDay - $currentDate; $i++) {
            $date = Carbon::now()->startOfMonth()->day($currentDate + $i)->addHour(14)
                ->format($type === "monthly" ? "d" : ($type === "yearly" ? "m-d" : "l"));

            $additionalRecords[] = [
                "amount" => 0,
                "date" => $type === "monthly" ? "Day - $date" : $date
            ];
        }

        return $additionalRecords;
    }
}
