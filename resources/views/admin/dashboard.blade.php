@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #heatmap {
            height: 450px;
            width: 100%;
            border-radius: 1rem;
            z-index: 10;
        }
        /* Custom map styling to match dark theme */
        .leaflet-container {
            background: #0b1329 !important;
        }
    </style>
@endsection

@section('content')
<div class="space-y-8">
    
    <!-- Title & Greeting -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Ringkasan Analitik Nasional</h1>
            <p class="text-slate-400 mt-1">Data sebaran demand, sebaran outlet/distributor, dan performa lead secara real-time.</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 px-4 py-2.5 rounded-2xl flex items-center gap-2">
            <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-ping"></span>
            <span class="text-xs font-semibold text-slate-300">Live Tracker Active</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-2xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform select-none">👥</div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Leads</span>
            <span class="text-3xl font-black text-white block mt-2">{{ number_format($totalLeads) }}</span>
            <span class="text-[10px] text-emerald-400 mt-1 block font-semibold">↑ Calon pembeli tercatat</span>
        </div>

        <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-2xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform select-none">🏪</div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Outlet / Petshop</span>
            <span class="text-3xl font-black text-white block mt-2">{{ number_format($totalOutlets) }}</span>
            <span class="text-[10px] text-amber-400 mt-1 block font-semibold">Tersebar nasional</span>
        </div>

        <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-2xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform select-none">🏢</div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Distributor Utama</span>
            <span class="text-3xl font-black text-white block mt-2">{{ number_format($totalDistributors) }}</span>
            <span class="text-[10px] text-blue-400 mt-1 block font-semibold">Penyuplai wilayah</span>
        </div>

        <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-2xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform select-none">📦</div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Katalog Produk</span>
            <span class="text-3xl font-black text-white block mt-2">{{ number_format($totalProducts) }}</span>
            <span class="text-[10px] text-violet-400 mt-1 block font-semibold">Premium Scented Litter</span>
        </div>
        
    </div>

    <!-- Peta Heatmap Demand -->
    <div class="bg-slate-900/20 border border-slate-800/80 p-6 rounded-3xl backdrop-blur-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-white flex items-center gap-2">📍 Peta Heatmap Demand Indonesia</h2>
                <p class="text-slate-400 text-xs mt-1">Konsentrasi pencarian produk BentoCat berdasarkan koordinat lokasi pencari terdekat.</p>
            </div>
            <div class="text-[10px] bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-lg text-slate-400">
                Data berbasis GPS Browser dari Leads
            </div>
        </div>
        <div id="heatmap" class="border border-slate-800/60 shadow-inner"></div>
    </div>

    <!-- Row Data Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Kota Teraktif -->
        <div class="bg-slate-900/20 border border-slate-800/80 p-6 rounded-3xl">
            <h2 class="text-xl font-bold text-white mb-4">🏆 Sebaran Lead per Kota</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs text-slate-500 uppercase border-b border-slate-850">
                        <tr>
                            <th class="py-3">Kota</th>
                            <th class="py-3 text-right">Jumlah Lead</th>
                            <th class="py-3 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850">
                        @forelse($citiesDemand as $demand)
                            <tr>
                                <td class="py-3.5 font-semibold text-slate-200">{{ $demand->nama }}</td>
                                <td class="py-3.5 text-right font-bold text-amber-400">{{ number_format($demand->total) }}</td>
                                <td class="py-3.5 text-right font-medium text-slate-400">
                                    {{ $totalLeads > 0 ? round(($demand->total / $totalLeads) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-slate-500 italic">Belum ada data pencarian terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Performa Produk -->
        <div class="bg-slate-900/20 border border-slate-800/80 p-6 rounded-3xl">
            <h2 class="text-xl font-bold text-white mb-4">🔥 Produk Paling Diminati</h2>
            <div class="space-y-4">
                @forelse($productStats as $stat)
                    @php
                        $percentage = $totalLeads > 0 ? ($stat->total / $totalLeads) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center text-sm mb-1.5">
                            <span class="font-semibold text-slate-200">{{ $stat->nama }}</span>
                            <span class="font-bold text-amber-400">{{ number_format($stat->total) }} Lead ({{ round($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-slate-950 rounded-full h-2 border border-slate-800">
                            <div class="bg-gradient-to-r from-amber-500 to-orange-600 h-full rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center py-6 text-slate-500 italic">Belum ada statistik minat produk.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Default center: Indonesia (Jakarta / Central Indonesia)
            const map = L.map('heatmap').setView([-2.548926, 118.0148634], 5);

            // Using premium dark / muted tiles for modern visual look
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            // Fetch php coordinates
            const pointsData = @json($heatmapPoints);
            
            // Add heatmap layer
            const heatPoints = [];
            
            pointsData.forEach(p => {
                heatPoints.push([p.lat, p.lng, p.count * 1.5]);
                
                // Add marker for exact locations
                L.circleMarker([p.lat, p.lng], {
                    radius: 5,
                    fillColor: "#f59e0b",
                    color: "#d97706",
                    weight: 1,
                    opacity: 0.8,
                    fillOpacity: 0.5
                }).addTo(map)
                  .bindPopup(`<strong class="text-slate-900">${p.name}</strong><br><span class="text-xs text-slate-500">Kota: ${p.city}</span>`);
            });

            if (heatPoints.length > 0) {
                // Initialize leaflet heat
                L.heatLayer(heatPoints, {
                    radius: 25,
                    blur: 15,
                    maxZoom: 10,
                    gradient: {0.4: 'blue', 0.6: 'cyan', 0.7: 'lime', 0.8: 'yellow', 1.0: 'red'}
                }).addTo(map);

                // Auto-fit bounds if we have points
                const bounds = L.latLngBounds(pointsData.map(p => [p.lat, p.lng]));
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        });
    </script>
@endsection
