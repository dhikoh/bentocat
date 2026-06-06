<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Distributor;
use App\Models\City;
use App\Models\Province;
use App\Models\ShippingContact;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $isMitra = $request->input('is_mitra');
        $provinceId = $request->input('provinsi_id');
        $cityIds = $request->input('city_ids', []);
        if (!is_array($cityIds)) {
            $cityIds = $cityIds ? [$cityIds] : [];
        }
        $status = $request->input('status');
        $isHidden = $request->input('is_hidden');
        $featured = $request->input('featured');

        $perPage = $request->input('per_page', 10);
        $perPageLimit = ($perPage === 'all') ? 9999 : (int)$perPage;
        
        $outlets = Outlet::with(['distributor', 'city'])
            ->when($search, function ($query, $search) {
                $lowered = '%' . strtolower($search) . '%';
                $query->where(function($q) use ($lowered, $search) {
                    $q->whereRaw('LOWER(nama_outlet) LIKE ?', [$lowered])
                      ->orWhere('whatsapp', 'LIKE', '%' . $search . '%')
                      ->orWhereHas('city', function ($c) use ($lowered) {
                          $c->whereRaw('LOWER(nama) LIKE ?', [$lowered]);
                      });
                });
            })
            ->when($isMitra !== null && $isMitra !== '', function ($query) use ($isMitra) {
                $query->where('is_mitra', $isMitra);
            })
            ->when($provinceId, function ($query, $provinceId) use ($cityIds) {
                if (!empty($cityIds)) {
                    $query->whereIn('kota_id', $cityIds);
                } else {
                    $query->whereHas('city', function ($c) use ($provinceId) {
                        $c->where('provinsi_id', $provinceId);
                    });
                }
            })
            ->when(!$provinceId && !empty($cityIds), function ($query) use ($cityIds) {
                $query->whereIn('kota_id', $cityIds);
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($isHidden !== null && $isHidden !== '', function ($query) use ($isHidden) {
                $query->where('is_hidden', $isHidden);
            })
            ->when($featured !== null && $featured !== '', function ($query) use ($featured) {
                $query->where('featured', $featured);
            })
            ->orderBy('nama_outlet')
            ->paginate($perPageLimit);

        // Fetch counts for summary cards
        $countDistributors = Distributor::count();
        $countMitra = Outlet::where('is_mitra', true)->count();
        $countNonMitra = Outlet::where('is_mitra', false)->count();
        $distributorsList = Distributor::orderBy('nama')->get();
        $provincesList = Province::orderBy('nama')->get();
        $citiesList = $provinceId ? City::where('provinsi_id', $provinceId)->orderBy('nama')->get() : [];
        $shippingContactsList = ShippingContact::orderBy('nama')->get();

        return view('admin.outlets.index', compact(
            'outlets', 'search', 'isMitra', 'provinceId', 'cityIds', 'perPage',
            'countDistributors', 'countMitra', 'countNonMitra',
            'distributorsList', 'provincesList', 'citiesList', 'shippingContactsList',
            'status', 'isHidden', 'featured'
        ));
    }

    public function create()
    {
        $distributors = Distributor::where('status', 'ACTIVE')->orderBy('nama')->get();
        $provinces = Province::orderBy('nama')->get();
        $oldProvinceId = old('provinsi_id');
        $cities = $oldProvinceId ? City::where('provinsi_id', $oldProvinceId)->orderBy('nama')->get() : [];
        $shippingContacts = ShippingContact::where('aktif', true)->orderBy('nama')->get();
        return view('admin.outlets.create', compact('distributors', 'provinces', 'cities', 'shippingContacts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'provinsi_id' => 'required|exists:provinces,id',
            'kota_id' => [
                'required',
                'exists:cities,id',
                function ($attribute, $value, $fail) use ($request) {
                    $city = City::find($value);
                    if ($city && $city->provinsi_id != $request->input('provinsi_id')) {
                        $fail('Kota yang dipilih tidak berada di dalam Provinsi yang dipilih.');
                    }
                }
            ],
            'nama_outlet' => 'required|string|max:255',
            'nama_pic' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25',
            'alamat_lengkap' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'google_maps_url' => 'nullable|url|max:2083',
            'featured' => 'boolean',
            'is_mitra' => 'boolean',
            'status' => 'required|in:AKTIF,NONAKTIF,STOK_KOSONG,TUTUP',
            'delivery_mode' => 'required|in:SELF_DELIVERY,RECOMMENDED_SHIPPING_CONTACT,PICKUP_ONLY',
            'shipping_contacts' => 'nullable|array',
            'shipping_contacts.*' => 'exists:shipping_contacts,id',
            'is_hidden' => 'boolean',
        ]);

        $validated['featured'] = $request->has('featured');
        $validated['is_mitra'] = $request->has('is_mitra');
        $validated['is_hidden'] = $request->has('is_hidden');

        unset($validated['provinsi_id']);
        $outlet = Outlet::create($validated);

        if ($request->filled('shipping_contacts') && $validated['delivery_mode'] === 'RECOMMENDED_SHIPPING_CONTACT') {
            $syncData = [];
            foreach ($request->input('shipping_contacts') as $index => $contactId) {
                $syncData[$contactId] = ['urutan' => $index + 1];
            }
            $outlet->shippingContacts()->sync($syncData);
        } else {
            $outlet->shippingContacts()->detach();
        }

        return redirect()->route('admin.outlets.index')->with('success', 'Outlet/Petshop berhasil ditambahkan.');
    }

    public function edit(Outlet $outlet)
    {
        $distributors = Distributor::where('status', 'ACTIVE')->orderBy('nama')->get();
        $provinces = Province::orderBy('nama')->get();
        $currentProvinceId = old('provinsi_id', $outlet->city ? $outlet->city->provinsi_id : null);
        $cities = $currentProvinceId ? City::where('provinsi_id', $currentProvinceId)->orderBy('nama')->get() : [];
        $shippingContacts = ShippingContact::where('aktif', true)->orderBy('nama')->get();
        $selectedContacts = $outlet->shippingContacts->pluck('id')->toArray();
        return view('admin.outlets.edit', compact('outlet', 'distributors', 'provinces', 'cities', 'shippingContacts', 'selectedContacts'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $validated = $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'provinsi_id' => 'required|exists:provinces,id',
            'kota_id' => [
                'required',
                'exists:cities,id',
                function ($attribute, $value, $fail) use ($request) {
                    $city = City::find($value);
                    if ($city && $city->provinsi_id != $request->input('provinsi_id')) {
                        $fail('Kota yang dipilih tidak berada di dalam Provinsi yang dipilih.');
                    }
                }
            ],
            'nama_outlet' => 'required|string|max:255',
            'nama_pic' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25',
            'alamat_lengkap' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'google_maps_url' => 'nullable|url|max:2083',
            'featured' => 'boolean',
            'is_mitra' => 'boolean',
            'status' => 'required|in:AKTIF,NONAKTIF,STOK_KOSONG,TUTUP',
            'delivery_mode' => 'required|in:SELF_DELIVERY,RECOMMENDED_SHIPPING_CONTACT,PICKUP_ONLY',
            'shipping_contacts' => 'nullable|array',
            'shipping_contacts.*' => 'exists:shipping_contacts,id',
            'is_hidden' => 'boolean',
        ]);

        $validated['featured'] = $request->has('featured');
        $validated['is_mitra'] = $request->has('is_mitra');
        $validated['is_hidden'] = $request->has('is_hidden');

        unset($validated['provinsi_id']);
        $outlet->update($validated);

        if ($request->filled('shipping_contacts') && $validated['delivery_mode'] === 'RECOMMENDED_SHIPPING_CONTACT') {
            $syncData = [];
            foreach ($request->input('shipping_contacts') as $index => $contactId) {
                $syncData[$contactId] = ['urutan' => $index + 1];
            }
            $outlet->shippingContacts()->sync($syncData);
        } else {
            $outlet->shippingContacts()->detach();
        }

        return redirect()->route('admin.outlets.index')->with('success', 'Outlet/Petshop berhasil diperbarui.');
    }

    public function destroy(Outlet $outlet)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang diperbolehkan menghapus outlet.');
        }

        if ($outlet->leadRequests()->exists()) {
            return back()->with('error', 'Outlet tidak dapat dihapus karena memiliki log data lead.');
        }

        $outlet->shippingContacts()->detach();
        $outlet->delete();
        return redirect()->route('admin.outlets.index')->with('success', 'Outlet/Petshop berhasil dihapus.');
    }

    public function exportCsv(Request $request)
    {
        $outlets = Outlet::with(['city', 'distributor', 'shippingContacts'])
            ->orderBy('nama_outlet')
            ->get();

        $response = new StreamedResponse(function () use ($outlets) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($handle, [
                'Nama Petshop',
                'Alamat',
                'No WA',
                'Mitra',
                'Kota',
                'Distributor',
                'Kurir',
                'Featured',
                'Sembunyikan',
                'Status'
            ]);

            foreach ($outlets as $outlet) {
                // Compile couriers: "Name (WA), Name2 (WA2)"
                $couriers = $outlet->shippingContacts->map(function ($c) {
                    return $c->nama . ' (' . $c->whatsapp . ')';
                })->implode(', ');

                fputcsv($handle, [
                    $outlet->nama_outlet,
                    $outlet->alamat_lengkap,
                    $outlet->whatsapp,
                    $outlet->is_mitra ? 'Ya' : 'Tidak',
                    $outlet->city->nama,
                    $outlet->distributor->nama,
                    $couriers,
                    $outlet->featured ? 'Ya' : 'Tidak',
                    $outlet->is_hidden ? 'Ya' : 'Tidak',
                    $outlet->status
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="bentocat_outlets_export_' . date('Ymd_His') . '.csv"',
        ]);

        return $response;
    }

    public function importCsv(Request $request)
    {
        @set_time_limit(600);

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:4096'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membuka file CSV.');
        }

        // Read headers
        $headers = fgetcsv($handle, 1000, ',');
        if (!$headers) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau tidak valid.');
        }

        // Remove UTF-8 BOM if present
        if (substr($headers[0], 0, 3) == "\xEF\xBB\xBF") {
            $headers[0] = substr($headers[0], 3);
        }

        // Clean headers (lowercase and trim)
        $headers = array_map(function ($h) {
            return strtolower(trim($h));
        }, $headers);

        // Map header titles to column indexes
        $colMap = [];
        $headerKeywords = [
            'nama_outlet' => ['nama petshop', 'nama outlet', 'nama', 'outlet', 'petshop'],
            'alamat' => ['alamat', 'alamat lengkap', 'address'],
            'whatsapp' => ['no wa', 'whatsapp', 'phone', 'telepon', 'no. wa', 'nomor wa'],
            'mitra' => ['mitra', 'is mitra', 'is_mitra', 'mitra resmi'],
            'kota' => ['kota', 'city', 'kabupaten'],
            'google_maps_url' => ['google maps link', 'google maps', 'maps link', 'link maps', 'maps_url', 'google_maps_url'],
            'latitude' => ['latitude', 'lat'],
            'longitude' => ['longitude', 'lng', 'long'],
            'distributor' => ['distributor', 'nama distributor', 'distributor_id'],
            'kurir' => ['kurir', 'courier', 'shipping_contact'],
            'featured' => ['featured', 'rekomendasi', 'toko rekomendasi'],
            'is_hidden' => ['sembunyikan', 'is hidden', 'is_hidden', 'hidden'],
            'status' => ['status', 'status operasional', 'operational status']
        ];

        foreach ($headerKeywords as $key => $keywords) {
            $colMap[$key] = -1;
            foreach ($keywords as $kw) {
                $idx = array_search($kw, $headers);
                if ($idx !== false) {
                    $colMap[$key] = $idx;
                    break;
                }
            }
        }

        // If 'nama_outlet' or 'whatsapp' is missing, return error
        if ($colMap['nama_outlet'] === -1 || $colMap['whatsapp'] === -1) {
            fclose($handle);
            return back()->with('error', 'Kolom wajib "Nama Petshop" dan "No WA" tidak ditemukan dalam header CSV.');
        }

        $inserted = 0;
        $updated = 0;
        $failed = [];

        $centralDistributor = Distributor::where('nama', 'like', '%Pusat%')->first();
        if (!$centralDistributor) {
            $centralDistributor = Distributor::first();
        }

        // Load lookups in-memory
        $allCities = City::all()->keyBy(fn($c) => strtolower($c->nama));
        $allProvinces = Province::all()->keyBy(fn($p) => strtolower($p->nama));
        $allDistributors = Distributor::all();
        $allOutletsByWa = Outlet::all()->keyBy('whatsapp');
        $allOutletsByNameCity = Outlet::all()->groupBy(fn($o) => strtolower($o->nama_outlet) . '|' . $o->kota_id);
        $allShippingContacts = ShippingContact::all()->keyBy('whatsapp');

        $cityProvinceMap = [
            'Demak' => 'Jawa Tengah',
            'Pemalang' => 'Jawa Tengah',
            'Situbondo' => 'Jawa Timur',
            'Kuningan' => 'Jawa Barat',
            'Banjarmasin' => 'Kalimantan Selatan',
            'Madiun' => 'Jawa Timur',
            'Sukoharjo' => 'Jawa Tengah',
            'Ponorogo' => 'Jawa Timur',
            'Surakarta' => 'Jawa Tengah',
            'Banjar' => 'Jawa Barat',
            'Magelang' => 'Jawa Tengah',
            'Badung' => 'Bali',
            'Bandung' => 'Jawa Barat',
            'Bekasi' => 'Jawa Barat',
            'Bogor' => 'Jawa Barat',
            'Buleleng' => 'Bali',
            'Boyolali' => 'Jawa Tengah',
            'Klaten' => 'Jawa Tengah',
            'Surabaya' => 'Jawa Timur',
            'Jakarta Pusat' => 'DKI Jakarta',
            'Tulungagung' => 'Jawa Timur',
            'Jambi' => 'Jambi',
            'Banda Aceh' => 'Aceh',
            'Denpasar' => 'Bali',
            'Banyuwangi' => 'Jawa Timur',
            'Sragen' => 'Jawa Tengah',
            'Jakarta Timur' => 'DKI Jakarta',
            'Cianjur' => 'Jawa Barat',
            'Sorong' => 'Lainnya',
            'Wonosobo' => 'Jawa Tengah',
            'Tasikmalaya' => 'Jawa Barat',
            'Depok' => 'Jawa Barat',
            'Sidoarjo' => 'Jawa Timur',
            'Garut' => 'Jawa Barat',
            'Pasuruan' => 'Jawa Timur',
            'Jakarta Selatan' => 'DKI Jakarta',
            'Lampung' => 'Lampung',
            'Tangerang' => 'Banten',
            'Bengkulu' => 'Bengkulu',
            'Tegal' => 'Jawa Tengah',
            'Sleman' => 'DI Yogyakarta',
            'Samarinda' => 'Kalimantan Timur',
            'Tabanan' => 'Bali',
            'Karawang' => 'Jawa Barat',
            'Pacitan' => 'Jawa Timur',
            'Trenggalek' => 'Jawa Timur',
            'Sumedang' => 'Jawa Barat',
            'Bojonegoro' => 'Jawa Timur',
            'Padang' => 'Sumatera Barat',
            'Lamongan' => 'Jawa Timur',
            'Kendari' => 'Sulawesi Tenggara',
            'Tuban' => 'Jawa Timur',
            'Balikpapan' => 'Kalimantan Timur',
            'Batu' => 'Jawa Timur',
            'Mojokerto' => 'Jawa Timur',
            'Batang' => 'Jawa Tengah',
            'Yogyakarta' => 'DI Yogyakarta',
            'Kediri' => 'Jawa Timur',
            'Cirebon' => 'Jawa Barat',
            'Semarang' => 'Jawa Tengah',
            'Jember' => 'Jawa Timur',
            'Manado' => 'Sulawesi Utara',
            'Magetan' => 'Jawa Timur',
            'Probolinggo' => 'Jawa Timur',
            'Batam' => 'Kepulauan Riau',
            'Purworejo' => 'Jawa Tengah',
            'Jakarta Barat' => 'DKI Jakarta',
            'Subang' => 'Jawa Barat',
            'Pekanbaru' => 'Riau',
            'Ngawi' => 'Jawa Timur',
            'Malang' => 'Jawa Timur',
            'Pontianak' => 'Kalimantan Barat',
            'Kudus' => 'Jawa Tengah',
            'Blitar' => 'Jawa Timur',
            'Nganjuk' => 'Jawa Timur',
            'Palembang' => 'Sumatera Selatan',
            'Medan' => 'Sumatera Utara',
            'Gresik' => 'Jawa Timur',
            'Purwakarta' => 'Jawa Barat',
            'Bantul' => 'DI Yogyakarta',
            'Jakarta Utara' => 'DKI Jakarta',
            'Temanggung' => 'Jawa Tengah',
            'Kebumen' => 'Jawa Tengah',
            'Kupang' => 'Nusa Tenggara Timur',
            'Salatiga' => 'Jawa Tengah',
            'Brebes' => 'Jawa Tengah',
            'Lumajang' => 'Jawa Timur',
            'Sukabumi' => 'Jawa Barat',
            'Rembang' => 'Jawa Tengah',
            'Palopo' => 'Sulawesi Selatan',
            'Mataram' => 'Nusa Tenggara Barat',
            'Pekalongan' => 'Jawa Tengah',
            'Lubuklinggau' => 'Sumatera Selatan',
            'Palu' => 'Sulawesi Tengah',
            'Banyumas' => 'Jawa Tengah',
            'Serang' => 'Banten',
            'Jombang' => 'Jawa Timur',
            'Karanganyar' => 'Jawa Tengah',
            'Cilegon' => 'Banten',
            'Parepare' => 'Sulawesi Selatan',
            'Majalengka' => 'Jawa Barat',
            'Bondowoso' => 'Jawa Timur',
            'Pati' => 'Jawa Tengah',
            'Gianyar' => 'Bali',
            'Ciamis' => 'Jawa Barat',
            'Pangandaran' => 'Jawa Barat'
        ];

        \Illuminate\Support\Facades\DB::transaction(function () use (
            $handle, $colMap, &$inserted, &$updated, &$failed,
            $centralDistributor, $allCities, $allProvinces, $allDistributors,
            $allOutletsByWa, $allOutletsByNameCity, $allShippingContacts, $cityProvinceMap
        ) {
            $rowNum = 1;
            $distributorCache = [];

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNum++;

                // Extract values using mapped indexes
                $namaOutlet = $colMap['nama_outlet'] !== -1 ? trim($row[$colMap['nama_outlet']] ?? '') : '';
                $alamat = $colMap['alamat'] !== -1 ? trim($row[$colMap['alamat']] ?? '') : '';
                $whatsapp = $colMap['whatsapp'] !== -1 ? preg_replace('/[^0-9]/', '', $row[$colMap['whatsapp']] ?? '') : '';
                $mitraRaw = $colMap['mitra'] !== -1 ? strtolower(trim($row[$colMap['mitra']] ?? 'tidak')) : 'tidak';
                $kotaName = $colMap['kota'] !== -1 ? trim($row[$colMap['kota']] ?? '') : '';
                $distributorName = $colMap['distributor'] !== -1 ? trim($row[$colMap['distributor']] ?? '') : '';
                $kurirRaw = $colMap['kurir'] !== -1 ? trim($row[$colMap['kurir']] ?? '') : '';

                $googleMapsUrl = $colMap['google_maps_url'] !== -1 ? trim($row[$colMap['google_maps_url']] ?? '') : null;
                $latitude = $colMap['latitude'] !== -1 && trim($row[$colMap['latitude']] ?? '') !== '' ? floatval($row[$colMap['latitude']]) : null;
                $longitude = $colMap['longitude'] !== -1 && trim($row[$colMap['longitude']] ?? '') !== '' ? floatval($row[$colMap['longitude']]) : null;

                $featuredRaw = $colMap['featured'] !== -1 ? strtolower(trim($row[$colMap['featured']] ?? '')) : null;
                $isHiddenRaw = $colMap['is_hidden'] !== -1 ? strtolower(trim($row[$colMap['is_hidden']] ?? '')) : null;
                $statusRaw = $colMap['status'] !== -1 ? strtoupper(trim($row[$colMap['status']] ?? '')) : null;

                if (empty($namaOutlet) || empty($whatsapp)) {
                    $failed[] = "Baris {$rowNum}: Nama Petshop dan No WA wajib diisi.";
                    continue;
                }

                // Standardize WA format
                if (str_starts_with($whatsapp, '0')) {
                    $whatsapp = '62' . substr($whatsapp, 1);
                } elseif (str_starts_with($whatsapp, '8')) {
                    $whatsapp = '62' . $whatsapp;
                }

                // Determine is_mitra
                $isMitra = in_array($mitraRaw, ['ya', 'yes', 'mitra', '1', 'true', 'aktif']);

                // Parse featured
                $isFeatured = null;
                if ($featuredRaw !== null && $featuredRaw !== '') {
                    $isFeatured = in_array($featuredRaw, ['ya', 'yes', '1', 'true', 'aktif']);
                }

                // Parse is_hidden
                $isHidden = null;
                if ($isHiddenRaw !== null && $isHiddenRaw !== '') {
                    $isHidden = in_array($isHiddenRaw, ['ya', 'yes', '1', 'true', 'sembunyikan', 'hidden']);
                }

                // Parse status
                $status = null;
                if ($statusRaw !== null && $statusRaw !== '') {
                    if (in_array($statusRaw, ['AKTIF', 'ACTIVE', 'ON'])) {
                        $status = 'AKTIF';
                    } elseif (in_array($statusRaw, ['STOK HABIS', 'STOK HABIS / KOSONG', 'STOK_KOSONG', 'EMPTY', 'OUT OF STOCK'])) {
                        $status = 'STOK_KOSONG';
                    } elseif (in_array($statusRaw, ['TUTUP', 'CLOSED'])) {
                        $status = 'TUTUP';
                    } elseif (in_array($statusRaw, ['NONAKTIF', 'INACTIVE', 'OFF', 'SEMBUNYI'])) {
                        $status = 'NONAKTIF';
                    }
                }

                // Find or create city dynamically in-memory
                $city = null;
                if (!empty($kotaName)) {
                    $kotaNameLower = strtolower($kotaName);
                    $city = $allCities->get($kotaNameLower);
                    if (!$city) {
                        $city = $allCities->first(fn($c) => stripos($c->nama, $kotaName) !== false);
                        if ($city) {
                            $allCities->put($kotaNameLower, $city);
                        }
                    }

                    if (!$city) {
                        $provinceName = 'Lainnya';
                        foreach ($cityProvinceMap as $cName => $pName) {
                            if (stripos($kotaName, $cName) !== false) {
                                $provinceName = $pName;
                                break;
                            }
                        }

                        $provLower = strtolower($provinceName);
                        $province = $allProvinces->get($provLower);
                        if (!$province) {
                            $province = Province::create(['nama' => $provinceName]);
                            $allProvinces->put($provLower, $province);
                        }

                        $city = City::create([
                            'provinsi_id' => $province->id,
                            'nama' => $kotaName,
                            'slug' => \Illuminate\Support\Str::slug($kotaName)
                        ]);
                        $allCities->put($kotaNameLower, $city);
                    }
                }

                if (!$city) {
                    $failed[] = "Baris {$rowNum}: Kota '{$kotaName}' wajib diisi.";
                    continue;
                }

                // Find distributor in-memory with caching
                $distributor = null;
                if (!empty($distributorName)) {
                    $distLower = strtolower($distributorName);
                    if (isset($distributorCache[$distLower])) {
                        $distributor = $distributorCache[$distLower];
                    } else {
                        $distributor = $allDistributors->first(fn($d) => stripos($d->nama, $distributorName) !== false);
                        if ($distributor) {
                            $distributorCache[$distLower] = $distributor;
                        }
                    }
                }
                if (!$distributor) {
                    $distributor = $centralDistributor;
                }

                // Check duplicate in-memory
                $existing = $allOutletsByWa->get($whatsapp);
                if (!$existing && $city) {
                    $key = strtolower($namaOutlet) . '|' . $city->id;
                    $group = $allOutletsByNameCity->get($key);
                    if ($group && $group->count() > 0) {
                        $existing = $group->first();
                    }
                }

                $outlet = null;
                if ($existing) {
                    $fillData = [
                        'distributor_id' => $distributor->id,
                        'nama_outlet' => $namaOutlet,
                        'alamat_lengkap' => $alamat,
                        'is_mitra' => $existing->is_mitra || $isMitra,
                        'google_maps_url' => $googleMapsUrl ?: $existing->google_maps_url,
                        'latitude' => $latitude ?: $existing->latitude,
                        'longitude' => $longitude ?: $existing->longitude,
                    ];
                    if ($status !== null) {
                        $fillData['status'] = $status;
                    }
                    if ($isFeatured !== null) {
                        $fillData['featured'] = $isFeatured;
                    }
                    if ($isHidden !== null) {
                        $fillData['is_hidden'] = $isHidden;
                    }

                    $existing->fill($fillData);
                    if ($existing->isDirty()) {
                        // Use saveQuietly() to bypass geocoding observer that triggers Nominatim HTTP requests & sleep
                        $existing->saveQuietly();
                        $updated++;
                    }
                    $outlet = $existing;
                } else {
                    $outlet = new Outlet([
                        'distributor_id' => $distributor->id,
                        'kota_id' => $city->id,
                        'nama_outlet' => $namaOutlet,
                        'nama_pic' => 'PIC ' . $namaOutlet,
                        'whatsapp' => $whatsapp,
                        'alamat_lengkap' => $alamat,
                        'is_mitra' => $isMitra,
                        'google_maps_url' => $googleMapsUrl,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'status' => $status ?? 'AKTIF',
                        'featured' => $isFeatured ?? false,
                        'is_hidden' => $isHidden ?? false,
                        'delivery_mode' => 'SELF_DELIVERY'
                    ]);
                    // Use saveQuietly() to bypass geocoding observer that triggers Nominatim HTTP requests & sleep
                    $outlet->saveQuietly();
                    $inserted++;
                }

                // Update in-memory caches to prevent duplicates within the same CSV
                $allOutletsByWa->put($whatsapp, $outlet);
                if ($city) {
                    $key = strtolower($namaOutlet) . '|' . $city->id;
                    $allOutletsByNameCity->put($key, collect([$outlet]));
                }

                // Sync Couriers if provided
                if (!empty($kurirRaw) && $outlet) {
                    $courierItems = explode(',', $kurirRaw);
                    $courierIds = [];

                    foreach ($courierItems as $item) {
                        $item = trim($item);
                        if (empty($item)) continue;

                        // Try to match "Name (Phone)"
                        $cName = $item;
                        $cPhone = '';

                        if (preg_match('/^(.*?)\s*\((.*?)\)$/', $item, $matches)) {
                            $cName = trim($matches[1]);
                            $cPhone = preg_replace('/[^0-9]/', '', $matches[2]);
                        } else {
                            // Just phone number or just name
                            $digitsOnly = preg_replace('/[^0-9]/', '', $item);
                            if (strlen($digitsOnly) >= 10) {
                                $cPhone = $digitsOnly;
                                $cName = 'Kurir ' . $digitsOnly;
                            }
                        }

                        if (!empty($cPhone)) {
                            $courier = $allShippingContacts->get($cPhone);
                            if (!$courier) {
                                $courier = ShippingContact::create([
                                    'whatsapp' => $cPhone,
                                    'nama' => $cName,
                                    'aktif' => true
                                ]);
                                $allShippingContacts->put($cPhone, $courier);
                            } else {
                                if ($courier->nama !== $cName || !$courier->aktif) {
                                    $courier->update(['nama' => $cName, 'aktif' => true]);
                                }
                            }
                            $courierIds[] = $courier->id;
                        }
                    }

                    if (count($courierIds) > 0) {
                        if ($outlet->delivery_mode !== 'RECOMMENDED_SHIPPING_CONTACT') {
                            $outlet->update(['delivery_mode' => 'RECOMMENDED_SHIPPING_CONTACT']);
                        }
                        $syncData = [];
                        foreach ($courierIds as $idx => $cid) {
                            $syncData[$cid] = ['urutan' => $idx + 1];
                        }
                        $outlet->shippingContacts()->syncWithoutDetaching($syncData);
                    }
                }
            }
        });

        fclose($handle);

        $msg = "Impor CSV selesai! {$inserted} petshop baru ditambahkan, {$updated} data duplikat diperbarui.";
        if (count($failed) > 0) {
            $msg .= " Namun terdapat beberapa baris gagal: " . implode(' | ', array_slice($failed, 0, 3));
            if (count($failed) > 3) {
                $msg .= " (dan " . (count($failed) - 3) . " lainnya)";
            }
            return back()->with('warning', $msg);
        }

        return back()->with('success', $msg);
    }

    public function batchDelete(Request $request)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang diperbolehkan menghapus outlet.');
        }

        if (is_string($request->input('outlet_ids'))) {
            $request->merge([
                'outlet_ids' => explode(',', $request->input('outlet_ids'))
            ]);
        }

        $ids = $request->input('outlet_ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada outlet yang dipilih.');
        }

        $outlets = Outlet::whereIn('id', $ids)->get();
        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($outlets as $outlet) {
            if ($outlet->leadRequests()->exists()) {
                $skippedCount++;
            } else {
                $outlet->shippingContacts()->detach();
                $outlet->delete();
                $deletedCount++;
            }
        }

        if ($skippedCount > 0) {
            return back()->with('warning', "Berhasil menghapus {$deletedCount} outlet. {$skippedCount} outlet dilewati karena memiliki log data lead.");
        }

        return back()->with('success', "Berhasil menghapus {$deletedCount} outlet terpilih.");
    }

    public function batchReassign(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['superadmin', 'editor'])) {
            return back()->with('error', 'Anda tidak memiliki wewenang untuk memindahkan distributor outlet.');
        }

        if (is_string($request->input('outlet_ids'))) {
            $request->merge([
                'outlet_ids' => explode(',', $request->input('outlet_ids'))
            ]);
        }

        $validated = $request->validate([
            'outlet_ids' => 'required|array',
            'outlet_ids.*' => 'exists:outlets,id',
            'distributor_id' => 'required|exists:distributors,id',
        ]);

        $ids = $validated['outlet_ids'];
        $distributorId = $validated['distributor_id'];

        $count = Outlet::whereIn('id', $ids)->update(['distributor_id' => $distributorId]);

        $distributor = Distributor::find($distributorId);

        return back()->with('success', "Berhasil memindahkan {$count} outlet ke distributor: {$distributor->nama}.");
    }

    public function batchReassignShipping(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['superadmin', 'editor'])) {
            return back()->with('error', 'Anda tidak memiliki wewenang untuk mengubah kontak pengiriman outlet.');
        }

        if (is_string($request->input('outlet_ids'))) {
            $request->merge([
                'outlet_ids' => explode(',', $request->input('outlet_ids'))
            ]);
        }

        $validated = $request->validate([
            'outlet_ids' => 'required|array',
            'outlet_ids.*' => 'exists:outlets,id',
            'shipping_contact_id' => 'nullable|exists:shipping_contacts,id',
        ]);

        $ids = $validated['outlet_ids'];
        $shippingContactId = $validated['shipping_contact_id'];

        $outlets = Outlet::whereIn('id', $ids)->get();
        foreach ($outlets as $outlet) {
            if ($shippingContactId) {
                $outlet->shippingContacts()->sync([$shippingContactId => ['urutan' => 1]]);
            } else {
                $outlet->shippingContacts()->detach();
            }
        }

        if ($shippingContactId) {
            $contact = ShippingContact::find($shippingContactId);
            return back()->with('success', "Berhasil menghubungkan " . count($ids) . " outlet ke kontak pengiriman: {$contact->nama}.");
        }

        return back()->with('success', "Berhasil menghapus kontak pengiriman dari " . count($ids) . " outlet.");
    }

    public function clearOutlets(Request $request)
    {
        if (!auth()->user() || auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Hanya Superadmin yang diperbolehkan mengosongkan data outlet.');
        }

        $type = $request->input('type', 'all');

        $query = Outlet::query();
        if ($type === 'non-mitra') {
            $query->where('is_mitra', false);
        }

        $outlets = $query->get();
        $deletedCount = 0;
        $skippedCount = 0;

        foreach ($outlets as $outlet) {
            if ($outlet->leadRequests()->exists()) {
                $skippedCount++;
            } else {
                $outlet->shippingContacts()->detach();
                $outlet->delete();
                $deletedCount++;
            }
        }

        $msg = "Berhasil menghapus {$deletedCount} data outlet.";
        if ($skippedCount > 0) {
            $msg .= " Sebanyak {$skippedCount} outlet dilewati karena memiliki riwayat lead.";
            return back()->with('warning', $msg);
        }

        return back()->with('success', $msg);
    }

    /**
     * Batch update status and attributes (featured, is_mitra, is_hidden, status) for outlets.
     */
    public function batchUpdateStatus(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['superadmin', 'editor'])) {
            return back()->with('error', 'Anda tidak memiliki wewenang untuk mengubah status outlet secara massal.');
        }

        if (is_string($request->input('outlet_ids'))) {
            $request->merge([
                'outlet_ids' => explode(',', $request->input('outlet_ids'))
            ]);
        }

        $validated = $request->validate([
            'outlet_ids' => 'required|array',
            'outlet_ids.*' => 'exists:outlets,id',
            'update_featured' => 'nullable|boolean',
            'featured' => 'nullable|boolean',
            'update_is_mitra' => 'nullable|boolean',
            'is_mitra' => 'nullable|boolean',
            'update_is_hidden' => 'nullable|boolean',
            'is_hidden' => 'nullable|boolean',
            'update_status' => 'nullable|boolean',
            'status' => 'nullable|in:AKTIF,STOK_KOSONG,TUTUP,NONAKTIF',
        ]);

        $ids = $validated['outlet_ids'];
        $updateData = [];

        if ($request->has('update_featured')) {
            $updateData['featured'] = $request->boolean('featured');
        }
        if ($request->has('update_is_mitra')) {
            $updateData['is_mitra'] = $request->boolean('is_mitra');
        }
        if ($request->has('update_is_hidden')) {
            $updateData['is_hidden'] = $request->boolean('is_hidden');
        }
        if ($request->has('update_status')) {
            $updateData['status'] = $request->input('status');
        }

        if (empty($updateData)) {
            return back()->with('warning', 'Tidak ada atribut status yang dipilih untuk diubah.');
        }

        try {
            $count = Outlet::whereIn('id', $ids)->update($updateData);
            return back()->with('success', "Berhasil memperbarui status {$count} outlet secara massal.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status outlet: ' . $e->getMessage());
        }
    }
}
