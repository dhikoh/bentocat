@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #heatmap {
            height: 400px;
            width: 100%;
            border-radius: 1rem;
            z-index: 10;
        }
        .leaflet-container {
            background: #f8fafc !important;
        }
    </style>
@endsection

@section('content')
<div class="space-y-8">
    
    <!-- Title & Greeting -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-white">Ringkasan Analitik Nasional</h1>
            <p class="text-slate-400 mt-1">Data sebaran demand, status kemitraan, dan performa lead secara real-time.</p>
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
            <span class="text-[10px] text-amber-400 mt-1 block font-semibold">Mitra & Prospek Aktif</span>
        </div>

        <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-2xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform select-none">💬</div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">WhatsApp Clicks</span>
            <span class="text-3xl font-black text-white block mt-2">{{ number_format($totalWaClicks) }}</span>
            <span class="text-[10px] text-blue-400 mt-1 block font-semibold">Konversi: <strong class="text-amber-500">{{ $conversionRate }}%</strong></span>
        </div>

        <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-2xl relative overflow-hidden group hover:border-slate-700 transition-all">
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-5 group-hover:scale-110 transition-transform select-none">🏢</div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Distributor Utama</span>
            <span class="text-3xl font-black text-white block mt-2">{{ number_format($totalDistributors) }}</span>
            <span class="text-[10px] text-violet-400 mt-1 block font-semibold">Penyuplai wilayah</span>
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

    <!-- Row Data Grid 1: Demand & Products -->
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

    <!-- Row Data Grid 2: Business Health (Aroma, Size, Prospecting Targets) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Aroma Terpopuler -->
        <div class="bg-slate-900/20 border border-slate-800/80 p-6 rounded-3xl">
            <h2 class="text-lg font-bold text-white mb-4">👃 Aroma Terlaris (Varian)</h2>
            <div class="space-y-4">
                @forelse($aromaStats as $aroma)
                    @php
                        $pct = $totalLeads > 0 ? ($aroma->total / $totalLeads) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-slate-300">{{ $aroma->varian_level_2 }}</span>
                            <span class="text-slate-400">{{ $aroma->total }} ({{ round($pct, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-slate-950 h-1.5 rounded-full border border-slate-800 overflow-hidden">
                            <div class="bg-amber-400 h-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 italic">Belum ada pencarian aroma.</p>
                @endforelse
            </div>
        </div>

        <!-- Ukuran Terpopuler -->
        <div class="bg-slate-900/20 border border-slate-800/80 p-6 rounded-3xl">
            <h2 class="text-lg font-bold text-white mb-4">⚖️ Ukuran Kemasan Terpopuler</h2>
            <div class="space-y-4">
                @forelse($sizeStats as $size)
                    @php
                        $pct = $totalLeads > 0 ? ($size->total / $totalLeads) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-slate-300">{{ $size->varian_level_3 }}</span>
                            <span class="text-slate-400">{{ $size->total }} ({{ round($pct, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-slate-950 h-1.5 rounded-full border border-slate-800 overflow-hidden">
                            <div class="bg-emerald-400 h-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 italic">Belum ada pencarian ukuran kemasan.</p>
                @endforelse
            </div>
        </div>

        <!-- Target Rekrutmen Mitra Baru (Prospecting Index) -->
        <div class="bg-slate-900/20 border border-slate-800/80 p-6 rounded-3xl">
            <div class="mb-2">
                <h2 class="text-lg font-bold text-white flex items-center gap-1.5">
                    🎯 Target Rekrutmen Mitra
                </h2>
                <p class="text-[10px] text-slate-400">Petshop Non-mitra yang paling sering dihubungi oleh pengunjung sekitar.</p>
            </div>
            <div class="divide-y divide-slate-850">
                @forelse($nonMitraProspects as $prospect)
                    <div class="py-2.5 first:pt-0 last:pb-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="block text-xs font-semibold text-slate-200">{{ $prospect->nama_outlet }}</span>
                                <span class="block text-[10px] text-amber-500">{{ $prospect->city_name }}</span>
                            </div>
                            <span class="bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[10px] font-bold px-2 py-0.5 rounded-lg">
                                {{ $prospect->total_clicks }} Klik WA
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 italic py-4">Belum ada permintaan di toko non-mitra.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Default center: Indonesia
            const map = L.map('heatmap').setView([-2.548926, 118.0148634], 5);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 20
            }).addTo(map);

            const pointsData = @json($heatmapPoints);
            const heatPoints = [];
            
            pointsData.forEach(p => {
                heatPoints.push([p.lat, p.lng, p.count * 1.5]);
                
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
                L.heatLayer(heatPoints, {
                    radius: 25,
                    blur: 15,
                    maxZoom: 10,
                    gradient: {0.4: 'blue', 0.6: 'cyan', 0.7: 'lime', 0.8: 'yellow', 1.0: 'red'}
                }).addTo(map);

                const bounds = L.latLngBounds(pointsData.map(p => [p.lat, p.lng]));
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        });
    </script>
@endsection
