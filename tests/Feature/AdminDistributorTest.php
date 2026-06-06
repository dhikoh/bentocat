<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\City;
use App\Models\Province;
use App\Models\Distributor;

class AdminDistributorTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $province;
    private $city;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $this->province = Province::create([
            'nama' => 'Jawa Timur'
        ]);

        $this->city = City::create([
            'provinsi_id' => $this->province->id,
            'nama' => 'Surabaya',
            'slug' => 'surabaya'
        ]);
    }

    public function test_distributor_store_validation_requires_matching_province_and_city()
    {
        $otherProvince = Province::create([
            'nama' => 'Jawa Barat'
        ]);

        $otherCity = City::create([
            'provinsi_id' => $otherProvince->id,
            'nama' => 'Bandung',
            'slug' => 'bandung'
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/distributors', [
            'provinsi_id' => $this->province->id,
            'kota_id' => $otherCity->id,
            'nama' => 'Distributor Bandung Baru',
            'pic' => 'PIC Bandung',
            'whatsapp' => '628123456789',
            'alamat' => 'Jl. Bandung Indah',
            'status' => 'ACTIVE'
        ]);

        $response->assertSessionHasErrors(['kota_id']);
    }

    public function test_distributor_store_validation_passes_when_province_and_city_match()
    {
        $response = $this->actingAs($this->admin)->post('/admin/distributors', [
            'provinsi_id' => $this->province->id,
            'kota_id' => $this->city->id,
            'nama' => 'Distributor Surabaya Baru',
            'pic' => 'PIC Sby',
            'whatsapp' => '628123456789',
            'alamat' => 'Jl. Surabaya Indah',
            'status' => 'ACTIVE'
        ]);

        $response->assertRedirect(route('admin.distributors.index'));
        $this->assertDatabaseHas('distributors', [
            'nama' => 'Distributor Surabaya Baru',
            'kota_id' => $this->city->id
        ]);
    }

    public function test_distributor_update_validation_requires_matching_province_and_city()
    {
        $distributor = Distributor::create([
            'kota_id' => $this->city->id,
            'nama' => 'Distributor Awal',
            'pic' => 'PIC Awal',
            'whatsapp' => '628123456789',
            'alamat' => 'Jl. Awal',
            'status' => 'ACTIVE'
        ]);

        $otherProvince = Province::create([
            'nama' => 'Jawa Barat'
        ]);

        $otherCity = City::create([
            'provinsi_id' => $otherProvince->id,
            'nama' => 'Bandung',
            'slug' => 'bandung'
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/distributors/{$distributor->id}", [
            'provinsi_id' => $this->province->id,
            'kota_id' => $otherCity->id,
            'nama' => 'Distributor Edit Bandung',
            'pic' => 'PIC Edit',
            'whatsapp' => '628123456789',
            'alamat' => 'Jl. Bandung Indah Edit',
            'status' => 'ACTIVE'
        ]);

        $response->assertSessionHasErrors(['kota_id']);
    }

    public function test_distributor_update_validation_passes_when_province_and_city_match()
    {
        $distributor = Distributor::create([
            'kota_id' => $this->city->id,
            'nama' => 'Distributor Awal',
            'pic' => 'PIC Awal',
            'whatsapp' => '628123456789',
            'alamat' => 'Jl. Awal',
            'status' => 'ACTIVE'
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/distributors/{$distributor->id}", [
            'provinsi_id' => $this->province->id,
            'kota_id' => $this->city->id,
            'nama' => 'Distributor Edit Surabaya',
            'pic' => 'PIC Edit Sby',
            'whatsapp' => '628123456789',
            'alamat' => 'Jl. Surabaya Indah Edit',
            'status' => 'ACTIVE'
        ]);

        $response->assertRedirect(route('admin.distributors.index'));
        $this->assertDatabaseHas('distributors', [
            'id' => $distributor->id,
            'nama' => 'Distributor Edit Surabaya',
            'kota_id' => $this->city->id
        ]);
    }
}
