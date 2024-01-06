<?php

namespace App\Http\Resources;

use App\Http\Controllers\HelperController;
use App\Models\Debt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomersDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $total_debt = Debt::where("customer_id", $this->id)->sum("actual_amount");
        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            "address" => $this->address,
            "staff" => $this->user->name,
            "profile" => HelperController::parseReturnImage($this->profile),
            "total_debt" => $total_debt
        ];;
    }
}
