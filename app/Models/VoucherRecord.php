<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherRecord extends Model
{
    use HasFactory;

    protected $fillable = ["unit_id", "product_id", "quantity", "cost", "voucher_id"];
}
