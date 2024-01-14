<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\BestSellersResource;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BestSellersController extends Controller
{
    public function bestSellers(Request $request)
    {
        $validTypes = ["weekly", "monthly", "yearly"];

        if (!in_array($request->type, $validTypes)) {
            return response()->json(["message" => "အချိန်ကာလအပိုင်းအခြားလိုအပ်ပါသည်"], 400);
        }

        [$startDate, $endDate] = DashboardHelperController::calculateDateRange($request->type);

        $bestSellingProducts = VoucherRecord::whereBetween("created_at", [$startDate, $endDate])
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(6)
            ->get();

        return BestSellersResource::collection($bestSellingProducts);
    }
}
