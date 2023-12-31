<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_quantity' => $this->item_quantity,
            'cost' => $this->cost,
            'place' => $this->place,
            'status' => $this->status,
            'remark' => $this->remark,
            'date' => HelperController::parseReturnDate($this->created_at),
        ];
    }
}
