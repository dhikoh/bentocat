<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadRequest;
use App\Models\Outlet;
use App\Models\Distributor;
use App\Models\City;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLeads = LeadRequest::count();
        $totalOutlets = Outlet::count();
        $totalDistributors = Distributor::count();
        $totalProducts = Product::count();

        // Demand by City (Heatmap data)
        // Group by city's latitude and longitude from outlets or customer profiles
        // We will fetch customer coordinates or outlet coordinates for regional demand
        $citiesDemand = LeadRequest::select('cities.nama', 'cities.slug', DB::raw('count(lead_requests.id) as total'))
            ->join('cities', 'lead_requests.kota_id', '=', 'cities.id')
            ->groupBy('cities.id', 'cities.nama', 'cities.slug')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Heatmap points based on Lead coordinates (Customer Profiles)
        $heatmapPoints = DB::table('customer_profiles')
            ->select('latitude', 'longitude', 'nama', 'kota')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($point) {
                return [
                    'lat' => (float) $point->latitude,
                    'lng' => (float) $point->longitude,
                    'count' => 1,
                    'name' => $point->nama,
                    'city' => $point->kota,
                ];
            });

        // Leads by Product
        $productStats = LeadRequest::select('products.nama', DB::raw('count(lead_requests.id) as total'))
            ->join('products', 'lead_requests.produk_id', '=', 'products.id')
            ->groupBy('products.id', 'products.nama')
            ->orderByDesc('total')
            ->get();

        return view('admin.dashboard', compact(
            'totalLeads', 'totalOutlets', 'totalDistributors', 'totalProducts',
            'citiesDemand', 'heatmapPoints', 'productStats'
        ));
    }
}
