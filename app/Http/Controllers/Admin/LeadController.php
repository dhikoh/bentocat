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
            ->paginate(15);

        $cities = City::orderBy('nama')->get();
        $products = Product::orderBy('nama')->get();

        return view('admin.leads.index', compact('leads', 'cities', 'products', 'search', 'cityId', 'productId'));
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
}
