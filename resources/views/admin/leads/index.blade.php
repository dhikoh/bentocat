@extends('layouts.admin')

@section('title', 'Database Leads')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Database Leads & Calon Pembeli</h1>
            <p class="text-sm text-slate-400">Daftar calon pembeli terdaftar yang mencari outlet terdekat untuk membeli produk BentoCat.</p>
        </div>
        <a href="{{ route('admin.leads.export', request()->query()) }}" class="bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-emerald-500/10 transition-all flex items-center gap-2">
            <span>Ekspor CSV (Excel)</span> 📊
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl">
        <form action="{{ route('admin.leads.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Cari Customer</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau WhatsApp..." class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Filter Kota</label>
                <select name="city_id" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ $cityId == $city->id ? 'selected' : '' }}>{{ $city->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Filter Produk</label>
                <select name="product_id" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none font-semibold">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>{{ $product->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-4 flex justify-end gap-2 pt-2 border-t border-slate-800/60">
                @if($search || $cityId || $productId)
                    <a href="{{ route('admin.leads.index') }}" class="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-xs flex items-center justify-center font-semibold transition-all">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-6 py-2 rounded-xl text-xs font-semibold transition-all">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-slate-900/20 border border-slate-800/80 rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-500 uppercase bg-slate-900/40 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Customer / WhatsApp</th>
                        <th class="px-6 py-4">Produk / Varian</th>
                        <th class="px-6 py-4">Kota Tujuan (GPS)</th>
                        <th class="px-6 py-4 text-center">Interaksi WA</th>
                        <th class="px-6 py-4">Tanggal Masuk</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-slate-900/20">
                            <td class="px-6 py-4">
                                <span class="block font-semibold text-white">{{ $lead->customer->nama }}</span>
                                <a href="https://wa.me/{{ $lead->customer->formatted_whatsapp }}" target="_blank" class="text-xs text-slate-400 hover:underline hover:text-amber-500">
                                    💬 {{ $lead->customer->whatsapp }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="block font-medium text-slate-200 text-xs">{{ $lead->product->nama }}</span>
                                <span class="block text-[10px] text-slate-500">
                                    {{ $lead->varian_level_1 }} {{ $lead->varian_level_2 ? '• ' . $lead->varian_level_2 : '' }} {{ $lead->varian_level_3 ? '• ' . $lead->varian_level_3 : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="block text-slate-300">{{ $lead->city->nama }}</span>
                                <span class="block text-[10px] text-slate-500">Lokasi: {{ $lead->customer->kota ?: '-' }} (GPS)</span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-300">
                                <span class="px-2 py-0.5 rounded bg-slate-950 border border-slate-800 text-[11px] text-amber-500" title="Klik tombol WhatsApp">
                                    {{ $lead->actions_count }} Klik
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $lead->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2 items-center">
                                <a href="{{ route('admin.leads.show', $lead->id) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                    Detail
                                </a>
                                @if(Auth::user() && Auth::user()->role === 'superadmin')
                                    <form action="{{ route('admin.leads.destroy', $lead->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data lead ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white border border-rose-500/20 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500 italic">Tidak ditemukan data lead masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($leads->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                {{ $leads->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
