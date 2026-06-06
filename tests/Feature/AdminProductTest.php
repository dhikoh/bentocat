<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bentocat.id',
            'password' => bcrypt('password123'),
            'role' => 'superadmin'
        ]);
    }

    public function test_can_create_product_with_thumbnail_file()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('litter.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'nama' => 'BentoCat Premium Lavender',
            'thumbnail_file' => $file,
            'deskripsi' => 'Bentonite cat litter with lavender scent.',
            'status' => 'ACTIVE'
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $product = Product::first();
        
        $this->assertNotNull($product);
        $this->assertStringContainsString('uploads/products/product-bentocat-premium-lavender', $product->thumbnail);
        
        // Verify file is saved in public directory
        $expectedPath = str_starts_with($product->thumbnail, '/storage/')
            ? storage_path('app/public/' . \Illuminate\Support\Str::after($product->thumbnail, '/storage/'))
            : public_path($product->thumbnail);
        $this->assertTrue(file_exists($expectedPath));

        // Clean up created file
        if (file_exists($expectedPath)) {
            @unlink($expectedPath);
        }
    }

    public function test_can_create_product_with_thumbnail_url()
    {
        $url = 'https://picsum.photos/200/300';
        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'nama' => 'BentoCat Premium Original',
            'thumbnail' => $url,
            'deskripsi' => 'Bentonite cat litter original.',
            'status' => 'ACTIVE'
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $product = Product::first();
        $this->assertNotNull($product);
        $this->assertEquals($url, $product->thumbnail);
    }

    public function test_updating_product_deletes_old_local_thumbnail()
    {
        // First create with file
        $file1 = UploadedFile::fake()->create('litter1.jpg', 100, 'image/jpeg');
        $this->actingAs($this->admin)->post('/admin/products', [
            'nama' => 'BentoCat Premium A',
            'thumbnail_file' => $file1,
            'deskripsi' => 'Description A',
            'status' => 'ACTIVE'
        ]);

        $product = Product::first();
        $oldPath = str_starts_with($product->thumbnail, '/storage/')
            ? storage_path('app/public/' . \Illuminate\Support\Str::after($product->thumbnail, '/storage/'))
            : public_path($product->thumbnail);
        $this->assertTrue(file_exists($oldPath));

        // Update with new file
        $file2 = UploadedFile::fake()->create('litter2.jpg', 100, 'image/jpeg');
        $response = $this->actingAs($this->admin)->put("/admin/products/{$product->id}", [
            'nama' => 'BentoCat Premium B',
            'thumbnail_file' => $file2,
            'deskripsi' => 'Description B',
            'status' => 'ACTIVE'
        ]);

        $response->assertRedirect(route('admin.products.index'));
        
        // Check old file was deleted
        $this->assertFalse(file_exists($oldPath));

        $product->refresh();
        $newPath = str_starts_with($product->thumbnail, '/storage/')
            ? storage_path('app/public/' . \Illuminate\Support\Str::after($product->thumbnail, '/storage/'))
            : public_path($product->thumbnail);
        $this->assertTrue(file_exists($newPath));

        // Clean up final file
        if (file_exists($newPath)) {
            @unlink($newPath);
        }
    }
}
