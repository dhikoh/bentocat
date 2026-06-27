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
            'thumbnail' => 'https://drive.google.com/file/d/1ejK9Gr1E6XNpJGgaulF9bDo0GLy8v0nU/view?usp=drive_link',
            'deskripsi' => "BentoCat Premium Bentonite Cat Litter adalah pasir kucing bentonite berkualitas tinggi (ultra-premium) yang dirancang khusus untuk kenyamanan kucing kesayangan Anda dan kebersihan rumah yang maksimal.\n\nKeunggulan BentoCat Premium:\n- Daya Serap Cairan Ekstra Cepat & Kuat: Pasir langsung menggumpal dengan kokoh saat terkena cairan, mencegah kebocoran ke dasar bak pasir dan mempermudah proses pembersihan.\n- Kontrol Bau Maksimal (Odour Control): Dilengkapi dengan teknologi pengikat bau dan aroma premium yang segar dan tahan lama, menjaga ruangan tetap wangi.\n- Formula Bebas Debu (99.9% Dust Free): Aman untuk sistem pernapasan kucing maupun pemiliknya, serta meminimalkan jejak kaki berdebu di lantai.\n- Alami & Ramah Lingkungan: Terbuat dari 100% bentonite alami pilihan yang aman bagi kesehatan kucing Anda.\n\nHadir dalam varian aroma menyegarkan Lavender, Lemon, Apel, Melon, dan Strawberry, dengan pilihan kemasan praktis ukuran 5.5, 10, dan 25.",
            'status' => 'ACTIVE',
            'label_level_1' => 'Kategori',
            'label_level_2' => 'Aroma',
            'label_level_3' => 'Kemasan'
        ]);

        // Level 1: Category
        $catVariant = ProductVariant::create([
            'produk_id' => $product->id,
            'parent_id' => null,
            'nama' => 'Pasir Bentonite Premium',
            'level' => 1
        ]);

        // Level 2: Aromas
        $aromas = ['Lavender', 'Lemon', 'Apel', 'Melon', 'Strawberry'];
        $sizes = ['5.5', '10', '25'];

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

