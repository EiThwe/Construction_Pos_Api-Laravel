<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtHistory extends Model
{
    use HasFactory;

    protected $fillable = ["amount", "debt_id", "user_id"];

    protected $casts = [
        "amount" => "integer",
        "debt_id" => "string",
        "user_id" => "string"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
