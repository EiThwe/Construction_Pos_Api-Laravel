<?php

namespace App\Http\Controllers\Voucher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Http\Resources\Voucher\VoucherDetailResource;
use App\Http\Resources\VouchersResource;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);
        $vouchers = HelperController::findAllQuery(Voucher::class, $request, []);

        return VouchersResource::collection($vouchers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);
        $voucher = Voucher::find(decrypt($id));
        if (is_null($voucher)) {
            return response()->json(["message" => "ဘောက်ချာမရှိပါ"], 400);
        }

        return new VoucherDetailResource($voucher);
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
        $voucher = Voucher::find(decrypt($id));

        if (!$voucher) {

            return response()->json(["message" => "ဘောက်ချာမရှိပါ", 400]);
        }

        $voucher->delete();

        return response()->json(["message" => "ဘောက်ချာဖျက်ခြင်း အောင်မြင်ပါသည်"]);
    }
}
