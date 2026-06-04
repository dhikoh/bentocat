<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_requires_authentication()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_dashboard_renders_for_authenticated_user()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bentocat.com',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }
}
