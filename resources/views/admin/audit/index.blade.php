@extends('layouts.admin')

@section('title', 'Audit & Kesehatan Bisnis')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Audit & Kesehatan Bisnis</h1>
            <p class="text-sm text-slate-400 mt-1">Pantau integritas database, deteksi duplikasi kontak, dan analisis metrik ekspansi pasar BentoCat.</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-400 text-sm flex items-center gap-3">
        <span>✅</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-400 text-sm flex items-center gap-3">
        <span>⚠️</span>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Section 1: Business Health Metrics & Customer Insights -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Customer Retention / Repeat Rate -->
        <div class="bg-slate-900/50 border border-slate-800 p-6 rounded-3xl backdrop-blur-xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-8xl opacity-5 select-none pointer-events-none group-hover:scale-110 transition-transform duration-300">👥</div>
            <span class="text-xs font-bold text-amber-500 uppercase tracking-widest block">Loyalty & Retention</span>
            <h3 class="text-slate-400 text-sm font-semibold mt-2">Customer Repeat Rate</h3>
            <div class="flex items-baseline gap-2 mt-3">
                <span class="text-4xl font-black text-white tracking-tight">{{ $retentionRate }}%</span>
                <span class="text-xs text-slate-400">rasio repeat user</span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-2 mt-4 overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ $retentionRate }}%"></div>
            </div>
            <p class="text-xs text-slate-500 mt-3">{{ $repeatCustomers }} dari {{ $totalCustomers }} total kustomer terdaftar melakukan pencarian lebih dari sekali.</p>
        </div>

        <!-- Active Partner Ratio -->
        <div class="bg-slate-900/50 border border-slate-800 p-6 rounded-3xl backdrop-blur-xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-8xl opacity-5 select-none pointer-events-none group-hover:scale-110 transition-transform duration-300">🏪</div>
            <span class="text-xs font-bold text-amber-500 uppercase tracking-widest block">Mitra Health Index</span>
            <h3 class="text-slate-400 text-sm font-semibold mt-2">Active Partner Ratio</h3>
            <div class="flex items-baseline gap-2 mt-3">
                <span class="text-4xl font-black text-white tracking-tight">{{ $partnerActiveRatio }}%</span>
                <span class="text-xs text-slate-400">mitra aktif</span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-2 mt-4 overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $partnerActiveRatio }}%"></div>
            </div>
            <p class="text-xs text-slate-500 mt-3">Rasio petshop yang berstatus aktif dalam sistem discovery publik.</p>
        </div>

        <!-- Courier Readiness -->
        <div class="bg-slate-900/50 border border-slate-800 p-6 rounded-3xl backdrop-blur-xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-8xl opacity-5 select-none pointer-events-none group-hover:scale-110 transition-transform duration-300">🛵</div>
            <span class="text-xs font-bold text-amber-500 uppercase tracking-widest block">Delivery Readiness</span>
            <h3 class="text-slate-400 text-sm font-semibold mt-2">Courier Attachment Rate</h3>
            <div class="flex items-baseline gap-2 mt-3">
                <span class="text-4xl font-black text-white tracking-tight">{{ $courierAttachmentRate }}%</span>
                <span class="text-xs text-slate-400">terkoneksi kurir</span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-2 mt-4 overflow-hidden">
                <div class="bg-indigo-500 h-full rounded-full transition-all duration-500" style="width: {{ $courierAttachmentRate }}%"></div>
            </div>
            <p class="text-xs text-slate-500 mt-3">Rasio outlet mitra aktif yang sudah dikaitkan dengan kurir lokal setempat.</p>
        </div>
    </div>

    <!-- Supply Gap Area & Market Gaps -->
    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-2xl">🚨</span>
            <div>
                <h2 class="text-xl font-bold text-white">Supply Gap Analysis (Peluang Ekspansi Mitra)</h2>
                <p class="text-xs text-slate-400">Daftar kota dengan demand tinggi (pencarian produk) yang saat ini **belum memiliki petshop mitra aktif** (menggunakan fallback distributor).</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 text-xs uppercase tracking-wider">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Nama Kota / Wilayah</th>
                        <th class="py-3 px-4">Jumlah Demand (Total Leads Terkumpul)</th>
                        <th class="py-3 px-4">Rekomendasi Tindakan Bisnis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm">
                    @forelse($supplyGapCities as $index => $city)
                    <tr class="hover:bg-slate-800/20 text-slate-300">
                        <td class="py-3 px-4 font-bold">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 font-semibold text-white">{{ $city->nama }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 font-bold">
                                📊 {{ $city->total_demand }} pencarian
                            </span>
                        </td>
                        <td class="py-3 px-4 text-xs text-slate-400">
                            🔴 Rekrut petshop di {{ $city->nama }} sebagai mitra resmi BentoCat segera untuk menangkap pasar ini.
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-500">
                            Semua kota yang memiliki demand telah terlayani oleh minimal satu mitra petshop aktif! Kesehatan distribusi sangat prima.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: Duplication Audit & Resolution Panel -->
    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span>🛡️</span> Pusat Resolusi Duplikasi Data
                </h2>
                <p class="text-xs text-slate-400 mt-1">Daftar kontak ganda (WhatsApp, atau Nama & Kota) terdeteksi. Satukan data secara otomatis untuk membersihkan database.</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-slate-800 gap-2 mb-6">
            <button onclick="switchTab('petshop')" id="tab-btn-petshop" class="px-5 py-3 text-sm font-bold border-b-2 border-amber-500 text-amber-400 transition-all">
                🏪 Petshop / Outlet ({{ count($petshopWaGroups) + count($petshopNameCityGroups) }})
            </button>
            <button onclick="switchTab('distributor')" id="tab-btn-distributor" class="px-5 py-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all">
                📦 Distributor ({{ count($distributorWaGroups) + count($distributorNameCityGroups) }})
            </button>
            <button onclick="switchTab('kurir')" id="tab-btn-kurir" class="px-5 py-3 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all">
                🛵 Kurir / Pengiriman ({{ count($courierWaGroups) + count($courierNameGroups) }})
            </button>
        </div>

        <!-- Tab 1: Petshop Duplicates -->
        <div id="tab-petshop" class="tab-content space-y-6">
            @if(count($petshopWaGroups) === 0 && count($petshopNameCityGroups) === 0)
                <div class="p-8 text-center text-slate-500 bg-slate-950/20 border border-slate-800/50 rounded-2xl">
                    🎉 Tidak ada data duplikasi Mitra maupun Non-Mitra Petshop terdeteksi. Database Anda bersih.
                </div>
            @endif

            @if(count($petshopWaGroups) > 0)
            <div>
                <h3 class="text-sm font-bold text-amber-500 mb-3">Duplikat Berdasarkan No WhatsApp</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($petshopWaGroups as $wa => $group)
                    <div class="bg-slate-950/40 border border-slate-800/80 p-5 rounded-2xl">
                        <div class="text-xs text-slate-400 font-semibold mb-3 flex items-center justify-between">
                            <span>WhatsApp: <strong class="text-white">{{ $wa }}</strong></span>
                            <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400">{{ $group->count() }} records ganda</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                            @foreach($group as $item)
                            <div class="p-3 bg-slate-900 border border-slate-800 rounded-xl">
                                <div class="font-bold text-white text-sm">{{ $item->nama_outlet }} <span class="text-[10px] text-slate-500 font-mono">#ID {{ $item->id }}</span></div>
                                <div class="text-xs text-slate-400 mt-1">📍 {{ $item->city->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $item->alamat_lengkap }}</div>
                                <div class="mt-2 flex items-center justify-between text-[10px]">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-800 text-slate-300">{{ $item->is_mitra ? 'MITRA' : 'NON-MITRA' }}</span>
                                    <span class="text-slate-400">Leads: <strong>{{ $item->leadRequests()->count() }}</strong></span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <form action="{{ route('admin.audit.merge') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3 pt-3 border-t border-slate-900">
                            @csrf
                            <input type="hidden" name="type" value="petshop">
                            <div class="text-xs font-bold text-slate-400 shrink-0">Satukan Group ini:</div>
                            <div class="flex flex-wrap items-center gap-2 w-full">
                                <select name="target_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Utama (Dipertahankan) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_outlet }} (ID {{ $item->id }} - {{ $item->is_mitra ? 'Mitra' : 'Non-mitra' }} - Leads: {{ $item->leadRequests()->count() }})</option>
                                    @endforeach
                                </select>
                                <span class="text-slate-500 text-xs">🔀</span>
                                <select name="duplicate_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Duplikat (Dilebur & Hapus) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_outlet }} (ID {{ $item->id }} - {{ $item->is_mitra ? 'Mitra' : 'Non-mitra' }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin melakukan merger kedua petshop ini? Seluruh data lead history akan digabungkan ke data Utama.')" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-4 py-2 rounded-lg transition-all ml-auto w-full sm:w-auto">
                                    Satukan Data
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($petshopNameCityGroups) > 0)
            <div class="pt-4 border-t border-slate-800/40">
                <h3 class="text-sm font-bold text-amber-500 mb-3">Duplikat Berdasarkan Nama & Kota</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($petshopNameCityGroups as $key => $group)
                    <div class="bg-slate-950/40 border border-slate-800/80 p-5 rounded-2xl">
                        <div class="text-xs text-slate-400 font-semibold mb-3 flex items-center justify-between">
                            <span>Nama & Kota: <strong class="text-white">{{ $group->first()->nama_outlet }} ({{ $group->first()->city->nama ?? '-' }})</strong></span>
                            <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400">{{ $group->count() }} records ganda</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                            @foreach($group as $item)
                            <div class="p-3 bg-slate-900 border border-slate-800 rounded-xl">
                                <div class="font-bold text-white text-sm">{{ $item->nama_outlet }} <span class="text-[10px] text-slate-500 font-mono">#ID {{ $item->id }}</span></div>
                                <div class="text-xs text-slate-400 mt-1">📞 {{ $item->whatsapp }}</div>
                                <div class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $item->alamat_lengkap }}</div>
                                <div class="mt-2 flex items-center justify-between text-[10px]">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-800 text-slate-300">{{ $item->is_mitra ? 'MITRA' : 'NON-MITRA' }}</span>
                                    <span class="text-slate-400">Leads: <strong>{{ $item->leadRequests()->count() }}</strong></span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <form action="{{ route('admin.audit.merge') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3 pt-3 border-t border-slate-900">
                            @csrf
                            <input type="hidden" name="type" value="petshop">
                            <div class="text-xs font-bold text-slate-400 shrink-0">Satukan Group ini:</div>
                            <div class="flex flex-wrap items-center gap-2 w-full">
                                <select name="target_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Utama (Dipertahankan) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_outlet }} (ID {{ $item->id }} - {{ $item->whatsapp }} - Leads: {{ $item->leadRequests()->count() }})</option>
                                    @endforeach
                                </select>
                                <span class="text-slate-500 text-xs">🔀</span>
                                <select name="duplicate_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Duplikat (Dilebur & Hapus) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_outlet }} (ID {{ $item->id }} - {{ $item->whatsapp }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin melakukan merger kedua petshop ini? Seluruh data lead history akan digabungkan ke data Utama.')" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-4 py-2 rounded-lg transition-all ml-auto w-full sm:w-auto">
                                    Satukan Data
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Tab 2: Distributor Duplicates -->
        <div id="tab-distributor" class="tab-content hidden space-y-6">
            @if(count($distributorWaGroups) === 0 && count($distributorNameCityGroups) === 0)
                <div class="p-8 text-center text-slate-500 bg-slate-950/20 border border-slate-800/50 rounded-2xl">
                    🎉 Tidak ada data duplikasi Distributor terdeteksi. Database Anda bersih.
                </div>
            @endif

            @if(count($distributorWaGroups) > 0)
            <div>
                <h3 class="text-sm font-bold text-amber-500 mb-3">Duplikat Berdasarkan No WhatsApp</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($distributorWaGroups as $wa => $group)
                    <div class="bg-slate-950/40 border border-slate-800/80 p-5 rounded-2xl">
                        <div class="text-xs text-slate-400 font-semibold mb-3 flex items-center justify-between">
                            <span>WhatsApp: <strong class="text-white">{{ $wa }}</strong></span>
                            <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400">{{ $group->count() }} records ganda</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                            @foreach($group as $item)
                            <div class="p-3 bg-slate-900 border border-slate-800 rounded-xl">
                                <div class="font-bold text-white text-sm">{{ $item->nama }} <span class="text-[10px] text-slate-500 font-mono">#ID {{ $item->id }}</span></div>
                                <div class="text-xs text-slate-400 mt-1">👤 PIC: {{ $item->pic }}</div>
                                <div class="text-xs text-slate-400 mt-1">📍 {{ $item->city->nama ?? '-' }}</div>
                                <div class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $item->alamat }}</div>
                            </div>
                            @endforeach
                        </div>
                        <form action="{{ route('admin.audit.merge') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3 pt-3 border-t border-slate-900">
                            @csrf
                            <input type="hidden" name="type" value="distributor">
                            <div class="text-xs font-bold text-slate-400 shrink-0">Satukan Group ini:</div>
                            <div class="flex flex-wrap items-center gap-2 w-full">
                                <select name="target_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Utama (Dipertahankan) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} (ID {{ $item->id }} - {{ $item->city->nama ?? '-' }})</option>
                                    @endforeach
                                </select>
                                <span class="text-slate-500 text-xs">🔀</span>
                                <select name="duplicate_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Duplikat (Dilebur & Hapus) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} (ID {{ $item->id }} - {{ $item->city->nama ?? '-' }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin melakukan merger distributor ini? Seluruh petshop yang terhubung dengan distributor duplikat akan dialihkan ke distributor Utama.')" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-4 py-2 rounded-lg transition-all ml-auto w-full sm:w-auto">
                                    Satukan Data
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($distributorNameCityGroups) > 0)
            <div class="pt-4 border-t border-slate-800/40">
                <h3 class="text-sm font-bold text-amber-500 mb-3">Duplikat Berdasarkan Nama & Kota</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($distributorNameCityGroups as $key => $group)
                    <div class="bg-slate-950/40 border border-slate-800/80 p-5 rounded-2xl">
                        <div class="text-xs text-slate-400 font-semibold mb-3 flex items-center justify-between">
                            <span>Nama & Kota: <strong class="text-white">{{ $group->first()->nama }} ({{ $group->first()->city->nama ?? '-' }})</strong></span>
                            <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400">{{ $group->count() }} records ganda</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                            @foreach($group as $item)
                            <div class="p-3 bg-slate-900 border border-slate-800 rounded-xl">
                                <div class="font-bold text-white text-sm">{{ $item->nama }} <span class="text-[10px] text-slate-500 font-mono">#ID {{ $item->id }}</span></div>
                                <div class="text-xs text-slate-400 mt-1">📞 {{ $item->whatsapp }}</div>
                                <div class="text-xs text-slate-400 mt-1">👤 PIC: {{ $item->pic }}</div>
                                <div class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $item->alamat }}</div>
                            </div>
                            @endforeach
                        </div>
                        <form action="{{ route('admin.audit.merge') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3 pt-3 border-t border-slate-900">
                            @csrf
                            <input type="hidden" name="type" value="distributor">
                            <div class="text-xs font-bold text-slate-400 shrink-0">Satukan Group ini:</div>
                            <div class="flex flex-wrap items-center gap-2 w-full">
                                <select name="target_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Utama (Dipertahankan) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} (ID {{ $item->id }} - {{ $item->whatsapp }})</option>
                                    @endforeach
                                </select>
                                <span class="text-slate-500 text-xs">🔀</span>
                                <select name="duplicate_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Duplikat (Dilebur & Hapus) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} (ID {{ $item->id }} - {{ $item->whatsapp }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin melakukan merger distributor ini? Seluruh petshop yang terhubung dengan distributor duplikat akan dialihkan ke distributor Utama.')" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-4 py-2 rounded-lg transition-all ml-auto w-full sm:w-auto">
                                    Satukan Data
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Tab 3: Courier Duplicates -->
        <div id="tab-kurir" class="tab-content hidden space-y-6">
            @if(count($courierWaGroups) === 0 && count($courierNameGroups) === 0)
                <div class="p-8 text-center text-slate-500 bg-slate-950/20 border border-slate-800/50 rounded-2xl">
                    🎉 Tidak ada data duplikasi Kontak Kurir / Shipping Contact terdeteksi. Database Anda bersih.
                </div>
            @endif

            @if(count($courierWaGroups) > 0)
            <div>
                <h3 class="text-sm font-bold text-amber-500 mb-3">Duplikat Berdasarkan No WhatsApp</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($courierWaGroups as $wa => $group)
                    <div class="bg-slate-950/40 border border-slate-800/80 p-5 rounded-2xl">
                        <div class="text-xs text-slate-400 font-semibold mb-3 flex items-center justify-between">
                            <span>WhatsApp: <strong class="text-white">{{ $wa }}</strong></span>
                            <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400">{{ $group->count() }} records ganda</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                            @foreach($group as $item)
                            <div class="p-3 bg-slate-900 border border-slate-800 rounded-xl">
                                <div class="font-bold text-white text-sm">{{ $item->nama }} <span class="text-[10px] text-slate-500 font-mono">#ID {{ $item->id }}</span></div>
                                <div class="text-xs text-slate-400 mt-1">🗒️ Keterangan: {{ $item->keterangan ?: '-' }}</div>
                            </div>
                            @endforeach
                        </div>
                        <form action="{{ route('admin.audit.merge') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3 pt-3 border-t border-slate-900">
                            @csrf
                            <input type="hidden" name="type" value="kurir">
                            <div class="text-xs font-bold text-slate-400 shrink-0">Satukan Group ini:</div>
                            <div class="flex flex-wrap items-center gap-2 w-full">
                                <select name="target_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Utama (Dipertahankan) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} (ID {{ $item->id }})</option>
                                    @endforeach
                                </select>
                                <span class="text-slate-500 text-xs">🔀</span>
                                <select name="duplicate_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Duplikat (Dilebur & Hapus) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} (ID {{ $item->id }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyatukan kurir ini? Hubungan kurir dengan petshop akan dipindahkan ke kurir Utama.')" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-4 py-2 rounded-lg transition-all ml-auto w-full sm:w-auto">
                                    Satukan Data
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($courierNameGroups) > 0)
            <div class="pt-4 border-t border-slate-800/40">
                <h3 class="text-sm font-bold text-amber-500 mb-3">Duplikat Berdasarkan Nama Kurir</h3>
                <div class="grid grid-cols-1 gap-4">
                    @foreach($courierNameGroups as $name => $group)
                    <div class="bg-slate-950/40 border border-slate-800/80 p-5 rounded-2xl">
                        <div class="text-xs text-slate-400 font-semibold mb-3 flex items-center justify-between">
                            <span>Nama Kurir: <strong class="text-white">{{ $name }}</strong></span>
                            <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400">{{ $group->count() }} records ganda</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                            @foreach($group as $item)
                            <div class="p-3 bg-slate-900 border border-slate-800 rounded-xl">
                                <div class="font-bold text-white text-sm">{{ $item->nama }} <span class="text-[10px] text-slate-500 font-mono">#ID {{ $item->id }}</span></div>
                                <div class="text-xs text-slate-400 mt-1">📞 {{ $item->whatsapp }}</div>
                                <div class="text-xs text-slate-500 mt-1">🗒️ Keterangan: {{ $item->keterangan ?: '-' }}</div>
                            </div>
                            @endforeach
                        </div>
                        <form action="{{ route('admin.audit.merge') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3 pt-3 border-t border-slate-900">
                            @csrf
                            <input type="hidden" name="type" value="kurir">
                            <div class="text-xs font-bold text-slate-400 shrink-0">Satukan Group ini:</div>
                            <div class="flex flex-wrap items-center gap-2 w-full">
                                <select name="target_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Utama (Dipertahankan) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} (ID {{ $item->id }} - {{ $item->whatsapp }})</option>
                                    @endforeach
                                </select>
                                <span class="text-slate-500 text-xs">🔀</span>
                                <select name="duplicate_id" required class="bg-slate-900 border border-slate-800 text-slate-300 text-xs rounded-lg p-2 focus:border-amber-500 focus:ring-0">
                                    <option value="">-- Pilih Duplikat (Dilebur & Hapus) --</option>
                                    @foreach($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} (ID {{ $item->id }} - {{ $item->whatsapp }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyatukan kurir ini? Hubungan kurir dengan petshop akan dipindahkan ke kurir Utama.')" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-4 py-2 rounded-lg transition-all ml-auto w-full sm:w-auto">
                                    Satukan Data
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function switchTab(type) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Remove active class from all buttons
        const tabs = ['petshop', 'distributor', 'kurir'];
        tabs.forEach(t => {
            const btn = document.getElementById('tab-btn-' + t);
            btn.classList.remove('border-amber-500', 'text-amber-400');
            btn.classList.add('border-transparent', 'text-slate-400');
        });

        // Show selected tab content
        document.getElementById('tab-' + type).classList.remove('hidden');
        
        // Add active style to selected button
        const activeBtn = document.getElementById('tab-btn-' + type);
        activeBtn.classList.remove('border-transparent', 'text-slate-400');
        activeBtn.classList.add('border-amber-500', 'text-amber-400');
    }
</script>
@endsection
