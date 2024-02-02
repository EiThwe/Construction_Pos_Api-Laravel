<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Resources\Record\RecordResource;
use App\Models\DebtHistory;
use App\Models\Expense;
use App\Models\PaySalary;
use App\Models\Product;
use App\Models\Record;
use App\Models\Stock;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function dailyClose()
    {
        $today = Carbon::today();

        $record = Record::where("created_at", $today)->whereDate("status", "daily")->first();

        if (!is_null($record)) {
            return response()->json(["message" => "ဆိုင်ပိတ်သိမ်းပြီးပါပြီ"], 400);
        }

        $expenses = Expense::whereDate("created_at", $today)->get();

        $vouchers = Voucher::whereDate("created_at", $today)->get();
        if (count($vouchers) === 0) {
            return response()->json(["message" => "ယနေ့အတွက် ရောင်းချထားခြင်းမရှိပါ"], 400);
        }


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
            "voucher_count" => $vouchers->count(),
            "user_id" => Auth::id(),
            "status" => "daily"
        ]);

        return response()->json(["message" => "တစ်နေ့စာ စာရင်းချုပ်ခြင်းအောင်မြင်ပါသည်"]);
    }

    public function monthlyClose(Request $request)
    {
        if (!($request->date)) {
            return response(["message" => "လချုပ်ချုပ်မည့် လ နှင့် နှစ် လိုအပ်ပါသည်"], 400);
        }

        $month = Carbon::parse($request->date)->format("n");

        $monthRecord = Record::whereMonth("month_date", $month)->where("status", "monthly")->first();

        if (!is_null($monthRecord)) {
            return response()->json(["message" => "စာရင်းချုပ်ပြီးပါပြီ ထပ်မံချုပ်၍မရပါ"], 400);
        }

        $records = Record::whereMonth("created_at", $month)->where("status", "daily")->get();

        if (count($records) === 0) {
            return response(["message" => "ချုပ်မည့် လ တွင် နေ့ချုပ်စာရင်းများမရှိပါ"], 400);
        }

        $total_expense = $records->sum("expense");
        $total_profit = $records->sum("profit");
        $total_revenue = $records->sum("revenue");
        $total_voucher_count = $records->sum("voucher_count");

        Record::create([
            "expense" => $total_expense,
            "revenue" => $total_revenue,
            "profit" => $total_profit,
            "voucher_count" => $total_voucher_count,
            "user_id" => Auth::id(),
            "month_date" => Carbon::parse($request->date)->toDateString(),
            "status" => "monthly"
        ]);

        return response()->json(["message" => "လချုပ် စာရင်းချုပ်ခြင်းအောင်မြင်ပါသည်"]);
    }

    public function isOpen()
    {
        $status = $this->isSaleOpen();

        return response()->json(["data" => ["status" => $status]]);
    }

    public function dailyList(Request $request)
    {
        if (!Gate::allows("checkPermission", "all")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        $records = $this->getList($request, "daily");

        return RecordResource::collection($records);
    }

    public function monthlyList(Request $request)
    {
        if (!Gate::allows("checkPermission", "all")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        $records = $this->getList($request, "monthly");

        return RecordResource::collection($records);
    }

    private function getList(Request $request, string $status)
    {
        return Record::when($request->has("search"), function ($query) use ($request) {
            $search = $request->search;

            $query->where(function (Builder $builder) use ($search) {
                $builder->where("revenue", 'like', '%' . $search . '%');
                $builder->orWhere("profit", 'like', '%' . $search . '%');
                $builder->orWhere("expense", 'like', '%' . $search . '%');
            });

            $query->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        })
            ->when($request->has('start_date') && $request->has('end_date'), function ($query) use ($request) {
                $query->whereBetween('created_at', [$request->start_date, Carbon::parse($request->end_date)->addDay()]);
            })
            ->orderBy('created_at', 'desc')
            ->where("status", $status)
            ->paginate($request->limit ?? 20)
            ->withQueryString();
    }

    public function destroy(string $id)
    {
        $record = Record::find(decrypt($id));

        if (is_null($record)) {
            return response()->json(["message" => "စာရင်းမရှိပါ"], 404);
        }

        $record->delete();

        return response()->json(["message" => "စာရင်းပယ်ဖျက်ခြင်း အောင်မြင်ပါသည်"]);
    }

    static public function isSaleOpen()
    {
        $today = Carbon::today();
        $record = Record::where("status", "daily")->whereDate("created_at", $today)->first();

        return is_null($record) ? true : false;
    }
}
