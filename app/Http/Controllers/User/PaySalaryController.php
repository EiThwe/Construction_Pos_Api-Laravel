<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaySalary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaySalaryController extends Controller
{
    public function paySalary(Request $request, string $id)
    {
        $this->validate($request, [
            "type" => "required|in:normal,bonus,reduce",
            "amount" => "required|numeric",
        ]);

        $user = User::find($id);
        if (is_null($user)) {
            return response()->json([
                "message" => "အကောင့်ရှာမတွေ့ပါ"
            ], 404);
        }

        PaySalary::create([
            "actual_salary" => $user->salary,
            "type" => $request->type,
            "amount" => $request->amount,
            "user_id" => $id,
            "created_by" => Auth::user()->name,
        ]);

        return response()->json(['message' => "လစာပေးချေမှု အောင်မြင်ပါသည်"], 201);
    }
}
