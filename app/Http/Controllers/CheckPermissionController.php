<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckPermissionController extends Controller
{
    static public function check(callable $callback)
    {
        try {
            $result = $callback();

            return $result;
        } catch (\Exception $e) {
            return response()->json(['message' => 'လုပ်ပိုင်ခွင့်မရှိပါ'], 403);
        }
    }
}
