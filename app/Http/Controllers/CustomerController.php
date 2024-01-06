<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Resources\CustomersDetailResource;
use App\Http\Resources\CustomersResource;
use App\Http\Resources\DebtResource;
use App\Models\Customer;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->limit === "0") {
            return response()->json(["data" => []]);
        }

        $customers = HelperController::findAllQuery(Customer::class, $request, ["name", "phone", "address"]);

        return CustomersResource::collection($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        logger(HelperController::handleLogoUpload($request->file('profile'), null));
        Customer::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "address" => $request->address,
            "user_id" => Auth::id(),
            "profile" => HelperController::handleLogoUpload($request->file('profile'), null)
        ]);
        return response()->json(['message' => "ဝယ်သူအချက်အလက် ထည့်သွင်းခြင်း အောင်မြင်ပါသည်"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::find($id);

        if (is_null($customer)) {
            return response()->json([
                "message" => "ရှာမတွေ့ပါ"
            ], 404);
        }

        // $debtsTotal = 

        return new CustomersDetailResource($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::find($id);
        if (is_null($customer)) {
            return response()->json([
                "message" => "ရှာမတွေ့ပါ"
            ], 404);
        }
        $customer->name = $request->name ?? $customer->name;
        $customer->phone = $request->phone ?? $customer->phone;
        $customer->address = $request->address ?? $customer->address;
        $customer->profile = $request->file("profile") ? HelperController::handleLogoUpload($request->file('profile'), $customer->profile) : $customer->profile;
        $customer->update();

        return  response()->json(['message' => "ဝယ်သူအချက်အလက် ပြင်ဆင်ခြင်း အောင်မြင်ပါသည်"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::find($id);
        if (is_null($customer)) {
            return response()->json([
                "message" => "ရှာမတွေ့ပါ"
            ], 404);
        }

        $customer->delete();

        return response()->json([
            "message" => "ဖျက်ခြင်း အောင်မြင်ပါသည်"
        ], 200);
    }

    public function debtRecords(Request $request, string $id)
    {
        logger($id);
        $additionalConditions = [["customer_id", "=", $id]];
        $debts = HelperController::findAllQuery(Debt::class, $request, [], $additionalConditions);

        return DebtResource::collection($debts);
    }
}
