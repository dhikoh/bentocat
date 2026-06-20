@extends('layouts.admin')

@section('title', 'Asisten Prompt Pemasaran')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Asisten Prompt Pemasaran</h1>
            <p class="text-slate-400 mt-1">Gunakan kecerdasan AI untuk membuat draf penawaran, penanganan komplain, dan konten sosial media berbasis standar handbook.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.prompt-generator.download') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-yellow-600 text-slate-950 font-bold rounded-xl hover:from-amber-400 hover:to-yellow-500 transition duration-150 shadow-lg shadow-amber-500/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh Handbook (.md)
            </a>
            <a href="{{ route('admin.prompt-generator.templates.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 text-slate-200 font-semibold rounded-xl hover:bg-slate-700 hover:text-white transition duration-150 border border-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Kelola Template
            </a>
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

    @if (session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tab Navigation -->
    <div class="flex flex-wrap gap-2 border-b border-slate-800 pb-px">
        <button type="button" onclick="switchTab('assistant')" id="tab-btn-assistant" class="tab-btn px-5 py-3 text-sm font-bold rounded-t-2xl border-b-2 transition-all cursor-pointer text-amber-500 border-amber-500">
            🤖 Generator Prompt
        </button>
        <button type="button" onclick="switchTab('product-profile')" id="tab-btn-product-profile" class="tab-btn px-5 py-3 text-sm font-bold rounded-t-2xl border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition-all cursor-pointer">
            📦 Profil Produk BentoCat
        </button>
    </div>

    <!-- Tab Content Area -->
    <div class="space-y-6">
        
        <!-- Tab Pane: Assistant / Prompt Generator -->
        <div id="tab-pane-assistant" class="tab-pane space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Form Input -->
                <div class="lg:col-span-7 bg-slate-900/60 border border-slate-800 p-6 rounded-3xl backdrop-blur-md space-y-6 shadow-xl">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-xl font-bold text-white">Konfigurasi Prompt</h2>
                        <p class="text-xs text-slate-400 mt-1">Pilih template dan masukkan detail isian untuk menyusun instruksi akhir untuk AI.</p>
                    </div>

                    <form id="prompt-form" action="{{ route('admin.prompt-generator.generate') }}" method="POST" class="space-y-5">
                        @csrf

                        <!-- Select Customer Profile -->
                        <div class="space-y-2">
                            <label for="customer_profile_id" class="block text-sm font-semibold text-slate-300">Profil Pelanggan (Opsional)</label>
                            <div class="flex gap-2">
                                <select id="customer_profile_id" name="customer_profile_id" class="flex-1 bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-3 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">
                                    <option value="">-- Tanpa Profil Pelanggan (Mode Umum) --</option>
                                    @foreach ($customers as $cust)
                                        <option value="{{ $cust->id }}" {{ old('customer_profile_id') == $cust->id ? 'selected' : '' }}>
                                            {{ $cust->nama }} ({{ $cust->kota ?? 'Belum ada kota' }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openCreateCustomerModal()" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-amber-500 border border-slate-700 rounded-xl text-sm font-bold transition flex items-center gap-1 cursor-pointer">
                                    ➕ Baru
                                </button>
                            </div>
                        </div>

                        <!-- Select Template -->
                        <div class="space-y-2">
                            <label for="template_id" class="block text-sm font-semibold text-slate-300">Pilih Template Pemasaran</label>
                            <select id="template_id" name="template_id" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-3 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">
                                <option value="" disabled selected>-- Pilih Template Pemasaran --</option>
                                @foreach ($templates as $tmpl)
                                    <option value="{{ $tmpl->id }}" 
                                            data-placeholders="{{ $tmpl->placeholders }}"
                                            data-target="{{ $tmpl->target_audience }}"
                                            data-tone="{{ $tmpl->tone }}"
                                            {{ old('template_id') == $tmpl->id ? 'selected' : '' }}>
                                        [{{ $tmpl->category }}] - {{ $tmpl->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Customer Chat Input -->
                        <div class="space-y-2">
                            <label for="customer_chat" class="block text-sm font-semibold text-slate-300">Salinan Chat / Pesan Masuk (Opsional)</label>
                            <textarea id="customer_chat" name="customer_chat" rows="3" placeholder="Tempel (paste) chat dari WhatsApp, DM Instagram, atau Email customer di sini jika ingin AI merespons pesan tersebut..." class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-4 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">{{ old('customer_chat') }}</textarea>
                            <p class="text-xs text-slate-500">Gunakan kolom ini jika Anda ingin AI menyusun draf balasan untuk pesan riil pelanggan.</p>
                        </div>

                        <!-- Dynamic Variables Fields (Populated by JS) -->
                        <div id="dynamic-variables-section" class="hidden space-y-4 p-4 bg-slate-950/40 border border-slate-800/80 rounded-2xl">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 pb-2">Variabel Template Dinamis</h3>
                            <div id="dynamic-variables-container" class="grid grid-cols-1 gap-4">
                                <!-- Inputs will be injected here -->
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Target Audience -->
                            <div class="space-y-2">
                                <label for="target_audience" class="block text-sm font-semibold text-slate-300">Target Audiens</label>
                                <input type="text" id="target_audience" name="target_audience" value="{{ old('target_audience') }}" placeholder="Contoh: Owner Petshop Malang" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-3 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">
                            </div>

                            <!-- Tone -->
                            <div class="space-y-2">
                                <label for="tone" class="block text-sm font-semibold text-slate-300">Gaya Bahasa (Tone)</label>
                                <input type="text" id="tone" name="tone" value="{{ old('tone') }}" placeholder="Contoh: Sopan, Menyakinkan, Kekeluargaan" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-3 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Language -->
                            <div class="space-y-2">
                                <label for="language" class="block text-sm font-semibold text-slate-300">Bahasa Output</label>
                                <select id="language" name="language" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-3 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">
                                    <option value="Bahasa Indonesia" {{ old('language') == 'Bahasa Indonesia' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                    <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Jawa Krama" {{ old('language') == 'Jawa Krama' ? 'selected' : '' }}>Jawa Krama / Gaul</option>
                                </select>
                            </div>

                            <!-- Length -->
                            <div class="space-y-2">
                                <label for="length" class="block text-sm font-semibold text-slate-300">Panjang Respon</label>
                                <select id="length" name="length" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-3 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">
                                    <option value="Ringkas (100 - 200 kata)" {{ old('length') == 'Ringkas (100 - 200 kata)' ? 'selected' : '' }}>Ringkas</option>
                                    <option value="Sedang (200 - 450 kata)" {{ old('length', 'Sedang (200 - 450 kata)') == 'Sedang (200 - 450 kata)' ? 'selected' : '' }}>Sedang (Standar)</option>
                                    <option value="Panjang & Detail (>450 kata)" {{ old('length') == 'Panjang & Detail (>450 kata)' ? 'selected' : '' }}>Panjang & Detail</option>
                                </select>
                            </div>

                            <!-- Emoji Style -->
                            <div class="space-y-2">
                                <label for="emoji_style" class="block text-sm font-semibold text-slate-300">Gaya Emoticon</label>
                                <select id="emoji_style" name="emoji_style" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-3 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">
                                    <option value="standard" {{ old('emoji_style') == 'standard' ? 'selected' : '' }}>Standard (Emoji)</option>
                                    <option value="none" {{ old('emoji_style') == 'none' ? 'selected' : '' }}>Tanpa Emoticon</option>
                                </select>
                            </div>

                            <!-- Generate Method Indicator -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-300">Metode</label>
                                <div class="text-xs text-slate-400 py-3 bg-slate-950/20 px-3 border border-slate-850 rounded-xl text-center">
                                    ⚡ Instan (AJAX)
                                </div>
                            </div>
                        </div>

                        <!-- Custom Notes -->
                        <div class="space-y-2">
                            <label for="custom_notes" class="block text-sm font-semibold text-slate-300">Catatan Khusus Tambahan (Opsional)</label>
                            <textarea id="custom_notes" name="custom_notes" rows="3" placeholder="Contoh: Tekankan bonus banner promosi gratis untuk petshop yang order sebelum tanggal 30." class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-4 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">{{ old('custom_notes') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-amber-500 to-yellow-600 text-slate-950 font-bold rounded-2xl hover:from-amber-400 hover:to-yellow-500 transition duration-150 shadow-lg shadow-amber-500/10">
                            ⚙️ Generate Prompt AI
                        </button>
                    </form>
                </div>

                <!-- Right: Prompt Output & Customer Panel -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Output Wrapper -->
                    <div class="bg-slate-900/60 border border-slate-800 p-6 rounded-3xl backdrop-blur-md space-y-4 shadow-xl">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                            <div>
                                <h2 class="text-xl font-bold text-white">Hasil Prompt</h2>
                                <p class="text-xs text-slate-400 mt-1">Salin teks di bawah ini ke ChatGPT, Claude, atau Gemini.</p>
                            </div>
                            <button type="button" onclick="copyPrompt()" id="copy-btn" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 cursor-pointer border border-slate-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                                Salin Prompt
                            </button>
                        </div>

                        <!-- Output Box -->
                        <div class="relative bg-slate-950/90 border border-slate-800 rounded-2xl p-4 overflow-hidden h-[300px] flex flex-col">
                            <textarea id="prompt-output" readonly class="w-full h-full bg-transparent text-slate-300 font-mono text-xs focus:outline-none resize-none border-none p-0 overflow-y-auto leading-relaxed" placeholder="Prompt akan terbuat di sini secara otomatis setelah Anda mengeklik 'Generate Prompt AI'."></textarea>
                        </div>

                        <!-- Usage Instruction Alert -->
                        <div class="p-4 bg-amber-500/5 border border-amber-500/10 rounded-2xl flex gap-3 text-xs text-amber-500/90 leading-relaxed">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <span class="font-bold block mb-0.5">Cara Penggunaan:</span>
                                1. Salin prompt di atas.<br>
                                2. Buka AI pilihan Anda (misalnya Claude.ai).<br>
                                3. Unggah file <strong>marketing_skills_handbook.md</strong>.<br>
                                4. Paste prompt dan tekan enter.
                            </div>
                        </div>
                    </div>

                    <!-- Customer Profile Info & History Panel -->
                    <div id="customer-info-panel" class="bg-slate-900/60 border border-slate-800 p-6 rounded-3xl backdrop-blur-md space-y-4 shadow-xl">
                        <!-- Default State (No Customer Selected) -->
                        <div id="customer-placeholder" class="text-center py-8 text-slate-500 text-xs">
                            <svg class="w-10 h-10 mx-auto text-slate-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <p class="font-semibold text-slate-400">Mode Umum: Tanpa Profil Pelanggan</p>
                            <p class="text-slate-500 mt-1">Draf prompt tidak ditargetkan khusus & riwayat tidak dicatat.</p>
                        </div>

                        <!-- Active State (Customer Selected) -->
                        <div id="customer-active-details" class="hidden space-y-4">
                            <!-- Customer Profile Card -->
                            <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-4 space-y-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h4 id="cust-card-nama" class="font-bold text-white text-sm">Nama Pelanggan</h4>
                                        <p id="cust-card-whatsapp" class="text-xs text-amber-500 font-semibold mt-0.5">+62 8xx-xxxx-xxxx</p>
                                    </div>
                                    <div class="flex gap-1.5">
                                        <button type="button" onclick="openEditCustomerModal()" class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg hover:text-white transition cursor-pointer border border-slate-750" title="Ubah Profil">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="confirmDeleteCustomer()" class="p-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-lg transition cursor-pointer border border-rose-500/20" title="Hapus Profil">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-400 space-y-1.5 pt-3 border-t border-slate-800/80">
                                    <p class="flex items-start gap-1.5"><span class="text-slate-500">📍 Alamat:</span> <span id="cust-card-alamat" class="text-slate-300">Alamat Lengkap</span></p>
                                    <p class="flex items-start gap-1.5"><span class="text-slate-500">🏢 Wilayah:</span> <span id="cust-card-kota" class="text-slate-300 font-semibold">Kota, Provinsi</span></p>
                                </div>
                            </div>

                            <!-- History Prompt List -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center justify-between border-b border-slate-800 pb-2">
                                    <span>Riwayat Prompt Terkait</span>
                                    <span id="cust-history-count" class="px-2 py-0.5 bg-slate-850 text-amber-500 rounded-full text-[10px] font-mono">0</span>
                                </h4>
                                
                                <div id="customer-history-list" class="space-y-2.5 max-h-[260px] overflow-y-auto pr-1">
                                    <!-- History items injected here -->
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>

        <!-- Tab Pane: Product Profile Settings -->
        <div id="tab-pane-product-profile" class="tab-pane hidden space-y-6">
            <div class="bg-slate-900/60 border border-slate-800 p-6 rounded-3xl backdrop-blur-md space-y-6 shadow-xl">
                <div class="border-b border-slate-800 pb-4">
                    <h2 class="text-xl font-bold text-white">Profil Deskripsi Produk BentoCat</h2>
                    <p class="text-xs text-slate-400 mt-1">Detail profil produk ini akan otomatis disisipkan ke dalam prompt asisten marketing agar AI memahami keunggulan dan sistem BentoCat.</p>
                </div>

                <form action="{{ route('admin.prompt-generator.save-product') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Product Name -->
                    <div class="space-y-2">
                        <label for="prompt_product_name" class="block text-sm font-semibold text-slate-300">Nama Produk Resmi</label>
                        <input type="text" id="prompt_product_name" name="prompt_product_name" value="{{ old('prompt_product_name', $productName) }}" placeholder="Contoh: BentoCat Premium Bentonite Cat Litter" class="w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-3 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">
                    </div>

                    <!-- Advantages -->
                    <div class="space-y-2">
                        <label for="prompt_advantages" class="block text-sm font-semibold text-slate-300">Keunggulan Utama (Satu per baris)</label>
                        <textarea id="prompt_advantages" name="prompt_advantages" rows="5" placeholder="Tulis keunggulan utama pasir kucing BentoCat..." class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-4 text-white text-sm font-mono focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">{{ old('prompt_advantages', $advantages) }}</textarea>
                    </div>

                    <!-- Marketing System -->
                    <div class="space-y-2">
                        <label for="prompt_marketing_system" class="block text-sm font-semibold text-slate-300">Sistem Pemasaran & Distribusi</label>
                        <textarea id="prompt_marketing_system" name="prompt_marketing_system" rows="4" placeholder="Jelaskan sistem pemasaran, misalnya penjualan melalui kemitraan petshop terdaftar dengan harga flat..." class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-4 text-white text-sm focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-500 transition">{{ old('prompt_marketing_system', $marketingSystem) }}</textarea>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-amber-500 to-yellow-600 text-slate-950 font-bold rounded-xl hover:from-amber-400 hover:to-yellow-500 transition duration-150 shadow-lg shadow-amber-500/10">
                            💾 Simpan Profil Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
@keyframes scaleUp {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-scale-up {
    animation: scaleUp 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

<!-- Create Customer Modal -->
<div id="create-customer-modal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity duration-200">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-4 animate-scale-up">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-lg font-bold text-white">Tambah Profil Pelanggan Baru</h3>
            <button type="button" onclick="closeCreateCustomerModal()" class="text-slate-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="create-customer-form" class="space-y-4" onsubmit="submitCreateCustomer(event)">
            <div class="space-y-1">
                <label for="new_nama" class="block text-xs font-semibold text-slate-400">Nama Pelanggan <span class="text-amber-500">*</span></label>
                <input type="text" id="new_nama" required placeholder="Contoh: Petshop Makmur" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-white text-sm focus:border-amber-500 focus:outline-none transition">
            </div>
            <div class="space-y-1">
                <label for="new_whatsapp" class="block text-xs font-semibold text-slate-400">WhatsApp <span class="text-amber-500">*</span></label>
                <input type="text" id="new_whatsapp" required placeholder="Contoh: 08123456789" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-white text-sm focus:border-amber-500 focus:outline-none transition">
            </div>
            <div class="space-y-1">
                <label for="new_alamat" class="block text-xs font-semibold text-slate-400">Alamat Lengkap</label>
                <textarea id="new_alamat" rows="2" placeholder="Contoh: Jl. Sudirman No. 12" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white text-sm focus:border-amber-500 focus:outline-none transition"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="new_provinsi" class="block text-xs font-semibold text-slate-400">Provinsi</label>
                    <input type="text" id="new_provinsi" placeholder="Contoh: Jawa Timur" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-white text-sm focus:border-amber-500 focus:outline-none transition">
                </div>
                <div class="space-y-1">
                    <label for="new_kota" class="block text-xs font-semibold text-slate-400">Kota/Kabupaten</label>
                    <input type="text" id="new_kota" placeholder="Contoh: Malang" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-white text-sm focus:border-amber-500 focus:outline-none transition">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeCreateCustomerModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl transition text-xs border border-slate-700 cursor-pointer">Batal</button>
                <button type="submit" id="submit-new-cust-btn" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-yellow-600 text-slate-950 font-bold rounded-xl hover:from-amber-400 hover:to-yellow-500 transition text-xs cursor-pointer">Simpan Pelanggan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Customer Modal -->
<div id="edit-customer-modal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity duration-200">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-4 animate-scale-up">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-lg font-bold text-white">Ubah Profil Pelanggan</h3>
            <button type="button" onclick="closeEditCustomerModal()" class="text-slate-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="edit-customer-form" class="space-y-4" onsubmit="submitEditCustomer(event)">
            <input type="hidden" id="edit_cust_id">
            <div class="space-y-1">
                <label for="edit_nama" class="block text-xs font-semibold text-slate-400">Nama Pelanggan <span class="text-amber-500">*</span></label>
                <input type="text" id="edit_nama" required placeholder="Contoh: Petshop Makmur" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-white text-sm focus:border-amber-500 focus:outline-none transition">
            </div>
            <div class="space-y-1">
                <label for="edit_whatsapp" class="block text-xs font-semibold text-slate-400">WhatsApp <span class="text-amber-500">*</span></label>
                <input type="text" id="edit_whatsapp" required placeholder="Contoh: 08123456789" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-white text-sm focus:border-amber-500 focus:outline-none transition">
            </div>
            <div class="space-y-1">
                <label for="edit_alamat" class="block text-xs font-semibold text-slate-400">Alamat Lengkap</label>
                <textarea id="edit_alamat" rows="2" placeholder="Contoh: Jl. Sudirman No. 12" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-white text-sm focus:border-amber-500 focus:outline-none transition"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="edit_provinsi" class="block text-xs font-semibold text-slate-400">Provinsi</label>
                    <input type="text" id="edit_provinsi" placeholder="Contoh: Jawa Timur" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-white text-sm focus:border-amber-500 focus:outline-none transition">
                </div>
                <div class="space-y-1">
                    <label for="edit_kota" class="block text-xs font-semibold text-slate-400">Kota/Kabupaten</label>
                    <input type="text" id="edit_kota" placeholder="Contoh: Malang" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-white text-sm focus:border-amber-500 focus:outline-none transition">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeEditCustomerModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl transition text-xs border border-slate-700 cursor-pointer">Batal</button>
                <button type="submit" id="submit-edit-cust-btn" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-yellow-600 text-slate-950 font-bold rounded-xl hover:from-amber-400 hover:to-yellow-500 transition text-xs cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const storageKey = 'bentocat_prompt_builder_settings';
    let currentHistoryData = [];

    document.addEventListener('DOMContentLoaded', function() {
        const templateSelect = document.getElementById('template_id');
        const customerSelect = document.getElementById('customer_profile_id');
        
        // Listen template changes
        templateSelect.addEventListener('change', function() {
            renderDynamicVariables(this);
            saveFormState();
        });

        // Listen customer selection changes
        customerSelect.addEventListener('change', function() {
            handleCustomerSelection(this.value);
            saveFormState();
        });

        // Intercept form submit for AJAX Prompt Generation
        const form = document.getElementById('prompt-form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            generatePromptViaAjax(this);
        });

        // Restore State from LocalStorage
        restoreFormState();

        // Setup live autosave listeners
        setupStateSavingListeners();
    });

    function saveFormState() {
        const state = {
            customer_profile_id: document.getElementById('customer_profile_id').value,
            template_id: document.getElementById('template_id').value,
            customer_chat: document.getElementById('customer_chat').value,
            target_audience: document.getElementById('target_audience').value,
            tone: document.getElementById('tone').value,
            language: document.getElementById('language').value,
            length: document.getElementById('length').value,
            emoji_style: document.getElementById('emoji_style').value,
            custom_notes: document.getElementById('custom_notes').value,
            variables: {}
        };
        
        document.querySelectorAll('#dynamic-variables-container input').forEach(input => {
            state.variables[input.name] = input.value;
        });

        localStorage.setItem(storageKey, JSON.stringify(state));
    }

    function restoreFormState() {
        const stored = localStorage.getItem(storageKey);
        if (!stored) return;

        try {
            const state = JSON.parse(stored);

            if (state.customer_profile_id !== undefined) {
                document.getElementById('customer_profile_id').value = state.customer_profile_id;
                handleCustomerSelection(state.customer_profile_id);
            }
            if (state.template_id !== undefined) {
                document.getElementById('template_id').value = state.template_id;
            }
            if (state.customer_chat !== undefined) {
                document.getElementById('customer_chat').value = state.customer_chat;
            }
            if (state.target_audience !== undefined) {
                document.getElementById('target_audience').value = state.target_audience;
            }
            if (state.tone !== undefined) {
                document.getElementById('tone').value = state.tone;
            }
            if (state.language !== undefined) {
                document.getElementById('language').value = state.language;
            }
            if (state.length !== undefined) {
                document.getElementById('length').value = state.length;
            }
            if (state.emoji_style !== undefined) {
                document.getElementById('emoji_style').value = state.emoji_style;
            }
            if (state.custom_notes !== undefined) {
                document.getElementById('custom_notes').value = state.custom_notes;
            }

            if (document.getElementById('template_id').value) {
                renderDynamicVariables(document.getElementById('template_id'));
                
                if (state.variables) {
                    Object.keys(state.variables).forEach(name => {
                        const input = document.querySelector(`#dynamic-variables-container input[name="${name}"]`);
                        if (input) {
                            input.value = state.variables[name];
                        }
                    });
                }
            }
        } catch (e) {
            console.error("Gagal memulihkan state dari localStorage:", e);
        }
    }

    function setupStateSavingListeners() {
        const formElements = [
            'customer_profile_id', 'template_id', 'customer_chat', 
            'target_audience', 'tone', 'language', 'length', 
            'emoji_style', 'custom_notes'
        ];
        
        formElements.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('change', saveFormState);
                el.addEventListener('input', saveFormState);
            }
        });

        const container = document.getElementById('dynamic-variables-container');
        container.addEventListener('input', saveFormState);
    }

    function handleCustomerSelection(customerId) {
        const placeholder = document.getElementById('customer-placeholder');
        const activeDetails = document.getElementById('customer-active-details');

        if (!customerId) {
            placeholder.classList.remove('hidden');
            activeDetails.classList.add('hidden');
            currentHistoryData = [];
            return;
        }

        placeholder.classList.add('hidden');
        activeDetails.classList.remove('hidden');

        document.getElementById('customer-history-list').innerHTML = `
            <div class="text-center py-4 text-slate-500 text-xs">
                <span class="inline-block animate-spin mr-1">⏳</span> Memuat riwayat...
            </div>
        `;

        fetch(`/admin/prompt-generator/customers/${customerId}/history`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('cust-card-nama').textContent = data.customer.nama;
            document.getElementById('cust-card-whatsapp').textContent = data.customer.whatsapp;
            document.getElementById('cust-card-alamat').textContent = data.customer.alamat || 'Belum ada alamat lengkap';
            document.getElementById('cust-card-kota').textContent = data.customer.kota 
                ? `${data.customer.kota}, ${data.customer.provinsi || ''}` 
                : 'Belum diisi';

            document.getElementById('edit_cust_id').value = data.customer.id;
            document.getElementById('edit_nama').value = data.customer.nama;
            document.getElementById('edit_whatsapp').value = data.customer.whatsapp;
            document.getElementById('edit_alamat').value = data.customer.alamat || '';
            document.getElementById('edit_provinsi').value = data.customer.provinsi || '';
            document.getElementById('edit_kota').value = data.customer.kota || '';

            currentHistoryData = data.history;
            renderHistoryList(data.history);
        })
        .catch(err => {
            document.getElementById('customer-history-list').innerHTML = `
                <div class="text-center py-4 text-rose-500 text-xs">
                    ❌ Gagal memuat data pelanggan.
                </div>
            `;
        });
    }

    function renderHistoryList(history) {
        const container = document.getElementById('customer-history-list');
        document.getElementById('cust-history-count').textContent = history.length;

        if (history.length === 0) {
            container.innerHTML = `
                <div class="text-center py-6 text-slate-500 text-xs bg-slate-950/20 border border-slate-850 rounded-2xl">
                    Belum ada riwayat prompt untuk pelanggan ini.
                </div>
            `;
            return;
        }

        container.innerHTML = history.map(item => {
            const chatPreview = item.chat_input 
                ? `<p class="text-[10px] text-slate-500 italic truncate mt-1">Chat: "${item.chat_input}"</p>`
                : '';
            return `
                <div class="p-3 bg-slate-950/50 border border-slate-800 rounded-xl space-y-2 hover:border-slate-700 transition">
                    <div class="flex items-start justify-between gap-2">
                        <div class="max-w-[70%]">
                            <span class="px-1.5 py-0.5 bg-amber-500/10 text-amber-500 text-[9px] font-bold rounded block truncate">
                                ${item.template_name}
                            </span>
                            <span class="text-[9px] text-slate-500 block mt-1">${item.created_at}</span>
                        </div>
                        <div class="flex gap-1">
                            <button type="button" onclick="useHistoryPrompt(${item.id})" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-amber-500 text-[10px] font-bold rounded-lg border border-slate-750 transition cursor-pointer" title="Gunakan Kembali">
                                📋 Gunakan
                            </button>
                            <button type="button" onclick="deleteHistoryItem(${item.id})" class="p-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-lg border border-rose-500/20 transition cursor-pointer" title="Hapus Riwayat">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    ${chatPreview}
                </div>
            `;
        }).join('');
    }

    function useHistoryPrompt(id) {
        const item = currentHistoryData.find(h => h.id === id);
        if (item) {
            const outputTextarea = document.getElementById('prompt-output');
            outputTextarea.value = item.generated_prompt;
            outputTextarea.scrollTop = 0;

            const outputBox = outputTextarea.parentElement;
            outputBox.classList.add('ring-2', 'ring-emerald-500/50');
            setTimeout(() => {
                outputBox.classList.remove('ring-2', 'ring-emerald-500/50');
            }, 1000);
        }
    }

    function deleteHistoryItem(id) {
        if (!confirm("Apakah Anda yakin ingin menghapus riwayat prompt ini?")) return;

        fetch(`/admin/prompt-generator/history/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const customerId = document.getElementById('customer_profile_id').value;
                handleCustomerSelection(customerId);
            }
        })
        .catch(err => alert("Gagal menghapus riwayat: " + err));
    }

    function openCreateCustomerModal() {
        document.getElementById('create-customer-modal').classList.remove('hidden');
    }

    function closeCreateCustomerModal() {
        document.getElementById('create-customer-modal').classList.add('hidden');
        document.getElementById('create-customer-form').reset();
    }

    function submitCreateCustomer(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('submit-new-cust-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Menyimpan...';

        const data = {
            nama: document.getElementById('new_nama').value,
            whatsapp: document.getElementById('new_whatsapp').value,
            alamat: document.getElementById('new_alamat').value,
            provinsi: document.getElementById('new_provinsi').value,
            kota: document.getElementById('new_kota').value,
        };

        fetch(`/admin/prompt-generator/customers/quick-store`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => {
            if (!res.ok) throw new Error("Terjadi kesalahan input.");
            return res.json();
        })
        .then(resData => {
            if (resData.success) {
                const dropdown = document.getElementById('customer_profile_id');
                const opt = document.createElement('option');
                opt.value = resData.customer.id;
                opt.textContent = `${resData.customer.nama} (${resData.customer.kota || 'Belum ada kota'})`;
                dropdown.appendChild(opt);
                
                dropdown.value = resData.customer.id;
                saveFormState();

                handleCustomerSelection(resData.customer.id);
                closeCreateCustomerModal();
            }
        })
        .catch(err => alert(err.message))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Simpan Pelanggan';
        });
    }

    function openEditCustomerModal() {
        document.getElementById('edit-customer-modal').classList.remove('hidden');
    }

    function closeEditCustomerModal() {
        document.getElementById('edit-customer-modal').classList.add('hidden');
    }

    function submitEditCustomer(e) {
        e.preventDefault();
        const id = document.getElementById('edit_cust_id').value;
        const submitBtn = document.getElementById('submit-edit-cust-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Menyimpan...';

        const data = {
            nama: document.getElementById('edit_nama').value,
            whatsapp: document.getElementById('edit_whatsapp').value,
            alamat: document.getElementById('edit_alamat').value,
            provinsi: document.getElementById('edit_provinsi').value,
            kota: document.getElementById('edit_kota').value,
        };

        fetch(`/admin/prompt-generator/customers/${id}/quick-update`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => {
            if (!res.ok) throw new Error("Terjadi kesalahan input.");
            return res.json();
        })
        .then(resData => {
            if (resData.success) {
                const dropdown = document.getElementById('customer_profile_id');
                const opt = dropdown.querySelector(`option[value="${id}"]`);
                if (opt) {
                    opt.textContent = `${resData.customer.nama} (${resData.customer.kota || 'Belum ada kota'})`;
                }
                saveFormState();

                handleCustomerSelection(id);
                closeEditCustomerModal();
            }
        })
        .catch(err => alert(err.message))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Simpan Perubahan';
        });
    }

    function confirmDeleteCustomer() {
        const id = document.getElementById('customer_profile_id').value;
        if (!id) return;
        if (!confirm("Apakah Anda yakin ingin menghapus profil pelanggan ini beserta semua riwayat prompt-nya secara permanen?")) return;

        fetch(`/admin/prompt-generator/customers/${id}/quick-destroy`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const dropdown = document.getElementById('customer_profile_id');
                const opt = dropdown.querySelector(`option[value="${id}"]`);
                if (opt) {
                    opt.remove();
                }
                dropdown.value = "";
                saveFormState();

                handleCustomerSelection("");
            }
        })
        .catch(err => alert("Gagal menghapus profil pelanggan: " + err));
    }

    function switchTab(tabId) {
        document.querySelectorAll('.tab-pane').forEach(el => {
            el.classList.add('hidden');
        });

        document.getElementById('tab-pane-' + tabId).classList.remove('hidden');

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-amber-500', 'border-amber-500');
            btn.classList.add('text-slate-400', 'border-transparent');
        });

        const activeBtn = document.getElementById('tab-btn-' + tabId);
        activeBtn.classList.remove('text-slate-400', 'border-transparent');
        activeBtn.classList.add('text-amber-500', 'border-amber-500');
    }

    function renderDynamicVariables(selectElement) {
        const option = selectElement.options[selectElement.selectedIndex];
        if (!option) return;
        const placeholdersStr = option.getAttribute('data-placeholders') || '';
        const targetAudience = option.getAttribute('data-target') || '';
        const toneVal = option.getAttribute('data-tone') || '';

        const container = document.getElementById('dynamic-variables-container');
        const section = document.getElementById('dynamic-variables-section');

        if (targetAudience) {
            document.getElementById('target_audience').value = targetAudience;
        }
        if (toneVal) {
            document.getElementById('tone').value = toneVal;
        }

        container.innerHTML = '';

        if (!placeholdersStr.trim()) {
            section.classList.add('hidden');
            return;
        }

        const placeholders = placeholdersStr.split(',').map(p => p.trim()).filter(p => p.length > 0);
        
        if (placeholders.length === 0) {
            section.classList.add('hidden');
            return;
        }

        section.classList.remove('hidden');

        placeholders.forEach(placeholder => {
            const formattedLabel = placeholder.split('_')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');

            const wrapper = document.createElement('div');
            wrapper.className = 'space-y-1.5';

            const label = document.createElement('label');
            label.className = 'block text-xs font-semibold text-slate-400';
            label.textContent = formattedLabel + ' (Opsional)';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = `variables[${placeholder}]`;
            input.placeholder = `Masukkan ${formattedLabel}...`;
            input.className = 'w-full bg-slate-950/80 border border-slate-800 rounded-xl px-4 py-2.5 text-white text-xs focus:border-amber-500 focus:outline-none transition';
            
            // Add change/input listener for dynamic vars
            input.addEventListener('change', saveFormState);
            input.addEventListener('input', saveFormState);

            wrapper.appendChild(label);
            wrapper.appendChild(input);
            container.appendChild(wrapper);
        });
    }

    function generatePromptViaAjax(formElement) {
        const submitBtn = formElement.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Menyusun Prompt...';

        const formData = new FormData(formElement);

        fetch(formElement.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal menyusun prompt. Silakan cek kembali inputan Anda.');
            }
            return response.json();
        })
        .then(data => {
            const outputTextarea = document.getElementById('prompt-output');
            outputTextarea.value = data.prompt;
            outputTextarea.scrollTop = 0;
            
            const outputBox = outputTextarea.parentElement;
            outputBox.classList.add('ring-2', 'ring-amber-500/50');
            setTimeout(() => {
                outputBox.classList.remove('ring-2', 'ring-amber-500/50');
            }, 1000);

            // Reload history if customer is selected
            const customerId = document.getElementById('customer_profile_id').value;
            if (customerId) {
                handleCustomerSelection(customerId);
            }
        })
        .catch(err => {
            alert(err.message);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    }

    function copyPrompt() {
        const copyText = document.getElementById("prompt-output");
        if (!copyText.value) {
            alert("Belum ada prompt yang di-generate!");
            return;
        }

        copyText.select();
        copyText.setSelectionRange(0, 99999);

        navigator.clipboard.writeText(copyText.value).then(() => {
            const copyBtn = document.getElementById("copy-btn");
            const originalHtml = copyBtn.innerHTML;
            
            copyBtn.innerHTML = `
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="text-emerald-400">Tersalin!</span>
            `;
            copyBtn.classList.add('border-emerald-500/40', 'bg-emerald-500/10');

            setTimeout(() => {
                copyBtn.innerHTML = originalHtml;
                copyBtn.classList.remove('border-emerald-500/40', 'bg-emerald-500/10');
            }, 2000);
        }).catch(err => {
            alert("Gagal menyalin: " + err);
        });
    }
</script>
@endsection

