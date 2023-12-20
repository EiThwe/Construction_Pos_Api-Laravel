<?php

namespace App\Http\Controllers;

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
                "message" => "user not found"
            ], 404);
        }

        PaySalary::create([
            "actual_salary" => $user->salary,
            "type" => $request->type,
            "amount" => $request->amount,
            "user_id" => $id,
            "created_by" => Auth::user()->name,
        ]);

        return response()->json(['message' => "salary has been paid successfully"], 201);
    }
}
