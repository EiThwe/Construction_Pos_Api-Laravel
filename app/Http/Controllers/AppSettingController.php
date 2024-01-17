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
        $cacheValue = cache("app-setting");

        if ($cacheValue) return $cacheValue;

        $setting = AppSetting::latest()->first();
        $setting["logo"] = HelperController::parseReturnImage($setting->logo);

        $response = response()->json(["data" => $setting]);

        cache()->put("app-setting", $response, 120);

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|min:3',
            'phone' => 'string|max:20',
            'email' => 'email|max:255',
            'address' => 'string|max:255',
            'google_map_url' => 'url|max:255',
        ]);

        $setting = AppSetting::firstOrFail();

        $newSetting = $setting->update([
            'name' => $request->input('name', $setting->name),
            'phone' => $request->input('phone', $setting->phone),
            'email' => $request->input('email', $setting->email),
            'address' => $request->input('address', $setting->address),
            'google_map_url' => $request->input('google_map_url', $setting->google_map_url),
            'logo' => HelperController::handleLogoUpload($request->file('logo'), null),
        ]);

        $newSetting = AppSetting::firstOrFail();
        $newSetting["logo"] = HelperController::parseReturnImage($newSetting->logo);

        cache()->forget('app-setting');
        cache()->put("app-setting", ["data" => $newSetting], 120);


        return response()->json(["message" => "ပြင်ဆင်ခြင်းအောင်မြင်ပါသည်", "data" => $newSetting]);
    }
}
