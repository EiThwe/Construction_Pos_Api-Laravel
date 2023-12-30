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
        return response()->json(['message' => "Expense has been created successfully"], 201);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, string $id)
    {
        $expense = Expense::find($id);
        if (is_null($expense)) {
            return response()->json([
                "message" => "expense not found"
            ], 404);
        }
        $expense->description = $request->description ?? $expense->description;
        $expense->amount = $request->amount ?? $expense->amount;
        $expense->remark = $request->remark ?? $expense->remark;
        $expense->update();

        return  response()->json(['message' => "expense has been updated successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::find($id);
        if (is_null($expense)) {
            return response()->json([
                "message" => "Expense is not found"
            ], 404);
        }
        $expense->delete();
        return response()->json([
            "message" => "An expense is deleted successfully"
        ], 200);
    }
}
