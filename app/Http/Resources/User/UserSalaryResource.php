<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSalaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "actual_salary" => $this->actual_salary,
            "type" => $this->type,
            "amount" => $this->amount,
            "created_by" => $this->created_by,
            "created_at" => $this->created_at,
        ];
    }
}
