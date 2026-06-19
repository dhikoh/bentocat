<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketingTemplate;

class MarketingTemplateSeeder extends Seeder
{
    public function run(): void
    {
        MarketingTemplate::truncate();

        $templates = [
            [
                'name' => '[Respons Chat] - Membalas Pesan / Pertanyaan Masuk',
                'category' => 'Respons Chat',
                'target_audience' => 'B2B & B2C',
                'tone' => 'Sopan, Ramah & Informatif',
                'placeholders' => 'poin_jawaban',
                'base_prompt' => "Buatlah draf balasan pesan/chat untuk menjawab pertanyaan dari customer.\n\nPoin penting yang harus disampaikan dalam jawaban kita: {poin_jawaban}.\n\nGunakan panduan penulisan pesan/email dari bab Emails pada handbook marketing_skills_handbook.md. Pastikan menyapa dengan sopan, memberikan informasi secara jelas berdasarkan deskripsi produk BentoCat, dan diakhiri dengan ajakan bertindak (CTA) yang ramah."
            ],
            [
                'name' => '[Respons Chat] - Penanganan Komplain & Masalah',
                'category' => 'Respons Chat',
                'target_audience' => 'B2B & B2C',
                'tone' => 'Empatis & Solutif',
                'placeholders' => 'solusi_yang_ditawarkan',
                'base_prompt' => "Buatlah draf balasan pesan/chat untuk menangani komplain atau masalah dari pelanggan.\n\nSolusi/kompensasi yang kita berikan kepada mereka: {solusi_yang_ditawarkan}.\n\nGunakan panduan penanganan keluhan dan retensi pelanggan dari bab Churn-Prevention serta nada bicara customer support profesional dari bab Emails pada handbook marketing_skills_handbook.md. Tunjukkan empati yang mendalam atas ketidaknyamanan yang dialami, jelaskan solusinya secara transparan, dan fokus pada memulihkan kepercayaan mereka terhadap produk BentoCat."
            ],
            [
                'name' => '[Respons Chat] - Menjawab Tawaran Influencer / KOL',
                'category' => 'Respons Chat',
                'target_audience' => 'KOL / Influencer',
                'tone' => 'Kasual, Bersahabat & Profesional',
                'placeholders' => 'nama_influencer, poin_keputusan',
                'base_prompt' => "Buatlah draf balasan pesan/DM untuk menanggapi tawaran kolaborasi dari influencer {nama_influencer}.\n\nKeputusan kolaborasi kita: {poin_keputusan}.\n\nGunakan nada bicara yang antusias namun tetap profesional dari bab Social pada handbook marketing_skills_handbook.md. Jika kita menyetujui kolaborasi, jelaskan mekanisme pengiriman produk sample BentoCat. Jika menolak atau menunda, sampaikan apresiasi dan alasan penolakan secara halus."
            ],
            [
                'name' => '[Outbound] - Penawaran Kerjasama Baru (Cold Outreach)',
                'category' => 'Penawaran Kerja Sama',
                'target_audience' => 'Petshop / Agen / Distributor',
                'tone' => 'Persuasif & Edukatif',
                'placeholders' => 'nama_penerima, keuntungan_khusus',
                'base_prompt' => "Buatlah draf pesan penawaran kerja sama B2B pertama kali (cold outreach) kepada {nama_penerima}.\n\nBenefit/keuntungan khusus yang kita tawarkan jika mereka bergabung: {keuntungan_khusus}.\n\nGunakan kerangka penulisan AIDA (Attention, Interest, Desire, Action) dari bab Copywriting dan taktik dari bab Cold-Email pada handbook marketing_skills_handbook.md. Pastikan menonjolkan keunggulan produk BentoCat sebagai pasir bentonit premium dengan daya gumpal instan dan logistik lokal yang cepat."
            ],
            [
                'name' => '[Outbound] - Follow-Up Setelah Kirim Sampel (Tester)',
                'category' => 'Follow-up',
                'target_audience' => 'Petshop / Klinik Hewan',
                'tone' => 'Sopan & Persuasif',
                'placeholders' => 'nama_petshop, tanggal_kirim_sampel',
                'base_prompt' => "Buatlah draf pesan follow-up setelah pengiriman sampel kepada pihak {nama_petshop}.\n\nSampel dikirim pada tanggal: {tanggal_kirim_sampel}.\n\nGunakan taktik follow-up beruntun dari bab Cold-Email dan teknik penutupan penjualan B2B dari bab Sales-Enablement pada handbook marketing_skills_handbook.md. Tanyakan bagaimana hasil uji coba sampel pasir kucing BentoCat di petshop mereka dan ajak mereka untuk melakukan pemesanan slot pertama (first order) dengan promo aktif."
            ],
            [
                'name' => '[Follow-Up] - Menindaklanjuti Penawaran (Follow-Up)',
                'category' => 'Follow-up',
                'target_audience' => 'Mitra Prospektif',
                'tone' => 'Profesional & Mengingatkan',
                'placeholders' => 'nama_penerima, topik_sebelumnya',
                'base_prompt' => "Buatlah draf pesan follow-up sopan untuk menanyakan keputusan kelanjutan dari penawaran sebelumnya kepada {nama_penerima}.\n\nTopik penawaran/pertemuan terakhir: {topik_sebelumnya}.\n\nGunakan taktik follow-up dari bab Cold-Email pada handbook marketing_skills_handbook.md. Buat pesan yang singkat, padat, ramah, dan berikan dorongan halus untuk menjadwalkan diskusi lanjutan."
            ],
            [
                'name' => '[Media Sosial] - Copywriting Iklan & Promosi Varian',
                'category' => 'Promosi Media Sosial',
                'target_audience' => 'Pecinta Kucing (B2C)',
                'tone' => 'Kreatif, Kasual & Menarik',
                'placeholders' => 'varian_produk, promo_aktif',
                'base_prompt' => "Buatlah naskah postingan media sosial (Instagram/FB) yang interaktif untuk mempromosikan varian {varian_produk}.\n\nDetail promo yang sedang berlangsung: {promo_aktif}.\n\nGunakan panduan pembuatan konten media sosial dari bab Social dan kerangka penulisan konten menarik dari bab Content-Strategy pada handbook marketing_skills_handbook.md. Sertakan CTA yang kuat agar audiens berbelanja di e-commerce resmi BentoCat atau mengunjungi petshop terdekat."
            ],
            [
                'name' => '[Edukasi & Promosi] - Naskah Video Pendek (TikTok/Reels/Shorts)',
                'category' => 'Promosi Media Sosial',
                'target_audience' => 'Pecinta Kucing (B2C)',
                'tone' => 'Kasual, Edukatif & Dinamis',
                'placeholders' => 'topik_edukasi, call_to_action',
                'base_prompt' => "Buat naskah video pendek (durasi 30-60 detik) untuk platform TikTok/Instagram Reels/YouTube Shorts dengan topik edukasi: \"{topik_edukasi}\".\n\nCall to Action (CTA) di akhir video: {call_to_action}.\n\nGunakan panduan hook-making dan penulisan skrip video pendek dari bab Social pada handbook marketing_skills_handbook.md. Buat struktur naskah yang terdiri dari Hook (0-5 detik), Edukasi/Penyelesaian Masalah (5-45 detik), dan CTA penawaran pasir kucing BentoCat (45-60 detik)."
            ]
        ];

        foreach ($templates as $tmpl) {
            MarketingTemplate::create($tmpl);
        }
    }
}
