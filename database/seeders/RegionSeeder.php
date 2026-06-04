<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\City;
use Illuminate\Support\Str;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            'DKI Jakarta' => [
                'Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur'
            ],
            'Jawa Barat' => [
                'Bandung', 'Bekasi', 'Depok', 'Bogor', 'Cirebon', 'Tasikmalaya', 'Cimahi', 'Sukabumi'
            ],
            'Banten' => [
                'Tangerang', 'Tangerang Selatan', 'Serang', 'Cilegon'
            ],
            'Jawa Tengah' => [
                'Semarang', 'Surakarta', 'Magelang', 'Pekalongan', 'Salatiga', 'Tegal'
            ],
            'DI Yogyakarta' => [
                'Yogyakarta', 'Sleman', 'Bantul'
            ],
            'Jawa Timur' => [
                'Surabaya', 'Malang', 'Blitar', 'Kediri', 'Madiun', 'Mojokerto', 'Pasuruan', 'Probolinggo', 'Batu', 'Sidoarjo'
            ],
            'Bali' => [
                'Denpasar', 'Badung', 'Gianyar'
            ],
            'Sumatera Utara' => [
                'Medan', 'Binjai', 'Pematangsiantar', 'Tanjungbalai'
            ],
            'Sumatera Selatan' => [
                'Palembang', 'Prabumulih', 'Lubuklinggau'
            ],
            'Sulawesi Selatan' => [
                'Macassar', 'Parepare', 'Palopo'
            ],
            'Kalimantan Timur' => [
                'Samarinda', 'Balikpapan', 'Bontang'
            ]
        ];

        foreach ($regions as $provinceName => $cities) {
            $province = Province::create(['nama' => $provinceName]);

            foreach ($cities as $cityName) {
                City::create([
                    'provinsi_id' => $province->id,
                    'nama' => $cityName,
                    'slug' => Str::slug($cityName)
                ]);
            }
        }
    }
}
