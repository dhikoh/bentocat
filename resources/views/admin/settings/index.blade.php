@extends('layouts.admin')

@section('title', 'Pengaturan Website')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Pengaturan Website</h1>
        <p class="text-slate-400 mt-1">Kelola identitas situs, konten hero, media banner, produk melayang, dan tautan sosial media resmi BentoCat.</p>
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

    @if (session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-slate-800 pb-px">
        <button type="button" onclick="switchTab('branding')" id="tab-btn-branding" class="tab-btn px-5 py-3 text-sm font-bold rounded-t-2xl border-b-2 transition-all cursor-pointer text-amber-500 border-amber-500">
            🎨 Branding & SEO
        </button>
        <button type="button" onclick="switchTab('hero-text')" id="tab-btn-hero-text" class="tab-btn px-5 py-3 text-sm font-bold rounded-t-2xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all cursor-pointer">
            📝 Teks Hero
        </button>
        <button type="button" onclick="switchTab('hero-media')" id="tab-btn-hero-media" class="tab-btn px-5 py-3 text-sm font-bold rounded-t-2xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all cursor-pointer">
            🎬 Media & CTA
        </button>
        <button type="button" onclick="switchTab('widgets')" id="tab-btn-widgets" class="tab-btn px-5 py-3 text-sm font-bold rounded-t-2xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all cursor-pointer">
            ✨ Floating Widgets & Produk
        </button>
        <button type="button" onclick="switchTab('contact')" id="tab-btn-contact" class="tab-btn px-5 py-3 text-sm font-bold rounded-t-2xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all cursor-pointer">
            💬 Kontak & Medsos
        </button>
        <button type="button" onclick="switchTab('features')" id="tab-btn-features" class="tab-btn px-5 py-3 text-sm font-bold rounded-t-2xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all cursor-pointer">
            ⭐ Fitur Keunggulan
        </button>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Tab 1: Branding & SEO -->
        <div id="tab-pane-branding" class="tab-pane space-y-6">
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-xl font-bold text-amber-400 pb-1 flex items-center gap-2">
                    <span>🎨</span> Branding & Identitas Website
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="site_name" class="block text-xs font-bold text-slate-400 uppercase">Nama Website / Brand</label>
                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label for="site_description" class="block text-xs font-bold text-slate-400 uppercase">Deskripsi SEO (Meta Description)</label>
                        <textarea name="site_description" id="site_description" rows="3"
                                  class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">{{ old('site_description', $settings['site_description']) }}</textarea>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Upload Logo Website</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-center p-2 overflow-hidden shrink-0">
                                <img id="site_logo_preview" src="{{ asset($settings['site_logo']) }}" alt="Logo Preview" class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1">
                                <input type="file" name="site_logo" id="site_logo" accept="image/*" onchange="previewImage(this, 'site_logo_preview')"
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer">
                                <p class="text-[10px] text-slate-500 mt-1">Format: PNG, JPG, JPEG, SVG. Maks 20MB.</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Upload Favicon / Icon</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-center p-4 overflow-hidden shrink-0">
                                <img id="site_favicon_preview" src="{{ asset($settings['site_favicon']) }}" alt="Favicon Preview" class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1">
                                <input type="file" name="site_favicon" id="site_favicon" accept="image/*" onchange="previewImage(this, 'site_favicon_preview')"
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer">
                                <p class="text-[10px] text-slate-500 mt-1">Format: ICO, PNG. Maks 512KB.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Hero Text -->
        <div id="tab-pane-hero-text" class="tab-pane hidden space-y-6">
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-xl font-bold text-amber-400 pb-1 flex items-center gap-2">
                    <span>📝</span> Teks Hero Banner Utama
                </h2>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label for="hero_badge_text" class="block text-xs font-bold text-slate-400 uppercase">Teks Lencana Mini (Tagline / Badge atas)</label>
                        <input type="text" name="hero_badge_text" id="hero_badge_text" value="{{ old('hero_badge_text', $settings['hero_badge_text']) }}" required
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label for="hero_title" class="block text-xs font-bold text-slate-400 uppercase">Judul Utama Hero (Heading H1)</label>
                        <input type="text" name="hero_title" id="hero_title" value="{{ old('hero_title', $settings['hero_title']) }}" required
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label for="hero_subtitle" class="block text-xs font-bold text-slate-400 uppercase">Deskripsi/Subtitle Hero</label>
                        <textarea name="hero_subtitle" id="hero_subtitle" rows="3" required
                                  class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">{{ old('hero_subtitle', $settings['hero_subtitle']) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Hero Media & CTA -->
        <div id="tab-pane-hero-media" class="tab-pane hidden space-y-6">
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-xl font-bold text-amber-400 pb-1 flex items-center gap-2">
                    <span>🎬</span> Media Interaktif & Tombol Aksi
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="hero_media_type" class="block text-xs font-bold text-slate-400 uppercase">Tipe Media Hero</label>
                        <select name="hero_media_type" id="hero_media_type" onchange="toggleMediaField(this.value)"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                            <option value="image" {{ $settings['hero_media_type'] === 'image' ? 'selected' : '' }}>Gambar Statis</option>
                            <option value="video" {{ $settings['hero_media_type'] === 'video' ? 'selected' : '' }}>Video Pendek (Looping)</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Berkas Media Hero (Gambar/Video)</label>
                        <input type="file" name="hero_media_path" id="hero_media_path" onchange="previewHeroMedia(this)"
                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-1">Maksimal 50MB. Format yang didukung: PNG, JPG, JPEG, SVG, MP4, WEBM.</p>
                    </div>

                    <!-- Media Preview Box -->
                    <div class="md:col-span-2 space-y-2">
                        <span class="block text-xs font-bold text-slate-400 uppercase">Preview Media Aktif</span>
                        <div class="w-full max-w-md h-60 rounded-3xl bg-slate-950 border border-slate-800 flex items-center justify-center p-2 overflow-hidden relative">
                            <img id="media_image_preview" src="{{ asset($settings['hero_media_path']) }}" class="w-full h-full object-cover rounded-2xl {{ $settings['hero_media_type'] === 'video' ? 'hidden' : '' }}">
                            <video id="media_video_preview" src="{{ asset($settings['hero_media_path']) }}" muted loop autoplay playsinline class="w-full h-full object-cover rounded-2xl {{ $settings['hero_media_type'] === 'image' ? 'hidden' : '' }}"></video>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="cta_primary_text" class="block text-xs font-bold text-slate-400 uppercase">Teks Tombol Utama (Primary CTA)</label>
                        <input type="text" name="cta_primary_text" id="cta_primary_text" value="{{ old('cta_primary_text', $settings['cta_primary_text']) }}" required
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="cta_secondary_text" class="block text-xs font-bold text-slate-400 uppercase">Teks Tombol Kedua (Secondary CTA)</label>
                        <input type="text" name="cta_secondary_text" id="cta_secondary_text" value="{{ old('cta_secondary_text', $settings['cta_secondary_text']) }}" required
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 4: Floating Widgets -->
        <div id="tab-pane-widgets" class="tab-pane hidden space-y-6">
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-xl font-bold text-amber-400 pb-1 flex items-center gap-2">
                    <span>✨</span> Floating Widgets & Kartu Produk Melayang
                </h2>
                
                <div class="border-b border-slate-800/80 pb-4 space-y-4">
                    <h3 class="text-sm font-bold text-slate-300">📦 Detail Produk Melayang (Floating Product Box)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="hero_product_title" class="block text-xs font-bold text-slate-400 uppercase">Nama Produk Utama</label>
                            <input type="text" name="hero_product_title" id="hero_product_title" value="{{ old('hero_product_title', $settings['hero_product_title']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="hero_product_desc" class="block text-xs font-bold text-slate-400 uppercase">Tag / Deskripsi Singkat Produk</label>
                            <input type="text" name="hero_product_desc" id="hero_product_desc" value="{{ old('hero_product_desc', $settings['hero_product_desc']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-4 md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase">Upload Gambar Kemasan Produk</label>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-center p-2 overflow-hidden shrink-0">
                                    <img id="hero_product_image_preview" src="{{ asset($settings['hero_product_image']) }}" alt="Product Preview" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="hero_product_image" id="hero_product_image" accept="image/*" onchange="previewImage(this, 'hero_product_image_preview')"
                                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 file:cursor-pointer">
                                    <p class="text-[10px] text-slate-500 mt-1">Format: PNG, JPG, JPEG, SVG. Maks 10MB.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-300">🎖️ Lencana Melayang (Floating Badges)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="hero_badge_1_text" class="block text-xs font-bold text-slate-400 uppercase">Lencana 1 (Kiri Atas - Teks Singkat)</label>
                            <input type="text" name="hero_badge_1_text" id="hero_badge_1_text" value="{{ old('hero_badge_1_text', $settings['hero_badge_1_text']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="hero_badge_2_text" class="block text-xs font-bold text-slate-400 uppercase">Lencana 2 (Kanan Tengah - Teks Singkat)</label>
                            <input type="text" name="hero_badge_2_text" id="hero_badge_2_text" value="{{ old('hero_badge_2_text', $settings['hero_badge_2_text']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="hero_badge_3_title" class="block text-xs font-bold text-slate-400 uppercase">Lencana 3 (Kanan Bawah - Judul)</label>
                            <input type="text" name="hero_badge_3_title" id="hero_badge_3_title" value="{{ old('hero_badge_3_title', $settings['hero_badge_3_title']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="hero_badge_3_desc" class="block text-xs font-bold text-slate-400 uppercase">Lencana 3 (Kanan Bawah - Deskripsi)</label>
                            <input type="text" name="hero_badge_3_desc" id="hero_badge_3_desc" value="{{ old('hero_badge_3_desc', $settings['hero_badge_3_desc']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 5: Contact & Medsos -->
        <div id="tab-pane-contact" class="tab-pane hidden space-y-6">
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-xl font-bold text-amber-400 pb-1 flex items-center gap-2">
                    <span>💬</span> Kontak & Sosial Media Resmi
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="contact_whatsapp" class="block text-xs font-bold text-slate-400 uppercase">WhatsApp Customer Service</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm font-semibold">+</span>
                            <input type="text" name="contact_whatsapp" id="contact_whatsapp" placeholder="628123456789"
                                   value="{{ old('contact_whatsapp', $settings['contact_whatsapp']) }}"
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl pl-8 pr-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">Diawali kode negara tanpa tanda "+" (contoh: 6287777717300).</p>
                    </div>
                    <div class="space-y-2">
                        <label for="social_instagram" class="block text-xs font-bold text-slate-400 uppercase">Tautan Instagram</label>
                        <input type="url" name="social_instagram" id="social_instagram" placeholder="https://instagram.com/bentocat"
                               value="{{ old('social_instagram', $settings['social_instagram']) }}"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label for="social_facebook" class="block text-xs font-bold text-slate-400 uppercase">Tautan Facebook</label>
                        <input type="url" name="social_facebook" id="social_facebook" placeholder="https://facebook.com/bentocat"
                               value="{{ old('social_facebook', $settings['social_facebook']) }}"
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 6: Fitur Keunggulan -->
        <div id="tab-pane-features" class="tab-pane hidden space-y-6">
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 md:p-8 space-y-6">
                <h2 class="text-xl font-bold text-amber-400 pb-1 flex items-center gap-2">
                    <span>⭐</span> Tiga Fitur Keunggulan Premium
                </h2>
                
                <!-- Fitur 1 -->
                <div class="border-b border-slate-800/80 pb-6 space-y-4">
                    <h3 class="text-sm font-bold text-slate-300">⚡ Fitur 1 (Molecular Bonding)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="feature_1_icon" class="block text-xs font-bold text-slate-400 uppercase">Ikon / Emoji</label>
                            <input type="text" name="feature_1_icon" id="feature_1_icon" value="{{ old('feature_1_icon', $settings['feature_1_icon']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label for="feature_1_title" class="block text-xs font-bold text-slate-400 uppercase">Judul Fitur</label>
                            <input type="text" name="feature_1_title" id="feature_1_title" value="{{ old('feature_1_title', $settings['feature_1_title']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2 md:col-span-3">
                            <label for="feature_1_desc" class="block text-xs font-bold text-slate-400 uppercase">Deskripsi Fitur (Mendukung HTML seperti &lt;strong&gt; atau &lt;b&gt;)</label>
                            <textarea name="feature_1_desc" id="feature_1_desc" rows="2" required
                                      class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">{{ old('feature_1_desc', $settings['feature_1_desc']) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Fitur 2 -->
                <div class="border-b border-slate-800/80 pb-6 space-y-4">
                    <h3 class="text-sm font-bold text-slate-300">🌸 Fitur 2 (Zero-Dust Tech)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="feature_2_icon" class="block text-xs font-bold text-slate-400 uppercase">Ikon / Emoji</label>
                            <input type="text" name="feature_2_icon" id="feature_2_icon" value="{{ old('feature_2_icon', $settings['feature_2_icon']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label for="feature_2_title" class="block text-xs font-bold text-slate-400 uppercase">Judul Fitur</label>
                            <input type="text" name="feature_2_title" id="feature_2_title" value="{{ old('feature_2_title', $settings['feature_2_title']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2 md:col-span-3">
                            <label for="feature_2_desc" class="block text-xs font-bold text-slate-400 uppercase">Deskripsi Fitur (Mendukung HTML seperti &lt;strong&gt; atau &lt;b&gt;)</label>
                            <textarea name="feature_2_desc" id="feature_2_desc" rows="2" required
                                      class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">{{ old('feature_2_desc', $settings['feature_2_desc']) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Fitur 3 -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-300">🍃 Fitur 3 (Odor Encapsulation)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="feature_3_icon" class="block text-xs font-bold text-slate-400 uppercase">Ikon / Emoji</label>
                            <input type="text" name="feature_3_icon" id="feature_3_icon" value="{{ old('feature_3_icon', $settings['feature_3_icon']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label for="feature_3_title" class="block text-xs font-bold text-slate-400 uppercase">Judul Fitur</label>
                            <input type="text" name="feature_3_title" id="feature_3_title" value="{{ old('feature_3_title', $settings['feature_3_title']) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">
                        </div>
                        <div class="space-y-2 md:col-span-3">
                            <label for="feature_3_desc" class="block text-xs font-bold text-slate-400 uppercase">Deskripsi Fitur (Mendukung HTML seperti &lt;strong&gt; atau &lt;b&gt;)</label>
                            <textarea name="feature_3_desc" id="feature_3_desc" rows="2" required
                                      class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none transition-all">{{ old('feature_3_desc', $settings['feature_3_desc']) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between border-t border-slate-800 pt-6">
            <span class="text-xs text-slate-500">Pastikan memeriksa semua perubahan sebelum menyimpan.</span>
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-3 rounded-2xl shadow-lg shadow-amber-500/10 transition-all flex items-center gap-2 cursor-pointer">
                <span>Simpan Pengaturan</span> 💾
            </button>
        </div>
    </form>
</div>

<script>
    function switchTab(tabId) {
        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(el => {
            el.classList.add('hidden');
        });

        // Show selected pane
        document.getElementById('tab-pane-' + tabId).classList.remove('hidden');

        // Reset all tab button styles
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-amber-500', 'border-amber-500');
            btn.classList.add('text-slate-400', 'border-transparent');
        });

        // Apply active styles to clicked button
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        activeBtn.classList.remove('text-slate-400', 'border-transparent');
        activeBtn.classList.add('text-amber-500', 'border-amber-500');
    }

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleMediaField(type) {
        const imgPreview = document.getElementById('media_image_preview');
        const vidPreview = document.getElementById('media_video_preview');
        
        if (type === 'image') {
            imgPreview.classList.remove('hidden');
            vidPreview.classList.add('hidden');
        } else {
            imgPreview.classList.add('hidden');
            vidPreview.classList.remove('hidden');
        }
    }

    function previewHeroMedia(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const type = file.type;
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imgPreview = document.getElementById('media_image_preview');
                const vidPreview = document.getElementById('media_video_preview');
                const selectType = document.getElementById('hero_media_type');

                if (type.startsWith('video/')) {
                    selectType.value = 'video';
                    imgPreview.classList.add('hidden');
                    vidPreview.classList.remove('hidden');
                    vidPreview.src = e.target.result;
                    vidPreview.play();
                } else if (type.startsWith('image/')) {
                    selectType.value = 'image';
                    imgPreview.classList.remove('hidden');
                    vidPreview.classList.add('hidden');
                    imgPreview.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
