<?php

namespace App\Http\Resources\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "value" => $this->value,
            "status" => $this->status,
            "from" => new UnitResource($this->fromUnit),
            "to" => new UnitResource($this->toUnit),
        ];
    }
}
