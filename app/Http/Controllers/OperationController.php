<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class OperationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            try {
                $this->authorize("checkPermission", "all");
            } catch (\Throwable $th) {
                return response()->json(["message" => "Unauthorized"], 403);
            }

            if (!Hash::check($request->hash_code, env("OPERATION_HASH_CODE"))) {
                return response()->json(["message" => "Invalid hash code"], 400);
            }

            return $next($request);
        });
    }

    public function migration()
    {
        Artisan::call('migrate:fresh --seed');

        return response()->json(["message" => "operation successful"]);
    }

    public function storageLink()
    {
        Artisan::call('storage:link');

        return response()->json(["message" => "storage link successful"]);
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');

        return response()->json(["message" => "cache clera successful"]);
    }
}
