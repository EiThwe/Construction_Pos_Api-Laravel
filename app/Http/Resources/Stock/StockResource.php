<?php

namespace App\Http\Resources\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StockResource extends JsonResource
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
            'image' => asset(Storage::url($this->image)),
            'name' => $this->name,
            'unit' => $this->unit->name,
            'sale_price' => $this->primary_price,
            'stock' => $this->stock,
            'stock_level' => $this->getStockLevel(),
        ];
    }

    private function getStockLevel(): string
    {
        if ($this->stock < 5) {
            return 'အလွန်နည်း';
        } elseif ($this->stock < 10) {
            return 'နည်း';
        } elseif ($this->stock < 20) {
            return 'ဝယ်ယူသင့်';
        } else {
            return 'ကောင်း';
        }
    }
}
