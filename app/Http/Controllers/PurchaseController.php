<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Resources\PurchaseDetailResource;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function purchase(StorePurchaseRequest $request)
    {
        // Validate the request data

        // Create a new Purchase
        $purchase = Purchase::create([
            'place' => $request->place,
            'cost' => $request->cost,
            'item_quantity' => count($request->purchase_items),
            'remark' => $request->remark,
            'user_id' => Auth::id(),
            'status' => 'left',
        ]);

        // Loop through purchase items and associate them with the purchase
        foreach ($request->purchase_items as $item) {
            $purchaseItem = new PurchaseItem([
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_id' => $item['unit_id'],
            ]);
            $purchase->purchaseItems()->save($purchaseItem);
        }

        return response()->json(['message' => 'Purchase saved successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retrieve a specific purchase with its associated items
        $purchase = Purchase::with('purchaseItems')->find($id);

        if (!$purchase) {
            return response()->json(['error' => 'Purchase not found'], 404);
        }

        return new PurchaseDetailResource($purchase);
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
        $purchase = Purchase::find($id);

        if (!$purchase) {
            return response()->json(['error' => 'Purchase not found'], 404);
        }

        // Delete associated purchase items
        $purchase->purchaseItems()->delete();

        // Delete the purchase
        $purchase->delete();

        return response()->json(['message' => 'Purchase deleted successfully']);
    }
}
