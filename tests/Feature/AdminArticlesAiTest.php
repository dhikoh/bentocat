<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;

class AdminArticlesAiTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_assist_endpoint_requires_authentication()
    {
        $response = $this->postJson('/admin/articles/ai-assist', [
            'title' => 'Tips Memelihara Kucing Persia'
        ]);

        $response->assertStatus(401);
    }

    public function test_ai_assist_endpoint_validates_required_title()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bentocat.com',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $response = $this->actingAs($admin)->postJson('/admin/articles/ai-assist', [
            'summary' => 'Hanya summary saja tanpa judul'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    public function test_ai_assist_endpoint_returns_suggestions()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bentocat.com',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        // Create another article to test internal links
        Article::create([
            'title' => 'Cara Merawat Bulu Kucing Agar Tebal',
            'slug' => 'cara-merawat-bulu-kucing-agar-tebal',
            'summary' => 'Panduan singkat merawat bulu kucing.',
            'content_json' => [],
            'status' => 'PUBLISHED'
        ]);

        $response = $this->actingAs($admin)->postJson('/admin/articles/ai-assist', [
            'title' => 'Nutrisi Penting Kucing Dewasa',
            'summary' => 'Makanan sehat yang bagus untuk kucing.'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'outline',
            'faqs' => [
                '*' => ['q', 'a']
            ],
            'seo_title',
            'seo_description',
            'internal_links' => [
                '*' => ['title', 'url']
            ]
        ]);

        $this->assertTrue($response->json('success'));
        $this->assertCount(4, $response->json('outline'));
        $this->assertCount(2, $response->json('faqs'));
    }
}
