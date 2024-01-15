<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Http\Resources\PromotionsDetailResource;
use App\Http\Resources\PromotionsResource;
use App\Models\Product;
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
            'remark' => $request->remark,
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
        $promotion = Promotion::find(decrypt($id));
        if (is_null($promotion)) {
            return response()->json([
                "message" => "ပရိုမိုးရှင်းမရှိပါ"
            ], 404);
        }
        return new PromotionsDetailResource($promotion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromotionRequest $request, string $id)
    {
        $promotion = Promotion::find(decrypt($id));
        if (is_null($promotion)) {
            return response()->json([
                "message" => "ပရိုမိုးရှင်းမရှိပါ"
            ], 404);
        }
        $promotion->name = $request->name ?? $promotion->name;
        $promotion->type = $request->type ?? $promotion->type;
        $promotion->amount = $request->amount ?? $promotion->amount;
        $promotion->remark = $request->remark ?? $promotion->remark;
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
        $promotion = Promotion::find(decrypt($id));

        if (!$promotion) {
            return response()->json(['error' => 'ပရိုမိုးရှင်းမရှိပါ'], 404);
        }

        // Delete the promotion
        $promotion->delete();

        return response()->json(['message' => 'ပရိုမိုးရှင်းဖျက်သိမ်းခြင်း အောင်မြင်ပါသည်']);
    }

    public function setPromotions(Request $request)
    {
        $promotion = Promotion::find(decrypt($request->promotion_id));

        if (!$promotion) {
            return response()->json(['error' => 'ပရိုမိုးရှင်းမရှိပါ'], 404);
        }

        $product_ids = array_map(function ($product_id) {
            return decrypt($product_id);
        }, $request->product_ids);

        Product::whereIn('id', $product_ids)
            ->update(['promotion_id' => $promotion->id]);

        return response()->json(['message' => 'အောင်မြင်ပါသည်']);
    }

    public function removePromotions(Request $request)
    {
        $product_ids = array_map(function ($product_id) {
            return decrypt($product_id);
        }, $request->product_ids);

        Product::whereIn('id', $product_ids)
            ->update(['promotion_id' => null]);

        return response()->json(['message' => 'အောင်မြင်ပါသည်']);
    }
}
