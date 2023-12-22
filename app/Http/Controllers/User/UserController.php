<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Resources\User\UserDetailResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = HelperController::findAllQuery(User::class, $request, ["name", "phone", "salary"]);

        return UserResource::collection($users);
    }

    public function store(Request $request)
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
            "password" => "required|min:6|confirmed",
            'profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            "profile" => HelperController::handleLogoUpload($request->file('profile'), null)
        ]);

        return response()->json([
            "message" => "အကောင့်ထည့်သွင်းခြင်း အောင်မြင်ပါသည်",
        ]);
    }

    public function show(string $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json([
                "message" => "အကောင့်ရှာမတွေ့ပါ"
            ], 404);
        }

        return new UserDetailResource($user);
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
            "password" => "min:6",
            "salary" => "numeric",
            'profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(["message" => "အကောင့်ရှာမတွေ့ပါ"], 400);
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
            'profile' => HelperController::handleLogoUpload($request->file('profile'), $user->profile)
        ]);

        return response()->json(["message" => "အကောင့်ပြင်ဆင်ခြင်း အောင်မြင်ပါသည်"]);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json(["message" => "အကောင့်ပယ်ဖျက်ခြင်း အောင်မြင်ပါသည်"]);
    }
}
