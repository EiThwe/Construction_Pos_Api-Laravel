<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "image", "actual_price", "primary_unit_id", "primary_price", "remark", "stock", "user_id",
        "promotion_id"
    ];

    protected $casts = [
        "name" => "string",
        "image" => "string",
        "actual_price" => "integer",
        "primary_unit_id" => "string",
        "primary_price" => "integer",
        "remark" => "string",
        "stock" => "string",
        "user_id" => "string",
        "promotion_id" => "string",
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, "primary_unit_id");
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
