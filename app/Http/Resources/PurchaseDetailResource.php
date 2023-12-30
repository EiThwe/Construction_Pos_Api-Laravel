<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailResource extends JsonResource
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
            'place' => $this->place,
            'cost' => $this->cost,
            'item_quantity' => $this->item_quantity,
            'remark' => $this->remark,
            'status' => $this->status,
            'purchase_items' => PurchaseItemResource::collection($this->whenLoaded('purchaseItems')),
        ];
    }
}
