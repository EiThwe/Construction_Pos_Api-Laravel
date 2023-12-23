<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
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
            "profile" => $this->profile,
            "name" => $this->name,
            "phone" => $this->phone,
            "salary" => $this->salary,
            "role" => $this->role,
            "address" => $this->address,
            "birth_date" => $this->birth_date,
            "join_date" => $this->join_date,
            // "password" => $this->password,
            "salary_records" => UserSalaryResource::collection($this->salaries)
        ];
    }
}
