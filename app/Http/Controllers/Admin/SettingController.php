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
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['superadmin', 'marketing'])) {
            abort(403, 'Unauthorized action. Hanya Superadmin dan Marketing yang dapat mengakses halaman pengaturan.');
        }

        $settings = [
            'site_name' => Setting::get('site_name', 'BentoCat'),
            'site_description' => Setting::get('site_description', ''),
            'contact_whatsapp' => Setting::get('contact_whatsapp', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_facebook' => Setting::get('social_facebook', ''),
            'site_logo' => Setting::get('site_logo', 'images/logo.png'),
            'site_favicon' => Setting::get('site_favicon', 'favicon.ico'),
            
            // Hero Section
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
            
            // Fitur Keunggulan
            'feature_1_icon' => Setting::get('feature_1_icon', '⚡'),
            'feature_1_title' => Setting::get('feature_1_title', 'Molecular Bonding'),
            'feature_1_desc' => Setting::get('feature_1_desc', 'Butiran pasir membentuk <strong>ikatan kisi molekul</strong> yang kuat saat bereaksi dengan cairan. Tidak mudah pecah.'),
            'feature_2_icon' => Setting::get('feature_2_icon', '🍃'),
            'feature_2_title' => Setting::get('feature_2_title', 'Zero-Dust Tech'),
            'feature_2_desc' => Setting::get('feature_2_desc', 'Sistem filtrasi ganda memisahkan butiran pasir dari <strong>mikro-partikel debu</strong> berbahaya.'),
            'feature_3_icon' => Setting::get('feature_3_icon', '🌸'),
            'feature_3_title' => Setting::get('feature_3_title', 'Odor Encapsulation'),
            'feature_3_desc' => Setting::get('feature_3_desc', 'Molekul bau (amonia) <strong>dikurung aktif</strong> oleh karbon aktif, bukan sekedar ditutupi parfum.'),

            // Tracking & SEO fields
            'gtm_id' => Setting::get('gtm_id', ''),
            'ga_id' => Setting::get('ga_id', ''),
            'meta_pixel_id' => Setting::get('meta_pixel_id', ''),
            'meta_verification_id' => Setting::get('meta_verification_id', ''),
            'seo_meta_title' => Setting::get('seo_meta_title', 'BentoCat - Pasir Kucing Bentonite Premium'),
            'seo_meta_description' => Setting::get('seo_meta_description', 'BentoCat Premium Bentonite Cat Litter - Pasir kucing clumping wangi super dengan kontrol bau amonia 24 jam.'),
            'seo_og_image' => Setting::get('seo_og_image', ''),
            'seo_twitter_title' => Setting::get('seo_twitter_title', ''),
            'seo_twitter_description' => Setting::get('seo_twitter_description', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['superadmin', 'marketing'])) {
            return back()->with('error', 'Hanya Superadmin dan Marketing yang diperbolehkan mengubah pengaturan website.');
        }

        if ($user->role === 'marketing') {
            // Validate ONLY Tracking & SEO fields
            $request->validate([
                'gtm_id' => 'nullable|string|max:50',
                'ga_id' => 'nullable|string|max:50',
                'meta_pixel_id' => 'nullable|string|max:50',
                'meta_verification_id' => 'nullable|string|max:255',
                'seo_meta_title' => 'required|string|max:255',
                'seo_meta_description' => 'required|string',
                'seo_og_image' => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:10240', // 10MB
                'seo_twitter_title' => 'nullable|string|max:255',
                'seo_twitter_description' => 'nullable|string',
            ]);

            // Save tracking/SEO settings
            Setting::set('gtm_id', $request->gtm_id);
            Setting::set('ga_id', $request->ga_id);
            Setting::set('meta_pixel_id', $request->meta_pixel_id);
            Setting::set('meta_verification_id', $request->meta_verification_id);
            Setting::set('seo_meta_title', $request->seo_meta_title);
            Setting::set('seo_meta_description', $request->seo_meta_description);
            Setting::set('seo_twitter_title', $request->seo_twitter_title);
            Setting::set('seo_twitter_description', $request->seo_twitter_description);

            if ($request->hasFile('seo_og_image')) {
                $ogPath = $request->file('seo_og_image')->store('seo', 'public');
                Setting::set('seo_og_image', 'storage/' . $ogPath);
            }

            return redirect()->back()->with('success', 'Pengaturan Tracking & SEO berhasil diperbarui.');
        }

        // Superadmin full validation
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_whatsapp' => 'nullable|string|max:20',
            'social_instagram' => 'nullable|url|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:20480',
            'site_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:512',
            
            // Hero Section
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

            // Fitur Keunggulan
            'feature_1_icon' => 'required|string|max:50',
            'feature_1_title' => 'required|string|max:255',
            'feature_1_desc' => 'required|string',
            'feature_2_icon' => 'required|string|max:50',
            'feature_2_title' => 'required|string|max:255',
            'feature_2_desc' => 'required|string',
            'feature_3_icon' => 'required|string|max:50',
            'feature_3_title' => 'required|string|max:255',
            'feature_3_desc' => 'required|string',

            // Tracking & SEO
            'gtm_id' => 'nullable|string|max:50',
            'ga_id' => 'nullable|string|max:50',
            'meta_pixel_id' => 'nullable|string|max:50',
            'meta_verification_id' => 'nullable|string|max:255',
            'seo_meta_title' => 'required|string|max:255',
            'seo_meta_description' => 'required|string',
            'seo_og_image' => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:10240',
            'seo_twitter_title' => 'nullable|string|max:255',
            'seo_twitter_description' => 'nullable|string',
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

        Setting::set('feature_1_icon', $request->feature_1_icon);
        Setting::set('feature_1_title', $request->feature_1_title);
        Setting::set('feature_1_desc', $request->feature_1_desc);
        Setting::set('feature_2_icon', $request->feature_2_icon);
        Setting::set('feature_2_title', $request->feature_2_title);
        Setting::set('feature_2_desc', $request->feature_2_desc);
        Setting::set('feature_3_icon', $request->feature_3_icon);
        Setting::set('feature_3_title', $request->feature_3_title);
        Setting::set('feature_3_desc', $request->feature_3_desc);

        // Tracking & SEO
        Setting::set('gtm_id', $request->gtm_id);
        Setting::set('ga_id', $request->ga_id);
        Setting::set('meta_pixel_id', $request->meta_pixel_id);
        Setting::set('meta_verification_id', $request->meta_verification_id);
        Setting::set('seo_meta_title', $request->seo_meta_title);
        Setting::set('seo_meta_description', $request->seo_meta_description);
        Setting::set('seo_twitter_title', $request->seo_twitter_title);
        Setting::set('seo_twitter_description', $request->seo_twitter_description);

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

        if ($request->hasFile('seo_og_image')) {
            $ogPath = $request->file('seo_og_image')->store('seo', 'public');
            Setting::set('seo_og_image', 'storage/' . $ogPath);
        }

        return redirect()->back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
