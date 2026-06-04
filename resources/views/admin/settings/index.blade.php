@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Pengaturan Website</h1>
        <p class="text-slate-400 mt-1">Kelola identitas situs, logo, favicon, informasi kontak, dan tautan sosial media resmi BentoCat.</p>
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

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Card 1: Identitas & Branding -->
        <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8 space-y-6">
            <h2 class="text-xl font-bold text-amber-400 border-b border-slate-800/80 pb-3 flex items-center gap-2">
                <span>🎨</span> Branding & Identitas Website
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Site Name -->
                <div class="space-y-2">
                    <label for="site_name" class="block text-xs font-bold text-slate-400 uppercase">Nama Website / Brand</label>
                    <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <!-- Site Description -->
                <div class="space-y-2 md:col-span-2">
                    <label for="site_description" class="block text-xs font-bold text-slate-400 uppercase">Deskripsi SEO (Meta Description)</label>
                    <textarea name="site_description" id="site_description" rows="3"
                              class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">{{ old('site_description', $settings['site_description']) }}</textarea>
                </div>

                <!-- Logo Upload -->
                <div class="space-y-4">
                    <label class="block text-xs font-bold text-slate-400 uppercase">Upload Logo Website</label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-center p-2 overflow-hidden shrink-0">
                            <img src="{{ asset($settings['site_logo']) }}" alt="Logo Preview" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="site_logo" id="site_logo" accept="image/*"
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer">
                            <p class="text-[10px] text-slate-500 mt-1">Format: PNG, JPG, JPEG, SVG. Maks 2MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Favicon Upload -->
                <div class="space-y-4">
                    <label class="block text-xs font-bold text-slate-400 uppercase">Upload Favicon / Icon</label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-center p-4 overflow-hidden shrink-0">
                            <img src="{{ asset($settings['site_favicon']) }}" alt="Favicon Preview" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="site_favicon" id="site_favicon" accept="image/*"
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer">
                            <p class="text-[10px] text-slate-500 mt-1">Format: ICO, PNG. Maks 512KB.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Kontak & Social Media -->
        <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8 space-y-6">
            <h2 class="text-xl font-bold text-amber-400 border-b border-slate-800/80 pb-3 flex items-center gap-2">
                <span>💬</span> Kontak & Sosial Media Resmi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- WhatsApp Support -->
                <div class="space-y-2">
                    <label for="contact_whatsapp" class="block text-xs font-bold text-slate-400 uppercase">WhatsApp Customer Service</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold">+</span>
                        <input type="text" name="contact_whatsapp" id="contact_whatsapp" placeholder="628123456789"
                               value="{{ old('contact_whatsapp', $settings['contact_whatsapp']) }}"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl pl-8 pr-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">Masukkan angka tanpa spasi atau tanda hubung, diawali kode negara (contoh: 6281234567890).</p>
                </div>

                <!-- Instagram -->
                <div class="space-y-2">
                    <label for="social_instagram" class="block text-xs font-bold text-slate-400 uppercase">Tautan Instagram</label>
                    <input type="url" name="social_instagram" id="social_instagram" placeholder="https://instagram.com/bentocat"
                           value="{{ old('social_instagram', $settings['social_instagram']) }}"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <!-- Facebook -->
                <div class="space-y-2">
                    <label for="social_facebook" class="block text-xs font-bold text-slate-400 uppercase">Tautan Facebook</label>
                    <input type="url" name="social_facebook" id="social_facebook" placeholder="https://facebook.com/bentocat"
                           value="{{ old('social_facebook', $settings['social_facebook']) }}"
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-3 rounded-2xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 cursor-pointer">
                <span>Simpan Perubahan</span> 💾
            </button>
        </div>
    </form>
</div>
@endsection
