<?php

namespace App\Http\Resources\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "id" => $this->id,
            "type" => $this->unitType,
            "remark" => $this->remark,
            "conversions" => ConversionsResource::collection($this->conversions)
        ];;
    }
}
