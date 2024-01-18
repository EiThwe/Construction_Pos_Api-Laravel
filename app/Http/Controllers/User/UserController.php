<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\CheckPermissionController;
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
            "phone" => "required|min:6",
            "birth_date" => "required",
            "join_date" => "required",
            "gender" => "required|in:ကျား,မ",
            "role" => "required|in:admin,manager,cashier,staff",
            "salary" => "required",
        ]);

        User::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "birth_date" => HelperController::handleToDateString($request->birth_date),
            "join_date" => HelperController::handleToDateString($request->join_date),
            "gender" => $request->gender,
            "address" => $request->address,
            "password" => Hash::make($request->password),
            "role" => $request->role,
            "position" => $request->position,
            "salary" => $request->salary,
            "profile" => HelperController::handleLogoUpload($request->file('profile'), null)
        ]);

        return response()->json([
            "message" => "အကောင့်ထည့်သွင်းခြင်း အောင်မြင်ပါသည်",
        ]);
    }

    public function show(string $id)
    {
        $user = User::find(decrypt($id));
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
            "phone" => "numeric|min:6",
            "birth_date" => "string",
            "join_date" => "string",
            "gender" => "in:ကျား,မ",
            "role" => "in:admin,manager,cashier,staff",
            "salary" => "numeric",
        ]);

        $user = User::find(decrypt($id));

        if (!$user) {
            return response()->json(["message" => "အကောင့်ရှာမတွေ့ပါ"], 400);
        }

        // Update the fields
        $user->update([
            'name' => $request->name ?? $user->name,
            'phone' => $request->phone ?? $user->phone,
            'birth_date' => $request->birth_date ? HelperController::handleToDateString($request->birth_date) : $user->birth_date,
            'join_date' => $request->join_date ? HelperController::handleToDateString($request->join_date) : $user->join_date,
            'gender' => $request->gender ?? $user->gender,
            'role' => $request->role ?? $user->role,
            'position' => $request->position ?? $user->position,
            'address' => $request->address ?? $user->address,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'salary' => $request->salary ?? $user->salary,
            'profile' => $request->file("profile") ? HelperController::handleLogoUpload($request->file('profile'), $user->profile) : $user->profile,
        ]);

        return response()->json(["message" => "အကောင့်ပြင်ဆင်ခြင်း အောင်မြင်ပါသည်"]);
    }

    public function destroy(string $id)
    {
        $user = User::find(decrypt($id));
        $user->delete();

        return response()->json(["message" => "အကောင့်ပယ်ဖျက်ခြင်း အောင်မြင်ပါသည်"]);
    }
}
