@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Kembali</a>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Produk Baru</h1>
        <p class="text-sm text-slate-400">Daftarkan produk BentoCat baru untuk memamerkan spesifikasi produk di website utama.</p>
    </div>

    <div class="bg-slate-900/40 border border-slate-800/80 p-6 md:p-8 rounded-3xl backdrop-blur-md">
        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Produk</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           placeholder="Contoh: BentoCat Premium Bentonite Cat Litter" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="thumbnail" class="block text-xs font-bold text-slate-400 uppercase mb-2">URL Gambar Thumbnail</label>
                    <input type="text" name="thumbnail" id="thumbnail" value="{{ old('thumbnail') }}" 
                           placeholder="Contoh: https://bentocat.com/img/litter-thumb.jpg" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-bold text-slate-400 uppercase mb-2">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi" rows="6" 
                          placeholder="Tuliskan spesifikasi produk, keunggulan gumpalan cepat, minim debu..." 
                          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="border-t border-slate-800/80 pt-6 flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-300 select-none cursor-pointer">
                    <span class="text-xs font-bold text-slate-400 uppercase mr-1">Status Publikasi:</span>
                    <select name="status" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-xs text-slate-200">
                        <option value="ACTIVE" selected>ACTIVE / TAMPILKAN</option>
                        <option value="INACTIVE">INACTIVE / SEMBUNYIKAN</option>
                    </select>
                </label>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.products.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/10 transition-all">
                        Simpan Produk
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
