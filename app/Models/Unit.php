<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ["name", "unit_type_id"];

    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }

    public function conversions()
    {
        return $this->hasMany(ConversionFactor::class, "from_unit_id");
    }
}
