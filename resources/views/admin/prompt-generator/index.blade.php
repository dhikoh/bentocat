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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                            <!-- Generate Method Indicator -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-300">Metode Pengiriman</label>
                                <div class="text-xs text-slate-400 py-3 bg-slate-950/20 px-3 border border-slate-850 rounded-xl">
                                    ⚡ Instan Tanpa Reload (AJAX)
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

                <!-- Right: Prompt Output -->
                <div class="lg:col-span-5 bg-slate-900/60 border border-slate-800 p-6 rounded-3xl backdrop-blur-md space-y-6 shadow-xl flex flex-col h-full justify-between">
                    <div class="space-y-4">
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
                        <div class="relative bg-slate-950/90 border border-slate-800 rounded-2xl p-4 overflow-hidden h-[360px] md:h-[420px] flex flex-col">
                            <textarea id="prompt-output" readonly class="w-full h-full bg-transparent text-slate-300 font-mono text-xs focus:outline-none resize-none border-none p-0 overflow-y-auto leading-relaxed" placeholder="Prompt akan terbuat di sini secara otomatis setelah Anda mengeklik 'Generate Prompt AI'."></textarea>
                        </div>
                    </div>

                    <!-- Usage Instruction Alert -->
                    <div class="p-4 bg-amber-500/5 border border-amber-500/10 rounded-2xl flex gap-3 text-xs text-amber-500/90 leading-relaxed mt-4">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const templateSelect = document.getElementById('template_id');
        
        // Listen template changes
        templateSelect.addEventListener('change', function() {
            renderDynamicVariables(this);
        });

        // Trigger on load if there's old selected value
        if (templateSelect.value) {
            renderDynamicVariables(templateSelect);
        }

        // Intercept form submit for AJAX Prompt Generation
        const form = document.getElementById('prompt-form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            generatePromptViaAjax(this);
        });
    });

    function switchTab(tabId) {
        // Hide all panes
        document.querySelectorAll('.tab-pane').forEach(el => {
            el.classList.add('hidden');
        });

        // Show selected pane
        document.getElementById('tab-pane-' + tabId).classList.remove('hidden');

        // Reset all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-amber-500', 'border-amber-500');
            btn.classList.add('text-slate-400', 'border-transparent');
        });

        // Apply styles to selected tab button
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        activeBtn.classList.remove('text-slate-400', 'border-transparent');
        activeBtn.classList.add('text-amber-500', 'border-amber-500');
    }

    function renderDynamicVariables(selectElement) {
        const option = selectElement.options[selectElement.selectedIndex];
        const placeholdersStr = option.getAttribute('data-placeholders') || '';
        const targetAudience = option.getAttribute('data-target') || '';
        const toneVal = option.getAttribute('data-tone') || '';

        const container = document.getElementById('dynamic-variables-container');
        const section = document.getElementById('dynamic-variables-section');

        // Autofill target audience and tone
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
            
            // Auto scroll output window
            outputTextarea.scrollTop = 0;
            
            // Temporary flash effect on output box
            const outputBox = outputTextarea.parentElement;
            outputBox.classList.add('ring-2', 'ring-amber-500/50');
            setTimeout(() => {
                outputBox.classList.remove('ring-2', 'ring-amber-500/50');
            }, 1000);
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
        copyText.setSelectionRange(0, 99999); // For mobile devices

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
