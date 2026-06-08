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

        // 1. WhatsApp Clicks & Conversion Rate
        $totalWaClicks = DB::table('lead_actions')
            ->whereIn('action_type', ['CLICK_WA_OUTLET', 'CLICK_WA_SHIPPING_CONTACT'])
            ->count();
        $conversionRate = $totalLeads > 0 ? round(($totalWaClicks / $totalLeads) * 100, 1) : 0;

        // 2. Aroma (Varian Level 2) Stats
        $aromaStats = LeadRequest::select('varian_level_2', DB::raw('count(id) as total'))
            ->whereNotNull('varian_level_2')
            ->where('varian_level_2', '!=', '')
            ->groupBy('varian_level_2')
            ->orderByDesc('total')
            ->get();

        // 3. Size (Varian Level 3) Stats
        $sizeStats = LeadRequest::select('varian_level_3', DB::raw('count(id) as total'))
            ->whereNotNull('varian_level_3')
            ->where('varian_level_3', '!=', '')
            ->groupBy('varian_level_3')
            ->orderByDesc('total')
            ->get();

        // 4. Non-Mitra Clicks (Prospecting Index)
        $nonMitraProspects = LeadRequest::select('outlets.nama_outlet', 'cities.nama as city_name', DB::raw('count(lead_requests.id) as total_clicks'))
            ->join('outlets', 'lead_requests.outlet_id', '=', 'outlets.id')
            ->join('cities', 'lead_requests.kota_id', '=', 'cities.id')
            ->where('outlets.is_mitra', false)
            ->groupBy('outlets.id', 'outlets.nama_outlet', 'cities.nama')
            ->orderByDesc('total_clicks')
            ->limit(5)
            ->get();

        // 5. Leads Trend (Last 30 Days)
        $leadsTrendData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $leadsTrendData[$date] = 0;
        }

        $leadsTrend = LeadRequest::select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get();

        foreach ($leadsTrend as $trend) {
            $leadsTrendData[$trend->date] = $trend->total;
        }

        return view('admin.dashboard', compact(
            'totalLeads', 'totalOutlets', 'totalDistributors', 'totalProducts',
            'citiesDemand', 'heatmapPoints', 'productStats', 'totalWaClicks',
            'conversionRate', 'aromaStats', 'sizeStats', 'nonMitraProspects',
            'leadsTrendData'
        ));
    }
}
