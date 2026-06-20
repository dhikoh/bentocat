<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\MarketingTemplate;
use App\Models\Setting;
use App\Models\CustomerProfile;
use App\Models\PromptHistory;

class AdminPromptGeneratorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed standard settings and templates if required or create on-the-fly
        Setting::set('prompt_product_name', 'BentoCat Test Product');
        Setting::set('prompt_advantages', 'Advantage 1');
        Setting::set('prompt_marketing_system', 'System 1');
    }

    public function test_prompt_generator_routes_require_authentication()
    {
        $response = $this->get('/admin/prompt-generator');
        $response->assertRedirect('/admin/login');
    }

    public function test_prompt_generator_routes_deny_unauthorized_roles()
    {
        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'editor'
        ]);

        $response = $this->actingAs($editor)->get('/admin/prompt-generator');
        $response->assertStatus(403);
    }

    public function test_prompt_generator_routes_allow_superadmin_and_marketing()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $marketing = User::create([
            'name' => 'Marketing User',
            'email' => 'marketing@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'marketing'
        ]);

        // Test Superadmin
        $response = $this->actingAs($admin)->get('/admin/prompt-generator');
        $response->assertStatus(200);

        // Test Marketing
        $response = $this->actingAs($marketing)->get('/admin/prompt-generator');
        $response->assertStatus(200);
    }

    public function test_can_save_product_profile_settings()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $response = $this->actingAs($admin)->post('/admin/prompt-generator/save-product', [
            'prompt_product_name' => 'New Product Name',
            'prompt_advantages' => "Advantage A\nAdvantage B",
            'prompt_marketing_system' => 'Direct to customer'
        ]);

        $response->assertRedirect('/admin/prompt-generator');
        
        $this->assertEquals('New Product Name', Setting::get('prompt_product_name'));
        $this->assertEquals("Advantage A\nAdvantage B", Setting::get('prompt_advantages'));
        $this->assertEquals('Direct to customer', Setting::get('prompt_marketing_system'));
    }

    public function test_can_generate_prompt()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $template = MarketingTemplate::create([
            'name' => 'Promo B2B',
            'category' => 'B2B',
            'target_audience' => 'Petshops',
            'tone' => 'Professional',
            'placeholders' => 'nama_petshop,harga',
            'base_prompt' => 'Tawarkan ke {nama_petshop} dengan harga {harga}.'
        ]);

        $response = $this->actingAs($admin)->post('/admin/prompt-generator/generate', [
            'template_id' => $template->id,
            'target_audience' => 'Owner Petshop Bandung',
            'tone' => 'Sopan & Ramah',
            'language' => 'Bahasa Indonesia',
            'length' => 'Sedang (200 - 450 kata)',
            'emoji_style' => 'standard',
            'variables' => [
                'nama_petshop' => 'Miau Petshop',
                'harga' => 'Rp 50.000'
            ],
            'custom_notes' => 'Tambahkan promo buy 10 get 1',
            'customer_chat' => 'Halo BentoCat, saya tertarik bermitra untuk Petshop saya.'
        ]);

        $response->assertRedirect('/admin/prompt-generator');
        $response->assertSessionHas('generated_prompt');
        
        $prompt = session('generated_prompt');
        $this->assertStringContainsString('Miau Petshop', $prompt);
        $this->assertStringContainsString('Rp 50.000', $prompt);
        $this->assertStringContainsString('B2B', $prompt);
        $this->assertStringContainsString('Owner Petshop Bandung', $prompt);
        $this->assertStringContainsString('Tambahkan promo buy 10 get 1', $prompt);
        $this->assertStringContainsString('Halo BentoCat, saya tertarik bermitra untuk Petshop saya.', $prompt);
        $this->assertStringContainsString('PESAN / CHAT MASUK DARI CUSTOMER', $prompt);
    }

    public function test_can_generate_prompt_via_ajax()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $template = MarketingTemplate::create([
            'name' => 'Promo B2B',
            'category' => 'B2B',
            'target_audience' => 'Petshops',
            'tone' => 'Professional',
            'placeholders' => 'nama_petshop',
            'base_prompt' => 'Tawarkan ke {nama_petshop}.'
        ]);

        // Test AJAX Response
        $response = $this->actingAs($admin)->postJson('/admin/prompt-generator/generate', [
            'template_id' => $template->id,
            'target_audience' => 'Owner Petshop Bandung',
            'tone' => 'Sopan & Ramah',
            'language' => 'Bahasa Indonesia',
            'length' => 'Sedang (200 - 450 kata)',
            'emoji_style' => 'standard',
            'variables' => [
                'nama_petshop' => 'Miau Petshop'
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['prompt']);
        $this->assertStringContainsString('Miau Petshop', $response->json('prompt'));
    }

    public function test_empty_variables_fallback_to_bracketed_placeholders()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $template = MarketingTemplate::create([
            'name' => 'Test Promo',
            'category' => 'B2B',
            'target_audience' => 'Petshops',
            'tone' => 'Professional',
            'placeholders' => 'nama_petshop,diskon_khusus',
            'base_prompt' => 'Tawarkan ke {nama_petshop} dengan diskon {diskon_khusus}.'
        ]);

        // Submit with diskon_khusus as empty string
        $response = $this->actingAs($admin)->postJson('/admin/prompt-generator/generate', [
            'template_id' => $template->id,
            'target_audience' => 'Owner Petshop Bandung',
            'tone' => 'Sopan & Ramah',
            'language' => 'Bahasa Indonesia',
            'length' => 'Sedang (200 - 450 kata)',
            'emoji_style' => 'standard',
            'variables' => [
                'nama_petshop' => 'Miau Petshop',
                'diskon_khusus' => ''
            ]
        ]);

        $response->assertStatus(200);
        $prompt = $response->json('prompt');
        $this->assertStringContainsString('Miau Petshop', $prompt);
        $this->assertStringContainsString('[Diskon Khusus]', $prompt);
    }


    public function test_can_perform_crud_on_marketing_templates()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        // 1. Index templates
        $response = $this->actingAs($admin)->get('/admin/prompt-generator/templates');
        $response->assertStatus(200);

        // 2. Create page
        $response = $this->actingAs($admin)->get('/admin/prompt-generator/templates/create');
        $response->assertStatus(200);

        // 3. Store template
        $response = $this->actingAs($admin)->post('/admin/prompt-generator/templates', [
            'name' => 'New Template',
            'category' => 'Social',
            'target_audience' => 'Followers',
            'tone' => 'Excited',
            'placeholders' => 'keyword',
            'base_prompt' => 'Tulis info dengan keyword {keyword}'
        ]);
        $response->assertRedirect('/admin/prompt-generator/templates');
        $this->assertDatabaseHas('marketing_templates', ['name' => 'New Template']);

        $template = MarketingTemplate::where('name', 'New Template')->first();

        // 4. Edit page
        $response = $this->actingAs($admin)->get("/admin/prompt-generator/templates/{$template->id}/edit");
        $response->assertStatus(200);

        // 5. Update template
        $response = $this->actingAs($admin)->put("/admin/prompt-generator/templates/{$template->id}", [
            'name' => 'Updated Template',
            'category' => 'Social',
            'target_audience' => 'Followers',
            'tone' => 'Excited',
            'placeholders' => 'keyword',
            'base_prompt' => 'Tulis info dengan keyword {keyword}'
        ]);
        $response->assertRedirect('/admin/prompt-generator/templates');
        $this->assertDatabaseHas('marketing_templates', ['name' => 'Updated Template']);

        // 6. Delete template
        $response = $this->actingAs($admin)->delete("/admin/prompt-generator/templates/{$template->id}");
        $response->assertRedirect('/admin/prompt-generator/templates');
        $this->assertDatabaseMissing('marketing_templates', ['name' => 'Updated Template']);
    }

    public function test_can_download_marketing_handbook()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $response = $this->actingAs($admin)->get('/admin/prompt-generator/download');
        $response->assertStatus(200);
        $this->assertEquals('marketing_skills_handbook.md', $response->headers->get('content-disposition') ? explode('filename=', $response->headers->get('content-disposition'))[1] : 'marketing_skills_handbook.md');
    }

    public function test_marketing_role_has_full_crud_access()
    {
        $marketing = User::create([
            'name' => 'Marketing User',
            'email' => 'marketing@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'marketing'
        ]);

        // 1. Can save product profile settings
        $response = $this->actingAs($marketing)->post('/admin/prompt-generator/save-product', [
            'prompt_product_name' => 'Marketing Modified Product',
            'prompt_advantages' => 'Advantage M',
            'prompt_marketing_system' => 'System M'
        ]);
        $response->assertRedirect('/admin/prompt-generator');
        $this->assertEquals('Marketing Modified Product', Setting::get('prompt_product_name'));

        // 2. Can generate prompt
        $template = MarketingTemplate::create([
            'name' => 'Promo B2B',
            'category' => 'B2B',
            'target_audience' => 'Petshops',
            'tone' => 'Professional',
            'placeholders' => 'nama_petshop',
            'base_prompt' => 'Tawarkan ke {nama_petshop}.'
        ]);

        $response = $this->actingAs($marketing)->post('/admin/prompt-generator/generate', [
            'template_id' => $template->id,
            'target_audience' => 'Owner Petshop Bandung',
            'tone' => 'Sopan & Ramah',
            'language' => 'Bahasa Indonesia',
            'length' => 'Sedang (200 - 450 kata)',
            'emoji_style' => 'standard',
            'variables' => [
                'nama_petshop' => 'Miau Petshop'
            ]
        ]);
        $response->assertRedirect('/admin/prompt-generator');
        $response->assertSessionHas('generated_prompt');

        // 3. Can perform CRUD on templates
        // Index
        $response = $this->actingAs($marketing)->get('/admin/prompt-generator/templates');
        $response->assertStatus(200);

        // Create page
        $response = $this->actingAs($marketing)->get('/admin/prompt-generator/templates/create');
        $response->assertStatus(200);

        // Store
        $response = $this->actingAs($marketing)->post('/admin/prompt-generator/templates', [
            'name' => 'Marketing Template',
            'category' => 'Social',
            'target_audience' => 'Followers',
            'tone' => 'Excited',
            'placeholders' => 'keyword',
            'base_prompt' => 'Tulis info dengan keyword {keyword}'
        ]);
        $response->assertRedirect('/admin/prompt-generator/templates');
        $this->assertDatabaseHas('marketing_templates', ['name' => 'Marketing Template']);

        $mTemplate = MarketingTemplate::where('name', 'Marketing Template')->first();

        // Edit page
        $response = $this->actingAs($marketing)->get("/admin/prompt-generator/templates/{$mTemplate->id}/edit");
        $response->assertStatus(200);

        // Update
        $response = $this->actingAs($marketing)->put("/admin/prompt-generator/templates/{$mTemplate->id}", [
            'name' => 'Updated Marketing Template',
            'category' => 'Social',
            'target_audience' => 'Followers',
            'tone' => 'Excited',
            'placeholders' => 'keyword',
            'base_prompt' => 'Tulis info dengan keyword {keyword}'
        ]);
        $response->assertRedirect('/admin/prompt-generator/templates');
        $this->assertDatabaseHas('marketing_templates', ['name' => 'Updated Marketing Template']);

        // Delete
        $response = $this->actingAs($marketing)->delete("/admin/prompt-generator/templates/{$mTemplate->id}");
        $response->assertRedirect('/admin/prompt-generator/templates');
        $this->assertDatabaseMissing('marketing_templates', ['name' => 'Updated Marketing Template']);
    }

    public function test_marketing_template_seeder_runs_successfully()
    {
        $this->seed(\Database\Seeders\MarketingTemplateSeeder::class);
        $this->assertDatabaseCount('marketing_templates', 8);
    }

    public function test_can_generate_prompt_with_customer_and_saves_history()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $customer = CustomerProfile::create([
            'uuid' => 'test-uuid-123',
            'nama' => 'Budi Petshop',
            'whatsapp' => '08123456789',
            'alamat' => 'Jl. Kucing No. 10',
            'provinsi' => 'Jawa Barat',
            'kota' => 'Bandung'
        ]);

        $template = MarketingTemplate::create([
            'name' => 'Promo B2B',
            'category' => 'B2B',
            'target_audience' => 'Petshops',
            'tone' => 'Professional',
            'placeholders' => 'nama_petshop',
            'base_prompt' => 'Tawarkan ke {nama_petshop}.'
        ]);

        $response = $this->actingAs($admin)->postJson('/admin/prompt-generator/generate', [
            'template_id' => $template->id,
            'target_audience' => 'Owner Petshop Bandung',
            'tone' => 'Sopan & Ramah',
            'language' => 'Bahasa Indonesia',
            'length' => 'Sedang (200 - 450 kata)',
            'emoji_style' => 'standard',
            'customer_profile_id' => $customer->id,
            'variables' => [
                'nama_petshop' => 'Miau Petshop'
            ],
            'customer_chat' => 'Mau beli BentoCat dong.'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('prompt_histories', [
            'customer_profile_id' => $customer->id,
            'template_name' => 'Promo B2B',
            'chat_input' => 'Mau beli BentoCat dong.'
        ]);
    }

    public function test_can_generate_prompt_without_emojis_when_emoji_style_is_none()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $template = MarketingTemplate::create([
            'name' => 'Promo B2B',
            'category' => 'B2B',
            'target_audience' => 'Petshops',
            'tone' => 'Professional',
            'placeholders' => 'nama_petshop',
            'base_prompt' => 'Tawarkan ke {nama_petshop}. 😊🐱'
        ]);

        $response = $this->actingAs($admin)->postJson('/admin/prompt-generator/generate', [
            'template_id' => $template->id,
            'target_audience' => 'Owner Petshop Bandung',
            'tone' => 'Sopan & Ramah',
            'language' => 'Bahasa Indonesia',
            'length' => 'Sedang (200 - 450 kata)',
            'emoji_style' => 'none',
            'variables' => [
                'nama_petshop' => 'Miau Petshop'
            ]
        ]);

        $response->assertStatus(200);
        $prompt = $response->json('prompt');
        
        // Check that the instruction forbids emojis
        $this->assertStringContainsString('DILARANG keras menggunakan emoji', $prompt);
    }

    public function test_can_perform_quick_customer_ajax_crud()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        // Store
        $response = $this->actingAs($admin)->postJson('/admin/prompt-generator/customers/quick-store', [
            'nama' => 'Anto Petshop',
            'whatsapp' => '08777777777',
            'alamat' => 'Jl. Anjing No. 1',
            'provinsi' => 'DKI Jakarta',
            'kota' => 'Jakarta Barat'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('customer_profiles', [
            'nama' => 'Anto Petshop',
            'whatsapp' => '628777777777' // setWhatsappAttribute cleans and formats
        ]);

        $customer = CustomerProfile::where('nama', 'Anto Petshop')->first();

        // Update
        $response = $this->actingAs($admin)->putJson("/admin/prompt-generator/customers/{$customer->id}/quick-update", [
            'nama' => 'Anto Petshop Updated',
            'whatsapp' => '08777777777',
            'alamat' => 'Jl. Anjing No. 2',
            'provinsi' => 'DKI Jakarta',
            'kota' => 'Jakarta Pusat'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseHas('customer_profiles', [
            'id' => $customer->id,
            'nama' => 'Anto Petshop Updated',
            'alamat' => 'Jl. Anjing No. 2'
        ]);

        // Destroy
        $response = $this->actingAs($admin)->deleteJson("/admin/prompt-generator/customers/{$customer->id}/quick-destroy");
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseMissing('customer_profiles', [
            'id' => $customer->id
        ]);
    }

    public function test_can_fetch_and_delete_prompt_history_via_ajax()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $customer = CustomerProfile::create([
            'uuid' => 'test-uuid-456',
            'nama' => 'Cici Petshop',
            'whatsapp' => '08123456789',
            'alamat' => 'Jl. Alamat'
        ]);

        $history = PromptHistory::create([
            'customer_profile_id' => $customer->id,
            'user_id' => $admin->id,
            'template_name' => 'Promo B2B',
            'chat_input' => 'Test chat',
            'generated_prompt' => 'Test prompt'
        ]);

        // Get History
        $response = $this->actingAs($admin)->getJson("/admin/prompt-generator/customers/{$customer->id}/history");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'customer',
            'history' => [
                '*' => [
                    'id',
                    'template_name',
                    'chat_input',
                    'generated_prompt',
                    'created_at'
                ]
            ]
        ]);

        // Delete History
        $response = $this->actingAs($admin)->deleteJson("/admin/prompt-generator/history/{$history->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
        $this->assertDatabaseMissing('prompt_histories', [
            'id' => $history->id
        ]);
    }
}
