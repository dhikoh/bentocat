<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $contributor = User::where('role', 'contributor')->first();
        if (!$contributor) {
            $contributor = User::first();
        }

        $articles = [
            [
                'title' => 'Mengapa Pasir Kucing Bentonite Adalah Pilihan Terbaik?',
                'summary' => 'Temukan alasan mengapa pasir kucing bentonite gumpal seperti BentoCat sangat disukai oleh cat owner karena kepraktisannya.',
                'content_json' => [
                    [
                        'type' => 'heading',
                        'text' => 'Keunggulan Pasir Bentonite untuk Kucing Kesayangan',
                        'level' => 2
                    ],
                    [
                        'type' => 'paragraph',
                        'text' => 'Pasir kucing bentonite terbuat dari tanah liat bentonite alami yang memiliki kemampuan menyerap cairan dengan sangat cepat. Ketika terkena urine kucing, pasir akan langsung menggumpal keras, sehingga memudahkan pembersihan sehari-hari.'
                    ],
                    [
                        'type' => 'callout',
                        'text' => 'BentoCat menggunakan formula bentonite premium dengan deodorizer alami yang mengunci bau amonia hingga 99%.',
                        'callout_type' => 'info'
                    ],
                    [
                        'type' => 'heading',
                        'text' => 'Pertanyaan yang Sering Diajukan (FAQ)',
                        'level' => 3
                    ],
                    [
                        'type' => 'qna',
                        'question' => 'Apakah pasir bentonite aman untuk anak kucing?',
                        'answer' => 'Ya, pasir bentonite alami aman. Namun, pastikan anak kucing Anda tidak memakannya. Untuk kitten yang baru belajar, awasi penggunaannya.'
                    ],
                    [
                        'type' => 'qna',
                        'question' => 'Berapa hari sekali pasir bentonite harus diganti total?',
                        'answer' => 'Direkomendasikan untuk mengganti pasir secara total dan mencuci bak pasir setiap 2-3 minggu sekali untuk menjaga higienitas.'
                    ]
                ],
                'seo_title' => 'Mengapa Pasir Kucing Bentonite Pilihan Terbaik? | BentoCat',
                'seo_description' => 'Pelajari kelebihan pasir bentonite gumpal premium untuk kenyamanan kucing Anda. BentoCat aman, minim debu, dan wangi segar tahan lama.',
                'status' => 'PUBLISHED'
            ],
            [
                'title' => 'Tips Menghemat Penggunaan Pasir Kucing di Rumah',
                'summary' => 'Pasir kucing boros? Terapkan 4 langkah praktis ini untuk menghemat pengeluaran pasir tanpa mengorbankan kebersihan litter box.',
                'content_json' => [
                    [
                        'type' => 'heading',
                        'text' => 'Cara Efektif Menghemat Pasir Litter Box',
                        'level' => 2
                    ],
                    [
                        'type' => 'paragraph',
                        'text' => 'Banyak cat owner mengeluhkan pasir kucing yang cepat habis. Hal ini biasanya disebabkan oleh daya gumpal yang lemah sehingga banyak pasir bersih yang ikut terbuang saat diserok.'
                    ],
                    [
                        'type' => 'heading',
                        'text' => '1. Gunakan Pasir Berkualitas Tinggi yang Cepat Menggumpal',
                        'level' => 3
                    ],
                    [
                        'type' => 'paragraph',
                        'text' => 'Pasir dengan daya gumpal tinggi seperti BentoCat akan langsung membentuk gumpalan kecil yang padat, meminimalkan pasir bersih yang terbuang percuma.'
                    ],
                    [
                        'type' => 'heading',
                        'text' => '2. Jaga Ketebalan Pasir Ideal',
                        'level' => 3
                    ],
                    [
                        'type' => 'paragraph',
                        'text' => 'Selalu isi litter box dengan ketebalan pasir minimal 7-10 cm. Jika terlalu tipis, urine kucing akan mengalir ke dasar wadah dan menempel erat, membuatnya sulit diserok dan berbau busuk.'
                    ]
                ],
                'seo_title' => '4 Tips Menghemat Penggunaan Pasir Kucing | BentoCat Indonesia',
                'seo_description' => 'Panduan lengkap cara menghemat pasir kucing bentonite agar tidak boros. Gunakan pasir gumpal premium BentoCat dan atur ketebalan yang pas.',
                'status' => 'PUBLISHED'
            ]
        ];

        foreach ($articles as $data) {
            Article::create([
                'author_id' => $contributor->id,
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'summary' => $data['summary'],
                'content_json' => $data['content_json'],
                'seo_title' => $data['seo_title'],
                'seo_description' => $data['seo_description'],
                'status' => $data['status'],
                'published_at' => now()
            ]);
        }
    }
}
