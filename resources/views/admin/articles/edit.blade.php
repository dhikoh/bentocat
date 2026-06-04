@extends('layouts.admin')

@section('title', 'Edit Artikel - ' . $article->title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.articles.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Kembali ke Daftar</a>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white">Edit Artikel: {{ $article->title }}</h1>
        <p class="text-sm text-slate-400">Gunakan editor berbasis blok untuk memperbarui konten interaktif.</p>
    </div>

    <form id="article-form" action="{{ route('admin.articles.update', $article->id) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="content_json" id="content_json" value="">

        <!-- Left: Block Editor Container -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Core Metadata -->
            <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-3xl space-y-4">
                <div>
                    <label for="title" class="block text-xs font-bold text-slate-400 uppercase mb-2">Judul Artikel</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" required placeholder="Tulis judul artikel yang menarik..." 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="summary" class="block text-xs font-bold text-slate-400 uppercase mb-2">Ringkasan / Ringkasan (Summary)</label>
                    <textarea name="summary" id="summary" rows="2" placeholder="Ringkasan singkat artikel untuk preview halaman depan..." 
                              class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-sm text-slate-200 focus:outline-none transition-all">{{ old('summary', $article->summary) }}</textarea>
                </div>
            </div>

            <!-- Dynamic Block Editor Panel -->
            <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-3xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-white">Susunan Blok Konten</h2>
                        <p class="text-xs text-slate-500 mt-1">Tambahkan berbagai jenis blok di bawah untuk membentuk tubuh artikel.</p>
                    </div>
                </div>

                <!-- Visual Blocks Container -->
                <div id="blocks-container" class="space-y-4">
                    <!-- Blocks will render here dynamically via JavaScript -->
                </div>

                <!-- Block Adder Controls -->
                <div class="border-t border-slate-800/80 pt-6">
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-3">Tambah Blok Baru</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <button type="button" onclick="addBlock('paragraph')" class="bg-slate-950 border border-slate-800 hover:border-amber-500 hover:bg-slate-900 text-xs font-bold py-2 px-3 rounded-xl text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            📝 Paragraf
                        </button>
                        <button type="button" onclick="addBlock('quick_answer')" class="bg-slate-950 border border-slate-800 hover:border-amber-500 hover:bg-slate-900 text-xs font-bold py-2 px-3 rounded-xl text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            💡 Jawaban Cepat
                        </button>
                        <button type="button" onclick="addBlock('dialog')" class="bg-slate-950 border border-slate-800 hover:border-amber-500 hover:bg-slate-900 text-xs font-bold py-2 px-3 rounded-xl text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            🗣️ Dialog
                        </button>
                        <button type="button" onclick="addBlock('analogy')" class="bg-slate-950 border border-slate-800 hover:border-amber-500 hover:bg-slate-900 text-xs font-bold py-2 px-3 rounded-xl text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            ⚖️ Analogi
                        </button>
                        <button type="button" onclick="addBlock('dalil')" class="bg-slate-950 border border-slate-800 hover:border-amber-500 hover:bg-slate-900 text-xs font-bold py-2 px-3 rounded-xl text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            📚 Riset / Studi
                        </button>
                        <button type="button" onclick="addBlock('doa')" class="bg-slate-950 border border-slate-800 hover:border-amber-500 hover:bg-slate-900 text-xs font-bold py-2 px-3 rounded-xl text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            💡 Tips Khusus
                        </button>
                        <button type="button" onclick="addBlock('image')" class="bg-slate-950 border border-slate-800 hover:border-amber-500 hover:bg-slate-900 text-xs font-bold py-2 px-3 rounded-xl text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            🖼️ Gambar
                        </button>
                        <button type="button" onclick="addBlock('video')" class="bg-slate-950 border border-slate-800 hover:border-amber-500 hover:bg-slate-900 text-xs font-bold py-2 px-3 rounded-xl text-slate-300 transition-all flex items-center justify-center gap-1.5">
                            🎥 Video Link
                        </button>
                    </div>
                </div>

            </div>

        </div>

        <!-- Right: SEO Config & Publish Actions -->
        <div class="space-y-6">
            
            <!-- Publish card -->
            <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-3xl space-y-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Publikasi</h2>
                
                <div>
                    <label for="status" class="block text-xs font-bold text-slate-500 uppercase mb-2">Status</label>
                    <select name="status" id="status" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-3 py-2 text-sm text-slate-200 focus:outline-none transition-all">
                        <option value="DRAFT" {{ $article->status === 'DRAFT' ? 'selected' : '' }}>Draft</option>
                        <option value="UNDER_REVIEW" {{ $article->status === 'UNDER_REVIEW' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="PUBLISHED" {{ $article->status === 'PUBLISHED' ? 'selected' : '' }}>Published (Publikasikan)</option>
                    </select>
                </div>

                <div class="pt-2 flex gap-3">
                    <a href="{{ route('admin.articles.index') }}" class="flex-1 bg-slate-850 hover:bg-slate-800 text-slate-400 text-center py-2.5 rounded-xl text-xs font-semibold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-2.5 rounded-xl text-xs shadow-lg shadow-amber-500/10 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </div>

            <!-- AI Writing Assistant Card -->
            <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-3xl space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🤖</span>
                    <h2 class="text-sm font-bold text-white uppercase tracking-wider">AI Writing Assistant</h2>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Dapatkan saran outline, pertanyaan FAQs, optimasi judul/deskripsi meta SEO, dan rekomendasi link internal otomatis berdasarkan judul dan deskripsi artikel.
                </p>
                
                <button type="button" id="btn-ai-assist" class="w-full bg-slate-950 border border-slate-800 hover:border-amber-500 hover:bg-slate-900 text-amber-500 text-xs font-bold py-2.5 px-3 rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>✨ Hasilkan Rekomendasi AI</span>
                </button>

                <!-- Loading Indicator -->
                <div id="ai-loading" class="hidden flex items-center justify-center gap-2 py-4">
                    <svg class="animate-spin h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-400">Sedang memproses AI...</span>
                </div>

                <!-- Error Box -->
                <div id="ai-error" class="hidden bg-rose-950/40 border border-rose-800/60 p-3 rounded-xl text-rose-400 text-xs leading-relaxed"></div>

                <!-- AI Results Container -->
                <div id="ai-results" class="hidden space-y-4 border-t border-slate-800/80 pt-4 max-h-[350px] overflow-y-auto pr-1">
                    <!-- Outline Suggestions -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-300">📌 Struktur Outline:</h3>
                        <ul id="ai-outline" class="text-xs text-slate-400 list-disc list-inside space-y-1 pl-1"></ul>
                    </div>

                    <!-- FAQ Suggestions -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-300">❓ Pertanyaan FAQs:</h3>
                        <div id="ai-faqs" class="space-y-2 text-xs text-slate-400 bg-slate-950/60 p-2.5 rounded-xl border border-slate-850"></div>
                    </div>

                    <!-- Meta Tags Suggestion -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-300">🔍 Optimasi SEO:</h3>
                        <div class="bg-slate-950/60 p-3 rounded-xl border border-slate-850 space-y-2">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block">JUDUL SEO:</span>
                                <span id="ai-seo-title" class="text-xs text-slate-300 block font-medium mt-0.5"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block">META DESKRIPSI:</span>
                                <span id="ai-seo-desc" class="text-xs text-slate-400 block mt-0.5 leading-relaxed"></span>
                            </div>
                            <button type="button" id="btn-apply-seo" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-1.5 px-2 rounded-lg text-[10px] transition-all cursor-pointer">
                                Terapkan Ke Kolom SEO
                            </button>
                        </div>
                    </div>

                    <!-- Internal Links Suggestion -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-300">🔗 Link Internal Otomatis:</h3>
                        <ul id="ai-links" class="text-[11px] text-slate-400 space-y-1.5 pl-1"></ul>
                    </div>
                </div>
            </div>

            <!-- SEO Metadata card -->
            <div class="bg-slate-900/40 border border-slate-800/80 p-6 rounded-3xl space-y-4">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider">Optimasi SEO (Google Search)</h2>
                
                <div>
                    <label for="seo_title" class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul SEO (SEO Title)</label>
                    <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $article->seo_title) }}" placeholder="Kosongkan untuk menyamakan dengan judul..." 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="seo_description" class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi Meta SEO</label>
                    <textarea name="seo_description" id="seo_description" rows="3" placeholder="Tulis meta description unik untuk search engine snippet..." 
                              class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none transition-all">{{ old('seo_description', $article->seo_description) }}</textarea>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Prefill with existing database blocks
    let blocks = {!! $blocksJson !!} || [];

    function renderBlocks() {
        const container = document.getElementById('blocks-container');
        container.innerHTML = '';

        if (blocks.length === 0) {
            container.innerHTML = `
                <div class="text-center py-10 border border-dashed border-slate-800 rounded-2xl text-slate-600 italic text-xs select-none">
                    Belum ada blok konten ditambahkan. Pilih jenis blok di bawah untuk mulai menulis.
                </div>
            `;
            return;
        }

        blocks.forEach((block, index) => {
            const blockDiv = document.createElement('div');
            blockDiv.className = "bg-slate-950/60 border border-slate-800 rounded-2xl p-5 space-y-4 relative group";

            // Reorder and delete controls header
            let headerHtml = `
                <div class="flex items-center justify-between border-b border-slate-850 pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-slate-400">#${index + 1}</span>
                        <span class="bg-amber-500/10 text-amber-400 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">${getBlockTypeName(block.type)}</span>
                    </div>
                    <div class="flex gap-1">
                        <button type="button" onclick="moveBlock(${index}, -1)" ${index === 0 ? 'disabled' : ''} class="p-1 hover:bg-slate-800 rounded text-slate-500 disabled:opacity-30">⬆️</button>
                        <button type="button" onclick="moveBlock(${index}, 1)" ${index === blocks.length - 1 ? 'disabled' : ''} class="p-1 hover:bg-slate-800 rounded text-slate-500 disabled:opacity-30">⬇️</button>
                        <button type="button" onclick="deleteBlock(${index})" class="p-1 hover:bg-rose-500/10 text-rose-500 rounded">❌</button>
                    </div>
                </div>
            `;

            let fieldsHtml = '';

            if (block.type === 'paragraph') {
                fieldsHtml = `
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Sub-Judul (Opsional)</label>
                            <input type="text" value="${block.heading || ''}" oninput="updateBlockField(${index}, 'heading', this.value)" placeholder="Contoh: Manfaat Pasir Bentonit" class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Isi Paragraf Teks</label>
                            <textarea rows="4" oninput="updateBlockField(${index}, 'text', this.value)" placeholder="Tuliskan isi paragraf di sini..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">${block.text || ''}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Link Referensi (Opsional)</label>
                            <input type="url" value="${block.referenceUrl || ''}" oninput="updateBlockField(${index}, 'referenceUrl', this.value)" placeholder="https://..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        </div>
                    </div>
                `;
            } else if (block.type === 'quick_answer') {
                fieldsHtml = `
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pertanyaan / Poin Jawaban Cepat</label>
                            <textarea rows="2" oninput="updateBlockField(${index}, 'text', this.value)" placeholder="Tulis poin jawaban ringkas di sini..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">${block.text || ''}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Link Referensi (Opsional)</label>
                            <input type="url" value="${block.referenceUrl || ''}" oninput="updateBlockField(${index}, 'referenceUrl', this.value)" placeholder="https://..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        </div>
                    </div>
                `;
            } else if (block.type === 'dialog') {
                let linesHtml = (block.lines || []).map((line, lineIndex) => `
                    <div class="flex gap-2 items-center">
                        <select onchange="updateDialogLine(${index}, ${lineIndex}, 'role', this.value)" class="bg-slate-900 border border-slate-850 text-xs text-slate-300 rounded-lg p-2 focus:outline-none">
                            <option value="anak" ${line.role === 'anak' ? 'selected' : ''}>Anak</option>
                            <option value="ibu" ${line.role === 'ibu' ? 'selected' : ''}>Ibu</option>
                            <option value="ayah" ${line.role === 'ayah' ? 'selected' : ''}>Ayah</option>
                        </select>
                        <input type="text" value="${line.text || ''}" oninput="updateDialogLine(${index}, ${lineIndex}, 'text', this.value)" placeholder="Ucapan dialog..." class="flex-1 bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        <button type="button" onclick="deleteDialogLine(${index}, ${lineIndex})" class="text-rose-500 text-xs px-2 py-1">Hapus</button>
                    </div>
                `).join('');

                fieldsHtml = `
                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Dialog Percakapan</label>
                        <div class="space-y-2" id="dialog-lines-${index}">
                            ${linesHtml}
                        </div>
                        <button type="button" onclick="addDialogLine(${index})" class="bg-slate-900 hover:bg-slate-800 text-xs text-amber-500 border border-slate-800 px-3 py-1.5 rounded-lg">
                            + Tambah Baris Percakapan
                        </button>
                    </div>
                `;
            } else if (block.type === 'analogy') {
                fieldsHtml = `
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Judul Analogi</label>
                            <input type="text" value="${block.title || ''}" oninput="updateBlockField(${index}, 'title', this.value)" placeholder="Contoh: Menyaring Air Kotor" class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Penjelasan Analogi</label>
                            <textarea rows="3" oninput="updateBlockField(${index}, 'text', this.value)" placeholder="Jelaskan analoginya di sini..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">${block.text || ''}</textarea>
                        </div>
                    </div>
                `;
            } else if (block.type === 'dalil') {
                fieldsHtml = `
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kutipan / Temuan Utama</label>
                            <textarea rows="2" oninput="updateBlockField(${index}, 'arabic', this.value)" placeholder="Masukkan kutipan penting atau data riset..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">${block.arabic || ''}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Penjelasan Riset / Studi</label>
                            <textarea rows="2" oninput="updateBlockField(${index}, 'translation', this.value)" placeholder="Penjelasan riset..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">${block.translation || ''}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Sumber Riset / Publikasi</label>
                                <input type="text" value="${block.source || ''}" oninput="updateBlockField(${index}, 'source', this.value)" placeholder="Contoh: Journal of Feline Medicine, 2024" class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Link Sumber URL</label>
                                <input type="url" value="${block.sourceUrl || ''}" oninput="updateBlockField(${index}, 'sourceUrl', this.value)" placeholder="https://..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                            </div>
                        </div>
                    </div>
                `;
            } else if (block.type === 'doa') {
                fieldsHtml = `
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Judul Tips / Rekomendasi</label>
                            <input type="text" value="${block.title || ''}" oninput="updateBlockField(${index}, 'title', this.value)" placeholder="Contoh: Cara Menyimpan Pasir Agar Awet" class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Poin Sorotan Utama (Highlight)</label>
                            <textarea rows="2" oninput="updateBlockField(${index}, 'arabic', this.value)" placeholder="Masukkan poin sorotan penting dari tips..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">${block.arabic || ''}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Detail Penerapan Tips</label>
                            <textarea rows="2" oninput="updateBlockField(${index}, 'translation', this.value)" placeholder="Penjelasan rinci mengenai tips..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">${block.translation || ''}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Sumber Tips / Ahli (Opsional)</label>
                                <input type="text" value="${block.source || ''}" oninput="updateBlockField(${index}, 'source', this.value)" placeholder="Contoh: Dr. Sarah (Cat Expert)" class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Link Sumber URL</label>
                                <input type="url" value="${block.sourceUrl || ''}" oninput="updateBlockField(${index}, 'sourceUrl', this.value)" placeholder="https://..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                            </div>
                        </div>
                    </div>
                `;
            } else if (block.type === 'image') {
                fieldsHtml = `
                    <div class="space-y-3">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 bg-slate-900 border border-slate-800 rounded-xl flex items-center justify-center overflow-hidden text-2xl select-none shrink-0">
                                ${block.url ? `<img src="${block.url}" class="w-full h-full object-cover">` : '🖼️'}
                            </div>
                            <div class="flex-1 space-y-2">
                                <label class="block text-[10px] font-bold text-slate-500 uppercase">Upload File Gambar</label>
                                <input type="file" accept="image/*" onchange="uploadBlockImage(this, ${index})" class="text-xs text-slate-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-500/10 file:text-amber-400 hover:file:bg-amber-500/20 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">URL Gambar Langsung (Bisa diedit manual)</label>
                            <input type="text" id="img-url-field-${index}" value="${block.url || ''}" oninput="updateBlockField(${index}, 'url', this.value)" placeholder="https://..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Caption Gambar (Keterangan di bawah foto)</label>
                            <input type="text" value="${block.caption || ''}" oninput="updateBlockField(${index}, 'caption', this.value)" placeholder="Contoh: Kemasan BentoCat Premium 10 Liter" class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        </div>
                    </div>
                `;
            } else if (block.type === 'video') {
                fieldsHtml = `
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">YouTube Video Link / URL Embed</label>
                            <input type="url" value="${block.url || ''}" oninput="updateBlockField(${index}, 'url', this.value)" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Caption Video</label>
                            <input type="text" value="${block.caption || ''}" oninput="updateBlockField(${index}, 'caption', this.value)" placeholder="Contoh: Video cara penggumpalan BentoCat" class="w-full bg-slate-900 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-200 focus:outline-none">
                        </div>
                    </div>
                `;
            }

            blockDiv.innerHTML = headerHtml + fieldsHtml;
            container.appendChild(blockDiv);
        });
    }

    function getBlockTypeName(type) {
        const names = {
            paragraph: "Paragraf / Teks",
            quick_answer: "Jawaban Cepat",
            dialog: "Dialog Percakapan",
            analogy: "Analogi",
            dalil: "Riset / Studi",
            doa: "Tips Khusus",
            image: "Gambar / Foto",
            video: "Video Link"
        };
        return names[type] || type;
    }

    function addBlock(type) {
        let blockSchema = { type: type };
        if (type === 'paragraph' || type === 'quick_answer') {
            blockSchema.heading = '';
            blockSchema.text = '';
            blockSchema.referenceUrl = '';
        } else if (type === 'dialog') {
            blockSchema.lines = [{ role: 'anak', text: '' }];
        } else if (type === 'analogy') {
            blockSchema.title = '';
            blockSchema.text = '';
        } else if (type === 'dalil' || type === 'doa') {
            blockSchema.title = '';
            blockSchema.arabic = '';
            blockSchema.translation = '';
            blockSchema.source = '';
            blockSchema.sourceUrl = '';
        } else if (type === 'image') {
            blockSchema.url = '';
            blockSchema.caption = '';
        } else if (type === 'video') {
            blockSchema.url = '';
            blockSchema.caption = '';
        }

        blocks.push(blockSchema);
        renderBlocks();
    }

    function updateBlockField(index, field, value) {
        blocks[index][field] = value;
    }

    // Dialog block helpers
    function addDialogLine(blockIndex) {
        blocks[blockIndex].lines.push({ role: 'anak', text: '' });
        renderBlocks();
    }

    function updateDialogLine(blockIndex, lineIndex, field, value) {
        blocks[blockIndex].lines[lineIndex][field] = value;
    }

    function deleteDialogLine(blockIndex, lineIndex) {
        blocks[blockIndex].lines.splice(lineIndex, 1);
        renderBlocks();
    }

    function deleteBlock(index) {
        if (confirm("Apakah Anda yakin ingin menghapus blok konten ini?")) {
            blocks.splice(index, 1);
            renderBlocks();
        }
    }

    function moveBlock(index, offset) {
        const target = index + offset;
        if (target < 0 || target >= blocks.length) return;
        
        const temp = blocks[index];
        blocks[index] = blocks[target];
        blocks[target] = temp;
        
        renderBlocks();
    }

    // AJAX image uploading
    function uploadBlockImage(input, index) {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        input.disabled = true;

        fetch('{{ route("admin.articles.upload-image") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            input.disabled = false;
            if (data.success) {
                blocks[index].url = data.url;
                renderBlocks();
            } else {
                alert("Upload gagal: " + (data.message || "Kesalahan tidak diketahui"));
            }
        })
        .catch(error => {
            input.disabled = false;
            console.error("Error uploading image:", error);
            alert("Terjadi kesalahan koneksi saat mengunggah gambar.");
        });
    }

    // Sync state on form submit
    document.getElementById('article-form').addEventListener('submit', function (e) {
        document.getElementById('content_json').value = JSON.stringify(blocks);
    });

    // Render initially
    renderBlocks();

    // AI Writing Assistant Event Listener
    document.getElementById('btn-ai-assist').addEventListener('click', async function () {
        const title = document.getElementById('title').value;
        const summary = document.getElementById('summary').value;

        if (!title.trim()) {
            alert('Silakan isi Judul Artikel terlebih dahulu untuk memulai analisis AI.');
            return;
        }

        const btn = document.getElementById('btn-ai-assist');
        const loading = document.getElementById('ai-loading');
        const errorBox = document.getElementById('ai-error');
        const results = document.getElementById('ai-results');

        btn.disabled = true;
        btn.classList.add('opacity-50');
        loading.classList.remove('hidden');
        errorBox.classList.add('hidden');
        results.classList.add('hidden');

        try {
            const response = await fetch('{{ route("admin.articles.ai-assist") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ title, summary })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Gagal mengambil rekomendasi AI.');
            }

            // Populate Outline
            const outlineUl = document.getElementById('ai-outline');
            outlineUl.innerHTML = '';
            data.outline.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item;
                outlineUl.appendChild(li);
            });

            // Populate FAQs
            const faqsDiv = document.getElementById('ai-faqs');
            faqsDiv.innerHTML = '';
            data.faqs.forEach(faq => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'space-y-0.5 border-b border-slate-850 pb-2 last:border-b-0 last:pb-0';
                itemDiv.innerHTML = `
                    <p class="font-bold text-slate-300">Q: ${faq.q}</p>
                    <p class="text-slate-400">${faq.a}</p>
                `;
                faqsDiv.appendChild(itemDiv);
            });

            // Populate SEO Recommendations
            document.getElementById('ai-seo-title').textContent = data.seo_title;
            document.getElementById('ai-seo-desc').textContent = data.seo_description;

            // Handle Apply SEO Button Click
            document.getElementById('btn-apply-seo').onclick = function () {
                document.getElementById('seo_title').value = data.seo_title;
                document.getElementById('seo_description').value = data.seo_description;
                alert('Rekomendasi judul dan deskripsi SEO berhasil diterapkan!');
            };

            // Populate Internal Links
            const linksUl = document.getElementById('ai-links');
            linksUl.innerHTML = '';
            data.internal_links.forEach(link => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-1.5';
                li.innerHTML = `
                    <span class="text-slate-500 mt-0.5">▪</span> 
                    <a href="${link.url}" target="_blank" class="text-amber-400 hover:underline hover:text-amber-500 transition-all">${link.title}</a>
                `;
                linksUl.appendChild(li);
            });

            results.classList.remove('hidden');
        } catch (e) {
            errorBox.textContent = e.message || 'Terjadi kesalahan sistem saat menghubungi server AI.';
            errorBox.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-50');
            loading.classList.add('hidden');
        }
    });
</script>
@endsection
