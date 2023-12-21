<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayDebtRequest;
use App\Http\Requests\StoreDebtRequest;
use App\Http\Resources\DebtDetailResource;
use App\Http\Resources\DebtResource;
use App\Models\Debt;
use App\Models\DebtHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $debts = HelperController::findAllQuery(Debt::class, $request, ["actual_amount", "name", "phone", "address", "left_amount"]);

        return DebtResource::collection($debts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDebtRequest $request)
    {
        Debt::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "address" => $request->address,
            "user_id" => Auth::id(),
            "voucher_id" => 1,
            "actual_amount" => $request->actual_amount,
            "left_amount" => $request->actual_amount,
            "remark" => $request->remark,
        ]);

        return response()->json(["message" => "အကြွေးစာရင်းတည်ဆောက်မှု အောင်မြင်ပါသည်"], 201);
    }

    public function payDebt(PayDebtRequest $request)
    {
        $debt = Debt::where("id", $request->debt_id)->first();

        if (is_null($debt)) {
            return response()->json([
                "message" => "အကြွေးစာရင်းမရှိပါ"
            ], 404);
        }

        if ($debt->left_amount - $request->amount >= 0) {
            $debt->left_amount =  $debt->left_amount - $request->amount;
        } else {
            return response()->json(["message" => "ပေးချေသောပမာဏက အကြွေးပမာဏထက်ကျော်လွန်နေပါသည်"], 400);
        }

        $debt->update();

        DebtHistory::create([
            "amount" => $request->amount,
            "debt_id" => $request->debt_id,
            "user_id" => Auth::id()
        ]);

        return response()->json(["message" => "အကြွေးပေးချေမှု ‌အောင်မြင်ပါသည်"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $debt = Debt::where("id", $id)->first();

        if (is_null($debt)) {
            return response()->json([
                "message" => "အကြွေးစာရင်းမရှိပါ"
            ], 404);
        }

        return new DebtDetailResource($debt);
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
        $debt = Debt::find($id);
        if (is_null($debt)) {
            return response()->json([
                "message" => "အကြွေးစာရင်းမရှိပါ"
            ], 404);
        }

        $debt->delete();

        return response()->json([
            "message" => "အကြွေးစာရင်းဖျက်သိမ်းမှု အောင်မြင်ပါသည်"
        ]);
    }
}
