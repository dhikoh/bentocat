<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::withCount('variants')
            ->when($search, function ($query, $search) {
                $lowered = '%' . strtolower($search) . '%';
                $query->whereRaw('LOWER(nama) LIKE ?', [$lowered]);
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.products.index', compact('products', 'search'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'thumbnail_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,webm|max:20480',
            'cropped_image_data' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:ACTIVE,INACTIVE',
            'label_level_1' => 'nullable|string|max:100',
            'label_level_2' => 'nullable|string|max:100',
            'label_level_3' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $count = Product::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        if ($request->filled('cropped_image_data')) {
            try {
                $base64Data = $request->input('cropped_image_data');
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $type = strtolower($type[1]); // png, jpg, jpeg
                    if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        throw new \Exception('Format gambar tidak valid.');
                    }
                    $data = base64_decode($base64Data);
                    if ($data === false) {
                        throw new \Exception('Gagal mendekode berkas gambar.');
                    }

                    $filename = 'product-' . $validated['slug'] . '-' . time() . '.' . $type;
                    $uploadPath = storage_path('app/public/uploads/products');
                    if (!file_exists($uploadPath)) {
                        if (!@mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
                            throw new \Exception("Tidak dapat membuat folder 'storage/app/public/uploads/products' di server.");
                        }
                    }
                    file_put_contents($uploadPath . '/' . $filename, $data);
                    $validated['thumbnail'] = '/storage/uploads/products/' . $filename;
                }
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal memproses gambar crop: ' . $e->getMessage());
            }
        } elseif ($request->hasFile('thumbnail_file')) {
            try {
                $file = $request->file('thumbnail_file');
                $filename = 'product-' . $validated['slug'] . '-' . time() . '.' . $file->getClientOriginalExtension();
                $uploadPath = storage_path('app/public/uploads/products');
                if (!file_exists($uploadPath)) {
                    if (!@mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
                        throw new \Exception("Tidak dapat membuat folder 'storage/app/public/uploads/products' di server.");
                    }
                }
                $file->move($uploadPath, $filename);
                $validated['thumbnail'] = '/storage/uploads/products/' . $filename;
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal mengunggah berkas: ' . $e->getMessage());
            }
        }

        unset($validated['thumbnail_file']);
        unset($validated['cropped_image_data']);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'thumbnail_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,webm|max:20480',
            'cropped_image_data' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:ACTIVE,INACTIVE',
            'label_level_1' => 'nullable|string|max:100',
            'label_level_2' => 'nullable|string|max:100',
            'label_level_3' => 'nullable|string|max:100',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $count = Product::where('slug', 'like', $validated['slug'] . '%')->where('id', '!=', $product->id)->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        if ($request->filled('cropped_image_data')) {
            try {
                $base64Data = $request->input('cropped_image_data');
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $type = strtolower($type[1]); // png, jpg, jpeg
                    if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        throw new \Exception('Format gambar tidak valid.');
                    }
                    $data = base64_decode($base64Data);
                    if ($data === false) {
                        throw new \Exception('Gagal mendekode berkas gambar.');
                    }

                    // Clean up old file if it exists and is a local upload
                    if ($product->thumbnail && !Str::startsWith($product->thumbnail, ['http://', 'https://'])) {
                        if (Str::startsWith($product->thumbnail, '/storage/')) {
                            $oldPath = storage_path('app/public/' . Str::after($product->thumbnail, '/storage/'));
                        } else {
                            $oldPath = public_path($product->thumbnail);
                        }
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }

                    $filename = 'product-' . $validated['slug'] . '-' . time() . '.' . $type;
                    $uploadPath = storage_path('app/public/uploads/products');
                    if (!file_exists($uploadPath)) {
                        if (!@mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
                            throw new \Exception("Tidak dapat membuat folder 'storage/app/public/uploads/products' di server.");
                        }
                    }
                    file_put_contents($uploadPath . '/' . $filename, $data);
                    $validated['thumbnail'] = '/storage/uploads/products/' . $filename;
                }
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal memproses gambar crop: ' . $e->getMessage());
            }
        } elseif ($request->hasFile('thumbnail_file')) {
            try {
                // Clean up old file if it exists and is a local upload
                if ($product->thumbnail && !Str::startsWith($product->thumbnail, ['http://', 'https://'])) {
                    if (Str::startsWith($product->thumbnail, '/storage/')) {
                        $oldPath = storage_path('app/public/' . Str::after($product->thumbnail, '/storage/'));
                    } else {
                        $oldPath = public_path($product->thumbnail);
                    }
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $file = $request->file('thumbnail_file');
                $filename = 'product-' . $validated['slug'] . '-' . time() . '.' . $file->getClientOriginalExtension();
                $uploadPath = storage_path('app/public/uploads/products');
                if (!file_exists($uploadPath)) {
                    if (!@mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
                        throw new \Exception("Tidak dapat membuat folder 'storage/app/public/uploads/products' di server.");
                    }
                }
                $file->move($uploadPath, $filename);
                $validated['thumbnail'] = '/storage/uploads/products/' . $filename;
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal mengunggah berkas: ' . $e->getMessage());
            }
        }

        unset($validated['thumbnail_file']);
        unset($validated['cropped_image_data']);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang diperbolehkan menghapus produk.');
        }

        if ($product->leadRequests()->exists()) {
            return back()->with('error', 'Produk tidak dapat dihapus karena memiliki log data lead.');
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    // --- RECURSIVE VARIANTS MANAGEMENT ---

    public function variants(Product $product)
    {
        // Get only level 1 (root) variants of this product
        $variantsTree = ProductVariant::where('produk_id', $product->id)
            ->whereNull('parent_id')
            ->with('children.children')
            ->get();

        return view('admin.products.variants', compact('product', 'variantsTree'));
    }

    public function storeVariant(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:product_variants,id',
        ]);

        $level = 1;
        if ($request->filled('parent_id')) {
            $parent = ProductVariant::findOrFail($validated['parent_id']);
            $level = $parent->level + 1;
            if ($level > 3) {
                return back()->with('error', 'Maksimal tingkat kedalaman varian adalah 3 level.');
            }
        }

        ProductVariant::create([
            'produk_id' => $product->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'nama' => $validated['nama'],
            'level' => $level,
        ]);

        return redirect()->route('admin.products.variants', $product->id)->with('success', 'Varian berhasil ditambahkan.');
    }

    public function destroyVariant(ProductVariant $variant)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang diperbolehkan menghapus varian produk.');
        }

        $productId = $variant->produk_id;
        $variant->delete(); // Cascades children in DB foreign key

        return redirect()->route('admin.products.variants', $productId)->with('success', 'Varian berhasil dihapus.');
    }
}
