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

        return response()->json($setting);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'google_map_url' => 'required|url|max:255',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust image validation rules
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
            // You can add other fields here

            // Handle logo update
            'logo' => $this->handleLogoUpload($request, $setting->logo),
        ]);

        return response()->json($setting);
    }

    // Helper method to handle logo upload
    private function handleLogoUpload(Request $request, $currentLogo)
    {
        if ($request->hasFile('logo')) {
            // Delete the current logo if it exists
            if ($currentLogo) {
                Storage::disk('public')->delete($currentLogo);
            }

            // Upload the new logo
            $path = $request->file('logo')->store('logos', 'public');

            return $path;
        }

        // If no new logo is provided, keep the current one
        return $currentLogo;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
