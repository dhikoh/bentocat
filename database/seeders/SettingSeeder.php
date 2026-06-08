<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name' => 'BentoCat',
            'site_logo' => 'images/logo.png',
            'site_favicon' => 'favicon.ico',
            'site_description' => 'Platform pencarian distributor & petshop resmi BentoCat seluruh Indonesia. Temukan pasir kucing bentonite premium terdekat.',
            'contact_whatsapp' => '6287777717300',
            'social_instagram' => 'https://instagram.com/bentocat',
            'social_facebook' => 'https://facebook.com/bentocat',
            
            // New Hero Section settings
            'hero_badge_text' => '🐾 BentoCat Premium Bentonite Cat Litter',
            'hero_title' => 'Pasir Kucing Premium, Sahabat Terbaik Kucing Anda!',
            'hero_subtitle' => 'Hemat Ongkir! Cari petshop resmi terdekat di kota Anda dengan harga lokal wajar tanpa markup tinggi marketplace.',
            'hero_media_type' => 'image',
            'hero_media_path' => 'images/hero_default.png',
            'hero_product_title' => 'BentoCat Premium',
            'hero_product_desc' => 'Odor Control • Instant Clumping • 99% Dust Free',
            'hero_product_image' => 'images/product_default.png',
            'hero_badge_1_text' => 'Vet Approved',
            'hero_badge_2_text' => 'Healthy & Natural',
            'hero_badge_3_title' => 'Complete Care for Every Stage',
            'hero_badge_3_desc' => 'Dari kitten hingga senior, menjaga kebersihanbox tetap steril.',
            'cta_primary_text' => 'Cari Toko Terdekat 📍',
            'cta_secondary_text' => 'Lihat Katalog Produk',

            // Fitur Keunggulan Premium
            'feature_1_icon' => '🧪',
            'feature_1_title' => 'Molecular Bonding',
            'feature_1_desc' => 'Butiran pasir membentuk <strong>ikatan kisi molekul</strong> yang kuat saat bereaksi dengan cairan. Tidak mudah pecah.',
            'feature_2_icon' => '💨',
            'feature_2_title' => 'Zero-Dust Tech',
            'feature_2_desc' => 'Sistem filtrasi ganda memisahkan butiran pasir dari <strong>mikro-partikel debu</strong> berbahaya.',
            'feature_3_icon' => '🛡️',
            'feature_3_title' => 'Odor Encapsulation',
            'feature_3_desc' => 'Molekul bau (amonia) <strong>dikurung aktif</strong> oleh karbon aktif, bukan sekedar ditutupi parfum.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
