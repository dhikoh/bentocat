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

    public function test_outlet_store_validation_requires_matching_province_and_city()
    {
        $otherProvince = \App\Models\Province::create([
            'nama' => 'Jawa Barat'
        ]);

        $otherCity = City::create([
            'provinsi_id' => $otherProvince->id,
            'nama' => 'Bandung',
            'slug' => 'bandung'
        ]);

        // Submit form with province 'Jawa Timur' (id of $this->city->provinsi_id) but city 'Bandung' ($otherCity->id)
        $response = $this->actingAs($this->admin)->post('/admin/outlets', [
            'distributor_id' => $this->distributor->id,
            'provinsi_id' => $this->city->provinsi_id,
            'kota_id' => $otherCity->id,
            'nama_outlet' => 'Mutiara Petshop Store Validation',
            'nama_pic' => 'Andi',
            'whatsapp' => '628123456780',
            'alamat_lengkap' => 'Jl. Pemuda No. 15',
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        $response->assertSessionHasErrors(['kota_id']);
    }

    public function test_outlet_store_validation_passes_when_province_and_city_match()
    {
        $response = $this->actingAs($this->admin)->post('/admin/outlets', [
            'distributor_id' => $this->distributor->id,
            'provinsi_id' => $this->city->provinsi_id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Mutiara Petshop Store Passes',
            'nama_pic' => 'Andi',
            'whatsapp' => '628123456780',
            'alamat_lengkap' => 'Jl. Pemuda No. 15',
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        $response->assertRedirect(route('admin.outlets.index'));
        $this->assertDatabaseHas('outlets', [
            'nama_outlet' => 'Mutiara Petshop Store Passes',
            'kota_id' => $this->city->id
        ]);
    }

    public function test_outlet_batch_reassign_shipping_contact()
    {
        $outlet1 = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Petshop A',
            'nama_pic' => 'Andi',
            'whatsapp' => '628123456780',
            'alamat_lengkap' => 'Jl. Pemuda No. 15',
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        $outlet2 = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Petshop B',
            'nama_pic' => 'Budi',
            'whatsapp' => '628123456781',
            'alamat_lengkap' => 'Jl. Pemuda No. 16',
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        $shippingContact = \App\Models\ShippingContact::create([
            'nama' => 'Ojek Online Surabaya',
            'whatsapp' => '628123456700',
            'tampil_ke_publik' => true
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/outlets/batch-reassign-shipping', [
            'outlet_ids' => [$outlet1->id, $outlet2->id],
            'shipping_contact_id' => $shippingContact->id
        ]);

        $response->assertRedirect(route('admin.outlets.index'));
        $this->assertTrue($outlet1->fresh()->shippingContacts->contains($shippingContact->id));
        $this->assertTrue($outlet2->fresh()->shippingContacts->contains($shippingContact->id));

        // Clear all contacts test
        $responseClear = $this->actingAs($this->admin)->post('/admin/outlets/batch-reassign-shipping', [
            'outlet_ids' => [$outlet1->id, $outlet2->id],
            'shipping_contact_id' => null
        ]);

        $responseClear->assertRedirect(route('admin.outlets.index'));
        $this->assertCount(0, $outlet1->fresh()->shippingContacts);
        $this->assertCount(0, $outlet2->fresh()->shippingContacts);
    }

    public function test_outlet_search_does_not_match_distributor_name()
    {
        // $this->distributor->nama is 'Distributor Bojonegoro' which contains 'ibu'
        $outlet = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Mutiara Petshop Unique Search',
            'nama_pic' => 'Andi',
            'whatsapp' => '628123456780',
            'alamat_lengkap' => 'Jl. Pemuda No. 15',
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        // Search for 'ibu'. Since the distributor name has 'Distributor' (containing 'ibu'),
        // if search matches distributor, it would find it. If it doesn't, it won't find it.
        $response = $this->actingAs($this->admin)->get('/admin/outlets?search=ibu');

        $response->assertStatus(200);
        $response->assertDontSee('Mutiara Petshop Unique Search');

        // Search for 'Mutiara' which is in the outlet name. It should see it.
        $response2 = $this->actingAs($this->admin)->get('/admin/outlets?search=Mutiara');
        $response2->assertStatus(200);
        $response2->assertSee('Mutiara Petshop Unique Search');
    }

    public function test_outlet_search_matches_whatsapp()
    {
        $outlet = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Kucing Imut Petshop',
            'nama_pic' => 'Budi',
            'whatsapp' => '6287779998881',
            'alamat_lengkap' => 'Jl. Kebagusan No. 20',
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        // Search for full WhatsApp number
        $response = $this->actingAs($this->admin)->get('/admin/outlets?search=6287779998881');
        $response->assertStatus(200);
        $response->assertSee('Kucing Imut Petshop');

        // Search for partial WhatsApp number
        $response2 = $this->actingAs($this->admin)->get('/admin/outlets?search=999888');
        $response2->assertStatus(200);
        $response2->assertSee('Kucing Imut Petshop');
    }
}
