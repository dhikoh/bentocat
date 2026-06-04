<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingContact;
use App\Models\Outlet;

class ShippingContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            [
                'nama' => 'Kurir Kilat Blitar (Pak Slamet)',
                'whatsapp' => '082111112222',
                'keterangan' => 'Khusus area Kota Blitar & Kabupaten Blitar selatan. Layanan Same-Day.',
                'aktif' => true,
                'target_outlet' => 'Kucingku Petshop Blitar'
            ],
            [
                'nama' => 'Depok Express Rider (Mas Tono)',
                'whatsapp' => '082122223333',
                'keterangan' => 'Melayani pengantaran cepat area Margonda, Pancoran Mas, Beji.',
                'aktif' => true,
                'target_outlet' => 'Depok Jaya Petshop'
            ],
            [
                'nama' => 'Serpong Cargo & Go (Pak Yudi)',
                'whatsapp' => '082133334444',
                'keterangan' => 'Pengiriman barang besar / pasir kucing area Gading Serpong, BSD.',
                'aktif' => true,
                'target_outlet' => 'Gading Serpong Pet Shop'
            ]
        ];

        foreach ($contacts as $data) {
            $contact = ShippingContact::create([
                'nama' => $data['nama'],
                'whatsapp' => $data['whatsapp'],
                'keterangan' => $data['keterangan'],
                'aktif' => $data['aktif']
            ]);

            $outlet = Outlet::where('nama_outlet', $data['target_outlet'])->first();
            if ($outlet) {
                $outlet->shippingContacts()->attach($contact->id, ['urutan' => 1]);
            }
        }
    }
}
