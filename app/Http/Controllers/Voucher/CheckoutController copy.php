<?php

namespace App\Http\Controllers\Voucher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\CheckoutRequest;
use App\Models\ConversionFactor;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class CheckoutController extends Controller
{
    public function checkout(CheckoutRequest $request)
    {
        // this is copy
        $voucher_records = [];
        $total_cost = 0;
        $total_profit = 0;
        $total_quantity = 0;

        foreach ($request->items as $item) {
            $product = Product::find($item["product_id"]);

            logger($product);

            if ($product->primary_unit_id === $item["unit_id"]) {
                $new_stock =  $product->stock - $item["quantity"];

                if ($new_stock < 0) {
                    //
                }

                $cost = $product->primary_price * $item["quantity"];

                $actual_cost = $product->actual_price * $item["quantity"];

                $total_cost += $cost;

                $total_profit += $cost - $actual_cost;

                $total_quantity += $item["quantity"];

                $product->stock = $new_stock;

                $product->update();

                $voucher_records[] = [
                    "unit_id" => $item["unit_id"],
                    "product_id" => $item["product_id"],
                    "quantity" => $item["quantity"],
                    "cost" => $cost
                ];
            } else {
                $productUnit = array_filter($product->productUnits, function ($productUnit) use ($item) {
                    return $productUnit["unit_id"] === $item["unit_id"];
                });

                if (!empty($foundUnit)) {
                    //
                }

                $conversion = ConversionFactor::where("from_unit_id", $item["unit_id"])->where("to_unit_id", $product->primary_unit_id)->first();

                if (is_null($conversion)) {
                    return response()->json(["message" => "ယူနစ်ချိတ်ဆက်မှုမရှိပါ"], 400);
                }

                $reduce_quantity = 0;

                if ($conversion->status === "more") {
                    $reduce_quantity = $item["quantity"] * $conversion->value;
                } else if ($conversion->status === "less") {
                    $reduce_quantity = $item["quantity"] / $conversion->value;
                }

                $new_stock = $product->stock - $reduce_quantity;

                if ($new_stock < 0) {
                    //
                }

                $cost = $productUnit["price"] * $item["quantity"];

                $actual_cost = $product->actual_price * $reduce_quantity;

                $total_cost += $cost;

                $total_profit += $cost - $actual_cost;

                $total_quantity += $item["quantity"];

                $product->stock = $new_stock;

                $product->update();

                $voucher_records[] = [
                    "unit_id" => $item["unit_id"],
                    "product_id" => $item["product_id"],
                    "quantity" => $item["quantity"],
                    "cost" => $cost
                ];
            }
            # code...
        }

        $voucher = Voucher::create([
            "voucher_number" => Voucher::generateVoucherNumber(),
            "cost" => $total_cost,
            "profit" => $total_profit,
            "item_count" => $total_quantity,
            "user_id" => Auth::id()
        ]);

        $voucher_records = array_map(function ($record) use ($voucher) {
            $record["voucher_id"] = $voucher->id;
            $record["created_at"] = Date::now();
            $record["updated_at"] = Date::now();

            return $record;
        }, $voucher_records);

        VoucherRecord::insert($voucher_records);

        return response()->json(["message" => "အောင်မြင်ပါသည်"]);
    }
}
