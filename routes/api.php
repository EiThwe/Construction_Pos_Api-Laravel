<?php


use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PaySalaryController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Resources\PromotionsResource;
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
    Route::post("users/create", [AuthController::class, "createUser"]);
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix("users")->group(function () {
            Route::post("logout", [AuthController::class, 'logout']);
            Route::put("update/{id}", [AuthController::class, 'update']);

            Route::post("pay-salary/{id}", [PaySalaryController::class, "paySalary"]);
        });

        Route::apiResource("expense", ExpenseController::class)->except("show");
        
        Route::apiResource("promotions", PromotionController::class)->except("show");

        Route::apiResource("debts", DebtController::class)->except("update");
        Route::post("debts/pay", [DebtController::class, "payDebt"]);
        Route::get('/purchases', [PurchaseController::class, 'index']);
        Route::get('/purchases/{id}', [PurchaseController::class, 'show']);
        Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy']);
        Route::post("purchases/create", [PurchaseController::class, "purchase"]);



        Route::apiResource("app-settings", AppSettingController::class)->only(["index", "update"]);
    });
    Route::post("login", [AuthController::class, 'login']);
});
