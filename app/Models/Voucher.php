<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;


class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        "voucher_number", "cost", "profit", "item_count", "user_id", "promotion_amount", "pay_amount",
        "reduce_amount", "change", "debt_amount"
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
