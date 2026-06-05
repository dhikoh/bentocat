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

        $customers = CustomerProfile::withCount('leadRequests')
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('whatsapp', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('provinsi', 'like', "%{$search}%")
                      ->orWhere('kota', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.customers.index', compact('customers', 'search'));
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
}
