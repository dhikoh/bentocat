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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Produk</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           placeholder="Contoh: BentoCat Premium Bentonite Cat Litter" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Gambar Thumbnail Produk</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <!-- Upload File Input -->
                        <div class="border-2 border-dashed border-slate-800 hover:border-amber-500/50 rounded-2xl p-5 flex flex-col items-center justify-center bg-slate-950/40 transition-all group relative cursor-pointer min-h-[140px]">
                            <input type="file" name="thumbnail_file" id="thumbnail_file" accept="image/png, image/jpeg, image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this)">
                            <div class="flex flex-col items-center text-center space-y-2 pointer-events-none">
                                <span class="text-3xl text-slate-500 group-hover:scale-110 transition-transform">📷</span>
                                <span class="text-xs font-semibold text-slate-300">Pilih File Gambar (PNG/JPG)</span>
                                <span class="text-[10px] text-slate-500" id="file-name-label">Maks. 2MB</span>
                            </div>
                        </div>

                        <!-- URL Fallback Input -->
                        <div class="space-y-3">
                            <div class="text-center md:hidden">
                                <span class="text-[10px] font-bold text-slate-700 uppercase tracking-widest">— ATAU —</span>
                            </div>
                            <label for="thumbnail" class="block text-[10px] font-bold text-slate-500 uppercase">Atau Masukkan URL Gambar</label>
                            <input type="text" name="thumbnail" id="thumbnail" value="{{ old('thumbnail') }}" 
                                   placeholder="Contoh: https://bentocat.com/img/litter-thumb.jpg" 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none transition-all">
                            
                            <!-- Live Preview Container -->
                            <div id="thumbnail-preview-container" class="hidden border border-slate-800/80 p-3 rounded-2xl bg-slate-900/60 flex gap-3 items-center max-w-sm">
                                <img id="thumbnail-preview" src="#" alt="Preview" class="w-14 h-14 object-cover rounded-lg border border-slate-850">
                                <div class="overflow-hidden">
                                    <span class="block text-[10px] font-bold text-emerald-400 uppercase">Preview Aktif</span>
                                    <span class="block text-[10px] text-slate-400 truncate" id="preview-path"></span>
                                </div>
                            </div>
                        </div>
                    </div>
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

<script>
function previewImage(input) {
    const file = input.files[0];
    const previewContainer = document.getElementById('thumbnail-preview-container');
    const preview = document.getElementById('thumbnail-preview');
    const nameLabel = document.getElementById('file-name-label');
    const pathLabel = document.getElementById('preview-path');
    const urlInput = document.getElementById('thumbnail');

    if (file) {
        nameLabel.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
            pathLabel.textContent = file.name + ' (Siap diunggah)';
            urlInput.value = '';
        }
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const urlInput = document.getElementById('thumbnail');
    const fileInput = document.getElementById('thumbnail_file');
    const nameLabel = document.getElementById('file-name-label');
    const previewContainer = document.getElementById('thumbnail-preview-container');
    const preview = document.getElementById('thumbnail-preview');
    const pathLabel = document.getElementById('preview-path');

    if (urlInput.value.trim() !== '') {
        preview.src = urlInput.value;
        previewContainer.classList.remove('hidden');
        pathLabel.textContent = urlInput.value;
    }

    urlInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            preview.src = this.value;
            previewContainer.classList.remove('hidden');
            pathLabel.textContent = this.value;
            fileInput.value = '';
            nameLabel.textContent = 'Maks. 2MB';
        } else {
            previewContainer.classList.add('hidden');
            pathLabel.textContent = '';
        }
    });
});
</script>
@endsection
