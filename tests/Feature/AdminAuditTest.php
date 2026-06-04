<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Province;
use App\Models\City;
use App\Models\Product;
use App\Models\Distributor;
use App\Models\Outlet;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\LeadRequest;
use App\Models\ShippingContact;
use Illuminate\Support\Facades\DB;

class AdminAuditTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $province;
    private $city;
    private $distributor;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Superadmin User
        $this->admin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@bentocat.com',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        // 2. Create Region Data
        $this->province = Province::create([
            'nama' => 'Jawa Timur'
        ]);

        $this->city = City::create([
            'provinsi_id' => $this->province->id,
            'nama' => 'Surabaya',
            'slug' => 'surabaya'
        ]);

        // 3. Create Distributor
        $this->distributor = Distributor::create([
            'kota_id' => $this->city->id,
            'nama' => 'Distributor Utama Surabaya',
            'pic' => 'Budi',
            'whatsapp' => '081299991111',
            'alamat' => 'Jl. Bubutan',
            'status' => 'ACTIVE'
        ]);

        // 4. Create Product
        $this->product = Product::create([
            'nama' => 'Pasir BentoCat 1',
            'slug' => 'pasir-bentocat-1',
            'status' => 'ACTIVE'
        ]);
    }

    public function test_audit_page_requires_authentication()
    {
        $response = $this->get('/admin/audit');
        $response->assertRedirect('/admin/login');
    }

    public function test_audit_page_renders_for_superadmin_with_correct_metrics()
    {
        $response = $this->actingAs($this->admin)->get('/admin/audit');
        $response->assertStatus(200);
        $response->assertSee('Audit & Kesehatan Bisnis', false);
        $response->assertSee('Customer Repeat Rate');
        $response->assertSee('Courier Attachment Rate');
        $response->assertSee('Active Partner Ratio');
    }

    public function test_audit_detects_duplicate_petshops_by_whatsapp()
    {
        // Create duplicate outlets (same WhatsApp)
        $outletA = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Petshop Pertama',
            'nama_pic' => 'Pic A',
            'whatsapp' => '081234567890',
            'alamat_lengkap' => 'Jl. Mawar No. 1',
            'status' => 'AKTIF',
            'is_mitra' => true
        ]);

        $outletB = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Petshop Kedua',
            'nama_pic' => 'Pic B',
            'whatsapp' => '081234567890',
            'alamat_lengkap' => 'Jl. Melati No. 2',
            'status' => 'AKTIF',
            'is_mitra' => true
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/audit');
        $response->assertStatus(200);
        $response->assertSee('Petshop Pertama');
        $response->assertSee('Petshop Kedua');
        $response->assertSee('081234567890');
    }

    public function test_merge_petshops_remaps_leads_and_deletes_duplicate()
    {
        // 1. Create Outlets
        $target = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Target Petshop',
            'nama_pic' => 'Pic Target',
            'whatsapp' => '081122223333',
            'alamat_lengkap' => 'Jl. Asli No. 10',
            'status' => 'AKTIF',
            'is_mitra' => true
        ]);

        $duplicate = Outlet::create([
            'distributor_id' => $this->distributor->id,
            'kota_id' => $this->city->id,
            'nama_outlet' => 'Duplicate Petshop',
            'nama_pic' => 'Pic Dupe',
            'whatsapp' => '081122223333',
            'alamat_lengkap' => 'Jl. Duplikat No. 11',
            'status' => 'AKTIF',
            'is_mitra' => true
        ]);

        // 2. Create Customer Profile & Lead
        $customer = CustomerProfile::create([
            'uuid' => 'test-customer-uuid-12345',
            'nama' => 'Pramita',
            'whatsapp' => '089988887777',
            'alamat' => 'Sidoarjo'
        ]);

        $lead = LeadRequest::create([
            'customer_id' => $customer->id,
            'produk_id' => $this->product->id,
            'varian_level_1' => 'Premium',
            'kota_id' => $this->city->id,
            'outlet_id' => $duplicate->id,
            'distributor_id' => $this->distributor->id
        ]);

        // 3. Create Pivot Shipping link for Duplicate
        $courier = ShippingContact::create([
            'nama' => 'JNE Lokalan',
            'whatsapp' => '081333334444',
            'aktif' => true
        ]);

        $target->shippingContacts()->attach($courier->id, ['urutan' => 1]);
        $duplicate->shippingContacts()->attach($courier->id, ['urutan' => 1]);

        // 4. Perform Merge post request
        $response = $this->actingAs($this->admin)->post('/admin/audit/merge', [
            'type' => 'petshop',
            'target_id' => $target->id,
            'duplicate_id' => $duplicate->id
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 5. Assert database state
        // Duplicate outlet should be deleted
        $this->assertDatabaseMissing('outlets', ['id' => $duplicate->id]);
        
        // Target outlet must still exist
        $this->assertDatabaseHas('outlets', ['id' => $target->id]);

        // LeadRequest must be remapped to Target Outlet
        $this->assertDatabaseHas('lead_requests', [
            'id' => $lead->id,
            'outlet_id' => $target->id
        ]);

        // Pivot table should have only target outlet connected (not throw unique error)
        $this->assertDatabaseHas('petshop_shipping_contacts', [
            'petshop_id' => $target->id,
            'shipping_contact_id' => $courier->id
        ]);
        $this->assertDatabaseMissing('petshop_shipping_contacts', [
            'petshop_id' => $duplicate->id
        ]);
    }
}
