@extends('layouts.admin')

@section('title', 'Tambah Aktivitas Harian')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.my-logs.index') }}" class="p-3 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 text-slate-300 rounded-2xl transition-all font-semibold">
            ⬅️ Kembali
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Tambah Log Aktivitas</h1>
            <p class="text-slate-400 mt-1">Catat detail pekerjaan yang telah Anda laksanakan hari ini.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-sm">
            <span class="font-bold block mb-1">Terjadi kesalahan validasi:</span>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8">
        <form action="{{ route('admin.my-logs.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="log_date" class="block text-xs font-bold text-slate-400 uppercase">Tanggal Kegiatan</label>
                <input type="date" name="log_date" id="log_date" value="{{ old('log_date', date('Y-m-d')) }}" required
                       class="w-full max-w-xs bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="outlet_id" class="block text-xs font-bold text-slate-400 uppercase">Outlet / Petshop yang Dikunjungi</label>
                    <div class="relative">
                        <input type="text" id="outlet_search" placeholder="🔍 Cari Petshop/Outlet..." 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none transition-all mb-2">
                        <select name="outlet_id" id="outlet_id" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                            <option value="">-- Tidak Ada / Tidak Melakukan Kunjungan --</option>
                            @foreach($outlets as $outlet)
                                <option value="{{ $outlet->id }}" {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>
                                    {{ $outlet->name }} ({{ $outlet->city->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="customer_profile_id" class="block text-xs font-bold text-slate-400 uppercase">Pelanggan yang Dihubungi (CRM)</label>
                    <div class="relative">
                        <input type="text" id="customer_search" placeholder="🔍 Cari Pelanggan..." 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none transition-all mb-2">
                        <select name="customer_profile_id" id="customer_profile_id" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                            <option value="">-- Tidak Ada / Tidak Menghubungi Pelanggan --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_profile_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama }} ({{ $customer->whatsapp }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label for="activity_title" class="block text-xs font-bold text-slate-400 uppercase">Judul Aktivitas / Pekerjaan</label>
                <input type="text" name="activity_title" id="activity_title" placeholder="Contoh: Kunjungan Petshop di Jakarta Selatan, Riset Kompetitor, dsb." value="{{ old('activity_title') }}" required
                       class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label for="activity_details" class="block text-xs font-bold text-slate-400 uppercase">Detail Kegiatan (Workflow & Hasil)</label>
                <textarea name="activity_details" id="activity_details" rows="6" placeholder="Tuliskan secara detail apa yang dikerjakan, siapa yang dihubungi, apa hasilnya, dan tantangan yang ditemui agar dapat dievaluasi oleh sistem AI." required
                          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">{{ old('activity_details') }}</textarea>
            </div>

            <div class="space-y-2">
                <label for="agenda" class="block text-xs font-bold text-slate-400 uppercase">Rencana Tindak Lanjut / Agenda Berikutnya</label>
                <textarea name="agenda" id="agenda" rows="3" placeholder="Tuliskan rencana, jadwal follow-up berikutnya, atau janji temu yang telah disepakati."
                          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">{{ old('agenda') }}</textarea>
            </div>

            <div class="flex items-center justify-between border-t border-slate-800 pt-6">
                <span class="text-xs text-slate-500">Isi dengan jujur untuk mempermudah evaluasi kinerja Anda.</span>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-3 rounded-2xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 cursor-pointer">
                    <span>Simpan Log</span> 💾
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const outletSearch = document.getElementById('outlet_search');
        const outletSelect = document.getElementById('outlet_id');
        const outletOptions = Array.from(outletSelect.options);

        outletSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            outletSelect.innerHTML = '';
            
            outletOptions.forEach(opt => {
                if (opt.value === '' || opt.text.toLowerCase().includes(query)) {
                    outletSelect.appendChild(opt);
                }
            });
        });

        const customerSearch = document.getElementById('customer_search');
        const customerSelect = document.getElementById('customer_profile_id');
        const customerOptions = Array.from(customerSelect.options);

        customerSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            customerSelect.innerHTML = '';
            
            customerOptions.forEach(opt => {
                if (opt.value === '' || opt.text.toLowerCase().includes(query)) {
                    customerSelect.appendChild(opt);
                }
            });
        });
    });
</script>
@endsection
