<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $expense = HelperController::findAllQuery(Expense::class, $request, ["description", "amount"]);

        return ExpenseResource::collection($expense);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $expense = Expense::create([
            "description" => $request->description,
            "amount" => $request->amount,
            "remark" => $request->remark,
            "user_id" => Auth::id(),
        ]);
        return response()->json(['message' => "ထွက်ငွေထည့်သွင်းခြင်း အောင်မြင်ပါသည်"], 201);
    }

    public function show(string $id)
    {
        $expense = Expense::find($id);

        if (is_null($expense)) {
            return response()->json([
                "message" => "ရှာမတွေ့ပါ"
            ], 404);
        }

        return new ExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, string $id)
    {
        $expense = Expense::find($id);
        if (is_null($expense)) {
            return response()->json([
                "message" => "ရှာမတွေ့ပါ"
            ], 404);
        }
        $expense->description = $request->description ?? $expense->description;
        $expense->amount = $request->amount ?? $expense->amount;
        $expense->remark = $request->remark ?? $expense->remark;
        $expense->update();

        return  response()->json(['message' => "ထွက်ငွေ ပြင်ဆင်ခြင်း အောင်မြင်ပါသည်"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::find($id);
        if (is_null($expense)) {
            return response()->json([
                "message" => "ရှာမတွေ့ပါ"
            ], 404);
        }

        $expense->delete();

        return response()->json([
            "message" => "ထွက်ငွေ ဖျက်ခြင်း အောင်မြင်ပါသည်"
        ], 200);
    }
}
