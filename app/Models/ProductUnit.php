<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        "unit_id", "price", "product_id"
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
