<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RevenueChartController extends Controller
{
    public function get(Request $request)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);
        $type = $request->type;
        $validTypes = ["weekly", "monthly", "yearly"];

        if (!in_array($type, $validTypes)) {
            return response()->json(["message" => "Invalid type"], 400);
        }

        $dates = DashboardHelperController::calculateDateRange($type);
        $status = ($type === "yearly") ? "monthly" : "daily";

        $records = DashboardHelperController::getRecords($dates, $status, $type, "revenue");

        $total_amount = array_reduce($records, fn ($pv, $cv) => $pv += $cv["amount"], 0);

        $additionalRecords = DashboardHelperController::generateAdditionalRecords($records, $type);

        $mergedRecords = array_merge($records, $additionalRecords);

        return response()->json(["data" => ["total_amount" => $total_amount, "records" => $mergedRecords,]]);
    }
}
