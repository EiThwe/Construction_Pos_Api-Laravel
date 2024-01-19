<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionFactor extends Model
{
    use HasFactory;

    protected $fillable = ["from_unit_id", "to_unit_id", "value", "status"];

    protected $casts = ["from_unit_id" => "string", "to_unit_id" => "string", "value" => "double", "status" => "string"];

    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }
}
