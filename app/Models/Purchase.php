<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ['place', 'cost', 'item_quantity', 'remark', 'user_id', 'status'];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
