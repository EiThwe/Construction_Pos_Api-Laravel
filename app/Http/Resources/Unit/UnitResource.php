<?php

namespace App\Http\Resources\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => Crypt::encrypt($this->id),
            "normal_id" => $this->id,
            "name" => $this->name,
            "unit_type_id" => $this->unit_type_id,
            "type" => $this->unitType->name,
        ];
    }
}
