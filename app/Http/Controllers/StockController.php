<?php

namespace App\Http\Controllers;

use App\Http\Requests\Stock\CreateStockRequest;
use App\Http\Resources\Stock\StockResource;
use App\Models\ConversionFactor;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orderBy = $request->input('order_by', 'stock');
        $orderDirection = $request->input('order_direction', 'asc');
        $additionalConditions = [["stock", "<=", 20]];

        $products = HelperController::findAllQuery(Product::class, $request, [], $additionalConditions, $orderBy, $orderDirection);

        return StockResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStockRequest $request)
    {
        $product = Product::find($request->product_id);

        $total_quantity = 0;

        if ($request->unit_id === $product->primary_unit_id) {
            $total_quantity = $request->quantity;
        } else {
            $conversion = ConversionFactor::where("from_unit_id", $request->unit_id)->where("to_unit_id", $product->primary_unit_id)->first();
            if (is_null($conversion)) {
                return response()->json(["message" => "ယူနစ်ချိတ်ဆက်မှုမရှိပါ"], 400);
            }


            if ($conversion->status === "more") {
                $total_quantity = $request->quantity * $conversion->value;
            } else if ($conversion->status === "less") {
                $total_quantity = $request->quantity / $conversion->value;
            }
        }

        $product->stock = $product->stock + $total_quantity;
        $product->update();

        Stock::create([
            "product_id" => $request->product_id,
            "cost" => $request->cost,
            "unit_id" => $request->unit_id,
            "user_id" => Auth::id(),
            "quantity" => $request->quantity,
        ]);

        return response()->json(["message" => "စတော့ထည့်သွင်းခြင်းအောင်မြင်ပါသည်"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
