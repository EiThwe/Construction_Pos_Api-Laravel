<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
use App\Http\Resources\Voucher\VoucherRecordResource;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DebtDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $voucher_records = VoucherRecord::where("voucher_id", $this->voucher_id)->get();


        return [
            "id" => encrypt($this->id),
            "actual_amount" => $this->actual_amount,
            "left_amount" => $this->left_amount,
            "name" => $this->customer->name,
            "customer_id" => encrypt($this->customer->id),
            "phone" => $this->customer->phone,
            "address" => $this->customer->address,
            "date" => HelperController::parseReturnDate($this->created_at, true),
            "staff" => $this->user->name,
            "debt_histories" => DebtHistoryResource::collection($this->debt_histories),
            "items" => VoucherRecordResource::collection($voucher_records),
            "is_left" => $this->left_amount == 0 ? false : true
        ];
    }
}
