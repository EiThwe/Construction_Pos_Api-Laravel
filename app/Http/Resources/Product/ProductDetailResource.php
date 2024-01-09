<?php

namespace App\Http\Resources\Product;

use App\Http\Controllers\HelperController;
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
            "image" => HelperController::parseReturnImage($this->image),
            "actual_price" => $this->actual_price,
            "primary_price" => $this->primary_price,
            "primary_unit_id" => $this->primary_unit_id,
            "stock" => $this->stock,
            "unit" => $this->unit->name,
            "units" =>  [$this->unit, ...NestedUnitResource::collection($this->productUnits)],
            "remark" => $this->remark,
            "categories" => ProductCategoryResource::collection($this->categories),
            "stock_histories" => StockHistoryResource::collection($this->stocks),
            "product_units" => $this->productUnits

        ];
    }
}
