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
            'site_logo' => 'bentocat.png',
            'site_favicon' => 'favicon.ico',
            'site_description' => 'Platform pencarian distributor & petshop resmi BentoCat seluruh Indonesia. Temukan pasir kucing bentonite premium terdekat.',
            'contact_whatsapp' => '6281234567890',
            'social_instagram' => 'https://instagram.com/bentocat',
            'social_facebook' => 'https://facebook.com/bentocat',
        ];

        foreach ($defaults as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
