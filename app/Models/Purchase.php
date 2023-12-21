<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ['place', 'cost', 'item_quantity', 'remark', 'user_id', 'all_received'];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
