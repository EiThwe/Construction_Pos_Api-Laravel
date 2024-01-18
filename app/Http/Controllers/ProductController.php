<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProdcutUnitResource;
use App\Http\Resources\Product\ProductDetailResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Stock\StockHistoryResource;
use App\Models\Category;
use App\Models\ConversionFactor;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Stock;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         try {
    //             $this->authorize("checkPermission", "all");
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
        
        $products = HelperController::findAllQuery(Product::class, $request, ["name", "primary_price", "actual_price"]);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        if (!Gate::allows("checkPermission", "cashier")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        if ($request->units) {
            foreach ($request->units as $unit) {
                $isUnitRelation = ConversionFactor::where("from_unit_id", decrypt($request->primary_unit_id))
                    ->where("to_unit_id", $unit["unit_id"])->first();

                if (is_null($isUnitRelation)) {
                    $fromUnit = Unit::find(decrypt($request->primary_unit_id));
                    $toUnit = Unit::find($unit["unit_id"]);

                    return response()->json(["message" => "$fromUnit->name နှင့် $toUnit->name တို့ အပြန်အလှန် ယူနစ်ချိတ်ဆက်မှုမရှိပါ"], 400);
                }

                $isConverseUnitRelation = ConversionFactor::where("from_unit_id", $unit["unit_id"])
                    ->where("to_unit_id", decrypt($request->primary_unit_id))->first();

                if (is_null($isConverseUnitRelation)) {
                    $fromUnit = Unit::find(decrypt($request->primary_unit_id));
                    $toUnit = Unit::find($unit["unit_id"]);

                    return response()->json(["message" => "$fromUnit->name နှင့် $toUnit->name တို့ အပြန်အလှန် ယူနစ်ချိတ်ဆက်မှုမရှိပါ"], 400);
                }
            }
        }


        $product = Product::create([
            "name" => $request->name,
            "actual_price" => $request->actual_price,
            "primary_unit_id" => decrypt($request->primary_unit_id),
            "primary_price" => $request->primary_price,
            "remark" => $request->remark,
            "stock" => 0,
            "image" => HelperController::handleLogoUpload($request->file('image'), null),
            "user_id" => Auth::id()
        ]);

        foreach ($request->categories as $categoryId) {
            $category = Category::find($categoryId);

            $category->products()->attach($product->id);
        }

        if ($request->units) {
            $units = array_map(function ($unit) use ($product) {
                $unit["unit_id"] = $unit["unit_id"];
                $unit["product_id"] = $product->id;

                return $unit;
            }, $request->units);

            ProductUnit::insert($units);
        }

        return response()->json(["message" => "ပစ္စည်းအသစ်ပြုလုပ်ခြင်း အောင်မြင်ပါသည်"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = decrypt($id);

        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(["message" => "ပစ္စည်းမရှီပါ"], 400);
        }

        $response = new ProductDetailResource($product);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::find(decrypt($id));

        if (is_null($product)) {
            return response()->json(["message" => "ပစ္စည်းမရှီပါ"], 400);
        }

        if ($request->units) {
            foreach ($request->units as $unit) {
                $isUnitRelation = ConversionFactor::where("from_unit_id", decrypt($request->primary_unit_id))
                    ->where("to_unit_id", $unit["unit_id"])->first();

                if (is_null($isUnitRelation)) {
                    $fromUnit = Unit::find(decrypt($request->primary_unit_id));
                    $toUnit = Unit::find($unit["unit_id"]);

                    return response()->json(["message" => "$fromUnit->name နှင့် $toUnit->name တို့ အပြန်အလှန် ယူနစ်ချိတ်ဆက်မှုမရှိပါ"], 400);
                }

                $isConverseUnitRelation = ConversionFactor::where("from_unit_id", $unit["unit_id"])
                    ->where("to_unit_id", decrypt($request->primary_unit_id))->first();

                if (is_null($isConverseUnitRelation)) {
                    $fromUnit = Unit::find(decrypt($request->primary_unit_id));
                    $toUnit = Unit::find($unit["unit_id"]);

                    return response()->json(["message" => "$fromUnit->name နှင့် $toUnit->name တို့ အပြန်အလှန် ယူနစ်ချိတ်ဆက်မှုမရှိပါ"], 400);
                }
            }
        }


        $product->name = $request->name ?? $product->name;
        $product->actual_price = $request->actual_price ?? $product->actual_price;
        $product->primary_unit_id = decrypt($request->primary_unit_id) ?? $product->primary_unit_id;
        $product->primary_price = $request->primary_price ?? $product->primary_price;
        $product->remark = $request->remark ?? $product->remark;
        $product->image = $request->file('image') ? HelperController::handleLogoUpload($request->file('image'), null) : $product->image;
        $product->user_id = Auth::id();

        if ($request->categories) {
            $product->categories()->detach();

            foreach ($request->categories as $categoryId) {
                $category = Category::find($categoryId);

                $category->products()->attach($product->id);
            }
        }

        ProductUnit::where("product_id", $product->id)->delete();

        if ($request->units && count($request->units) > 0) {
            $units = array_map(function ($unit) use ($product) {
                $unit["product_id"] = $product->id;

                return $unit;
            }, $request->units);

            ProductUnit::insert($units);
        }


        $product->update();

        return response()->json(["message" => "ပစ္စည်းပြင်ဆင်ခြင်း အောင်မြင်ပါသည်"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find(decrypt($id));

        if (is_null($product)) {
            return response()->json(["message" => "ပစ္စည်းမရှီပါ"], 400);
        }

        $product->delete();

        return response()->json(["message" => "ပစ္စည်းဖျက်သိမ်းခြင်း အောင်မြင်ပါသည်"]);
    }

    public function productUnits(string $id)
    {
        $product = Product::find(decrypt($id));

        if (is_null($product)) {
            return response()->json(["message" => "ပစ္စည်းမရှီပါ"], 400);
        }

        return new ProdcutUnitResource($product);
    }

    public function productStockHistories(Request $request, string $id)
    {
        $additionalConditions = [["product_id", "=", decrypt($id)]];
        $stockHistories = HelperController::findAllQuery(Stock::class, $request, [], $additionalConditions);

        return StockHistoryResource::collection($stockHistories);
    }
}
