@extends('layouts.admin')

@section('title', 'Database Pelanggan (CRM)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Database Pelanggan BentoCat (CRM)</h1>
            <p class="text-sm text-slate-400">Daftar pelanggan (cat owners) yang melakukan pencarian outlet melalui website.</p>
        </div>
        <div class="flex items-center gap-3 self-start md:self-auto">
            <a href="{{ route('admin.customers.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 text-sm">
                <span>Tambah Pelanggan</span> ➕
            </a>
            <a href="{{ route('admin.customers.export', ['search' => $search]) }}" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-5 py-2.5 rounded-xl transition-all flex items-center gap-2 text-sm">
                <span>Ekspor CSV</span> 📥
            </a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-4 rounded-2xl">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pelanggan, nomor WA, alamat..." class="flex-1 bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none transition-all">
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2 rounded-xl text-sm transition-all">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.customers.index') }}" class="bg-slate-900 border border-slate-800 hover:bg-slate-800 text-slate-400 px-4 py-2 rounded-xl text-sm flex items-center justify-center transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-slate-900/20 border border-slate-800/80 rounded-3xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-500 uppercase bg-slate-900/40 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">Nomor WhatsApp</th>
                        <th class="px-6 py-4">Alamat Lengkap</th>
                        <th class="px-6 py-4">Lokasi (GPS)</th>
                        <th class="px-6 py-4">Total Leads (Interaksi)</th>
                        <th class="px-6 py-4">Tanggal Bergabung</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-900/20">
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $customer->nama }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="https://wa.me/{{ $customer->formatted_whatsapp }}" target="_blank" class="text-slate-300 hover:text-amber-500 font-medium hover:underline flex items-center gap-1.5">
                                    💬 {{ $customer->whatsapp }}
                                </a>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-xs text-slate-400" title="{{ $customer->alamat }}">
                                {{ $customer->alamat }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="block text-slate-300">{{ $customer->kota ?: '-' }}</span>
                                <span class="block text-slate-500 text-[10px]">{{ $customer->provinsi ?: '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-amber-500">
                                {{ $customer->lead_requests_count }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $customer->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="bg-slate-800 hover:bg-slate-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                    Edit
                                </a>
                                @if(Auth::user() && Auth::user()->role === 'superadmin')
                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Menghapus data pelanggan ini juga akan menghapus semua data leads dan log aktivitas terkait secara permanen. Lanjutkan?');">
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
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500 italic">Tidak ditemukan data pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($customers->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-900/10">
                {{ $customers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
