@extends('layouts.admin')

@section('title', 'Tambah Kontak Pengiriman')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.shipping-contacts.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Kembali</a>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Kontak Pengiriman Baru</h1>
        <p class="text-sm text-slate-400">Daftarkan kurir lokal, ojek online, atau jasa kirim barang wilayah untuk direkomendasikan kepada pembeli.</p>
    </div>

    <div class="bg-slate-900/40 border border-slate-800/80 p-6 md:p-8 rounded-3xl backdrop-blur-md">
        <form action="{{ route('admin.shipping-contacts.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Ekspedisi / Kurir</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           placeholder="Contoh: Kurir Instan Mas Joko Sidoarjo" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="whatsapp" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nomor WhatsApp Kurir</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" required 
                           placeholder="Contoh: 628123456789" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                    <span class="block text-[10px] text-slate-500 mt-1">Gunakan kode negara (628...).</span>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-bold text-slate-400 uppercase mb-2">Keterangan Jangkauan & Tarif (Opsional)</label>
                <textarea name="keterangan" id="keterangan" rows="4" 
                          placeholder="Contoh: Melayani pengiriman area Waru, Sedati, Juanda. Tarif flat Rp10.000 s/d 5km." 
                          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">{{ old('keterangan') }}</textarea>
            </div>

            <div class="border-t border-slate-800/80 pt-6 flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-300 select-none cursor-pointer">
                    <input type="checkbox" name="aktif" value="1" checked class="rounded border-slate-850 text-amber-500 bg-slate-950">
                    Kurir Berstatus Aktif
                </label>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.shipping-contacts.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/10 transition-all">
                        Simpan Kurir
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
