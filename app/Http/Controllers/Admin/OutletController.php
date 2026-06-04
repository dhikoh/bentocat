<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Distributor;
use App\Models\City;
use App\Models\ShippingContact;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $isMitra = $request->input('is_mitra');
        
        $outlets = Outlet::with(['distributor', 'city'])
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_outlet', 'like', "%{$search}%")
                      ->orWhere('nama_pic', 'like', "%{$search}%")
                      ->orWhereHas('city', function ($c) use ($search) {
                          $c->where('nama', 'like', "%{$search}%");
                      })
                      ->orWhereHas('distributor', function ($d) use ($search) {
                          $d->where('nama', 'like', "%{$search}%");
                      });
                });
            })
            ->when($isMitra !== null && $isMitra !== '', function ($query) use ($isMitra) {
                $query->where('is_mitra', $isMitra);
            })
            ->orderBy('nama_outlet')
            ->paginate(10);

        return view('admin.outlets.index', compact('outlets', 'search', 'isMitra'));
    }

    public function create()
    {
        $distributors = Distributor::where('status', 'ACTIVE')->orderBy('nama')->get();
        $cities = City::orderBy('nama')->get();
        $shippingContacts = ShippingContact::where('aktif', true)->orderBy('nama')->get();
        return view('admin.outlets.create', compact('distributors', 'cities', 'shippingContacts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'kota_id' => 'required|exists:cities,id',
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
        ]);

        $validated['featured'] = $request->has('featured');
        $validated['is_mitra'] = $request->has('is_mitra');

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
        $cities = City::orderBy('nama')->get();
        $shippingContacts = ShippingContact::where('aktif', true)->orderBy('nama')->get();
        $selectedContacts = $outlet->shippingContacts->pluck('id')->toArray();
        return view('admin.outlets.edit', compact('outlet', 'distributors', 'cities', 'shippingContacts', 'selectedContacts'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $validated = $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'kota_id' => 'required|exists:cities,id',
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
        ]);

        $validated['featured'] = $request->has('featured');
        $validated['is_mitra'] = $request->has('is_mitra');

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
                'Kurir'
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
                    $couriers
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
        
        $inserted = 0;
        $updated = 0;
        $failed = [];
        $rowNum = 1;

        $centralDistributor = Distributor::where('nama', 'like', '%Pusat%')->first();
        if (!$centralDistributor) {
            $centralDistributor = Distributor::first();
        }

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $rowNum++;
            
            // Check if row is empty or has insufficient columns
            if (count($row) < 3) {
                $failed[] = "Baris {$rowNum}: Format kolom tidak sesuai.";
                continue;
            }

            $namaOutlet = trim($row[0] ?? '');
            $alamat = trim($row[1] ?? '');
            $whatsapp = preg_replace('/[^0-9]/', '', $row[2] ?? '');
            $mitraRaw = strtolower(trim($row[3] ?? 'ya'));
            $kotaName = trim($row[4] ?? '');
            $distributorName = trim($row[5] ?? '');
            $kurirRaw = trim($row[6] ?? '');

            if (empty($namaOutlet) || empty($whatsapp)) {
                $failed[] = "Baris {$rowNum}: Nama Petshop dan No WA wajib diisi.";
                continue;
            }

            // Determine is_mitra
            $isMitra = in_array($mitraRaw, ['ya', 'yes', 'mitra', '1', 'true']);

            // Find city
            $city = null;
            if (!empty($kotaName)) {
                $city = City::where('nama', 'like', "%{$kotaName}%")->first();
            }
            if (!$city) {
                $failed[] = "Baris {$rowNum}: Kota '{$kotaName}' tidak terdaftar di sistem.";
                continue;
            }

            // Find distributor
            $distributor = null;
            if (!empty($distributorName)) {
                $distributor = Distributor::where('nama', 'like', "%{$distributorName}%")->first();
            }
            if (!$distributor) {
                $distributor = $centralDistributor;
            }

            // Check duplicate
            // Rule: Same WA OR Same Name + City
            $existing = Outlet::where('whatsapp', $whatsapp)
                ->orWhere(function ($query) use ($namaOutlet, $city) {
                    $query->where('nama_outlet', $namaOutlet)
                          ->where('kota_id', $city->id);
                })
                ->first();

            $outlet = null;
            if ($existing) {
                $existing->update([
                    'distributor_id' => $distributor->id,
                    'nama_outlet' => $namaOutlet,
                    'alamat_lengkap' => $alamat,
                    'is_mitra' => $isMitra,
                    'status' => 'AKTIF'
                ]);
                $outlet = $existing;
                $updated++;
            } else {
                $outlet = Outlet::create([
                    'distributor_id' => $distributor->id,
                    'kota_id' => $city->id,
                    'nama_outlet' => $namaOutlet,
                    'nama_pic' => 'PIC ' . $namaOutlet,
                    'whatsapp' => $whatsapp,
                    'alamat_lengkap' => $alamat,
                    'is_mitra' => $isMitra,
                    'status' => 'AKTIF',
                    'delivery_mode' => 'SELF_DELIVERY'
                ]);
                $inserted++;
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
                        $courier = ShippingContact::firstOrCreate(
                            ['whatsapp' => $cPhone],
                            ['nama' => $cName, 'aktif' => true]
                        );
                        $courierIds[] = $courier->id;
                    }
                }

                if (count($courierIds) > 0) {
                    $outlet->update(['delivery_mode' => 'RECOMMENDED_SHIPPING_CONTACT']);
                    $syncData = [];
                    foreach ($courierIds as $idx => $cid) {
                        $syncData[$cid] = ['urutan' => $idx + 1];
                    }
                    $outlet->shippingContacts()->syncWithoutDetaching($syncData);
                }
            }
        }

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
}
