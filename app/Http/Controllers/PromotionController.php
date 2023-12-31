<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Http\Resources\PromotionsDetailResource;
use App\Http\Resources\PromotionsResource;
use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $promotions = HelperController::findAllQuery(Promotion::class, $request, ["place", "cost", "item_quantity", "remark"]);

        return PromotionsResource::collection($promotions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePromotionRequest $request)
    {
        Promotion::create([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,
            'expired_at' => HelperController::handleToDateString($request->expired_at),
            'started_at' => HelperController::handleToDateString($request->started_at),
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Promotion saved successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $promotion = Promotion::find($id);
        if (is_null($promotion)) {
            return response()->json([
                "message" => "promotion not found"
            ], 404);
        }
        return new PromotionsDetailResource($promotion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromotionRequest $request, string $id)
    {
        logger($request);
        $promotion = Promotion::find($id);
        if (is_null($promotion)) {
            return response()->json([
                "message" => "promotion not found"
            ], 404);
        }
        $promotion->name = $request->name ?? $promotion->name;
        $promotion->type = $request->type ?? $promotion->type;
        $promotion->amount = $request->amount ?? $promotion->amount;
        $promotion->started_at = $request->started_at ? HelperController::handleToDateString($request->started_at) : $promotion->started_at;
        $promotion->expired_at = $request->expired_at ? HelperController::handleToDateString($request->expired_at) : $promotion->expired_at;
        $promotion->user_id = Auth::id();

        $promotion->update();

        return  response()->json(['message' => "promotion has been updated successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $promotion = Promotion::find($id);

        if (!$promotion) {
            return response()->json(['error' => 'promotion not found'], 404);
        }

        // Delete the promotion
        $promotion->delete();

        return response()->json(['message' => 'Promotion deleted successfully']);
    }
}
