<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;
    protected $fillable = ["name", "phone", "email", "address", "google_map_url", "logo", "user_id"];

    protected $casts = [
        "name" => "string",
        "phone" => "string",
        "email" => "string",
        "address" => "string",
        "google_map_url" => "string",
        "logo" => "string",
        "user_id" => "string",
    ];
}
