<?php

namespace App\Http\Controllers;

use App\Http\Requests\Promotion\RemovePromotionRequest;
use App\Http\Requests\Promotion\SetPromotionRequest;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Http\Resources\PromotionsDetailResource;
use App\Http\Resources\PromotionsResource;
use App\Models\Product;
use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PromotionController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         try {
    //             $this->authorize("checkPermission", "manager");
    //         } catch (\Throwable $th) {
    //             return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);
    //         }

    //         return $next($request);
    //     });
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        if ($request->has("only_active")) {
            $additional = [["status", "=", "active"]];
        } else {
            $additional = [];
        }

        $promotions = HelperController::findAllQuery(Promotion::class, $request, ["place", "cost", "item_quantity", "remark"], $additional);

        return PromotionsResource::collection($promotions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePromotionRequest $request)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        Promotion::create([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,
            'remark' => $request->remark,
            'expired_at' => HelperController::handleToDateString($request->expired_at),
            'started_at' => HelperController::handleToDateString($request->started_at),
            'user_id' => Auth::id(),
            'status' => 'active'
        ]);

        return response()->json(['message' => 'Promotion saved successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

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
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

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

        return  response()->json(['message' => "ပြုပြင်ခြင်းအောင်မြင်ပါသည်"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        $promotion = Promotion::find(decrypt($id));

        if (!$promotion) {
            return response()->json(['error' => 'ပရိုမိုးရှင်းမရှိပါ'], 404);
        }

        // Delete the promotion
        $promotion->delete();

        return response()->json(['message' => 'ပရိုမိုးရှင်းဖျက်သိမ်းခြင်း အောင်မြင်ပါသည်']);
    }

    public function setPromotions(SetPromotionRequest $request)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

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

    public function removePromotions(RemovePromotionRequest $request)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        $product_ids = array_map(function ($product_id) {
            return decrypt($product_id);
        }, $request->product_ids);

        Product::whereIn('id', $product_ids)
            ->update(['promotion_id' => null]);

        return response()->json(['message' => 'အောင်မြင်ပါသည်']);
    }

    public function deactivateExpiredPromotions()
    {
        if (!Gate::allows("checkPermission", "all")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        $currentDate = Carbon::now();

        $expiredPromotions = Promotion::where("status", "active")->whereDate("expired_at", "<", $currentDate);

        $expiredPromotionIds = $expiredPromotions->select("id")->get();

        $expiredPromotions->update(["status" => "expired"]);

        Product::whereIn('promotion_id', $expiredPromotionIds)
            ->update(['promotion_id' => null]);

        return response()->json(['message' => 'အောင်မြင်ပါသည်']);
    }
}
