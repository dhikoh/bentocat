<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Distributor;
use App\Models\City;
use App\Models\Province;

class DistributorSeeder extends Seeder
{
    public function run(): void
    {
        $distributorsData = [
            [
                'nama' => 'BentoCat Indonesia',
                'pic' => 'Admin BentoCat Indonesia',
                'whatsapp' => '6287777717300',
                'alamat' => 'Blitar',
                'provinsi_name' => 'Banten',
                'city_name' => 'Tangerang', // fallback city in Banten
            ],
            [
                'nama' => 'Gukguk Meong',
                'pic' => 'Admin Gukguk Meong',
                'whatsapp' => '6281905222223',
                'alamat' => 'Jl. Kampung Baris No.505, Karangturi, Kec. Semarang Tim., Kota Semarang, Jawa Tengah 50124',
                'provinsi_name' => 'Jawa Tengah',
                'city_name' => 'Semarang',
            ],
            [
                'nama' => 'Aulia Petshop',
                'pic' => 'Admin Aulia Petshop',
                'whatsapp' => '6282132395055',
                'alamat' => 'Jl. Ahmad Yani No.2A, Jambean, Sukorejo, Kec. Bojonegoro, Kabupaten Bojonegoro, Jawa Timur 62115',
                'provinsi_name' => 'Jawa Timur',
                'city_name' => 'Bojonegoro',
            ],
            [
                'nama' => 'Cherry Petshop',
                'pic' => 'Admin Cherry Petshop',
                'whatsapp' => '6282180888077',
                'alamat' => 'Jl. Jend. A. Yani, Merening Wetan, Kedawung, Kec. Kroya, Kabupaten Cilacap, Jawa Tengah 53282',
                'provinsi_name' => 'Jawa Tengah',
                'city_name' => 'Cilacap',
            ]
        ];

        foreach ($distributorsData as $data) {
            $city = City::where('nama', $data['city_name'])->first();
            if (!$city) {
                // Find any city in the province if specific city is not found
                $province = Province::where('nama', $data['provinsi_name'])->first();
                if ($province) {
                    $city = City::where('provinsi_id', $province->id)->first();
                }
            }

            if ($city) {
                Distributor::create([
                    'kota_id' => $city->id,
                    'nama' => $data['nama'],
                    'pic' => $data['pic'],
                    'whatsapp' => $data['whatsapp'],
                    'alamat' => $data['alamat'],
                    'tampil_ke_publik' => false, // Distributor Semua disembunyikan
                    'status' => 'ACTIVE'
                ]);
            }
        }
    }
}

