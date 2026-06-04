<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;
use App\Models\Distributor;
use App\Models\City;

class OutletSeeder extends Seeder
{
    public function run(): void
    {
        $outletsData = [
            'Blitar' => [
                [
                    'nama_outlet' => 'Blitar Petshop & Aquarium',
                    'nama_pic' => 'Budi',
                    'whatsapp' => '081223344556',
                    'alamat_lengkap' => 'Jl. Merdeka No. 10, Kepanjenkidul, Kota Blitar',
                    'latitude' => -8.098300,
                    'longitude' => 112.168100,
                    'featured' => true,
                    'delivery_mode' => 'SELF_DELIVERY'
                ],
                [
                    'nama_outlet' => 'Kucingku Petshop Blitar',
                    'nama_pic' => 'Dewi',
                    'whatsapp' => '081223344557',
                    'alamat_lengkap' => 'Jl. Tanjung Raya No. 40, Sukorejo, Kota Blitar',
                    'latitude' => -8.102200,
                    'longitude' => 112.155400,
                    'featured' => false,
                    'delivery_mode' => 'RECOMMENDED_SHIPPING_CONTACT'
                ]
            ],
            'Surabaya' => [
                [
                    'nama_outlet' => 'Galaxy Petshop Surabaya',
                    'nama_pic' => 'Herman',
                    'whatsapp' => '081334455667',
                    'alamat_lengkap' => 'Ruko Galaxy Mall No. 5, Mulyorejo, Kota Surabaya',
                    'latitude' => -7.265200,
                    'longitude' => 112.784500,
                    'featured' => true,
                    'delivery_mode' => 'SELF_DELIVERY'
                ],
                [
                    'nama_outlet' => 'East Coast Pet Care',
                    'nama_pic' => 'Yuni',
                    'whatsapp' => '081334455668',
                    'alamat_lengkap' => 'Jl. Kenjeran No. 450, Kenjeran, Kota Surabaya',
                    'latitude' => -7.243500,
                    'longitude' => 112.795100,
                    'featured' => false,
                    'delivery_mode' => 'PICKUP_ONLY'
                ]
            ],
            'Depok' => [
                [
                    'nama_outlet' => 'Depok Jaya Petshop',
                    'nama_pic' => 'Rian',
                    'whatsapp' => '081445566778',
                    'alamat_lengkap' => 'Jl. Margonda Raya No. 12, Pancoran Mas, Kota Depok',
                    'latitude' => -6.398500,
                    'longitude' => 106.822200,
                    'featured' => true,
                    'delivery_mode' => 'RECOMMENDED_SHIPPING_CONTACT'
                ],
                [
                    'nama_outlet' => 'Beji Cat House & Care',
                    'nama_pic' => 'Indah',
                    'whatsapp' => '081445566779',
                    'alamat_lengkap' => 'Jl. Nusantara Raya No. 50, Beji, Kota Depok',
                    'latitude' => -6.372500,
                    'longitude' => 106.811800,
                    'featured' => false,
                    'delivery_mode' => 'SELF_DELIVERY'
                ]
            ],
            'Bandung' => [
                [
                    'nama_outlet' => 'Dago Petshop & Salon',
                    'nama_pic' => 'Ridwan',
                    'whatsapp' => '081556677889',
                    'alamat_lengkap' => 'Jl. Ir. H. Juanda No. 150, Coblong, Kota Bandung',
                    'latitude' => -6.883500,
                    'longitude' => 107.618500,
                    'featured' => true,
                    'delivery_mode' => 'SELF_DELIVERY'
                ],
                [
                    'nama_outlet' => 'PVJ Cat & Dog Care',
                    'nama_pic' => 'Sari',
                    'whatsapp' => '081556677890',
                    'alamat_lengkap' => 'Jl. Sukajadi No. 131, Sukajadi, Kota Bandung',
                    'latitude' => -6.891200,
                    'longitude' => 107.598200,
                    'featured' => false,
                    'delivery_mode' => 'PICKUP_ONLY'
                ]
            ],
            'Tangerang' => [
                [
                    'nama_outlet' => 'BSD Pet Center',
                    'nama_pic' => 'Ferry',
                    'whatsapp' => '081667788990',
                    'alamat_lengkap' => 'Ruko Golden Boulevard Blok C-10, Serpong, Tangerang',
                    'latitude' => -6.280500,
                    'longitude' => 106.671200,
                    'featured' => true,
                    'delivery_mode' => 'SELF_DELIVERY'
                ],
                [
                    'nama_outlet' => 'Gading Serpong Pet Shop',
                    'nama_pic' => 'Lani',
                    'whatsapp' => '081667788991',
                    'alamat_lengkap' => 'Ruko Boulevard Gading Serpong, Kelapa Dua, Tangerang',
                    'latitude' => -6.241500,
                    'longitude' => 106.628500,
                    'featured' => false,
                    'delivery_mode' => 'RECOMMENDED_SHIPPING_CONTACT'
                ]
            ]
        ];

        foreach ($outletsData as $cityName => $outlets) {
            $city = City::where('nama', $cityName)->first();
            $distributor = Distributor::whereHas('city', function ($q) use ($cityName) {
                $q->where('nama', $cityName);
            })->first();

            if ($city && $distributor) {
                foreach ($outlets as $data) {
                    Outlet::create([
                        'distributor_id' => $distributor->id,
                        'kota_id' => $city->id,
                        'nama_outlet' => $data['nama_outlet'],
                        'nama_pic' => $data['nama_pic'],
                        'whatsapp' => $data['whatsapp'],
                        'alamat_lengkap' => $data['alamat_lengkap'],
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'google_maps_url' => 'https://maps.google.com/?q=' . $data['latitude'] . ',' . $data['longitude'],
                        'featured' => $data['featured'],
                        'status' => 'AKTIF',
                        'delivery_mode' => $data['delivery_mode']
                    ]);
                }
            }
        }
    }
}
