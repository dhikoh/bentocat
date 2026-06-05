@extends('layouts.admin')

@section('title', 'Tambah Pelanggan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.customers.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Kembali</a>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Pelanggan Baru</h1>
        <p class="text-sm text-slate-400">Daftarkan profil pelanggan baru secara manual ke dalam database CRM.</p>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-slate-900/40 border border-slate-800/80 p-6 md:p-8 rounded-3xl backdrop-blur-md">
        <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Pelanggan</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           placeholder="Contoh: Budi Santoso" 
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
                <label for="alamat" class="block text-xs font-bold text-slate-400 uppercase mb-2">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" rows="3" required 
                          placeholder="Tuliskan alamat lengkap rumah pelanggan..." 
                          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">{{ old('alamat') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="provinsi" class="block text-xs font-bold text-slate-400 uppercase mb-2">Provinsi (Opsional)</label>
                    <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi') }}" 
                           placeholder="Contoh: Jawa Barat" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="kota" class="block text-xs font-bold text-slate-400 uppercase mb-2">Kota (Opsional)</label>
                    <input type="text" name="kota" id="kota" value="{{ old('kota') }}" 
                           placeholder="Contoh: Bandung" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="latitude" class="block text-xs font-bold text-slate-400 uppercase mb-2">Latitude (Opsional)</label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" 
                           placeholder="Contoh: -6.917464" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="longitude" class="block text-xs font-bold text-slate-400 uppercase mb-2">Longitude (Opsional)</label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" 
                           placeholder="Contoh: 107.619122" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>
            </div>

            <div class="border-t border-slate-800/80 pt-6 flex justify-end gap-3">
                <a href="{{ route('admin.customers.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-350 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                    Batal
                </a>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/10 transition-all">
                    Simpan Pelanggan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
