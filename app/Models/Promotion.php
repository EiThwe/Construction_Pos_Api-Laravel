<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'amount',
        'started_at',
        'expired_at',
        'remark',
        'user_id',
        'status'
    ];

    protected $casts = [
        'name' => "string",
        'type' => "string",
        'amount' => "double",
        'started_at' => "string",
        'expired_at' => "string",
        'remark' => "string",
        'user_id' => "string",
        'status' => "string"
    ];

    // Make sure the user relationship is defined in your model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
