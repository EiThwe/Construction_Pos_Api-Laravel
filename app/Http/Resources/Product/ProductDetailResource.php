<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\ProductCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductDetailResource extends JsonResource
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
            "image" => asset(Storage::url($this->image)),
            "actual_price" => $this->actual_price,
            "primary_price" => $this->primary_price,
            "stock" => $this->stock,
            "primary_unit_id" => $this->primary_unit_id,
            "remark" => $this->remark,
            "categories" => ProductCategoryResource::collection($this->categories),
            "product_units" => $this->productUnits
        ];
    }
}
