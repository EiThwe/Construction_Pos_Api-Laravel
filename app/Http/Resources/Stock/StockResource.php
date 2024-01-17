<?php

namespace App\Http\Resources\Stock;

use App\Http\Controllers\HelperController;
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
            'id' => encrypt($this->id),
            'image' => HelperController::parseReturnImage($this->image),
            'name' => $this->name,
            'unit' => $this->unit->name,
            'sale_price' => $this->primary_price,
            'stock' => $this->stock,
            'stock_level' => $this->getStockLevel(),
        ];
    }

    private function getStockLevel(): string
    {
        switch (true) {
            case ($this->stock == 0):
                $result = 'စတော့ကုန်';
                break;
            case ($this->stock < 5):
                $result = 'အလွန်နည်း';
                break;
            case ($this->stock < 10):
                $result = 'နည်း';
                break;
            case ($this->stock < 20):
                $result = 'ဝယ်ယူသင့်';
                break;
            default:
                $result = 'ကောင်း';
                break;
        }

        return $result;
    }
}
