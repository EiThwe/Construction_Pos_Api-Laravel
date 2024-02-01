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
                    $type === "monthly" ? "d" : ($type === "yearly" ? "F" : "l")
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
        if ($type === "yearly") {
            $allRecords = [];

            for ($i = 1; $i <= 12; $i++) {
                $isExist = array_filter($records, function ($record) use ($i) {
                    $recordDate = Carbon::parse($record["created_at"])->format("m");

                    return $recordDate == $i;
                });

                if ($isExist) {
                    $foundRecord = reset($isExist); // Get the first element of the filtered array
                    $allRecords[] = [
                        "amount" => $foundRecord["amount"],
                        "date" => $foundRecord["date"]
                    ];
                } else {
                    $date = Carbon::create()->month($i)->format("F");

                    $allRecords[] = [
                        "amount" => 0,
                        "date" => $date
                    ];
                }
            }

            return $allRecords;
        }

        $endDay = ($type === "monthly") ? Carbon::now()->endOfMonth()->format("d") :
            Carbon::now()->endOfWeek()->format("d");
        $startDay = Carbon::now()->startOfWeek()->format("d");
        $lastDayOfPrevMonth =  Carbon::now()->subMonth()->endOfMonth()->format("d");

        $allRecords = [];


        if ($type === "weekly" && ($startDay > $endDay)) {
            $endDay += $lastDayOfPrevMonth;
        }

        for ($i = $type === "monthly" ? 1 : $startDay; $i <= $endDay; $i++) {

            $isExist = array_filter($records, function ($record) use ($i, $lastDayOfPrevMonth, $type) {
                $recordDate = Carbon::parse($record["created_at"])->format("j");

                if ($type === "weekly" && ($i > $lastDayOfPrevMonth)) {
                    $i -= $lastDayOfPrevMonth;
                }

                return $recordDate == $i;
            });


            if (count($isExist) > 0) {
                $foundRecord = reset($isExist); // Get the first element of the filtered array
                $allRecords[] = [
                    "amount" => $foundRecord["amount"],
                    "date" => $foundRecord["date"]
                ];
            } else {
                $doubleDigitI =  $i < 10 ? "0$i" : $i;

                $date = "Day - $doubleDigitI";

                if ($type === "weekly") {
                    if ($endDay > $lastDayOfPrevMonth) {
                        if ($i <= $lastDayOfPrevMonth) {
                            $date =  Carbon::now()->subMonth()->setDays($i)->format("l");
                        } else {
                            $date = Carbon::now()->setDays($i - $lastDayOfPrevMonth)->format("l");
                        }
                    } else {
                        $date =  Carbon::now()->setDays($i)->format("l");
                    }
                }


                $allRecords[] = [
                    "amount" => 0,
                    "date" =>  $date
                ];
            }
        }

        return $allRecords;
    }
}
