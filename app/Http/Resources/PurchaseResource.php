<?php

namespace App\Http\Resources;

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
            'item_quantity' => $this->item_quantity,
            'cost' => $this->cost,
            'place' => $this->place,
            'all_received' => $this->all_received ? "true" : "false",
            'remark' => $this->remark,
        ];
    }
}
