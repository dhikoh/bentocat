<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Distributor;
use App\Models\City;

class DistributorSeeder extends Seeder
{
    public function run(): void
    {
        $distributionCities = [
            'Blitar' => [
                'nama' => 'BentoCat Distributor Blitar (Pusat)',
                'pic' => 'Pak Joko',
                'whatsapp' => '081234567890',
                'alamat' => 'Jl. Sudirman No. 45, Kepanjenkidul, Kota Blitar, Jawa Timur'
            ],
            'Surabaya' => [
                'nama' => 'BentoCat Distributor Surabaya',
                'pic' => 'Ibu Maria',
                'whatsapp' => '081345678901',
                'alamat' => 'Ruko Juanda Raya No. 12, Sidoarjo / Surabaya'
            ],
            'Depok' => [
                'nama' => 'BentoCat Distributor Depok & Jakarta Selatan',
                'pic' => 'Pak Andi',
                'whatsapp' => '081456789012',
                'alamat' => 'Jl. Margonda Raya No. 200, Beji, Kota Depok'
            ],
            'Bandung' => [
                'nama' => 'BentoCat Distributor Priangan (Bandung)',
                'pic' => 'Kang Asep',
                'whatsapp' => '081567890123',
                'alamat' => 'Jl. Soekarno-Hatta No. 500, Buahbatu, Kota Bandung'
            ],
            'Tangerang' => [
                'nama' => 'BentoCat Distributor Banten (Tangerang)',
                'pic' => 'Pak Hendra',
                'whatsapp' => '081678901234',
                'alamat' => 'Bumi Serpong Damai (BSD) Sektor 4, Tangerang'
            ]
        ];

        foreach ($distributionCities as $cityName => $data) {
            $city = City::where('nama', $cityName)->first();
            if ($city) {
                Distributor::create([
                    'kota_id' => $city->id,
                    'nama' => $data['nama'],
                    'pic' => $data['pic'],
                    'whatsapp' => $data['whatsapp'],
                    'alamat' => $data['alamat'],
                    'tampil_ke_publik' => true,
                    'status' => 'ACTIVE'
                ]);
            }
        }
    }
}
