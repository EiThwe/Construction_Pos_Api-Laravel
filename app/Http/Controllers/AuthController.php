<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function createUser(Request $request)
    {
        $request->validate([
            "name" => "required|min:3",
            "phone" => "required|numeric|min:9",
            "birth_date" => "required",
            "join_date" => "required",
            "gender" => "required|in:male,female",
            "role" => "required|in:admin,manager,cashier",
            "address" => "required|min:50",
            "salary" => "required",
            "password" => "required|min:8|confirmed",
        ]);


        User::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "birth_date" => $request->birth_date,
            "join_date" => $request->join_date,
            "gender" => $request->gender,
            "address" => $request->address,
            "password" => Hash::make($request->password),
            "role" => $request->role,
            "salary" => $request->salary,
        ]);

        return response()->json([
            "message" => "user has been created successfully",
        ]);
    }

    public function login(Request $request)
    {

        $request->validate([
            "name" => "required",
            "password" => "required|min:8"
        ]);

        if (!Auth::attempt($request->only('name', 'password'))) {
            return response()->json([
                "message" => "name or password wrong",
            ]);
        }

        $token = Auth::user()->createToken($request->has("device") ? $request->device : 'unknown')->plainTextToken;

        return response()->json([
            "message" => "login successfully",
            "token" => $token
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "logout successful"
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            "name" => "min:3",
            "phone" => "numeric|min:9",
            "birth_date" => "string",
            "join_date" => "string",
            "gender" => "in:male,female",
            "role" => "in:admin,manager,cashier",
            "address" => "min:50",
            "password" => "min:8",
            "salary" => "numeric",
        ]);

        $user = User::where("id", $id);

        if (!$user) {
            return response()->json(["message" => "user not found"], 400);
        }

        // Update the fields
        $user->update([
            'name' => $request->name ?? $user->name,
            'phone' => $request->phone ?? $user->phone,
            'birth_date' => $request->birth_date ?? $user->birth_date,
            'join_date' => $request->join_date ?? $user->join_date,
            'gender' => $request->gender ?? $user->gender,
            'role' => $request->role ?? $user->role,
            'address' => $request->address ?? $user->address,
            'password' => $request->password ?? $user->password,
            'salary' => $request->salary ?? $user->salary,
        ]);

        return response()->json(["message" => "user updated successfully"]);
    }
}
