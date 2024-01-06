<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = ["customer_id", "user_id", "voucher_id", "actual_amount", "left_amount", "remark"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function debt_histories()
    {
        return $this->hasMany(DebtHistory::class);
    }
}
