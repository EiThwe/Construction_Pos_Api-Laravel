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
            'place' => $this->place,
            'cost' => $this->cost,
            'item_quantity' => $this->item_quantity,
            'remark' => $this->remark,
            'all_received' => $this->all_received ? "true" : "false",
            'purchase_items' => PurchaseItemResource::collection($this->whenLoaded('purchaseItems')),
        ];
    }
}
