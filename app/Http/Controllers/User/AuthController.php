<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "name" => "required",
            "password" => "required|min:6"
        ]);

        if (!Auth::attempt($request->only('name', 'password'))) {
            return response()->json([
                "message" => "မှားယွင်းနေပါသည်",
            ], 400);
        };

        $token = Auth::user()->createToken($request->has("device") ? $request->device : 'unknown')->plainTextToken;

        $userInfo = Auth::user();

        return response()->json([
            "message" => "အကောင့်ဝင်ခြင်း အောင်မြင်ပါသည်",
            "token" => $token,
            "role" => Auth::user()->role,
            "user_info" => [
                "name" => $userInfo->name,
                "profile" =>  HelperController::parseReturnImage($userInfo->profile),
                "normal_id" => $userInfo->id,
                "gender" => $userInfo->gender

            ]
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "အကောင့်ထွက်ခြင်း အောင်မြင်ပါသည်"
        ]);
    }
}
