<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::create([
            'nama' => 'BentoCat Premium Bentonite Cat Litter',
            'slug' => Str::slug('BentoCat Premium Bentonite Cat Litter'),
            'thumbnail' => 'bentocat.png',
            'deskripsi' => 'Pasir kucing bentonite premium dengan daya serap tinggi, gumpal kuat, dan aroma premium tahan lama untuk menjaga kebersihan rumah Anda.',
            'status' => 'ACTIVE'
        ]);

        // Level 1: Category
        $catVariant = ProductVariant::create([
            'produk_id' => $product->id,
            'parent_id' => null,
            'nama' => 'Pasir Bentonite Premium',
            'level' => 1
        ]);

        // Level 2: Aromas
        $aromas = ['Coffee Scent', 'Lavender Scent', 'Lemon Scent', 'Baby Powder Scent', 'Apple Scent'];
        $sizes = ['5 Liter', '10 Liter'];

        foreach ($aromas as $aroma) {
            $aromaVariant = ProductVariant::create([
                'produk_id' => $product->id,
                'parent_id' => $catVariant->id,
                'nama' => $aroma,
                'level' => 2
            ]);

            // Level 3: Sizes
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'produk_id' => $product->id,
                    'parent_id' => $aromaVariant->id,
                    'nama' => $size,
                    'level' => 3
                ]);
            }
        }
    }
}
