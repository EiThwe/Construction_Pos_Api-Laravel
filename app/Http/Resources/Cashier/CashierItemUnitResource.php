<?php

namespace App\Http\Resources\Cashier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashierItemUnitResource extends JsonResource
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
            "unit_id" => encrypt($this->unit_id),
            "name" => $this->unit->name,
            "price" => $this->price,
        ];
    }
}
