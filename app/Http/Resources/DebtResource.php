<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
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
            "id" => encrypt($this->id),
            "name" => $this->customer->name,
            "phone" => $this->customer->phone,
            "actual_amount" => $this->actual_amount,
            "left_amount" => $this->left_amount,
            "date" => HelperController::parseReturnDate($this->created_at, true),
            "staff" => $this->user->name,
            "is_left" => $this->left_amount == 0 ? false : true
        ];
    }
}
