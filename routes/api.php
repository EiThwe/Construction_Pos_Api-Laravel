<?php


use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Dashboard\BestSellersController;
use App\Http\Controllers\Dashboard\ExpenseChartController;
use App\Http\Controllers\Dashboard\ProfitChartController;
use App\Http\Controllers\Dashboard\RevenueChartController;
use App\Http\Controllers\Dashboard\StatsController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\PaySalaryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Voucher\CashierController;
use App\Http\Controllers\Voucher\CheckoutController;
use App\Http\Controllers\Voucher\VoucherController;
use App\Models\Customer;
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

        Route::apiResource("users", UserController::class)->except("update");
        Route::post("users/{id}/edit", [UserController::class, "update"]);
        Route::post("users/pay-salary/{id}", [PaySalaryController::class, "paySalary"]);

        Route::apiResource("expenses", ExpenseController::class);

        Route::apiResource("promotions", PromotionController::class);

        Route::apiResource("categories", CategoryController::class);

        Route::get("cashiers", [CashierController::class, "index"]);

        Route::apiResource("vouchers", VoucherController::class)->only(["index", "show"]);

        Route::apiResource("products", ProductController::class)->except("update");
        Route::post("products/{id}/update", [ProductController::class, "update"]);
        Route::get("products/{id}/units", [ProductController::class, "productUnits"]);
        Route::get("products/{id}/stocks", [ProductController::class, "productStockHistories"]);

        Route::apiResource("stocks", StockController::class);
        Route::get("lowstocks", [StockController::class, "lowStocks"]);

        Route::apiResource("units", UnitController::class);

        Route::apiResource("debts", DebtController::class)->except("update");
        Route::post("debts/pay", [DebtController::class, "payDebt"]);

        Route::apiResource("purchases", PurchaseController::class)->except("update");
        Route::post("purchases/{id}/records", [PurchaseController::class, "addRecords"]);
        Route::post("purchases/{id}/receive", [PurchaseController::class, "allReceive"]);

        Route::post("checkout", [CheckoutController::class, "checkout"]);

        Route::apiResource("customers", CustomerController::class)->except("update");
        Route::post("customers/update/{id}", [CustomerController::class, "update"]);
        Route::get("customers/{id}/debts", [CustomerController::class, "debtRecords"]);

        Route::get("sale/isopen", [ReportController::class, "isOpen"]);
        Route::post("sale/daily/close", [ReportController::class, "dailyClose"]);
        Route::post("sale/monthly/close", [ReportController::class, "monthlyClose"]);
        Route::get("sale/daily/list", [ReportController::class, "dailyList"]);
        Route::get("sale/monthly/list", [ReportController::class, "monthlyList"]);

        Route::get("dashboard/bestsellers", [BestSellersController::class, "bestSellers"]);
        Route::get("dashboard/stats", [StatsController::class, "getStats"]);
        Route::get("dashboard/profit", [ProfitChartController::class, "get"]);
        Route::get("dashboard/expense", [ExpenseChartController::class, "get"]);
        Route::get("dashboard/revenue", [RevenueChartController::class, "get"]);
    });
    Route::apiResource("app-settings", AppSettingController::class)->only(["index", "store"]);

    Route::post("auth/login", [AuthController::class, 'login']);
});
