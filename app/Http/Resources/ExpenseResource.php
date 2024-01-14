<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ExpenseResource extends JsonResource
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
            "description" => $this->description,
            "amount" => $this->amount,
            "remark" => $this->remark,
            "date" => HelperController::parseReturnDate($this->created_at),
            "user_id" => encrypt($this->user_id),
        ];
    }
}
