<?php

namespace App\Http\Resources\Cashier;

use App\Http\Controllers\HelperController;
use App\Http\Resources\Category\ProductCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashierItemResource extends JsonResource
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
            "name" => $this->name,
            "stock" => $this->stock,
            "image" => HelperController::parseReturnImage($this->image),
            "primary_unit_id" => $this->primary_unit_id,
            "primary_price" => $this->primary_price,
            "categories" => ProductCategoryResource::collection($this->categories),
            "units" =>  [
                ["price" => $this->primary_price, "unit_id" => $this->unit->id, "name" => $this->unit->name, "id" => $this->id],
                ...CashierItemUnitResource::collection($this->productUnits)
            ],
            "promotion" => $this->promotion
        ];
    }
}
