<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = ["name", "phone", "address", "user_id", "voucher_id", "actual_amount", "left_amount"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function debt_histories()
    {
        return $this->hasMany(DebtHistory::class);
    }
}
