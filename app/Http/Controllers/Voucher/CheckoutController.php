<?php

namespace App\Http\Controllers\Voucher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Requests\Voucher\CheckoutRequest;
use App\Http\Resources\Voucher\VoucherRecordResource;
use App\Models\ConversionFactor;
use App\Models\Debt;
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
        return HelperController::transaction(function () use ($request) {
            $voucher_records = [];
            $total_cost = 0;
            $total_profit = 0;
            $total_quantity = 0;
            $total_promotion_amount = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item["product_id"]);

                $promotion = $product->promotion;

                if ($product->primary_unit_id === $item["unit_id"]) {
                    $new_stock =  $product->stock - $item["quantity"];

                    if ($new_stock < 0) {
                        throw new \Exception("စတော့မလုံလောက်ပါ");
                    }

                    $cost = 0;

                    if (!empty($promotion)) {
                        $cost = $product->primary_price * $item["quantity"];
                        $promotion_amount = 0;

                        if ($promotion->type === "percentage") {
                            $promotion_amount = ($cost * $promotion->amount / 100);
                        } else {
                            $promotion_amount = $promotion->amount;
                        }

                        $cost = $cost - $promotion_amount;
                        $total_promotion_amount += $promotion_amount;
                    } else {
                        $cost = $product->primary_price * $item["quantity"];
                    }

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
                    $productUnits = array_filter(json_decode($product->productUnits, true) ?? [], function ($productUnit) use ($item) {
                        return $productUnit["unit_id"] === $item["unit_id"];
                    });

                    if (empty($productUnits)) {
                        throw new \Exception("ပစ္စည်းအတွက်ယူနစ်သတ်မှတ်ထားခြင်းမရှိပါ");
                    }

                    $productUnit = $productUnits[0];

                    $conversion = ConversionFactor::where("from_unit_id", $item["unit_id"])->where("to_unit_id", $product->primary_unit_id)->first();

                    if (is_null($conversion)) {
                        throw new \Exception("ယူနစ်ချိတ်ဆက်ထားခြင်းမရှိပါ");
                    }

                    $reduce_quantity = 0;

                    if ($conversion->status === "more") {
                        $reduce_quantity = $item["quantity"] * $conversion->value;
                    } else if ($conversion->status === "less") {
                        $reduce_quantity = $item["quantity"] / $conversion->value;
                    }

                    $new_stock = $product->stock - $reduce_quantity;

                    if ($new_stock < 0) {
                        throw new \Exception("စတော့မလုံလောက်ပါ");
                    }

                    $cost = 0;

                    if (!empty($promotion)) {
                        $cost = $productUnit["price"] * $item["quantity"];
                        $promotion_amount = 0;

                        if ($promotion->type === "percentage") {
                            $promotion_amount = ($cost * $promotion->amount / 100);
                        } else {
                            $promotion_amount = $promotion->amount;
                        }

                        $cost = $cost - $promotion_amount;
                        $total_promotion_amount += $promotion_amount;
                    } else {
                        // Use array syntax to access 'price' key
                        $cost = $productUnit["price"] * $item["quantity"];
                    }

                    $actual_cost = $product->actual_price * $reduce_quantity;

                    $total_cost += $cost;

                    $total_profit += $cost - $actual_cost;

                    $total_quantity +=  $reduce_quantity;

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



            if (($request->pay_amount + $request->reduce_amount) < $total_cost) {
                $debt_amount = $total_cost - ($request->pay_amount + $request->reduce_amount);
                $change = 0;
                $is_debt = true;
            } else {
                $change = ($request->pay_amount + $request->reduce_amount) - $total_cost;
                $debt_amount = 0;
                $is_debt = false;
            }


            $voucher = Voucher::create([
                "voucher_number" => Voucher::generateVoucherNumber(),
                "cost" => $total_cost,
                "profit" => $total_profit,
                "promotion_amount" => $total_promotion_amount,
                "pay_amount" => $request->pay_amount,
                "reduce_amount" => $request->reduce_amount,
                "change" => $change,
                "debt_amount" => $debt_amount,
                "item_count" => $total_quantity,
                "user_id" => Auth::id()
            ]);

            if ($is_debt) {
                Debt::create([
                    "voucher_id" => $voucher->id,
                    "user_id" => Auth::id(),
                    "actual_amount" => $debt_amount,
                    "left_amount" => $debt_amount,
                    "customer_id" => $request->customer_id,
                    "remark" => $request->remark
                ]);
            }

            $voucher_records = array_map(function ($record) use ($voucher) {
                $record["voucher_id"] = $voucher->id;
                $record["created_at"] = Date::now();
                $record["updated_at"] = Date::now();

                return $record;
            }, $voucher_records);

            VoucherRecord::insert($voucher_records);

            $insertedRecords = VoucherRecord::where('voucher_id', $voucher->id)->get();

            return response()->json(["message" => "အောင်မြင်ပါသည်", "data" => [
                "date" => HelperController::parseReturnDate($voucher->created_at, true),
                "voucher_number" => $voucher->voucher_number,
                "staff" => $voucher->user->name,
                "cost" => $total_cost,
                "pay_amount" => $voucher->pay_amount,
                "reduce_amount" => $voucher->reduce_amount,
                "promotion_amount" => $total_promotion_amount,
                "change" => $change,
                "debt_amount" => $debt_amount,
                "items" => VoucherRecordResource::collection($insertedRecords)
            ]]);
        });
    }
}
