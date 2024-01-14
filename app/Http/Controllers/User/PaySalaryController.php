<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
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
            "pay_month" => "required|string",
        ]);

        $user = User::find(decrypt($id));

        if (is_null($user)) {
            return response()->json([
                "message" => "အကောင့်ရှာမတွေ့ပါ"
            ], 404);
        }

        PaySalary::create([
            "actual_salary" => $user->salary,
            "type" => $request->type,
            "amount" => $request->amount,
            "user_id" => decrypt($id),
            "pay_month" => $request->pay_month,
            "created_by" => Auth::user()->name,
        ]);

        return response()->json(['message' => "လစာပေးချေမှု အောင်မြင်ပါသည်"]);
    }
}
