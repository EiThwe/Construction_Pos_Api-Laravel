<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = ["revenue", "expense", "profit", "user_id", "status", "month_date", "voucher_count"];

    protected $casts = [
        "revenue" => "integer",
        "expense" => "integer",
        "profit" => "integer",
        "user_id" => "string",
        "status" => "string",
        "month_date" => "timestamp",
        "voucher_count" => "integer"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
