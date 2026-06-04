<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'BentoCat'),
            'site_description' => Setting::get('site_description', ''),
            'contact_whatsapp' => Setting::get('contact_whatsapp', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_facebook' => Setting::get('social_facebook', ''),
            'site_logo' => Setting::get('site_logo', 'bentocat.png'),
            'site_favicon' => Setting::get('site_favicon', 'favicon.ico'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_whatsapp' => 'nullable|string|max:20',
            'social_instagram' => 'nullable|url|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:20480',
            'site_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:512',
        ]);

        Setting::set('site_name', $request->site_name);
        Setting::set('site_description', $request->site_description);
        Setting::set('contact_whatsapp', $request->contact_whatsapp);
        Setting::set('social_instagram', $request->social_instagram);
        Setting::set('social_facebook', $request->social_facebook);

        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('branding', 'public');
            Setting::set('site_logo', 'storage/' . $logoPath);
        }

        if ($request->hasFile('site_favicon')) {
            $faviconPath = $request->file('site_favicon')->store('branding', 'public');
            Setting::set('site_favicon', 'storage/' . $faviconPath);
        }

        return redirect()->back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
