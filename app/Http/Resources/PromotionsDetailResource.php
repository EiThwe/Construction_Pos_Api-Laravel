<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionsDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id' => encrypt($this->id),
            'name' => $this->name,
            'type' => $this->type,
            'amount' => $this->amount,
            'started_at' => $this->started_at,
            'expired_at' => $this->expired_at,
            'user' => $this->user->name,
            'product_id' => encrypt($this->product_id),
        ];;
    }
}
