@extends('layouts.admin')

@section('title', 'Detail Lead #' . $lead->id)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.leads.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Database Leads</a>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Detail Lead ID: #{{ $lead->id }}</h1>
            <p class="text-sm text-slate-400">Masuk pada {{ $lead->created_at->format('d M Y H:i:s') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="bg-slate-900 border border-slate-800 px-4 py-2 rounded-xl text-xs font-bold text-slate-300">
                Status: <span class="text-emerald-400 font-extrabold uppercase">Routed</span>
            </div>
            @if(Auth::user() && Auth::user()->role === 'superadmin')
                <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data lead ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-lg shadow-rose-500/10 transition-all">
                        Hapus Lead 🗑️
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Left: Customer Profile -->
        <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-3xl space-y-4">
            <h2 class="text-lg font-bold text-white border-b border-slate-850 pb-2">👤 Profil Customer</h2>
            
            <div class="space-y-3.5 text-sm">
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase">Nama Lengkap</span>
                    <span class="block text-white font-semibold text-base mt-0.5">{{ $lead->customer->nama }}</span>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase">Nomor WhatsApp</span>
                    <a href="https://wa.me/{{ $lead->customer->formatted_whatsapp }}" target="_blank" class="text-amber-400 hover:underline font-semibold block text-base mt-0.5">
                        💬 {{ $lead->customer->whatsapp }}
                    </a>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase">Alamat Rumah</span>
                    <span class="block text-slate-300 mt-0.5">{{ $lead->customer->alamat }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-500 uppercase">Provinsi (GPS Browser)</span>
                        <span class="block text-slate-300 mt-0.5">{{ $lead->customer->provinsi ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-500 uppercase">Kota (GPS Browser)</span>
                        <span class="block text-slate-300 mt-0.5">{{ $lead->customer->kota ?: '-' }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-bold text-slate-500 uppercase">Latitude (GPS)</span>
                        <span class="block text-slate-400 font-mono text-xs mt-0.5">{{ $lead->customer->latitude ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-slate-500 uppercase">Longitude (GPS)</span>
                        <span class="block text-slate-400 font-mono text-xs mt-0.5">{{ $lead->customer->longitude ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Product Selection & Allocated Outlets -->
        <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-3xl space-y-4">
            <h2 class="text-lg font-bold text-white border-b border-slate-850 pb-2">📦 Produk & Routing Toko</h2>
            
            <div class="space-y-4 text-sm">
                <!-- Product Selection -->
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase">Produk yang Dicari</span>
                    <span class="block text-white font-semibold text-base mt-0.5">{{ $lead->product->nama }}</span>
                    <span class="block text-[11px] text-slate-400 mt-0.5">
                        Kategori: <strong class="text-amber-500">{{ $lead->varian_level_1 }}</strong>
                        @if($lead->varian_level_2) • Aroma: <strong class="text-blue-400">{{ $lead->varian_level_2 }}</strong> @endif
                        @if($lead->varian_level_3) • Ukuran: <strong class="text-violet-400">{{ $lead->varian_level_3 }}</strong> @endif
                    </span>
                </div>

                <!-- Allocated Outlet -->
                <div class="bg-slate-950/60 p-3 rounded-xl border border-slate-850">
                    <span class="block text-[10px] font-bold text-slate-500 uppercase">Petshop Outlet Teralokasi</span>
                    <span class="block text-white font-bold text-sm mt-0.5">{{ $lead->outlet->nama_outlet }}</span>
                    <span class="block text-[11px] text-slate-400 mt-1">Kota: {{ $lead->city->nama }}</span>
                    <span class="block text-[11px] text-slate-400">WA Toko: {{ $lead->outlet->whatsapp }}</span>
                </div>

                <!-- Allocated Distributor -->
                <div class="bg-slate-950/60 p-3 rounded-xl border border-slate-850">
                    <span class="block text-[10px] font-bold text-slate-500 uppercase">Distributor Penanggung Jawab Wilayah</span>
                    <span class="block text-slate-300 font-semibold text-xs mt-0.5">{{ $lead->distributor->nama }}</span>
                    <span class="block text-[11px] text-slate-400 mt-0.5">PIC: {{ $lead->distributor->pic }} | WA: {{ $lead->distributor->whatsapp }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Action / Logs (Activity Tracker) -->
    <div class="bg-slate-900/20 border border-slate-800/80 p-6 rounded-3xl">
        <h2 class="text-lg font-bold text-white mb-4">⏱️ Log Aktivitas Interaksi Customer</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-500 uppercase bg-slate-900/40 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-3">Waktu Kejadian</th>
                        <th class="px-6 py-3">Jenis Tindakan</th>
                        <th class="px-6 py-3">Keterangan Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($lead->actions as $action)
                        <tr class="hover:bg-slate-900/20">
                            <td class="px-6 py-3 text-xs text-slate-400">
                                {{ $action->created_at ? $action->created_at->format('d M Y H:i:s') : '-' }}
                            </td>
                            <td class="px-6 py-3">
                                @if($action->action_type === 'CLICK_WA_OUTLET')
                                    <span class="px-2 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase">Click WA Outlet</span>
                                @elseif($action->action_type === 'CLICK_WA_SHIPPING_CONTACT')
                                    <span class="px-2 py-0.5 rounded bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase">Click WA Kurir</span>
                                @elseif($action->action_type === 'VIEW_OUTLET')
                                    <span class="px-2 py-0.5 rounded bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[10px] font-bold uppercase">View Outlet</span>
                                @elseif($action->action_type === 'VIEW_SHIPPING_CONTACT')
                                    <span class="px-2 py-0.5 rounded bg-purple-500/10 border border-purple-500/20 text-purple-400 text-[10px] font-bold uppercase">View Kurir</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-slate-800 border border-slate-700 text-slate-300 text-[10px] font-bold uppercase">Interaksi Lain</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-xs text-slate-350">
                                @if($action->action_type === 'CLICK_WA_OUTLET')
                                    Membuka obrolan WhatsApp menuju outlet {{ $lead->outlet->nama_outlet ?? 'Toko' }}.
                                @elseif($action->action_type === 'CLICK_WA_SHIPPING_CONTACT')
                                    Membuka obrolan WhatsApp menuju kurir rekomendasi mitra outlet.
                                @elseif($action->action_type === 'VIEW_OUTLET')
                                    Menampilkan daftar outlet petshop terdekat di halaman pencarian.
                                @elseif($action->action_type === 'VIEW_SHIPPING_CONTACT')
                                    Melihat informasi kurir pengiriman mitra outlet.
                                @else
                                    Interaksi tidak terdefinisi.
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-6 text-center text-slate-500 italic">Belum ada rekaman klik interaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
