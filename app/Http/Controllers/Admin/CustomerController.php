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
}
