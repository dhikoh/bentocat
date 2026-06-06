<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Province;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Distributor;
use App\Models\Outlet;
use App\Models\User;
use App\Models\Article;
use App\Models\CustomerProfile;
use App\Models\LeadRequest;
use App\Models\LeadAction;

class ClientDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    private $province;
    private $city;
    private $product;
    private $distributor;
    private $outletFeatured;
    private $outletRegular;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic regional data
        $this->province = Province::create([
            'nama' => 'Jawa Timur',
            'slug' => 'jawa-timur'
        ]);

        $this->city = City::create([
            'provinsi_id' => $this->province->id,
            'nama' => 'Surabaya',
            'slug' => 'surabaya'
        ]);

        // Seed dummy product and variants
        $this->product = Product::create([
            'nama' => 'BentoCat Clay Premium',
            'slug' => 'bentocat-clay-premium',
            'deskripsi' => 'Pasir kucing gumpal wangi premium',
            'status' => 'ACTIVE'
        ]);

        $v1 = ProductVariant::create([
            'produk_id' => $this->product->id,
            'nama' => 'Premium Series',
            'parent_id' => null
        ]);

        $v2 = ProductVariant::create([
            'produk_id' => $this->product->id,
            'nama' => 'Lavender',
            'parent_id' => $v1->id
        ]);

        // Seed Distributor
        $this->distributor = Distributor::create([
            'kota_id' => $this->city->id,
            'nama' => 'Distributor Jatim Raya',
            'pic' => 'Budi Santoso',
            'whatsapp' => '081222223333',
            'alamat' => 'Jl. Pergudangan Margomulyo No. 10',
            'aktif' => 1
        ]);

        // Seed Outlets (Featured vs Regular)
        // Featured outlet: coordinate -7.2600, 112.7500
        $this->outletFeatured = Outlet::create([
            'kota_id' => $this->city->id,
            'distributor_id' => $this->distributor->id,
            'nama_outlet' => 'Petshop Jaya Featured',
            'nama_pic' => 'Ahmad',
            'whatsapp' => '081333334444',
            'alamat_lengkap' => 'Jl. Gubeng No. 12',
            'latitude' => -7.26000000,
            'longitude' => 112.75000000,
            'featured' => 1,
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);

        // Regular outlet: coordinate -7.3000, 112.7800
        $this->outletRegular = Outlet::create([
            'kota_id' => $this->city->id,
            'distributor_id' => $this->distributor->id,
            'nama_outlet' => 'Petshop Petani Regular',
            'nama_pic' => 'Budi',
            'whatsapp' => '081444445555',
            'alamat_lengkap' => 'Jl. Rungkut Madya No. 50',
            'latitude' => -7.30000000,
            'longitude' => 112.78000000,
            'featured' => 0,
            'status' => 'AKTIF',
            'delivery_mode' => 'SELF_DELIVERY'
        ]);
    }

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('BentoCat');
        $response->assertSee('BentoCat Premium Bentonite');
    }

    public function test_ajax_get_cities_endpoint(): void
    {
        $response = $this->get('/api/cities-by-province/' . $this->province->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->city->id,
            'nama' => 'Surabaya',
            'slug' => 'surabaya'
        ]);
    }

    public function test_search_outlet_and_logs_lead(): void
    {
        $searchPayload = [
            'provinsi_id' => $this->province->id,
            'kota_id' => $this->city->id,
            'produk_id' => $this->product->id,
            'varian_level_1' => 'Premium Series',
            'varian_level_2' => 'Lavender',
            // User simulated coordinate: very close to Gubeng (-7.2610, 112.7510)
            'latitude' => -7.26100000,
            'longitude' => 112.75100000,
        ];

        $response = $this->post(route('search-outlet'), $searchPayload);

        $response->assertStatus(200);
        $response->assertSee('Petshop Jaya Featured');
        $response->assertSee('Petshop Petani Regular');
    }

    public function test_create_lead_and_action_api(): void
    {
        $apiPayload = [
            'nama' => 'John Doe Customer',
            'whatsapp' => '089988887777',
            'alamat' => 'Jl. Raya Darmo No. 100, Surabaya',
            'provinsi_id' => $this->province->id,
            'kota_id' => $this->city->id,
            'produk_id' => $this->product->id,
            'varian_level_1' => 'Premium Series',
            'varian_level_2' => 'Lavender',
            'latitude' => -7.26100000,
            'longitude' => 112.75100000,
            'outlet_id' => $this->outletFeatured->id,
            'action_type' => 'CLICK_WA_OUTLET',
        ];

        $response = $this->postJson(route('leads.create-and-log'), $apiPayload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('customer_profiles', [
            'whatsapp' => '6289988887777',
            'nama' => 'John Doe Customer',
            'kota' => 'Surabaya',
        ]);

        // Verify lead request created
        $this->assertDatabaseHas('lead_requests', [
            'produk_id' => $this->product->id,
            'varian_level_1' => 'Premium Series',
            'varian_level_2' => 'Lavender',
            'kota_id' => $this->city->id,
            'outlet_id' => $this->outletFeatured->id,
        ]);

        // Verify action log created
        $this->assertDatabaseHas('lead_actions', [
            'action_type' => 'CLICK_WA_OUTLET',
        ]);
    }

    public function test_ajax_log_action_triggers_correctly(): void
    {
        // Setup dummy lead
        $customer = CustomerProfile::create([
            'uuid' => 'test-uuid-1234',
            'nama' => 'Tester Client',
            'whatsapp' => '081223344',
            'alamat' => 'Test address',
        ]);

        $lead = LeadRequest::create([
            'customer_id' => $customer->id,
            'produk_id' => $this->product->id,
            'varian_level_1' => 'Premium Series',
            'kota_id' => $this->city->id,
            'outlet_id' => $this->outletFeatured->id,
            'distributor_id' => $this->distributor->id
        ]);

        $response = $this->postJson(route('leads.log-action'), [
            'lead_id' => $lead->id,
            'action_type' => 'CLICK_WA_OUTLET'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('lead_actions', [
            'lead_id' => $lead->id,
            'action_type' => 'CLICK_WA_OUTLET'
        ]);
    }

    public function test_seo_city_routing_renders(): void
    {
        $response = $this->get(route('city.landing', $this->city->slug));

        $response->assertStatus(200);
        $response->assertSee('Resmi Jual Pasir Kucing BentoCat di');
        $response->assertSee('Petshop Jaya Featured');
    }

    public function test_blog_pages(): void
    {
        $adminUser = User::factory()->create();

        $article = Article::create([
            'author_id' => $adminUser->id,
            'title' => 'Cara Tepat Memilih Pasir Kucing Wangi Gumpal',
            'slug' => 'cara-tepat-memilih-pasir-kucing-wangi-gumpal',
            'summary' => 'Berikut adalah tips membandingkan kelebihan pasir bentonite.',
            'content_json' => json_encode([
                [
                    'type' => 'paragraph',
                    'content' => 'Pasir kucing bentonite menyerap amonia dengan cepat.'
                ],
                [
                    'type' => 'quote',
                    'quote' => 'Kebersihan litter box adalah kunci kesehatan anabul Anda.',
                    'source' => 'Vet Indonesia'
                ]
            ]),
            'status' => 'PUBLISHED',
            'published_at' => now()
        ]);

        // Test Index
        $responseIndex = $this->get(route('blog.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Cara Tepat Memilih Pasir Kucing Wangi Gumpal');

        // Test Detail
        $responseDetail = $this->get(route('blog.show', $article->slug));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('Pasir kucing bentonite menyerap amonia dengan cepat.');
        $responseDetail->assertSee('Vet Indonesia');
    }

    public function test_api_get_outlets_by_city(): void
    {
        $response = $this->get('/api/outlets-by-city/' . $this->city->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'nama_outlet' => 'Petshop Jaya Featured'
        ]);
    }
}
