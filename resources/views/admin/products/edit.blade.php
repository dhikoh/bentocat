@extends('layouts.admin')

@section('title', 'Edit Produk - ' . $product->nama)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Kembali</a>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white">Edit Produk: {{ $product->nama }}</h1>
        <p class="text-sm text-slate-400">Perbarui spesifikasi produk, gambar, atau status aktif publikasi.</p>
    </div>

    <div class="bg-slate-900/40 border border-slate-800/80 p-6 md:p-8 rounded-3xl backdrop-blur-md">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="cropped_image_data" id="cropped_image_data">

            <div class="space-y-6">
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Produk</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $product->nama) }}" required 
                           placeholder="Contoh: BentoCat Premium Bentonite Cat Litter" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Gambar Thumbnail Produk</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <!-- Upload File Input -->
                        <div class="border-2 border-dashed border-slate-800 hover:border-amber-500/50 rounded-2xl p-5 flex flex-col items-center justify-center bg-slate-950/40 transition-all group relative cursor-pointer min-h-[140px]">
                            <input type="file" name="thumbnail_file" id="thumbnail_file" accept="image/png, image/jpeg, image/jpg, video/mp4, video/quicktime, video/x-msvideo, video/webm" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this)">
                            <div class="flex flex-col items-center text-center space-y-2 pointer-events-none">
                                <span class="text-3xl text-slate-500 group-hover:scale-110 transition-transform">🎥</span>
                                <span class="text-xs font-semibold text-slate-300">Pilih Gambar / Video Produk</span>
                                <span class="text-[10px] text-slate-500" id="file-name-label">Maks. 20MB</span>
                            </div>
                        </div>

                        <!-- URL Fallback Input -->
                        <div class="space-y-3">
                            <div class="text-center md:hidden">
                                <span class="text-[10px] font-bold text-slate-700 uppercase tracking-widest">— ATAU —</span>
                            </div>
                            <label for="thumbnail" class="block text-[10px] font-bold text-slate-500 uppercase">Atau Masukkan URL Gambar</label>
                            <input type="text" name="thumbnail" id="thumbnail" value="{{ old('thumbnail', $product->thumbnail) }}" 
                                   placeholder="Contoh: https://bentocat.com/img/litter-thumb.jpg" 
                                   class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-xs text-slate-200 focus:outline-none transition-all">
                            
                            <!-- Live Preview Container -->
                            <div id="thumbnail-preview-container" class="{{ $product->thumbnail ? '' : 'hidden' }} border border-slate-800/80 p-3 rounded-2xl bg-slate-900/60 flex gap-3 items-center max-w-sm">
                                <img id="thumbnail-preview" src="{{ $product->thumbnail ? (str_starts_with($product->thumbnail, 'http') ? $product->thumbnail : asset($product->thumbnail)) : '#' }}" alt="Preview" class="w-14 h-14 object-cover rounded-lg border border-slate-850 bg-white">
                                <div class="overflow-hidden">
                                    <span class="block text-[10px] font-bold text-emerald-400 uppercase">Preview Aktif</span>
                                    <span class="block text-[10px] text-slate-400 truncate" id="preview-path">{{ $product->thumbnail }}</span>
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
                          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">{{ old('deskripsi', $product->deskripsi) }}</textarea>
            </div>

            <div class="border-t border-slate-800/80 pt-6 space-y-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Label Varian Produk (Kustom)</h3>
                <p class="text-xs text-slate-500">Sesuaikan penamaan label tingkatan varian untuk produk ini agar sesuai pada modal pencarian dan halaman kelola varian. Kosongkan untuk menggunakan label default.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="label_level_1" class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Label Tingkat 1 (Default: Kategori)</label>
                        <input type="text" name="label_level_1" id="label_level_1" value="{{ old('label_level_1', $product->label_level_1) }}" 
                               placeholder="Misal: Jenis Pasir, Rasa" 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label for="label_level_2" class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Label Tingkat 2 (Default: Varian)</label>
                        <input type="text" name="label_level_2" id="label_level_2" value="{{ old('label_level_2', $product->label_level_2) }}" 
                               placeholder="Misal: Aroma, Ukuran" 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label for="label_level_3" class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Label Tingkat 3 (Default: Ukuran)</label>
                        <input type="text" name="label_level_3" id="label_level_3" value="{{ old('label_level_3', $product->label_level_3) }}" 
                               placeholder="Misal: Kemasan, Berat" 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2 text-xs text-slate-200 focus:outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800/80 pt-6 flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-300 select-none cursor-pointer">
                    <span class="text-xs font-bold text-slate-400 uppercase mr-1">Status Publikasi:</span>
                    <select name="status" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-xs text-slate-200">
                        <option value="ACTIVE" {{ $product->status === 'ACTIVE' ? 'selected' : '' }}>ACTIVE / TAMPILKAN</option>
                        <option value="INACTIVE" {{ $product->status === 'INACTIVE' ? 'selected' : '' }}>INACTIVE / SEMBUNYIKAN</option>
                    </select>
                </label>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.products.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                        Batal
                    </a>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-amber-500/10 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Cropper.js (Client-side Image Cropping) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<style>
    .cropper-view-box,
    .cropper-face {
        border-radius: 8px;
    }
    .cropper-line, .cropper-point {
        background-color: #f59e0b;
    }
</style>

<!-- Cropper Modal -->
<div id="cropper-modal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 max-w-lg w-full space-y-4 shadow-2xl">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold text-white">Sesuaikan Potongan Gambar (1:1)</h3>
            <button type="button" onclick="closeCropperModal()" class="text-slate-400 hover:text-white text-xl">&times;</button>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white max-h-[300px] flex justify-center items-center">
            <img id="cropper-image" src="" alt="Source Image" class="max-w-full block bg-white">
        </div>
        <div class="flex justify-end gap-3 pt-2">
            <button type="button" onclick="closeCropperModal()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-5 py-2.5 rounded-xl text-xs font-semibold transition-all">Batal</button>
            <button type="button" onclick="cropAndSave()" class="bg-amber-500 hover:bg-amber-600 text-slate-950 px-5 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-amber-500/10 transition-all">Potong & Simpan</button>
        </div>
    </div>
</div>

<script>
let cropper = null;
let currentFileInput = null;

function previewImage(input) {
    const file = input.files[0];
    const previewContainer = document.getElementById('thumbnail-preview-container');
    const nameLabel = document.getElementById('file-name-label');
    const urlInput = document.getElementById('thumbnail');
    const croppedInput = document.getElementById('cropped_image_data');

    if (file) {
        nameLabel.textContent = file.name;

        if (file.type.startsWith('image/')) {
            currentFileInput = input;
            const reader = new FileReader();
            reader.onload = function(e) {
                const cropperImage = document.getElementById('cropper-image');
                cropperImage.src = e.target.result;

                document.getElementById('cropper-modal').classList.remove('hidden');
                document.getElementById('cropper-modal').classList.add('flex');

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false
                });
            };
            reader.readAsDataURL(file);
        } else {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = '';
                let mediaHtml = '';
                if (file.type.startsWith('video/')) {
                    mediaHtml = `<video id="thumbnail-preview" src="${e.target.result}" class="w-14 h-14 object-cover rounded-lg border border-slate-850" muted autoplay loop></video>`;
                } else {
                    mediaHtml = `<img id="thumbnail-preview" src="${e.target.result}" alt="Preview" class="w-14 h-14 object-cover rounded-lg border border-slate-850 bg-white">`;
                }
                mediaHtml += `
                    <div class="overflow-hidden">
                        <span class="block text-[10px] font-bold text-emerald-450 uppercase">Preview Aktif</span>
                        <span class="block text-[10px] text-slate-400 truncate" id="preview-path">${file.name} (Siap diunggah)</span>
                    </div>
                `;
                previewContainer.innerHTML = mediaHtml;
                previewContainer.classList.remove('hidden');
                urlInput.value = '';
                croppedInput.value = '';
            };
            reader.readAsDataURL(file);
        }
    }
}

function closeCropperModal() {
    document.getElementById('cropper-modal').classList.add('hidden');
    document.getElementById('cropper-modal').classList.remove('flex');
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    if (currentFileInput && !document.getElementById('cropped_image_data').value) {
        currentFileInput.value = '';
        document.getElementById('file-name-label').textContent = 'Maks. 20MB';
    }
}

function cropAndSave() {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({
        width: 800,
        height: 800,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high'
    });

    // Create a new canvas to draw a white background under the cropped transparent image
    const whiteCanvas = document.createElement('canvas');
    whiteCanvas.width = 800;
    whiteCanvas.height = 800;
    const ctx = whiteCanvas.getContext('2d');
    
    // Fill background with solid white
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, 800, 800);
    
    // Draw the cropped image on top of the white background
    ctx.drawImage(canvas, 0, 0, 800, 800);

    const dataUrl = whiteCanvas.toDataURL('image/png');
    document.getElementById('cropped_image_data').value = dataUrl;

    const previewContainer = document.getElementById('thumbnail-preview-container');
    const urlInput = document.getElementById('thumbnail');

    previewContainer.innerHTML = `
        <img id="thumbnail-preview" src="${dataUrl}" alt="Preview" class="w-14 h-14 object-cover rounded-lg border border-slate-850 bg-white">
        <div class="overflow-hidden">
            <span class="block text-[10px] font-bold text-emerald-450 uppercase">Preview Terpotong (1:1)</span>
            <span class="block text-[10px] text-slate-450 truncate" id="preview-path">Gambar berhasil dipotong</span>
        </div>
    `;
    previewContainer.classList.remove('hidden');
    urlInput.value = '';

    closeCropperModal();
}

document.addEventListener('DOMContentLoaded', function() {
    const urlInput = document.getElementById('thumbnail');
    const fileInput = document.getElementById('thumbnail_file');
    const nameLabel = document.getElementById('file-name-label');
    const previewContainer = document.getElementById('thumbnail-preview-container');

    const initialUrl = urlInput.value.trim();
    if (initialUrl !== '') {
        previewContainer.innerHTML = '';
        let mediaHtml = '';
        const isVideo = /\.(mp4|mov|avi|webm)$/i.test(initialUrl);
        const resolvedUrl = initialUrl.startsWith('http') ? initialUrl : '{{ asset('') }}' + initialUrl.replace(/^\//, '');
        if (isVideo) {
            mediaHtml = `<video id="thumbnail-preview" src="${resolvedUrl}" class="w-14 h-14 object-cover rounded-lg border border-slate-850" muted autoplay loop></video>`;
        } else {
            mediaHtml = `<img id="thumbnail-preview" src="${resolvedUrl}" alt="Preview" class="w-14 h-14 object-cover rounded-lg border border-slate-850 bg-white">`;
        }
        mediaHtml += `
            <div class="overflow-hidden">
                <span class="block text-[10px] font-bold text-emerald-450 uppercase">Preview Aktif</span>
                <span class="block text-[10px] text-slate-400 truncate" id="preview-path">${initialUrl}</span>
            </div>
        `;
        previewContainer.innerHTML = mediaHtml;
        previewContainer.classList.remove('hidden');
    }

    urlInput.addEventListener('input', function() {
        const val = this.value.trim();
        if (val !== '') {
            previewContainer.innerHTML = '';
            let mediaHtml = '';
            const isVideo = /\.(mp4|mov|avi|webm)$/i.test(val);
            if (isVideo) {
                mediaHtml = `<video id="thumbnail-preview" src="${val}" class="w-14 h-14 object-cover rounded-lg border border-slate-850" muted autoplay loop></video>`;
            } else {
                mediaHtml = `<img id="thumbnail-preview" src="${val}" alt="Preview" class="w-14 h-14 object-cover rounded-lg border border-slate-850 bg-white">`;
            }
            mediaHtml += `
                <div class="overflow-hidden">
                    <span class="block text-[10px] font-bold text-emerald-450 uppercase">Preview Aktif</span>
                    <span class="block text-[10px] text-slate-400 truncate" id="preview-path">${val}</span>
                </div>
            `;
            previewContainer.innerHTML = mediaHtml;
            previewContainer.classList.remove('hidden');
            fileInput.value = '';
            document.getElementById('cropped_image_data').value = '';
            nameLabel.textContent = 'Maks. 20MB';
        } else {
            previewContainer.classList.add('hidden');
            previewContainer.innerHTML = '';
        }
    });
});
</script>
@endsection
