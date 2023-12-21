<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DebtHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "amount" => $this->amount,
            "date" => Carbon::parse($this->created_at)->format("d-m-Y"),
            "staff" => $this->user->name,
        ];
    }
}
