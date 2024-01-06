<?php

namespace App\Http\Resources\Voucher;

use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "image" => HelperController::parseReturnImage($this->product->image),
            "name" => $this->product->name,
            "unit" => $this->unit->name,
            "quantity" => $this->quantity,
            "cost" => $this->cost,
        ];
    }
}
