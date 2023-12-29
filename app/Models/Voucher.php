<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = ["voucher_number", "cost", "profit", "item_count", "user_id"];

    public static function generateVoucherNumber()
    {
        // You can customize the prefix and length as needed
        $prefix = 'VOUCHER';
        $length = 10;

        // Generate a unique ID based on the current timestamp
        $uniqueId = uniqid();

        // Combine prefix and unique ID, and truncate to desired length
        $voucherNumber = strtoupper($prefix . substr($uniqueId, 0, $length - strlen($prefix)));

        return $voucherNumber;
    }
}
