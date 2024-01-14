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
            'revenue' => $this->revenue,
            'profit' => $this->profit,
            'expense' => $this->expense,
            'staff' => $this->user->name,
            'status' => $this->getStatus(),
            'time' => HelperController::parseReturnDate($this->created_at, true),
        ];
    }

    private function getStatus(): string
    {
        if ($this->profit === $this->expense) {
            return 'အရင်း';
        } elseif ($this->profit > $this->expense) {
            return 'မြတ်';
        } else {
            return 'ရှုံး';
        }
    }
}
