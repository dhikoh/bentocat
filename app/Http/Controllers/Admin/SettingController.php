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
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            abort(403, 'Unauthorized action. Hanya Superadmin yang dapat mengakses halaman pengaturan.');
        }

        $settings = [
            'site_name' => Setting::get('site_name', 'BentoCat'),
            'site_description' => Setting::get('site_description', ''),
            'contact_whatsapp' => Setting::get('contact_whatsapp', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_facebook' => Setting::get('social_facebook', ''),
            'site_logo' => Setting::get('site_logo', 'images/logo.png'),
            'site_favicon' => Setting::get('site_favicon', 'favicon.ico'),
            
            // New fields
            'hero_badge_text' => Setting::get('hero_badge_text', '🐾 BentoCat Premium Bentonite Cat Litter'),
            'hero_title' => Setting::get('hero_title', 'Pasir Kucing Premium, Sahabat Terbaik Kucing Anda!'),
            'hero_subtitle' => Setting::get('hero_subtitle', 'Hemat Ongkir! Cari petshop resmi terdekat di kota Anda dengan harga lokal wajar tanpa markup tinggi marketplace.'),
            'hero_media_type' => Setting::get('hero_media_type', 'image'),
            'hero_media_path' => Setting::get('hero_media_path', 'images/hero_default.png'),
            'hero_product_title' => Setting::get('hero_product_title', 'BentoCat Premium'),
            'hero_product_desc' => Setting::get('hero_product_desc', 'Odor Control • Instant Clumping • 99% Dust Free'),
            'hero_product_image' => Setting::get('hero_product_image', 'images/product_default.png'),
            'hero_badge_1_text' => Setting::get('hero_badge_1_text', 'Vet Approved'),
            'hero_badge_2_text' => Setting::get('hero_badge_2_text', 'Healthy & Natural'),
            'hero_badge_3_title' => Setting::get('hero_badge_3_title', 'Complete Care for Every Stage'),
            'hero_badge_3_desc' => Setting::get('hero_badge_3_desc', 'Dari kitten hingga senior, menjaga kebersihanbox tetap steril.'),
            'cta_primary_text' => Setting::get('cta_primary_text', 'Cari Toko Terdekat 📍'),
            'cta_secondary_text' => Setting::get('cta_secondary_text', 'Lihat Katalog Produk'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang diperbolehkan mengubah pengaturan website.');
        }

        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_whatsapp' => 'nullable|string|max:20',
            'social_instagram' => 'nullable|url|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:20480',
            'site_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:512',
            
            // New Hero Section validations
            'hero_badge_text' => 'required|string|max:255',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',
            'hero_media_type' => 'required|string|in:image,video',
            'hero_media_path' => 'nullable|file|mimes:png,jpg,jpeg,gif,svg,mp4,webm|max:51200', // 50MB
            'hero_product_title' => 'required|string|max:255',
            'hero_product_desc' => 'required|string|max:255',
            'hero_product_image' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:10240', // 10MB
            'hero_badge_1_text' => 'required|string|max:255',
            'hero_badge_2_text' => 'required|string|max:255',
            'hero_badge_3_title' => 'required|string|max:255',
            'hero_badge_3_desc' => 'required|string',
            'cta_primary_text' => 'required|string|max:255',
            'cta_secondary_text' => 'required|string|max:255',
        ]);

        Setting::set('site_name', $request->site_name);
        Setting::set('site_description', $request->site_description);
        Setting::set('contact_whatsapp', $request->contact_whatsapp);
        Setting::set('social_instagram', $request->social_instagram);
        Setting::set('social_facebook', $request->social_facebook);
        
        Setting::set('hero_badge_text', $request->hero_badge_text);
        Setting::set('hero_title', $request->hero_title);
        Setting::set('hero_subtitle', $request->hero_subtitle);
        Setting::set('hero_media_type', $request->hero_media_type);
        Setting::set('hero_product_title', $request->hero_product_title);
        Setting::set('hero_product_desc', $request->hero_product_desc);
        Setting::set('hero_badge_1_text', $request->hero_badge_1_text);
        Setting::set('hero_badge_2_text', $request->hero_badge_2_text);
        Setting::set('hero_badge_3_title', $request->hero_badge_3_title);
        Setting::set('hero_badge_3_desc', $request->hero_badge_3_desc);
        Setting::set('cta_primary_text', $request->cta_primary_text);
        Setting::set('cta_secondary_text', $request->cta_secondary_text);

        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('branding', 'public');
            Setting::set('site_logo', 'storage/' . $logoPath);
        }

        if ($request->hasFile('site_favicon')) {
            $faviconPath = $request->file('site_favicon')->store('branding', 'public');
            Setting::set('site_favicon', 'storage/' . $faviconPath);
        }

        if ($request->hasFile('hero_media_path')) {
            $heroMedia = $request->file('hero_media_path')->store('hero', 'public');
            Setting::set('hero_media_path', 'storage/' . $heroMedia);
        }

        if ($request->hasFile('hero_product_image')) {
            $productImg = $request->file('hero_product_image')->store('hero', 'public');
            Setting::set('hero_product_image', 'storage/' . $productImg);
        }

        return redirect()->back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
