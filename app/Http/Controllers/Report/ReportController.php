<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\DebtHistory;
use App\Models\Expense;
use App\Models\PaySalary;
use App\Models\Product;
use App\Models\Record;
use App\Models\Stock;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function saleClose()
    {
        $today = Carbon::today();

        $todayFormatted = $today->toDateString();

        $record = Record::whereDate("created_at", $todayFormatted)->first();

        if (!is_null($record)) {
            return response()->json(["message" => "ဆိုင်ပိတ်သိမ်းပြီးပါပြီ"], 400);
        }

        $expenses = Expense::whereDate("created_at", $today)->get();
        $vouchers = Voucher::whereDate("created_at", $today)->get();
        $stocks = Stock::whereDate("created_at", $today)->get();
        $salaries = PaySalary::whereDate("created_at", $today)->get();
        $debt_histories = DebtHistory::whereDate("created_at", $today)->get();
        $products = Product::all();

        $product_amount = 0;

        foreach ($products as $product) {
            $product_amount += $product->stock * $product->actual_price;
        }

        $salary_cost = $salaries->sum("actual_salary") + $salaries->sum("amount");
        $stock_cost = $stocks->sum("cost");
        $expense_amount = $expenses->sum("amount");
        $revenue = $vouchers->sum("cost") - $vouchers->sum("debt_amount");
        $debt_amount = $debt_histories->sum("amount");

        $total_expense = $stock_cost + $expense_amount + $salary_cost;
        $total_revenue =  $revenue + $product_amount + $debt_amount;
        $total_profit = $total_revenue - $total_expense;

        Record::create([
            "expense" => $total_expense,
            "revenue" => $total_revenue,
            "profit" => $total_profit,
            "user_id" => Auth::id(),
            "status" => "daily"
        ]);

        return response()->json(["message" => "တစ်နေ့စာ စာရင်းချုပ်ခြင်းအောင်မြင်ပါသည်"]);
    }

    public function monthlyClose()
    {
        $month = Carbon::today()->month;

        $monthRecord = Record::whereMonth("created_at", $month)->where("status", "monthly")->first();

        if (!is_null($monthRecord)) {
            return response()->json(["message" => "ယခုလအတွက် စာရင်းချုပ်ပြီးပါပြီ"], 400);
        }

        $records = Record::whereMonth("created_at", $month)->where("status", "daily")->get();

        $total_expense = $records->sum("expense");
        $total_profit = $records->sum("profit");
        $total_revenue = $records->sum("revenue");

        Record::create([
            "expense" => $total_expense,
            "revenue" => $total_revenue,
            "profit" => $total_profit,
            "user_id" => Auth::id(),
            "status" => "monthly"
        ]);

        return response()->json(["message" => "လချုပ် စာရင်းချုပ်ခြင်းအောင်မြင်ပါသည်"]);
    }
}
