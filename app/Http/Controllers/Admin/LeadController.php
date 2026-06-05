<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadRequest;
use App\Models\LeadAction;
use App\Models\City;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $cityId = $request->input('city_id');
        $productId = $request->input('product_id');

        $perPage = $request->input('per_page', 15);
        $perPageLimit = ($perPage === 'all') ? 9999 : (int)$perPage;

        $leads = LeadRequest::with(['customer', 'product', 'city', 'outlet', 'distributor'])
            ->withCount('actions')
            ->when($search, function ($query, $search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('whatsapp', 'like', "%{$search}%");
                });
            })
            ->when($cityId, function ($query, $cityId) {
                $query->where('kota_id', $cityId);
            })
            ->when($productId, function ($query, $productId) {
                $query->where('produk_id', $productId);
            })
            ->orderByDesc('created_at')
            ->paginate($perPageLimit);

        $cities = City::orderBy('nama')->get();
        $products = Product::orderBy('nama')->get();

        $countTotalLeads = LeadRequest::count();
        $countNoInteractionLeads = LeadRequest::doesntHave('actions')->count();

        return view('admin.leads.index', compact('leads', 'cities', 'products', 'search', 'cityId', 'productId', 'perPage', 'countTotalLeads', 'countNoInteractionLeads'));
    }

    public function show(LeadRequest $lead)
    {
        $lead->load(['customer', 'product', 'city', 'outlet', 'distributor', 'actions']);
        return view('admin.leads.show', compact('lead'));
    }

    public function exportCsv(Request $request)
    {
        $cityId = $request->input('city_id');
        $productId = $request->input('product_id');

        $leads = LeadRequest::with(['customer', 'product', 'city', 'outlet', 'distributor'])
            ->when($cityId, function ($query, $cityId) {
                $query->where('kota_id', $cityId);
            })
            ->when($productId, function ($query, $productId) {
                $query->where('produk_id', $productId);
            })
            ->orderByDesc('created_at')
            ->get();

        $response = new StreamedResponse(function () use ($leads) {
            $handle = fopen('php://output', 'w');
            
            // BOM for UTF-8 Excel support
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($handle, [
                'ID Lead',
                'Tanggal',
                'Nama Customer',
                'WhatsApp Customer',
                'Alamat Customer',
                'Provinsi Customer (GPS)',
                'Kota Customer (GPS)',
                'Produk Pilihan',
                'Varian 1 (Kategori)',
                'Varian 2 (Aroma)',
                'Varian 3 (Ukuran)',
                'Kota Outlet',
                'Nama Outlet',
                'WhatsApp Outlet',
                'Nama Distributor',
                'WhatsApp Distributor'
            ]);

            foreach ($leads as $lead) {
                fputcsv($handle, [
                    $lead->id,
                    $lead->created_at->format('Y-m-d H:i:s'),
                    $lead->customer->nama,
                    $lead->customer->whatsapp,
                    $lead->customer->alamat,
                    $lead->customer->provinsi ?: '-',
                    $lead->customer->kota ?: '-',
                    $lead->product->nama,
                    $lead->varian_level_1,
                    $lead->varian_level_2 ?: '-',
                    $lead->varian_level_3 ?: '-',
                    $lead->city->nama,
                    $lead->outlet->nama_outlet,
                    $lead->outlet->whatsapp,
                    $lead->distributor->nama,
                    $lead->distributor->whatsapp
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="bentocat_leads_export_' . date('Ymd_His') . '.csv"',
        ]);

        return $response;
    }

    public function destroy(LeadRequest $lead)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Hanya Superadmin yang memiliki izin untuk menghapus data lead.');
        }

        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Data Lead berhasil dihapus.');
    }

    public function clearLeads(Request $request)
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Hanya Superadmin yang memiliki izin untuk mengosongkan data lead.');
        }

        $type = $request->input('type', 'all');

        if ($type === 'no-interaction') {
            $count = LeadRequest::doesntHave('actions')->count();
            LeadRequest::doesntHave('actions')->delete();
            return redirect()->route('admin.leads.index')->with('success', "Berhasil menghapus {$count} data lead tanpa interaksi.");
        }

        $count = LeadRequest::count();
        LeadRequest::query()->delete();
        return redirect()->route('admin.leads.index')->with('success', "Berhasil mengosongkan {$count} data lead.");
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
            'tanggal' => ['tanggal', 'date', 'created_at'],
            'nama_customer' => ['nama customer', 'customer name', 'nama pelanggan', 'customer', 'nama_customer'],
            'whatsapp_customer' => ['whatsapp customer', 'whatsapp pelanggan', 'no wa customer', 'customer whatsapp', 'whatsapp_customer'],
            'alamat_customer' => ['alamat customer', 'alamat pelanggan', 'customer address', 'alamat_customer'],
            'provinsi_customer' => ['provinsi customer (gps)', 'provinsi pelanggan', 'provinsi customer', 'provinsi_customer'],
            'kota_customer' => ['kota customer (gps)', 'kota pelanggan', 'kota customer', 'kota_customer'],
            'produk' => ['produk pilihan', 'produk', 'product', 'produk_pilihan'],
            'varian_1' => ['varian 1 (kategori)', 'varian 1', 'varian_1', 'kategori'],
            'varian_2' => ['varian 2 (aroma)', 'varian 2', 'varian_2', 'aroma'],
            'varian_3' => ['varian 3 (ukuran)', 'varian 3', 'varian_3', 'ukuran'],
            'kota_outlet' => ['kota outlet', 'kota_outlet'],
            'nama_outlet' => ['nama outlet', 'outlet name', 'nama_outlet', 'outlet'],
            'whatsapp_outlet' => ['whatsapp outlet', 'no wa outlet', 'whatsapp_outlet'],
            'nama_distributor' => ['nama distributor', 'nama_distributor', 'distributor']
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

        // We require customer name, customer whatsapp, and product name.
        if ($colMap['nama_customer'] === -1 || $colMap['whatsapp_customer'] === -1 || $colMap['produk'] === -1) {
            fclose($handle);
            return back()->with('error', 'Kolom wajib "Nama Customer", "WhatsApp Customer", dan "Produk Pilihan" tidak ditemukan.');
        }

        $inserted = 0;
        $skipped = 0;
        $failed = [];

        $defaultProduct = Product::first();
        $defaultCity = City::first();
        $defaultDistributor = \App\Models\Distributor::where('nama', 'like', '%Pusat%')->first() ?: \App\Models\Distributor::first();

        // Load lookups in-memory
        $allCustomersByWa = \App\Models\CustomerProfile::all()->keyBy('whatsapp');
        $allProducts = Product::all();
        $allCities = City::all()->keyBy(fn($c) => strtolower($c->nama));
        $allDistributors = \App\Models\Distributor::all();
        $allOutletsByNameCity = \App\Models\Outlet::all()->groupBy(fn($o) => strtolower($o->nama_outlet) . '|' . $o->kota_id);
        $allOutletsByWa = \App\Models\Outlet::all()->keyBy('whatsapp');
        
        // Load recent leads for duplicate checking (last 60 days to keep memory usage reasonable)
        $recentLeads = LeadRequest::where('created_at', '>=', now()->subDays(60))->get();

        \Illuminate\Support\Facades\DB::transaction(function () use (
            $handle, $colMap, &$inserted, &$skipped, &$failed,
            $defaultProduct, $defaultCity, $defaultDistributor,
            $allCustomersByWa, $allProducts, $allCities, $allDistributors,
            $allOutletsByNameCity, $allOutletsByWa, $recentLeads
        ) {
            $rowNum = 1;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNum++;

                $tanggalRaw = $colMap['tanggal'] !== -1 ? trim($row[$colMap['tanggal']] ?? '') : '';
                $namaCustomer = trim($row[$colMap['nama_customer']] ?? '');
                $whatsappCustomer = preg_replace('/[^0-9]/', '', $row[$colMap['whatsapp_customer']] ?? '');
                $alamatCustomer = $colMap['alamat_customer'] !== -1 ? trim($row[$colMap['alamat_customer']] ?? '') : '';
                $provinsiCustomer = $colMap['provinsi_customer'] !== -1 ? trim($row[$colMap['provinsi_customer']] ?? '') : '';
                $kotaCustomer = $colMap['kota_customer'] !== -1 ? trim($row[$colMap['kota_customer']] ?? '') : '';
                $produkName = trim($row[$colMap['produk']] ?? '');
                $varian1 = $colMap['varian_1'] !== -1 ? trim($row[$colMap['varian_1']] ?? '') : null;
                $varian2 = $colMap['varian_2'] !== -1 ? trim($row[$colMap['varian_2']] ?? '') : null;
                $varian3 = $colMap['varian_3'] !== -1 ? trim($row[$colMap['varian_3']] ?? '') : null;
                $kotaOutletName = $colMap['kota_outlet'] !== -1 ? trim($row[$colMap['kota_outlet']] ?? '') : '';
                $namaOutlet = $colMap['nama_outlet'] !== -1 ? trim($row[$colMap['nama_outlet']] ?? '') : '';
                $whatsappOutlet = $colMap['whatsapp_outlet'] !== -1 ? preg_replace('/[^0-9]/', '', $row[$colMap['whatsapp_outlet']] ?? '') : '';
                $distributorName = $colMap['nama_distributor'] !== -1 ? trim($row[$colMap['nama_distributor']] ?? '') : '';

                if (empty($namaCustomer) || empty($whatsappCustomer) || empty($produkName)) {
                    $failed[] = "Baris {$rowNum}: Data customer & produk wajib diisi.";
                    continue;
                }

                // Standardize WA Customer
                if (str_starts_with($whatsappCustomer, '0')) {
                    $whatsappCustomer = '62' . substr($whatsappCustomer, 1);
                } elseif (str_starts_with($whatsappCustomer, '8')) {
                    $whatsappCustomer = '62' . $whatsappCustomer;
                }

                // Find or create Customer in-memory
                $customer = $allCustomersByWa->get($whatsappCustomer);
                if (!$customer) {
                    $customer = \App\Models\CustomerProfile::create([
                        'uuid' => (string) \Illuminate\Support\Str::uuid(),
                        'nama' => $namaCustomer,
                        'whatsapp' => $whatsappCustomer,
                        'alamat' => $alamatCustomer,
                        'provinsi' => $provinsiCustomer,
                        'kota' => $kotaCustomer
                    ]);
                    $allCustomersByWa->put($whatsappCustomer, $customer);
                } else {
                    // Update empty fields
                    $customer->update([
                        'nama' => $namaCustomer,
                        'alamat' => $customer->alamat ?: $alamatCustomer,
                        'provinsi' => $customer->provinsi ?: $provinsiCustomer,
                        'kota' => $customer->kota ?: $kotaCustomer
                    ]);
                }

                // Find Product in-memory
                $product = $allProducts->first(fn($p) => stripos($p->nama, $produkName) !== false);
                if (!$product) {
                    $product = $allProducts->first(fn($p) => strtolower($p->nama) === strtolower($produkName)) ?: $defaultProduct;
                }

                // Find City in-memory
                $city = null;
                if (!empty($kotaOutletName)) {
                    $city = $allCities->get(strtolower($kotaOutletName));
                    if (!$city) {
                        $city = $allCities->first(fn($c) => stripos($c->nama, $kotaOutletName) !== false);
                    }
                }
                if (!$city && !empty($kotaCustomer)) {
                    $city = $allCities->get(strtolower($kotaCustomer));
                    if (!$city) {
                        $city = $allCities->first(fn($c) => stripos($c->nama, $kotaCustomer) !== false);
                    }
                }
                if (!$city) {
                    $city = $defaultCity;
                }

                // Find Distributor in-memory
                $distributor = null;
                if (!empty($distributorName)) {
                    $distributor = $allDistributors->first(fn($d) => stripos($d->nama, $distributorName) !== false);
                }
                if (!$distributor) {
                    $distributor = $defaultDistributor;
                }

                // Find or create Outlet if outlet name is provided
                $outlet = null;
                if (!empty($namaOutlet)) {
                    // Match by name and city in-memory
                    $key = strtolower($namaOutlet) . '|' . $city->id;
                    $group = $allOutletsByNameCity->get($key);
                    if ($group && $group->count() > 0) {
                        $outlet = $group->first();
                    }

                    if (!$outlet && !empty($whatsappOutlet)) {
                        // Standardize WA Outlet
                        if (str_starts_with($whatsappOutlet, '0')) {
                            $whatsappOutlet = '62' . substr($whatsappOutlet, 1);
                        } elseif (str_starts_with($whatsappOutlet, '8')) {
                            $whatsappOutlet = '62' . $whatsappOutlet;
                        }
                        $outlet = $allOutletsByWa->get($whatsappOutlet);
                    }

                    if (!$outlet) {
                        $outlet = \App\Models\Outlet::create([
                            'distributor_id' => $distributor->id,
                            'kota_id' => $city->id,
                            'nama_outlet' => $namaOutlet,
                            'nama_pic' => 'PIC ' . $namaOutlet,
                            'whatsapp' => $whatsappOutlet ?: ('628' . rand(100000000, 999999999)),
                            'alamat_lengkap' => $alamatCustomer ?: 'Alamat Outlet ' . $namaOutlet,
                            'is_mitra' => false,
                            'status' => 'AKTIF',
                            'delivery_mode' => 'SELF_DELIVERY'
                        ]);
                        // Update cache
                        $allOutletsByWa->put($outlet->whatsapp, $outlet);
                        $allOutletsByNameCity->put($key, collect([$outlet]));
                    }
                }

                // Parse Date
                $createdAt = now();
                if (!empty($tanggalRaw)) {
                    try {
                        $createdAt = \Carbon\Carbon::parse($tanggalRaw);
                    } catch (\Exception $e) {
                        $createdAt = now();
                    }
                }

                // Check duplicate Lead in-memory to prevent double logs
                $existingLead = $recentLeads->first(function ($l) use ($customer, $product, $outlet, $createdAt) {
                    return $l->customer_id == $customer->id &&
                           $l->produk_id == $product->id &&
                           $l->outlet_id == ($outlet ? $outlet->id : null) &&
                           abs($l->created_at->timestamp - $createdAt->timestamp) <= 60;
                });

                if ($existingLead) {
                    $skipped++;
                    continue;
                }

                // Create Lead Request
                $lead = new LeadRequest();
                $lead->customer_id = $customer->id;
                $lead->produk_id = $product->id;
                $lead->kota_id = $city->id;
                $lead->outlet_id = $outlet ? $outlet->id : null;
                $lead->distributor_id = $distributor ? $distributor->id : null;
                $lead->varian_1 = $varian1;
                $lead->varian_2 = $varian2;
                $lead->varian_3 = $varian3;
                $lead->created_at = $createdAt;
                $lead->updated_at = $createdAt;
                $lead->save();

                // Add to recentLeads in-memory cache to prevent internal duplicate in same CSV upload
                $recentLeads->push($lead);

                $inserted++;
            }
        });

        fclose($handle);

        $msg = "Impor CSV selesai! {$inserted} lead baru ditambahkan, {$skipped} data duplikat dilewati.";
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
