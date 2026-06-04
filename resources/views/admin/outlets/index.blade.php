@extends('layouts.admin')

@section('title', 'Kelola Petshop & Outlet')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Kelola Outlet / Petshop Terdekat</h1>
            <p class="text-sm text-slate-400">Daftar mitra toko retail (petshop) yang menyediakan stok BentoCat maupun toko prospek.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.outlets.export') }}" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white font-bold px-4 py-2.5 rounded-xl shadow-lg transition-all flex items-center gap-2 text-sm">
                <span>Ekspor CSV</span> 📥
            </a>
            <a href="{{ route('admin.outlets.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 text-sm">
                <span>Tambah Outlet</span> 🐾
            </a>
        </div>
    </div>
 
    <!-- Summary Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Distributor Penyuplai</span>
                <span class="block text-2xl font-extrabold text-white mt-1">{{ $countDistributors }}</span>
            </div>
            <span class="text-2xl bg-blue-500/10 p-3 rounded-xl border border-blue-500/20 text-blue-400">🏢</span>
        </div>
        
        <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Petshop Mitra Resmi</span>
                <span class="block text-2xl font-extrabold text-white mt-1">{{ $countMitra }}</span>
            </div>
            <span class="text-2xl bg-amber-500/10 p-3 rounded-xl border border-amber-500/20 text-amber-400">🛡️</span>
        </div>

        <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Petshop Non-Mitra</span>
                <span class="block text-2xl font-extrabold text-white mt-1">{{ $countNonMitra }}</span>
            </div>
            <span class="text-2xl bg-slate-800 p-3 rounded-xl border border-slate-700 text-slate-400">🏪</span>
        </div>
    </div>

    <!-- CSV Bulk Import Panel -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl">
        <h2 class="text-md font-bold text-white mb-2 flex items-center gap-2">
            <span>Impor Bulk Petshop dari CSV</span>
            <span class="text-xs text-amber-500 font-normal">(Mencegah Duplikasi Otomatis berdasarkan WhatsApp / Nama & Kota)</span>
        </h2>
        <form action="{{ route('admin.outlets.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-end md:items-center gap-4">
            @csrf
            <div class="flex-1 w-full">
                <input type="file" name="csv_file" required class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-750 border border-slate-800 bg-slate-950 rounded-xl px-3 py-2 text-slate-300 focus:outline-none focus:border-amber-500">
            </div>
            <button type="submit" class="w-full md:w-auto bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-6 py-2.5 rounded-xl shadow-lg text-sm transition-all whitespace-nowrap">
                Unggah & Proses 🚀
            </button>
        </form>
        <p class="text-[11px] text-slate-500 mt-2">
            Format CSV: <strong>Nama Petshop, Alamat, No WA, Mitra, Kota, Distributor, Kurir</strong> (Pisahkan kurir dengan koma, contoh: <code>Kurir A (0812345678)</code>).
        </p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-4 rounded-2xl">
        <form action="{{ route('admin.outlets.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama outlet, PIC, kota, atau distributor..." class="flex-1 bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none transition-all">
            
            <select name="is_mitra" class="bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-300 focus:outline-none transition-all">
                <option value="">Semua Hubungan</option>
                <option value="1" {{ $isMitra === '1' ? 'selected' : '' }}>Mitra Resmi BentoCat</option>
                <option value="0" {{ $isMitra === '0' ? 'selected' : '' }}>Toko Terdaftar (Non-Mitra)</option>
            </select>

            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2 rounded-xl text-sm transition-all">
                Cari
            </button>
            @if($search || $isMitra !== null)
                <a href="{{ route('admin.outlets.index') }}" class="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm flex items-center justify-center transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Grid / Table -->
    <div class="bg-slate-900/20 border border-slate-800/80 rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-500 uppercase bg-slate-900/40 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Outlet / Kota</th>
                        <th class="px-6 py-4 text-center">Status Mitra</th>
                        <th class="px-6 py-4">Distributor Penyuplai</th>
                        <th class="px-6 py-4">PIC / WhatsApp</th>
                        <th class="px-6 py-4 text-center">Metode Pengiriman</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($outlets as $outlet)
                        <tr class="hover:bg-slate-900/20">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-semibold text-white">{{ $outlet->nama_outlet }}</span>
                                    @if($outlet->featured)
                                        <span class="text-xs" title="Featured Outlet">⭐️</span>
                                    @endif
                                </div>
                                <span class="block text-xs text-amber-500">{{ $outlet->city->nama }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($outlet->is_mitra)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-500/10 border border-amber-500/30 text-amber-400">
                                        🛡️ Mitra Resmi
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-800 border border-slate-700 text-slate-400">
                                        Non-Mitra
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $outlet->distributor->nama }}
                             </td>
                            <td class="px-6 py-4">
                                <span class="block font-medium text-slate-200">{{ $outlet->nama_pic }}</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $outlet->whatsapp) }}" target="_blank" class="text-xs text-slate-400 hover:underline hover:text-amber-500 flex items-center gap-1">
                                    💬 {{ $outlet->whatsapp }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-slate-400">
                                @if($outlet->delivery_mode === 'SELF_DELIVERY')
                                    <span class="text-emerald-400 font-medium">🛵 Pengiriman Sendiri</span>
                                @elseif($outlet->delivery_mode === 'RECOMMENDED_SHIPPING_CONTACT')
                                    <span class="text-blue-400 font-medium">📋 Kurir Rekomendasi</span>
                                @else
                                    <span class="text-slate-500 font-medium">🚶 Ambil di Toko</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($outlet->status === 'AKTIF')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase">Aktif</span>
                                @elseif($outlet->status === 'STOK_KOSONG')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400 uppercase">Stok Habis</span>
                                @elseif($outlet->status === 'TUTUP')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 border border-slate-700 text-slate-500 uppercase">Tutup</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 border border-rose-500/20 text-rose-400 uppercase">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.outlets.edit', $outlet->id) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.outlets.destroy', $outlet->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus outlet ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500 italic">Tidak ditemukan outlet/petshop terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($outlets->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                {{ $outlets->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
