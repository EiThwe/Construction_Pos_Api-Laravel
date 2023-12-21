<?php


use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\PaySalaryController;
use App\Http\Controllers\User\UserController;

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
        Route::post("auth/logout", [AuthController::class, 'logout']);

        Route::prefix("users")->group(function () {
            Route::controller(UserController::class)->group(function () {
                Route::post("create",  "createUser");
                Route::put("update/{id}", 'update');
            });

            Route::post("pay-salary/{id}", [PaySalaryController::class, "paySalary"]);
        });

        Route::apiResource("expense", ExpenseController::class)->except("show");

        Route::apiResource("categories", CategoryController::class)->except("show");


        Route::apiResource("debts", DebtController::class)->except("update");
        Route::post("debts/pay", [DebtController::class, "payDebt"]);

        Route::apiResource("app-settings", AppSettingController::class)->only(["index", "update"]);
    });

    Route::post("auth/login", [AuthController::class, 'login']);
});
