<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ["description", "amount", "remark", "user_id"];

    protected $casts = [
        "description" => "string",
        "amount" => "integer",
        "remark" => "string",
        "user_id" => "string",
    ];

}
