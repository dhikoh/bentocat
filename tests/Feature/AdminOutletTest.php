<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\City;
use App\Models\Distributor;
use App\Models\Outlet;
use Illuminate\Support\Facades\Http;

class AdminOutletTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $city;
    private $distributor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $province = \App\Models\Province::create([
            'nama' => 'Jawa Timur'
        ]);

        $this->city = City::create([
            'provinsi_id' => $province->id,
            'nama' => 'Bojonegoro',
            'slug' => 'bojonegoro',
            'latitude' => -7.150975,
            'longitude' => 111.881824
        ]);

        $this->distributor = Distributor::create([
            'kota_id' => $this->city->id,
            'nama' => 'Distributor Bojonegoro',
            'pic' => 'PIC Bjn',
            'whatsapp' => '628123456789',
            'alamat' => 'Jl. Distributor Utama',
            'tampil_ke_publik' => true,
            'status' => 'ACTIVE'
        ]);
    }

    public function test_outlet_extracts_coordinates_from_long_google_maps_url()
    {
        $outlet = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Mutiara Petshop',
            'nama_pic' => 'Andi',
            'whatsapp' => '628123456780',
            'alamat_lengkap' => 'Jl. Pemuda No. 10',
            'google_maps_url' => 'https://www.google.com/maps/place/Mutiara+Petshop/@-7.147139,111.886472,17z/data=!4m6!3m5!1s0x2e77821c!8m2!3d-7.147139!4d111.886472',
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        $this->assertEquals(-7.147139, floatval($outlet->latitude));
        $this->assertEquals(111.886472, floatval($outlet->longitude));
    }

    public function test_outlet_extracts_coordinates_from_short_google_maps_url()
    {
        $shortUrl = 'https://maps.app.goo.gl/abcdefg';
        $resolvedUrl = 'https://www.google.com/maps/place/Mutiara+Petshop/@-7.147139,111.886472,17z/data=...';

        Http::fake([
            $shortUrl => Http::response('', 302, [
                'Location' => $resolvedUrl
            ])
        ]);

        $outlet = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Mutiara Petshop 2',
            'nama_pic' => 'Andi',
            'whatsapp' => '628123456780',
            'alamat_lengkap' => 'Jl. Pemuda No. 12',
            'google_maps_url' => $shortUrl,
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        $this->assertEquals(-7.147139, floatval($outlet->latitude));
        $this->assertEquals(111.886472, floatval($outlet->longitude));
    }

    public function test_outlet_geocodes_address_via_nominatim_when_coordinates_empty()
    {
        $nominatimUrlPattern = 'https://nominatim.openstreetmap.org/search*';

        Http::fake([
            $nominatimUrlPattern => Http::response([
                [
                    'lat' => '-7.151234',
                    'lon' => '111.881234'
                ]
            ], 200)
        ]);

        $outlet = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Mutiara Petshop 3',
            'nama_pic' => 'Andi',
            'whatsapp' => '628123456780',
            'alamat_lengkap' => 'Jl. Pemuda No. 15',
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        $this->assertEquals(-7.151234, floatval($outlet->latitude));
        $this->assertEquals(111.881234, floatval($outlet->longitude));
    }
}
