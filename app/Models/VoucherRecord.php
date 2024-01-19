<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherRecord extends Model
{
    use HasFactory;

    protected $fillable = ["unit_id", "product_id", "quantity", "cost", "voucher_id"];

    protected $casts = [
        "unit_id" => "string",
        "product_id" => "string",
        "quantity" => "double",
        "cost" => "integer",
        "voucher_id" => "string"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
