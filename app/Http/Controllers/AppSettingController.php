<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = AppSetting::latest()->first();
        $setting["logo"] = asset(Storage::url($setting->logo));

        return response()->json(["data" => $setting]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'google_map_url' => 'required|url|max:255',
        ]);

        // Assuming you only have one row in the settings table
        $setting = AppSetting::firstOrFail();


        // Update the fields
        $setting->update([
            'name' => $request->input('name', $setting->name),
            'phone' => $request->input('phone', $setting->phone),
            'email' => $request->input('email', $setting->email),
            'address' => $request->input('address', $setting->address),
            'google_map_url' => $request->input('google_map_url', $setting->google_map_url),
            'user_id' => Auth::id(),
            'logo' => HelperController::handleLogoUpload($request->file('logo'), null),
        ]);

        return response()->json(["message" => "ပြင်ဆင်ခြင်းအောင်မြင်ပါသည်"]);
    }
}
