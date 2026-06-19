<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketingTemplate;

class MarketingTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Penawaran Mitra Petshop Baru (B2B)',
                'category' => 'Penawaran Kerja Sama',
                'target_audience' => 'Petshop (B2B)',
                'tone' => 'Persuasif & Edukatif',
                'placeholders' => 'nama_petshop, harga_penawaran, minimal_order_karung, bonus_tambahan',
                'base_prompt' => "Buatlah draf pesan penawaran kerja sama B2B yang menarik dan profesional untuk dikirimkan kepada pemilik {nama_petshop}.\n\nKami menawarkan produk BentoCat Premium Cat Litter dengan harga spesial {harga_penawaran} per bag, dengan minimal pemesanan {minimal_order_karung} karung.\nSebagai benefit tambahan, kami juga menyertakan promo: {bonus_tambahan}.\n\nGunakan kerangka penulisan AIDA (Attention, Interest, Desire, Action) dari bab Copywriting dan taktik dari bab Cold-Email pada handbook marketing_skills_handbook.md. Pastikan menekankan keunggulan produk dan efisiensi logistik lokal BentoCat."
            ],
            [
                'name' => 'Penanganan Komplain Pasir/Kualitas (B2C)',
                'category' => 'Penanganan Komplain',
                'target_audience' => 'Pecinta Kucing (B2C)',
                'tone' => 'Profesional & Empatis',
                'placeholders' => 'nama_pelanggan, keluhan_pelanggan, solusi_kompensasi',
                'base_prompt' => "Buatlah draf balasan pesan/email pelayanan pelanggan yang sangat sopan dan berempati untuk merespon komplain dari {nama_pelanggan}.\n\nDetail keluhan pelanggan: \"{keluhan_pelanggan}\".\nSolusi/kompensasi yang kita tawarkan: {solusi_kompensasi}.\n\nGunakan panduan penanganan keluhan dan retensi pelanggan dari bab Churn-Prevention serta nada bicara customer support profesional dari bab Emails pada handbook marketing_skills_handbook.md. Fokus pada menjaga loyalitas pelanggan dan memulihkan kepuasan mereka terhadap BentoCat."
            ],
            [
                'name' => 'Postingan Promosi Varian Baru (Instagram/FB)',
                'category' => 'Promosi Media Sosial',
                'target_audience' => 'Pecinta Kucing (B2C)',
                'tone' => 'Kasual & Menarik',
                'placeholders' => 'nama_varian, keunggulan_aroma, diskon_promo_gajian',
                'base_prompt' => "Buatlah draf copywriting postingan media sosial yang interaktif dan memikat untuk mempromosikan varian baru BentoCat: {nama_varian}.\n\nDetail varian:\n- Aroma utama: {keunggulan_aroma}\n- Penawaran khusus: {diskon_promo_gajian}\n\nGunakan panduan pembuatan konten media sosial dari bab Social dan kerangka penulisan konten menarik dari bab Content-Strategy pada handbook marketing_skills_handbook.md. Sertakan CTA yang jelas agar audiens mencari toko petshop resmi terdekat dan gunakan beberapa hashtag relevan secara proporsional."
            ],
            [
                'name' => 'Follow-Up Kemitraan Distributor',
                'category' => 'Follow-up',
                'target_audience' => 'Distributor (B2B)',
                'tone' => 'Profesional & Persuasif',
                'placeholders' => 'nama_distributor, tanggal_pertemuan_terakhir, hal_menarik_diskusi',
                'base_prompt' => "Buatlah draf pesan tindak lanjut (follow-up) kemitraan yang sopan dan profesional untuk dikirimkan kepada pihak {nama_distributor}.\n\nKonteks: Kita sebelumnya sempat berdiskusi pada tanggal {tanggal_pertemuan_terakhir} mengenai {hal_menarik_diskusi}.\n\nGunakan taktik follow-up beruntun dari bab Cold-Email dan teknik negosiasi B2B dari bab Sales-Enablement pada handbook marketing_skills_handbook.md. Dorong pihak mereka untuk menjadwalkan panggilan/pertemuan singkat berikutnya guna memfinalisasi kemitraan distribusi BentoCat."
            ]
        ];

        foreach ($templates as $tmpl) {
            MarketingTemplate::create($tmpl);
        }
    }
}
