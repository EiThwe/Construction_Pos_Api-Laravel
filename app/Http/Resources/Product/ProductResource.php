<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "image" => $this->image,
            "actual_price" => $this->actual_price,
            "primary_price" => $this->primary_price,
            "unit" => $this->primary_unit,
            "categories" => "စမ်းသပ်ဆဲ"
        ];
    }
}
