<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
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
            "date" => HelperController::parseReturnDate($this->created_at, true),
            "staff" => $this->user->name,
        ];
    }
}
