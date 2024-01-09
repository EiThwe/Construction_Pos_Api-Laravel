<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayDebtRequest;
use App\Http\Requests\StoreDebtRequest;
use App\Http\Resources\DebtDetailResource;
use App\Http\Resources\DebtResource;
use App\Models\Debt;
use App\Models\DebtHistory;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $debts = Debt::when($request->has("search"), function ($query) use ($request) {
            $search = $request->search;

            $query->where(function (Builder $builder) use ($search) {
                $builder->where("actual_amount", 'like', '%' . $search . '%');
                $builder->orWhere("left_amount", 'like', '%' . $search . '%');
            });

            $query->orWhereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                $q->orWhere('phone', 'like', '%' . $search . '%');
            });
        })
            ->when($request->has('start_date') && $request->has('end_date'), function ($query) use ($request) {
                $query->whereBetween('created_at', [$request->start_date, Carbon::parse($request->end_date)->addDay()]);
            })
            ->paginate($request->limit ?? 20)
            ->withQueryString();


        return DebtResource::collection($debts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDebtRequest $request)
    {
        Debt::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "address" => $request->address,
            "user_id" => Auth::id(),
            "voucher_id" => 1,
            "actual_amount" => $request->actual_amount,
            "left_amount" => $request->actual_amount,
            "remark" => $request->remark,
        ]);

        return response()->json(["message" => "အကြွေးစာရင်းတည်ဆောက်မှု အောင်မြင်ပါသည်"], 201);
    }

    public function payDebt(PayDebtRequest $request)
    {
        $debt = Debt::where("id", $request->debt_id)->first();

        if (is_null($debt)) {
            return response()->json([
                "message" => "အကြွေးစာရင်းမရှိပါ"
            ], 404);
        }

        if ($debt->left_amount - $request->amount >= 0) {
            $debt->left_amount =  $debt->left_amount - $request->amount;
        } else {
            return response()->json(["message" => "ပေးချေသောပမာဏက အကြွေးပမာဏထက်ကျော်လွန်နေပါသည်"], 400);
        }

        $debt->update();

        DebtHistory::create([
            "amount" => $request->amount,
            "debt_id" => $request->debt_id,
            "user_id" => Auth::id()
        ]);

        return response()->json(["message" => "အကြွေးပေးချေမှု ‌အောင်မြင်ပါသည်"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $debt = Debt::where("id", $id)->first();

        if (is_null($debt)) {
            return response()->json([
                "message" => "အကြွေးစာရင်းမရှိပါ"
            ], 404);
        }

        return new DebtDetailResource($debt);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $debt = Debt::find($id);
        if (is_null($debt)) {
            return response()->json([
                "message" => "အကြွေးစာရင်းမရှိပါ"
            ], 404);
        }

        $debt->delete();

        return response()->json([
            "message" => "အကြွေးစာရင်းဖျက်သိမ်းမှု အောင်မြင်ပါသည်"
        ]);
    }
}
