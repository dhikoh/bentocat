@extends('layouts.admin')

@section('title', 'Database Pelanggan (CRM)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Database Pelanggan BentoCat (CRM)</h1>
            <p class="text-sm text-slate-400">Daftar pelanggan (cat owners) yang melakukan pencarian outlet melalui website.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 self-start md:self-auto">
            @if(Auth::user() && Auth::user()->role === 'superadmin')
                <button type="button" onclick="openClearModal()" class="bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-450 font-bold px-4 py-2.5 rounded-xl shadow-lg transition-all flex items-center gap-2 text-sm">
                    <span>Kosongkan Data</span> 🗑️
                </button>
            @endif
            @if(Auth::user() && Auth::user()->role !== 'marketing')
            <a href="{{ route('admin.customers.create') }}" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 text-sm whitespace-nowrap">
                <span>Tambah Pelanggan</span> ➕
            </a>
            <a href="{{ route('admin.customers.export', ['search' => $search]) }}" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-5 py-2.5 rounded-xl transition-all flex items-center gap-2 text-sm whitespace-nowrap">
                <span>Ekspor CSV</span> 📥
            </a>
            @endif
        </div>
    </div>

    <!-- CSV Bulk Import Panel -->
    @if(Auth::user() && Auth::user()->role !== 'marketing')
    <div class="bg-slate-900/40 border border-slate-800/80 p-5 rounded-2xl">
        <h2 class="text-md font-bold text-white mb-2 flex items-center gap-2">
            <span>Impor Bulk Pelanggan dari CSV</span>
            <span class="text-xs text-amber-500 font-normal">(Mencegah Duplikasi Otomatis berdasarkan WhatsApp)</span>
        </h2>
        <form action="{{ route('admin.customers.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-end md:items-center gap-4">
            @csrf
            <div class="flex-1 w-full">
                <input type="file" name="csv_file" required class="block w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-750 border border-slate-800 bg-slate-950 rounded-xl px-3 py-2 text-slate-300 focus:outline-none focus:border-amber-500">
            </div>
            <button type="submit" class="w-full md:w-auto bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-6 py-2.5 rounded-xl shadow-lg text-sm transition-all whitespace-nowrap">
                Unggah & Proses 🚀
            </button>
        </form>
        <p class="text-[11px] text-slate-500 mt-2">
            Format CSV: <strong>Nama, WhatsApp, Alamat, Latitude, Longitude, Provinsi, Kota</strong> (Mendukung pemetaan kolom otomatis dari data ekspor).
        </p>
    </div>
    @endif

    <!-- Search & Filter -->
    <div class="bg-slate-900/40 border border-slate-800/80 p-4 rounded-2xl">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pelanggan, nomor WA, alamat..." class="flex-1 bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none transition-all">
            
            <select name="per_page" onchange="this.form.submit()" class="bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-300 focus:outline-none transition-all">
                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 Baris</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 Baris</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 Baris</option>
                <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500 Baris</option>
                <option value="all" {{ $perPage === 'all' ? 'selected' : '' }}>Semua Baris</option>
            </select>

            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2 rounded-xl text-sm transition-all">
                Cari
            </button>
            @if($search || $perPage != 15)
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
                        <th class="px-6 py-4">Follow-up Terakhir</th>
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
                            <td class="px-6 py-4">
                                @if($customer->latestMarketingLog)
                                    <div class="space-y-1">
                                        <div class="text-xs font-semibold text-white">
                                            {{ $customer->latestMarketingLog->log_date->format('d M Y') }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            Oleh: <span class="text-amber-400 font-semibold">{{ $customer->latestMarketingLog->user->name ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[9px] font-bold 
                                                @if($customer->latestMarketingLog->crm_stage === 'Hot') bg-rose-500/10 text-rose-400 border border-rose-500/20
                                                @elseif($customer->latestMarketingLog->crm_stage === 'Warm') bg-amber-500/10 text-amber-400 border border-amber-500/20
                                                @elseif($customer->latestMarketingLog->crm_stage === 'Closed-Won') bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                                @elseif($customer->latestMarketingLog->crm_stage === 'Closed-Lost') bg-slate-800 text-slate-500 border border-slate-700
                                                @else bg-sky-500/10 text-sky-400 border border-sky-500/20
                                                @endif">
                                                {{ $customer->latestMarketingLog->crm_stage }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-bold">
                                                {{ $customer->latestMarketingLog->potential_closing }}%
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500 italic">Belum di-followup</span>
                                @endif
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
                                @if(Auth::user() && Auth::user()->role !== 'marketing')
                                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="bg-slate-800 hover:bg-slate-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                    Edit
                                </a>
                                @endif
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
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500 italic">Tidak ditemukan data pelanggan.</td>
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

    <!-- Clear Data Modal -->
    @if(Auth::user() && Auth::user()->role === 'superadmin')
        <div id="clear-data-modal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-2xl max-w-md w-[90%] shadow-2xl space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>Hapus & Kosongkan Data Pelanggan</span> ⚠️
                    </h3>
                    <p class="text-sm text-slate-400 mt-1">Pilih jenis penghapusan data secara massal. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                
                <div class="space-y-2">
                    <form action="{{ route('admin.customers.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh data pelanggan yang TIDAK MEMILIKI riwayat leads/aktivitas?')">
                        @csrf
                        <input type="hidden" name="type" value="no-activity">
                        <button type="submit" class="w-full bg-slate-850 hover:bg-slate-800 border border-slate-700 text-slate-200 font-bold py-2.5 px-4 rounded-xl text-xs transition-all flex items-center justify-between text-left">
                            <span>Hapus Tanpa Aktivitas (0 Leads)</span>
                            <span class="text-[10px] text-rose-450 font-semibold px-2 py-0.5 rounded bg-rose-500/10 border border-rose-500/20">Hapus {{ $countNoActivityCustomers }} Data</span>
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.customers.clear') }}" method="POST" onsubmit="return confirm('PERINGATAN KERAS: Tindakan ini akan menghapus SELURUH data pelanggan CRM beserta data leads dan tindakan WhatsApp yang terhubung dengannya secara permanen!')">
                        @csrf
                        <input type="hidden" name="type" value="all">
                        <button type="submit" class="w-full bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-450 font-bold py-2.5 px-4 rounded-xl text-xs transition-all flex items-center justify-between text-left">
                            <span>Hapus Seluruh Data Pelanggan</span>
                            <span class="text-[10px] text-rose-450 font-semibold px-2 py-0.5 rounded bg-rose-500/25 border border-rose-500/30">Hapus {{ $countTotalCustomers }} Data</span>
                        </button>
                    </form>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" onclick="closeClearModal()" class="bg-slate-800 hover:bg-slate-750 text-slate-350 font-semibold px-4 py-2 rounded-xl text-xs transition-all">
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <script>
        function openClearModal() {
            const modal = document.getElementById('clear-data-modal');
            if (modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
            }
        }

        function closeClearModal() {
            const modal = document.getElementById('clear-data-modal');
            if (modal) {
                modal.classList.add('opacity-0', 'pointer-events-none');
            }
        }
        </script>
    @endif
</div>
@endsection
