<?php


use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\PaySalaryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Voucher\CheckoutController;
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

        Route::apiResource("users", UserController::class);
        Route::post("users/pay-salary/{id}", [PaySalaryController::class, "paySalary"]);

        Route::apiResource("expenses", ExpenseController::class);

        Route::apiResource("promotions", PromotionController::class);

        Route::apiResource("categories", CategoryController::class);

        Route::apiResource("products", ProductController::class)->except("update");
        Route::post("products/{id}/update", [ProductController::class, "update"]);

        Route::apiResource("stocks", StockController::class);

        Route::apiResource("units", UnitController::class);

        Route::apiResource("debts", DebtController::class)->except("update");
        Route::post("debts/pay", [DebtController::class, "payDebt"]);

        Route::apiResource("purchases", PurchaseController::class)->except("update");
        Route::post("purchases/{id}/records", [PurchaseController::class, "addRecords"]);
        Route::post("purchases/{id}/receive", [PurchaseController::class, "allReceive"]);

        Route::post("/checkout", [CheckoutController::class, "checkout"]);

        Route::apiResource("app-settings", AppSettingController::class)->only(["index", "store"]);
    });

    Route::post("auth/login", [AuthController::class, 'login']);
});
