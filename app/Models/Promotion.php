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

    // Make sure the user relationship is defined in your model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
