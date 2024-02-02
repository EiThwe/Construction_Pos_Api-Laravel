<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;


class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        "voucher_number", "cost", "actual_cost", "profit", "user_id", "promotion_amount", "pay_amount",
        "reduce_amount", "change", "debt_amount"
    ];

    protected $casts = [
        "voucher_number" => "string",
        "cost" => "integer",
        "actual_cost" => "integer",
        "profit" => "integer",
        "user_id" => "string",
        "promotion_amount" => "integer",
        "pay_amount" => "integer",
        "reduce_amount" => "integer",
        "change" => "integer",
        "debt_amount"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucherRecords()
    {
        return $this->hasMany(VoucherRecord::class);
    }

    public static function generateVoucherNumber()
    {
        $voucherCode = Hashids::encode(random_int(1, 99999999));

        return strtolower($voucherCode);
    }
}
