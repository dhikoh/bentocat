@extends('layouts.admin')

@section('title', 'Tambah Template Prompt')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs text-amber-500 font-bold uppercase tracking-wider">
        <a href="{{ route('admin.prompt-generator.index') }}" class="hover:underline">Asisten Prompt</a>
        <span>&gt;</span>
        <a href="{{ route('admin.prompt-generator.templates.index') }}" class="hover:underline">Daftar Template</a>
        <span>&gt;</span>
        <span class="text-slate-400">Tambah</span>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Template Prompt Baru</h1>
        <p class="text-sm text-slate-400">Buat template prompt kustom dengan variabel dinamis untuk asisten marketing.</p>
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

    <div class="bg-slate-900/60 border border-slate-800 p-6 rounded-3xl backdrop-blur-md shadow-xl">
        <form action="{{ route('admin.prompt-generator.templates.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-semibold text-slate-300">Nama Template</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Penawaran B2B Petshop Baru" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label for="category" class="block text-sm font-semibold text-slate-300">Kategori</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}" required placeholder="Contoh: B2B Prospecting atau Customer Service" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Target Audience -->
                <div class="space-y-2">
                    <label for="target_audience" class="block text-sm font-semibold text-slate-300">Target Audiens Default</label>
                    <input type="text" id="target_audience" name="target_audience" value="{{ old('target_audience') }}" required placeholder="Contoh: Owner Petshop Mandiri" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <!-- Tone -->
                <div class="space-y-2">
                    <label for="tone" class="block text-sm font-semibold text-slate-300">Gaya Bahasa Default</label>
                    <input type="text" id="tone" name="tone" value="{{ old('tone') }}" required placeholder="Contoh: Sopan, Persuasif" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>
            </div>

            <!-- Placeholders -->
            <div class="space-y-2">
                <label for="placeholders" class="block text-sm font-semibold text-slate-300">Variabel Dinamis (Pisahkan dengan koma)</label>
                <input type="text" id="placeholders" name="placeholders" value="{{ old('placeholders') }}" placeholder="Contoh: nama_petshop,harga,bonus_tambahan" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                <p class="text-[11px] text-slate-500">Gunakan format snake_case tanpa spasi untuk nama variabel (contoh: <code>nama_petshop</code>). Variabel ini otomatis menjadi isian form pada saat membuat prompt.</p>
            </div>

            <!-- Base Prompt -->
            <div class="space-y-2">
                <label for="base_prompt" class="block text-sm font-semibold text-slate-300">Base Prompt Template</label>
                <textarea id="base_prompt" name="base_prompt" rows="8" required placeholder="Tulis instruksi utama template di sini. Gunakan {nama_variabel} untuk menyisipkan variabel dinamis di atas..." class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl p-4 text-sm text-slate-200 font-mono focus:outline-none transition-all">{{ old('base_prompt') }}</textarea>
                
                <!-- Tips -->
                <div class="p-3.5 bg-slate-950/50 border border-slate-800 rounded-xl text-xs text-slate-400 space-y-1">
                    <span class="font-bold text-amber-500">💡 Tips Penulisan:</span>
                    <p>Sertakan tanda kurung kurawal untuk menyisipkan variabel. Contoh:</p>
                    <p class="font-mono text-slate-300 text-[11px]">"Tawarkan produk BentoCat ke petshop bernama <strong>{nama_petshop}</strong> dengan harga grosir flat <strong>{harga}</strong>..."</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-800/80 pt-4">
                <a href="{{ route('admin.prompt-generator.templates.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold rounded-xl border border-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl shadow-lg shadow-amber-500/10 transition">
                    Simpan Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
