<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingContact;
use App\Models\Outlet;
use App\Models\City;

class ShippingContactSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Zendo Semarang
        $zendo = ShippingContact::create([
            'nama' => 'Zendo Semarang',
            'whatsapp' => '6285169679679',
            'keterangan' => 'Zendo Semarang adalah layanan ojek online (ojol) dan penyedia jasa on-demand serbapengguna berbasis WhatsApp yang melayani wilayah Kota dan Kabupaten Semarang. Berbeda dengan ojol pada umumnya yang wajib menggunakan aplikasi khusus, pesanan di Zendo diproses langsung secara manual dan praktis melalui obrolan dengan admin.',
            'aktif' => true
        ]);

        // Find Semarang city
        $semarangCity = City::where('nama', 'Semarang')->first();
        if ($semarangCity) {
            $semarangMitras = Outlet::where('kota_id', $semarangCity->id)
                ->where('is_mitra', true)
                ->get();

            foreach ($semarangMitras as $outlet) {
                $outlet->update(['delivery_mode' => 'RECOMMENDED_SHIPPING_CONTACT']);
                $outlet->shippingContacts()->syncWithoutDetaching([$zendo->id => ['urutan' => 1]]);
            }
        }

        // 2. Japrijek Bojonegoro
        $japrijek = ShippingContact::create([
            'nama' => 'Japrijek Bojonegoro',
            'whatsapp' => '6285804667887',
            'keterangan' => 'Japrijek Bojonegoro adalah layanan kurir lokal dan ojek online (ojol) berbasis WhatsApp yang beroperasi di wilayah Kabupaten Bojonegoro. Layanan ini melayani berbagai kebutuhan antar-jemput dan pengiriman harian masyarakat. Waktu Pelayanan: Buka setiap hari mulai pukul 06.00 hingga 23.00',
            'aktif' => true
        ]);

        // Find Bojonegoro city
        $bojonegoroCity = City::where('nama', 'Bojonegoro')->first();
        if ($bojonegoroCity) {
            $bojonegoroMitras = Outlet::where('kota_id', $bojonegoroCity->id)
                ->where('is_mitra', true)
                ->get();

            foreach ($bojonegoroMitras as $outlet) {
                $outlet->update(['delivery_mode' => 'RECOMMENDED_SHIPPING_CONTACT']);
                $outlet->shippingContacts()->syncWithoutDetaching([$japrijek->id => ['urutan' => 1]]);
            }
        }
    }
}

