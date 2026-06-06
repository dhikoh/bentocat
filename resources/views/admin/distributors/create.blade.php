@extends('layouts.admin')

@section('title', 'Tambah Distributor')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.distributors.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Kembali</a>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Distributor Baru</h1>
        <p class="text-sm text-slate-400">Daftarkan distributor penyuplai produk BentoCat untuk mengelola sebaran stok petshop lokal.</p>
    </div>

    <div class="bg-slate-900/40 border border-slate-800/80 p-6 md:p-8 rounded-3xl backdrop-blur-md">
        <form action="{{ route('admin.distributors.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nama" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Distributor</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                       placeholder="Contoh: CV. Bento Lestari Abadi" 
                       class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="provinsi_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Provinsi Wilayah</label>
                    <select name="provinsi_id" id="provinsi_id" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ old('provinsi_id') == $province->id ? 'selected' : '' }}>
                                {{ $province->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kota_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Pilih Kota Wilayah</label>
                    <select name="kota_id" id="kota_id" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                        <option value="">-- Pilih Kota --</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('kota_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="pic" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama PIC (Penanggung Jawab)</label>
                    <input type="text" name="pic" id="pic" value="{{ old('pic') }}" required 
                           placeholder="Contoh: Herlambang" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="whatsapp" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nomor WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" required 
                           placeholder="Contoh: 628123456789" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                    <span class="block text-[10px] text-slate-500 mt-1">Gunakan kode negara (misal: 62812...) tanpa spasi atau tanda +.</span>
                </div>
            </div>

            <div>
                <label for="alamat" class="block text-xs font-bold text-slate-400 uppercase mb-2">Alamat Lengkap Kantor / Gudang</label>
                <textarea name="alamat" id="alamat" rows="4" required 
                          placeholder="Tuliskan alamat lengkap jalan, nomor gudang, RT/RW..." 
                          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">{{ old('alamat') }}</textarea>
            </div>

            <div class="border-t border-slate-800/80 pt-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 text-sm text-slate-300 select-none cursor-pointer">
                        <input type="checkbox" name="tampil_ke_publik" value="1" checked class="rounded border-slate-850 text-amber-500 bg-slate-950">
                        Tampilkan Publik
                    </label>

                    <label class="flex items-center gap-2 text-sm text-slate-300 select-none cursor-pointer">
                        <span class="text-xs font-bold text-slate-400 uppercase mr-1">Status:</span>
                        <select name="status" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-xs text-slate-200">
                            <option value="ACTIVE" selected>ACTIVE / AKTIF</option>
                            <option value="INACTIVE">INACTIVE / NONAKTIF</option>
                        </select>
                    </label>
                </div>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.distributors.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/10 transition-all">
                        Simpan Distributor
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('provinsi_id').addEventListener('change', function() {
            const provinceId = this.value;
            const citySelect = document.getElementById('kota_id');
            citySelect.innerHTML = '<option value="">-- Loading Kota --</option>';
            if (!provinceId) {
                citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
                return;
            }
            
            fetch(`/api/cities-by-province/${provinceId}?admin=1`)
                .then(res => res.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.nama;
                        citySelect.appendChild(option);
                    });
                })
                .catch(err => {
                    console.error(err);
                    citySelect.innerHTML = '<option value="">-- Gagal memuat kota --</option>';
                });
        });
    });
</script>
@endsection
