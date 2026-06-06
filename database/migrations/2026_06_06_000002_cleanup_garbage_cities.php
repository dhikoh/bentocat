<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\City;
use App\Models\Province;
use App\Models\Outlet;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Mapping of garbage cities to correct cities and provinces
        $mappings = [
            'Vets' => ['city' => 'Yogyakarta', 'province' => 'DI Yogyakarta'],
            'Vika' => ['city' => 'Yogyakarta', 'province' => 'DI Yogyakarta'],
            'Yohanes' => ['city' => 'Denpasar', 'province' => 'Bali'],
            'Yuki' => ['city' => 'Jakarta Pusat', 'province' => 'DKI Jakarta'],
            'Yuliansyah' => ['city' => 'Demak', 'province' => 'Jawa Tengah'],
            'Zona' => ['city' => 'Banjarmasin', 'province' => 'Kalimantan Selatan'],
            'Zone' => ['city' => 'Pemalang', 'province' => 'Jawa Tengah'],
            'Widjie' => ['city' => 'Semarang', 'province' => 'Jawa Tengah'],
            'Wine' => ['city' => 'Kuningan', 'province' => 'Jawa Barat'],
            'Wirvet' => ['city' => 'Situbondo', 'province' => 'Jawa Timur'],
            'Wiyadi' => ['city' => 'Jakarta Timur', 'province' => 'DKI Jakarta'],
            'Indonesia' => ['city' => 'Jakarta Barat', 'province' => 'DKI Jakarta'],
        ];

        foreach ($mappings as $garbageName => $correct) {
            // Find if there is a garbage city
            $garbageCity = City::where('nama', $garbageName)->first();
            if ($garbageCity) {
                // Find or create correct province
                $province = Province::firstOrCreate(['nama' => $correct['province']]);
                
                // Find or create correct city
                $correctCity = City::firstOrCreate(
                    ['nama' => $correct['city']],
                    [
                        'provinsi_id' => $province->id,
                        'slug' => Str::slug($correct['city'])
                    ]
                );

                // Reassign all outlets pointing to this garbage city
                Outlet::where('kota_id', $garbageCity->id)->update([
                    'kota_id' => $correctCity->id
                ]);

                // Delete the garbage city
                $garbageCity->delete();
            }
        }

        // Clean up empty 'Lainnya' province if it exists and has no cities
        $lainnyaProvince = Province::where('nama', 'Lainnya')->first();
        if ($lainnyaProvince) {
            if ($lainnyaProvince->cities()->count() === 0) {
                $lainnyaProvince->delete();
            }
        }
    }

    public function down(): void
    {
        // Data cleaning does not need revert in down()
    }
};
