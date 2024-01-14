<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => encrypt($this->id),
            "name" => $this->name,
            "phone" => $this->phone,
            "address" => $this->address,
            "staff" => $this->user->name,
            "profile" => HelperController::parseReturnImage($this->profile)
        ];
    }
}
