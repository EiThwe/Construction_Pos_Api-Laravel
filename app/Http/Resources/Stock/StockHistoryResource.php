<?php

namespace App\Http\Resources\Stock;

use App\Http\Controllers\HelperController;
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
            "id" => encrypt($this->id),
            "staff" => $this->user->name,
            "unit" => $this->unit->name,
            "quantity" => $this->quantity,
            "cost" => $this->cost,
            "remark" => $this->remark,
            "date" => HelperController::parseReturnDate($this->created_at, true),
        ];
    }
}
