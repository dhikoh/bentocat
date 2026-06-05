<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $perPage = $request->input('per_page', 15);
        $perPageLimit = ($perPage === 'all') ? 9999 : (int)$perPage;

        $customers = CustomerProfile::withCount('leadRequests')
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('whatsapp', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('provinsi', 'like', "%{$search}%")
                      ->orWhere('kota', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate($perPageLimit);

        $countTotalCustomers = CustomerProfile::count();
        $countNoActivityCustomers = CustomerProfile::doesntHave('leadRequests')->count();

        return view('admin.customers.index', compact('customers', 'search', 'perPage', 'countTotalCustomers', 'countNoActivityCustomers'));
    }

    public function exportCsv(Request $request)
    {
        $search = $request->input('search');

        $customers = CustomerProfile::withCount('leadRequests')
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('whatsapp', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->get();

        $response = new StreamedResponse(function () use ($customers) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($handle, [
                'ID Pelanggan',
                'Nama',
                'WhatsApp',
                'Alamat',
                'Provinsi (GPS)',
                'Kota (GPS)',
                'Total Interaksi (Leads)',
                'Tanggal Bergabung'
            ]);

            foreach ($customers as $customer) {
                fputcsv($handle, [
                    $customer->id,
                    $customer->nama,
                    $customer->whatsapp,
                    $customer->alamat,
                    $customer->provinsi ?: '-',
                    $customer->kota ?: '-',
                    $customer->lead_requests_count,
                    $customer->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="bentocat_customers_export_' . date('Ymd_His') . '.csv"',
        ]);

        return $response;
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25|unique:customer_profiles,whatsapp',
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'provinsi' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
        ]);

        $validated['uuid'] = (string) \Illuminate\Support\Str::uuid();

        CustomerProfile::create($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(CustomerProfile $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, CustomerProfile $customer)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:25|unique:customer_profiles,whatsapp,' . $customer->id,
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'provinsi' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(CustomerProfile $customer)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Hanya Superadmin yang memiliki izin untuk menghapus data pelanggan.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan beserta seluruh log lead & aktivitas terkait berhasil dihapus.');
    }

    public function clearCustomers(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Hanya Superadmin yang memiliki izin untuk mengosongkan data pelanggan.');
        }

        $type = $request->input('type', 'all');

        if ($type === 'no-activity') {
            $count = CustomerProfile::doesntHave('leadRequests')->count();
            CustomerProfile::doesntHave('leadRequests')->delete();
            return redirect()->route('admin.customers.index')->with('success', "Berhasil menghapus {$count} data pelanggan tanpa aktivitas.");
        }

        $count = CustomerProfile::count();
        CustomerProfile::query()->delete();
        return redirect()->route('admin.customers.index')->with('success', "Berhasil mengosongkan {$count} data pelanggan.");
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

        $headers = fgetcsv($handle, 1000, ',');
        if (!$headers) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau tidak valid.');
        }

        // BOM Removal
        if (substr($headers[0], 0, 3) == "\xEF\xBB\xBF") {
            $headers[0] = substr($headers[0], 3);
        }

        $headers = array_map(function ($h) {
            return strtolower(trim($h));
        }, $headers);

        $colMap = [];
        $headerKeywords = [
            'nama' => ['nama', 'nama pelanggan', 'nama customer', 'name'],
            'whatsapp' => ['whatsapp', 'no wa', 'phone', 'telepon', 'no. wa', 'nomor wa', 'wa'],
            'alamat' => ['alamat', 'alamat lengkap', 'address'],
            'latitude' => ['latitude', 'lat'],
            'longitude' => ['longitude', 'lng', 'long'],
            'provinsi' => ['provinsi', 'province', 'provinsi (gps)', 'provinsi customer (gps)'],
            'kota' => ['kota', 'city', 'kota (gps)', 'kota customer (gps)']
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

        if ($colMap['nama'] === -1 || $colMap['whatsapp'] === -1) {
            fclose($handle);
            return back()->with('error', 'Kolom wajib "Nama" dan "WhatsApp" tidak ditemukan dalam header CSV.');
        }

        $inserted = 0;
        $updated = 0;
        $failed = [];

        // Load all customers in memory
        $allCustomersByWa = CustomerProfile::all()->keyBy('whatsapp');

        \Illuminate\Support\Facades\DB::transaction(function () use (
            $handle, $colMap, &$inserted, &$updated, &$failed, $allCustomersByWa
        ) {
            $rowNum = 1;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNum++;

                $nama = trim($row[$colMap['nama']] ?? '');
                $whatsapp = preg_replace('/[^0-9]/', '', $row[$colMap['whatsapp']] ?? '');
                $alamat = $colMap['alamat'] !== -1 ? trim($row[$colMap['alamat']] ?? '') : '';
                $latitude = $colMap['latitude'] !== -1 && trim($row[$colMap['latitude']] ?? '') !== '' ? floatval($row[$colMap['latitude']]) : null;
                $longitude = $colMap['longitude'] !== -1 && trim($row[$colMap['longitude']] ?? '') !== '' ? floatval($row[$colMap['longitude']]) : null;
                $provinsi = $colMap['provinsi'] !== -1 ? trim($row[$colMap['provinsi']] ?? '') : null;
                $kota = $colMap['kota'] !== -1 ? trim($row[$colMap['kota']] ?? '') : null;

                if (empty($nama) || empty($whatsapp)) {
                    $failed[] = "Baris {$rowNum}: Nama dan WhatsApp wajib diisi.";
                    continue;
                }

                // Standardize WhatsApp format
                if (str_starts_with($whatsapp, '0')) {
                    $whatsapp = '62' . substr($whatsapp, 1);
                } elseif (str_starts_with($whatsapp, '8')) {
                    $whatsapp = '62' . $whatsapp;
                }

                $existing = $allCustomersByWa->get($whatsapp);

                if ($existing) {
                    $existing->update([
                        'nama' => $nama,
                        'alamat' => $alamat ?: $existing->alamat,
                        'latitude' => $latitude ?: $existing->latitude,
                        'longitude' => $longitude ?: $existing->longitude,
                        'provinsi' => $provinsi ?: $existing->provinsi,
                        'kota' => $kota ?: $existing->kota,
                    ]);
                    $updated++;
                } else {
                    $newCust = CustomerProfile::create([
                        'uuid' => (string) \Illuminate\Support\Str::uuid(),
                        'nama' => $nama,
                        'whatsapp' => $whatsapp,
                        'alamat' => $alamat,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'provinsi' => $provinsi,
                        'kota' => $kota
                    ]);
                    $allCustomersByWa->put($whatsapp, $newCust);
                    $inserted++;
                }
            }
        });

        fclose($handle);

        $msg = "Impor CSV selesai! {$inserted} pelanggan baru ditambahkan, {$updated} data duplikat diperbarui.";
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
