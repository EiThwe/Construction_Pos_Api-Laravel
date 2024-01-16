<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionsResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type === "percentage" ? "ရာခိုင်နှုန်း" : "ပမာဏနှုတ်",
            'amount' => $this->amount,
            'status' => $this->status,
            'started_at' => HelperController::parseReturnDate($this->started_at),
            'expired_at' => HelperController::parseReturnDate($this->expired_at),
            'user' => $this->user->name,
            'product_id' => encrypt($this->product_id),
        ];
    }
}
