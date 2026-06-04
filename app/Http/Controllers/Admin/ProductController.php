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
                $query->where('nama', 'like', "%{$search}%");
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
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $count = Product::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = 'product-' . $validated['slug'] . '-' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file->move($uploadPath, $filename);
            $validated['thumbnail'] = '/uploads/products/' . $filename;
        }

        unset($validated['thumbnail_file']);

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
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        $count = Product::where('slug', 'like', $validated['slug'] . '%')->where('id', '!=', $product->id)->count();
        if ($count > 0) {
            $validated['slug'] = $validated['slug'] . '-' . ($count + 1);
        }

        if ($request->hasFile('thumbnail_file')) {
            // Clean up old file if it exists and is a local upload
            if ($product->thumbnail && !Str::startsWith($product->thumbnail, ['http://', 'https://'])) {
                $oldPath = public_path($product->thumbnail);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('thumbnail_file');
            $filename = 'product-' . $validated['slug'] . '-' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file->move($uploadPath, $filename);
            $validated['thumbnail'] = '/uploads/products/' . $filename;
        }

        unset($validated['thumbnail_file']);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
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
        $productId = $variant->produk_id;
        $variant->delete(); // Cascades children in DB foreign key

        return redirect()->route('admin.products.variants', $productId)->with('success', 'Varian berhasil dihapus.');
    }
}
