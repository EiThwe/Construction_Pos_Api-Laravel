<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
        }

        $token = Auth::user()->createToken($request->has("device") ? $request->device : 'unknown')->plainTextToken;

        return response()->json([
            "message" => "အကောင့်ဝင်ခြင်း အောင်မြင်ပါသည်",
            "token" => $token
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
