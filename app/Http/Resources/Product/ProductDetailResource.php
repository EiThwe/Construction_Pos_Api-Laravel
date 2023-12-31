<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\ProductCategoryResource;
use App\Http\Resources\Stock\StockHistoryResource;
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
            "unit" => $this->unit->name,
            "remark" => $this->remark,
            "categories" => ProductCategoryResource::collection($this->categories),
            "stock_histories" => StockHistoryResource::collection($this->stocks),
            "units" => [$this->unit, ...$this->productUnits],
        ];
    }
}
