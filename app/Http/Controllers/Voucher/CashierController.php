<?php

namespace App\Http\Controllers\Voucher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Resources\Cashier\CashierItemResource;
use App\Models\Product;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index(Request $request)
    {
        $additionalConditions = [["stock", ">", 0]];
        $products = HelperController::findAllQuery(
            Product::class,
            $request,
            ["name", "primary_price", "actual_price"]
        );

        return CashierItemResource::collection($products);
    }
}
