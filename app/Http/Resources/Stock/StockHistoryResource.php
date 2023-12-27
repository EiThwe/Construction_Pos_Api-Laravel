<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockHistoryResource extends JsonResource
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
            "quantity" => $this->quantity,
            "cost" => $this->cost,
            "date" => $this->created_at,
        ];
    }
}
