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
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $path = $request->file('logo')->store('settings', config('filesystems.default'));
            
            // Delete old logo if exists
            $oldLogo = Setting::where('key', 'store_logo')->first()?->value;
            if ($oldLogo) {
                Storage::disk(config('filesystems.default'))->delete($oldLogo);
            }

            Setting::updateOrCreate(
                ['key' => 'store_logo'],
                ['value' => $path, 'group' => 'general']
            );
        }

        return response()->json(['message' => 'Settings updated successfully.']);
    }
}
