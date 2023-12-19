<?php


use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::prefix("v1")->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix("users")->group(function () {
            Route::post("create", [AuthController::class, "create"]);
            Route::post("logout", [AuthController::class, 'logout']);
            Route::put("update/{id}", [AuthController::class, 'update']);
        });
      
        Route::apiResource("app-settings", AppSettingController::class)->only(["index","update"]);
    });
    Route::post("login", [AuthController::class, 'login']);
});
