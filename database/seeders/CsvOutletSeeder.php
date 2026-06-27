<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\City;
use App\Models\Outlet;
use App\Models\Distributor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CsvOutletSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = base_path('google_maps_outlets_clean.csv');
        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        $this->command->info("Starting CSV Outlet Import...");

        // Define garbage cities list
        $garbageList = [
            '&', 'Affan', 'Agung', 'Aj', 'Alfayat', 'Alpan', 'Amazon', 'Ambar',
            'Anabulku', 'Aneka', 'Animalzone', 'Apollo', 'Apositive', 'Aprian',
            'Aquarium', 'Aquatik', 'Ari', 'Aris', 'Arsn', 'Aulia', 'Aya', 'Barokah',
            'Baru', 'Bibir', 'Bram', 'Brown', 'Buka', 'Cattery', 'Central', 'Choco',
            'Choky', 'Cleopatra', 'Daddo', 'Damai', 'Darmawan', 'Delonixpetshop',
            'Dida', 'Disa', 'Dua', 'Duren', 'Edi', 'Family', 'Faust', 'Fh', 'Fhidame',
            'Gloria', 'Glory', 'Grace', 'Group.', 'Gs', 'Gudang', 'Hachi', 'Hanna',
            'Happy', 'Heni', 'Home', 'Homestay', 'Owner', 'Petshop', 'Klinik', 'Vets',
            'Vika', 'Yohanes', 'Yuki', 'Yuliansyah', 'Zona', 'Zone', 'Widjie', 'Wine',
            'Wirvet', 'Wiyadi', 'Indonesia'
        ];

        // Specific garbage name-to-city overrides
        $garbageMappings = [
            'Admin Aulia Petshop' => 'Bojonegoro',
            'AJ Petshop Kaltim' => 'Samarinda',
            'Ambar Petshop' => 'Semarang',
            'Arsn Petshop Grosir' => 'Bojonegoro',
            'Bahagia Petshop Nusa Dua Bali' => 'Badung',
            'Bahagia Petshop Yeh Aya Bali' => 'Denpasar',
            'Brown Petshop Yogyakarta' => 'Yogyakarta',
            'Central Petshop' => 'Yogyakarta',
            'Corporate Marketing & Development Jogja Petshop Heni' => 'Yogyakarta',
            'Delonixpetshop 1' => 'Bojonegoro',
            'Erdiman Petshop UMS FH Jateng' => 'Surakarta',
            'FU End User Darma NTB Lombok Mau Buka Petshop' => 'Mataram',
            'FU Petshop Edi Cikarang' => 'Bekasi',
            'FU Petshop Irosat Aris Jogja' => 'Yogyakarta',
            'hana Kuningan Mau Buka Petshop' => 'Kuningan',
            'Kak Edho Admin Aulia Petshop' => 'Bojonegoro',
            'Owi Gudang Petshop DIY' => 'Yogyakarta',
            'Owner Happy Petshop Bu Dida Situbondo' => 'Situbondo',
            'Owner Ibu Riana Amazon Petshop' => 'Surabaya',
            'Owner Roemi Petshop Pak Alpan Jogja' => 'Yogyakarta',
            'Paw Rangers Canggu Petshop Grooming Kak Agung Bali' => 'Badung',
            'Petshop Anabulku Bajarmasin' => 'Banjarmasin',
            'Petshop Aneka Kuningan' => 'Kuningan',
            'Petshop Apollo Jogja' => 'Yogyakarta',
            'Petshop Apositive Jogja' => 'Yogyakarta',
            'Petshop Barokah Jogja' => 'Yogyakarta',
            'Petshop Cat Homestay Duri' => 'Bengkalis',
            'Petshop Choco Lombok' => 'Mataram',
            'Petshop Choky Banten' => 'Tangerang',
            'Petshop Cleopatra Tiban' => 'Batam',
            'Petshop Disa Jogja' => 'Yogyakarta',
            'Petshop Eriyanto Cattery Riau' => 'Pekanbaru',
            'Petshop Family Jepara' => 'Jepara',
            'Petshop faust Belitung' => 'Tanjungpandan',
            'Petshop Fhidame Bali' => 'Denpasar',
            'Petshop Hanna Indramayu' => 'Indramayu',
            'Petshop Happy Situbondo' => 'Situbondo',
            'Petshop Home Bali' => 'Denpasar',
            'Petshop Kak Ari Papua' => 'Jayapura',
            'Petshop Kak Geral Alfayat Bukittinggi' => 'Bukittinggi',
            'Petshop Kidcat Pekan Baru Riau' => 'Pekanbaru',
            'Petshop Owner Pak Denny Darmawan Cikarang' => 'Bekasi',
            'Petshop Waleri Owner Affan' => 'Kendal',
            'Sun Glory Petshop Bali' => 'Denpasar',
            'Tanjung Duren Petshop' => 'Jakarta Barat',
        ];

        // Known city-to-province mapping for new clean cities
        $cityToProvince = [
            'Mataram' => 'Nusa Tenggara Barat',
            'Lampung' => 'Lampung',
            'Jambi' => 'Jambi',
            'Jember' => 'Jawa Timur',
            'Klaten' => 'Jawa Tengah',
            'Tulungagung' => 'Jawa Timur',
            'Banyuwangi' => 'Jawa Timur',
            'Sragen' => 'Jawa Tengah',
            'Kendal' => 'Jawa Tengah',
            'Kendari' => 'Sulawesi Tenggara',
            'Batam' => 'Kepulauan Riau',
            'Boyolali' => 'Jawa Tengah',
            'Ponorogo' => 'Jawa Timur',
            'Karawang' => 'Jawa Barat',
            'Manado' => 'Sulawesi Utara',
            'Palu' => 'Sulawesi Tengah',
            'Cianjur' => 'Jawa Barat',
            'Sumedang' => 'Jawa Barat',
            'Bengkulu' => 'Bengkulu',
            'Pontianak' => 'Kalimantan Barat',
            'Magetan' => 'Jawa Timur',
            'Nganjuk' => 'Jawa Timur',
            'Kebumen' => 'Jawa Tengah',
            'Purbalingga' => 'Jawa Tengah',
            'Garut' => 'Jawa Barat',
            'Trenggalek' => 'Jawa Timur',
            'Kudus' => 'Jawa Tengah',
            'Purwakarta' => 'Jawa Barat',
            'Palangkaraya' => 'Kalimantan Tengah',
            'Purwodadi' => 'Jawa Tengah',
            'Subang' => 'Jawa Barat',
            'Banyumas' => 'Jawa Tengah',
            'Jombang' => 'Jawa Timur',
            'Majalengka' => 'Jawa Barat',
            'Wonosobo' => 'Jawa Tengah',
            'Temanggung' => 'Jawa Tengah',
            'Purwokerto' => 'Jawa Tengah',
            'Banda Aceh' => 'Aceh',
            'Pekanbaru' => 'Riau',
            'Brebes' => 'Jawa Tengah',
            'Jepara' => 'Jawa Tengah',
            'Rembang' => 'Jawa Tengah',
            'Buleleng' => 'Bali',
            'Pacitan' => 'Jawa Timur',
            'Purworejo' => 'Jawa Tengah',
            'Ternate' => 'Maluku Utara',
            'Ngawi' => 'Jawa Timur',
            'Kupang' => 'Nusa Tenggara Timur',
            'Batang' => 'Jawa Tengah',
            'Gorontalo' => 'Gorontalo',
            'Lumajang' => 'Jawa Timur',
            'Bondowoso' => 'Jawa Timur',
            'Karangasem' => 'Bali',
            'Pemalang' => 'Jawa Tengah',
            'Blora' => 'Jawa Tengah',
            'Ciamis' => 'Jawa Barat',
            'Indramayu' => 'Jawa Barat',
            'Situbondo' => 'Jawa Timur',
            'Kuningan' => 'Jawa Barat',
            
            // Newly added clean cities
            'Banjarmasin' => 'Kalimantan Selatan',
            'Jakarta' => 'DKI Jakarta',
            'Sorong' => 'Papua Barat',
            'Martapura' => 'Kalimantan Selatan',
            'Tabanan' => 'Bali',
            'Pare' => 'Jawa Timur',
            'Wajo' => 'Sulawesi Selatan',
            'Makasar' => 'Sulawesi Selatan',
            'Jimbaran' => 'Bali',
            'Pakerisan' => 'Bali',
            'Purigading' => 'Bali',
            'Sesetan' => 'Bali',
            'Banjarnegara' => 'Jawa Tengah',
            'Sumbawa' => 'Nusa Tenggara Barat',
            'Banjarbaru' => 'Kalimantan Selatan',
            'Dumai' => 'Riau',
            'Yogya' => 'DI Yogyakarta',
            'Tanjungselor' => 'Kalimantan Utara',
            'Prambanan' => 'DI Yogyakarta',
            'Kalideres' => 'DKI Jakarta',
            'Tebet' => 'DKI Jakarta',
            'Payakumbuh' => 'Sumatera Barat',
            'Tabalong' => 'Kalimantan Selatan',
            'Pamulang' => 'Banten',
            'Sentani' => 'Papua',
            'Pangkalanbun' => 'Kalimantan Tengah',
            'Pinrang' => 'Sulawesi Selatan',
            'Mahira' => 'Jawa Timur',
            'Tanjung' => 'Kalimantan Selatan',
            'Timika' => 'Papua',
            'Umbulharjo' => 'DI Yogyakarta',
            'Kertanegara' => 'Jawa Tengah',
            'Lombok' => 'Nusa Tenggara Barat',
            'Baubau' => 'Sulawesi Tenggara',
            'Jaya' => 'DKI Jakarta',
            'Takalar' => 'Sulawesi Selatan',
            'Cibubur' => 'Jawa Barat',
            'Banjar' => 'Jawa Barat',
            'Bangli' => 'Bali',
            'Singkawang' => 'Kalimantan Barat',
            'Banjar Baru' => 'Kalimantan Selatan',
            'Demak' => 'Jawa Tengah',
            'Cepu' => 'Jawa Tengah',
            'Bengkalis' => 'Riau',
            'Kroya' => 'Jawa Tengah',
            'Cileungsi' => 'Jawa Barat',
            'Kuta' => 'Bali',
            'Cikarang' => 'Jawa Barat',
            'Kotawaringin' => 'Kalimantan Tengah',
            'Tanjungpandan' => 'Bangka Belitung',
            'Lebak' => 'Banten',
            'Kotamobagu' => 'Sulawesi Utara',
            'Gowa' => 'Sulawesi Selatan',
            'Jangki' => 'DI Yogyakarta',
            'Jayapura' => 'Papua',
            'Bukittinggi' => 'Sumatera Barat',
            'Mranggen' => 'Jawa Tengah',
            'Anyer' => 'Banten',
            'Riau' => 'Riau',
            'Anyar' => 'Bali',
            'Teluk' => 'Banten',
            'Pandeglang' => 'Banten',
            'Purbalingga' => 'Jawa Tengah',
            'Ungaran' => 'Jawa Tengah',
            'Bintaro' => 'Banten',
            'Pariaman' => 'Sumatera Barat',
            'Pangandaran' => 'Jawa Barat',
            'Luwu' => 'Sulawesi Selatan',
            'Bulukumba' => 'Sulawesi Selatan',
            'Masamba' => 'Sulawesi Selatan',
            'Ubud' => 'Bali',
            'Pelaihari' => 'Kalimantan Selatan',
            'Manokwari' => 'Papua Barat',
            'Kulonprogo' => 'DI Yogyakarta'
        ];

        // Default distributor (Pusat)
        $distributor = Distributor::where('nama', 'BentoCat Indonesia')->first();
        $distributorId = $distributor ? $distributor->id : (Distributor::first() ? Distributor::first()->id : 1);

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file);
        
        // Remove BOM if present
        if ($header && str_starts_with($header[0], "\xEF\xBB\xBF")) {
            $header[0] = substr($header[0], 3);
        }

        $imported = 0;
        $updated = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($file)) !== false) {
                if (empty($row) || count($row) < 5) {
                    continue;
                }

                $namaOutlet = trim($row[0]);
                $alamatLengkap = trim($row[1]);
                $whatsapp = trim($row[2]);
                $isMitraString = strtolower(trim($row[3]));
                $rawCity = trim($row[4]);
                $gmapsUrl = trim($row[5] ?? '');
                $lat = !empty($row[6]) ? floatval($row[6]) : null;
                $lng = !empty($row[7]) ? floatval($row[7]) : null;

                if (empty($namaOutlet)) {
                    continue;
                }

                // Resolve garbage city
                $cityName = $rawCity;
                if (isset($garbageMappings[$namaOutlet])) {
                    $cityName = $garbageMappings[$namaOutlet];
                } elseif (in_array($cityName, $garbageList) || empty($cityName)) {
                    // Try to infer from name
                    $lowerName = strtolower($namaOutlet);
                    if (str_contains($lowerName, 'jogja') || str_contains($lowerName, 'yogyakarta')) {
                        $cityName = 'Yogyakarta';
                    } elseif (str_contains($lowerName, 'bali')) {
                        $cityName = 'Denpasar';
                    } elseif (str_contains($lowerName, 'banten')) {
                        $cityName = 'Tangerang';
                    } elseif (str_contains($lowerName, 'kuningan')) {
                        $cityName = 'Kuningan';
                    } elseif (str_contains($lowerName, 'situbondo')) {
                        $cityName = 'Situbondo';
                    } elseif (str_contains($lowerName, 'cikarang')) {
                        $cityName = 'Bekasi';
                    } else {
                        $cityName = 'Lainnya';
                    }
                }

                // Determine province
                $provinceName = 'Lainnya';
                $existingCity = City::where('nama', $cityName)->first();
                if ($existingCity) {
                    $province = Province::find($existingCity->provinsi_id);
                    $provinceName = $province ? $province->nama : 'Lainnya';
                } elseif (isset($cityToProvince[$cityName])) {
                    $provinceName = $cityToProvince[$cityName];
                }

                // Ensure Province exists
                $province = Province::firstOrCreate(
                    ['nama' => $provinceName],
                    [
                        'is_hidden' => ($provinceName === 'Lainnya')
                    ]
                );

                // Ensure City exists
                $city = City::firstOrCreate(
                    ['nama' => $cityName],
                    [
                        'provinsi_id' => $province->id,
                        'slug' => Str::slug($cityName),
                        'is_hidden' => ($provinceName === 'Lainnya' || $cityName === 'Lainnya')
                    ]
                );

                // Check if outlet exists by nama_outlet
                $outlet = Outlet::firstOrNew(['nama_outlet' => $namaOutlet]);
                $isNew = !$outlet->exists;

                $outlet->nama_pic = 'PIC ' . $namaOutlet;
                $outlet->whatsapp = $whatsapp;
                $outlet->alamat_lengkap = $alamatLengkap;
                $outlet->kota_id = $city->id;
                $outlet->distributor_id = $distributorId;
                $outlet->google_maps_url = $gmapsUrl;
                $outlet->latitude = $lat;
                $outlet->longitude = $lng;
                $outlet->is_mitra = ($isMitraString === 'ya');
                $outlet->featured = false;
                $outlet->is_hidden = !$outlet->is_mitra; // Petshop Non Mitra Semuanya Disembunyikan
                $outlet->status = 'AKTIF';
                $outlet->delivery_mode = 'SELF_DELIVERY';

                // Save quietly to bypass Nomimatim rate limits and delay loops
                $outlet->saveQuietly();

                if ($isNew) {
                    $imported++;
                } else {
                    $updated++;
                }
            }

            DB::commit();
            $this->command->info("Import completed successfully!");
            $this->command->info("New Outlets: {$imported}, Updated Outlets: {$updated}");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Import failed: " . $e->getMessage());
        }

        fclose($file);
    }
}
