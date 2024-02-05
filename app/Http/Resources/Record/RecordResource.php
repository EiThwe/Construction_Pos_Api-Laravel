<?php

namespace App\Http\Resources\Record;

use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>  encrypt($this->id),
            'revenue' => $this->revenue,
            'profit' => $this->profit,
            'expense' => $this->expense,
            'voucher_count' => $this->voucher_count,
            'staff' => $this->user->name,
            'status' => $this->getStatus(),
            'date' => HelperController::parseReturnDate($this->created_at, true),
        ];
    }

    private function getStatus(): string
    {
        if ($this->profit === 0) {
            return 'အရင်း';
        } elseif ($this->profit > 0) {
            return 'မြတ်';
        } else {
            return 'ရှုံး';
        }
    }
}
