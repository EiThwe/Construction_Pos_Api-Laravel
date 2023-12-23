<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = HelperController::findAllQuery(Product::class, $request, ["name", "primary_price", "actual_price"]);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        $product = Product::create([
            "name" => $request->name,
            "actual_price" => $request->actual_price,
            "primary_unit" => $request->primary_unit,
            "primary_price" => $request->primary_price,
            "remark" => $request->remark,
            "stock" => 0,
            "image" => HelperController::handleLogoUpload($request->file('image'), null),
            "user_id" => Auth::id()
        ]);

        $units = array_map(function ($unit) use ($product) {
            $unit["product_id"] = $product->id;

            return $unit;
        }, $request->units);

        ProductUnit::insert($units);

        return response()->json(["message" => "ပစ္စည်းအသစ်ပြုလုပ်ခြင်း အောင်မြင်ပါသည်"]);
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
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(["message" => "ပစ္စည်းမရှီပါ"], 400);
        }

        $product->name = $request->name ?? $product->name;
        $product->actual_price = $request->actual_price ?? $product->actual_price;
        $product->primary_unit = $request->primary_unit ?? $product->primary_unit;
        $product->primary_price = $request->primary_price ?? $product->primary_price;
        $product->remark = $request->remark ?? $product->remark;
        $product->image = HelperController::handleLogoUpload($request->file('image'), null);
        $product->user_id = Auth::id();

        $product->update();

        return response()->json(["message" => "ပစ္စည်းပြင်ဆင်ခြင်း အောင်မြင်ပါသည်"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json(["message" => "ပစ္စည်းဖျက်သိမ်းခြင်း အောင်မြင်ပါသည်"]);
    }
}
