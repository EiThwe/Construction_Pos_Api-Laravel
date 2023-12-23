<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "image", "actual_price", "primary_unit", "primary_price", "remark", "stock", "user_id", "promotion_id"
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
