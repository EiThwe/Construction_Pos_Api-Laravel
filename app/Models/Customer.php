<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ["name", "address", "phone", "user_id", "profile"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }
}
