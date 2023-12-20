<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySalary extends Model
{
    use HasFactory;

    protected $fillable = [
        "actual_salary", "type", "amount", "user_id", "created_by"
    ];
}
