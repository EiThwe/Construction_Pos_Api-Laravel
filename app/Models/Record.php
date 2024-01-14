<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = ["revenue", "expense", "profit", "user_id", "status", "month_date"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
