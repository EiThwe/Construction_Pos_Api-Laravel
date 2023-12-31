<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
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
            'date' => HelperController::parseReturnDate($this->created_at),
            'purchase_records' => PurchaseRecordResource::collection($this->purchaseRecords),
            'purchase_items' => PurchaseItemResource::collection($this->whenLoaded('purchaseItems')),
        ];
    }
}
