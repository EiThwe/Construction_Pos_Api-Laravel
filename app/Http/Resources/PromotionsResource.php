<?php

namespace App\Http\Resources;

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
            'name' => $this->name,
            'type' => $this->type,
            'amount' => $this->amount,
            'started_at' => $this->started_at,
            'expired_at' => $this->expired_at,
            'remark' => $this->remark,
            'user' => $this->user->name,
            'product_id' => $this->product_id,
        ];
    }
}
