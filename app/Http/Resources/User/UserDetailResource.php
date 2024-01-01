<?php

namespace App\Http\Resources\User;

use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            "profile" => HelperController::parseReturnImage($this->profile),
            "name" => $this->name,
            "phone" => $this->phone,
            "salary" => $this->salary,
            "role" => $this->role,
            "position" => $this->position,
            "gender" => $this->gender,
            "address" => $this->address,
            "birth_date" => HelperController::parseReturnDate($this->birth_date),
            "join_date" => HelperController::parseReturnDate($this->join_date),
            "salary_records" => UserSalaryResource::collection($this->salaries)
        ];
    }
}
