<?php

namespace App\Http\Resources;

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
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type === "percentage" ? "ရာခိုင်နှုန်း" : "ပမာဏနှုတ်",
            'amount' => $this->amount,
            'started_at' => Carbon::parse($this->started_at)->format('j M Y'),
            'expired_at' => Carbon::parse($this->expired_at)->format('j M Y'),
            'user' => $this->user->name,
            'product_id' => $this->product_id,
        ];
    }
}
