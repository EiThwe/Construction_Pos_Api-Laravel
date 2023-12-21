<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DebtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "actual_amount" => $this->actual_amount,
            "left_amount" => $this->left_amount,
            "name" => $this->name,
            "phone" => $this->phone,
            "address" => $this->address,
            "date" => Carbon::parse($this->created_at)->format("d-m-Y"),
            "staff" => $this->user->name
        ];
    }
}
