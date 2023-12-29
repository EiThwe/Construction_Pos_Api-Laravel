<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity', 'unit_id'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
