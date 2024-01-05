<?php

namespace App\Http\Resources\Voucher;

use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "staff" => $this->user->name,
            "voucher_no" => $this->voucher_number,
            "time" => HelperController::parseReturnDate($this->created_at, true),
            "item_count" => $this->item_count,
            "cost" => $this->cost,
            "voucher_records" => VoucherRecordResource::collection($this->voucherRecords)
        ];
    }
}
