<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfitChartController extends Controller
{
    public function get(Request $request)
    {
        $type = $request->type;
        $validTypes = ["weekly", "monthly", "yearly"];

        if (!in_array($type, $validTypes)) {
            return response()->json(["message" => "Invalid type"], 400);
        }

        $dates = DashboardHelperController::calculateDateRange($type);
        $status = ($type === "yearly") ? "monthly" : "daily";

        $records = DashboardHelperController::getRecords($dates, $status, $type, "profit");

        logger($records);

        $total_amount = array_reduce($records, fn ($pv, $cv) => $pv += $cv["amount"], 0);

        $additionalRecords =  DashboardHelperController::generateAdditionalRecords($records, $type);

        $mergedRecords = array_merge($records, $additionalRecords);

        return response()->json(["data" => ["total_amount" => $total_amount, "records" => $mergedRecords,]]);
    }
}
