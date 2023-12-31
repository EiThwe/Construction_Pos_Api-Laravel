<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "description" => $this->description,
            "cashier" => $this->user->name,
            "date" =>  HelperController::parseReturnDate($this->created_at, true),
        ];
    }
}
