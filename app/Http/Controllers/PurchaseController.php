<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Resources\PurchaseDetailResource;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purchases = HelperController::findAllQuery(Purchase::class, $request, ["place", "cost", "item_quantity", "remark"]);

        return PurchaseResource::collection($purchases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        if (!Gate::allows("checkPermission", "cashier")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);
        $purchase = Purchase::create([
            'place' => $request->place,
            'cost' => $request->cost,
            'item_quantity' => count($request->purchase_items),
            'remark' => $request->remark,
            'user_id' => Auth::id(),
            'status' => 'left',
        ]);

        foreach ($request->purchase_items as $item) {
            $purchaseItem = new PurchaseItem([
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_id' => decrypt($item['unit_id']),
            ]);
            $purchase->purchaseItems()->save($purchaseItem);
        }

        return response()->json(['message' => 'ပရိုမိုးရှင်းထည့်သွင်းခြင်းအောင်မြင်ပါသည်']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $purchase = Purchase::with('purchaseItems')->find(decrypt($id));

        if (!$purchase) {
            return response()->json(['message' => 'ပရိုမိုးရှင်း ရှာမတွေ့ပါ'], 404);
        }

        return new PurchaseDetailResource($purchase);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        $purchase = Purchase::find(decrypt($id));

        if (!$purchase) {
            return response()->json(['message' => 'ပရိုမိုးရှင်း ရှာမတွေ့ပါ'], 404);
        }

        // Delete associated purchase items
        $purchase->purchaseItems()->delete();

        // Delete the purchase
        $purchase->delete();

        return response()->json(['message' => 'ပရိုမိုးရှင်းဖျက်သိမ်းခြင်းအောင်မြင်ပါသည်']);
    }

    public function addRecords(Request $request, string $id)
    {
        $request->validate([
            "description" => "required"
        ]);

        PurchaseRecords::create([
            "purchase_id" => decrypt($id),
            "user_id" => Auth::id(),
            "description" => $request->description
        ]);

        return response()->json(['message' => 'မှတ်တမ်းထည့်ခြင်းအောင်မြင်ပါသည်']);
    }

    public function allReceive(Request $request, string $id)
    {
        $purchase = Purchase::find(decrypt($id));

        if (is_null($purchase)) {
            return response()->json(["message" => "မှာယူမှတ်တမ်းမရှိပါ"], 400);
        }

        $purchase->status = "received";
        $purchase->update();

        return response()->json(["message" => "ပစ္စည်းအားလုံးရောက်ရှိကြောင်း အတည်ပြုပြီးပါပြီ"]);
    }
}
