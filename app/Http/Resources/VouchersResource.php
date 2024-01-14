<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VouchersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => encrypt($this->id),
            "sale_person" => $this->user->name,
            "voucher_no" => $this->voucher_number,
            "time" => HelperController::parseReturnDate($this->created_at, true),
            "item_count" => $this->item_count,
            "cash" => $this->cost
        ];
    }
}
