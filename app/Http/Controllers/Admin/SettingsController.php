<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized. Only Super Admin can update settings.'], 403);
        }

        $data = $request->except(['_token', 'logo']);
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', config('filesystems.default'));
            
            // Delete old local logo if exists
            $oldLogo = Setting::where('key', 'store_logo')->first()?->value;
            if ($oldLogo && !filter_var($oldLogo, FILTER_VALIDATE_URL)) {
                Storage::disk(config('filesystems.default'))->delete($oldLogo);
            }

            Setting::updateOrCreate(
                ['key' => 'store_logo'],
                ['value' => $path, 'group' => 'general']
            );
        } elseif ($request->filled('logo_url')) {
             Setting::updateOrCreate(
                ['key' => 'store_logo'],
                ['value' => $request->logo_url, 'group' => 'general']
            );
        }

        return response()->json(['message' => 'Settings updated successfully.']);
    }
}
