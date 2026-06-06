@extends('layouts.admin')

@section('title', 'Edit Outlet - ' . $outlet->nama_outlet)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.outlets.index') }}" class="text-sm font-bold text-amber-500 hover:underline">← Kembali</a>
    </div>

    <div>
        <h1 class="text-2xl font-bold text-white">Edit Outlet: {{ $outlet->nama_outlet }}</h1>
        <p class="text-sm text-slate-400">Perbarui rincian toko retail, koordinat maps, status stok, atau kurir pengiriman rekomendasi.</p>
    </div>

    <div class="bg-slate-900/40 border border-slate-800/80 p-6 md:p-8 rounded-3xl backdrop-blur-md">
        <form action="{{ route('admin.outlets.update', $outlet->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_outlet" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Outlet / Petshop</label>
                    <input type="text" name="nama_outlet" id="nama_outlet" value="{{ old('nama_outlet', $outlet->nama_outlet) }}" required 
                           placeholder="Contoh: Mutiara Petshop Juanda" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="distributor_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Distributor Penyuplai</label>
                    <select name="distributor_id" id="distributor_id" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                        <option value="">-- Pilih Distributor --</option>
                        @foreach($distributors as $distributor)
                            <option value="{{ $distributor->id }}" {{ old('distributor_id', $outlet->distributor_id) == $distributor->id ? 'selected' : '' }}>
                                {{ $distributor->nama }} ({{ $distributor->city->nama }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="provinsi_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Provinsi Lokasi</label>
                    <select name="provinsi_id" id="provinsi_id" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}" {{ old('provinsi_id', $outlet->city ? $outlet->city->provinsi_id : null) == $province->id ? 'selected' : '' }}>
                                {{ $province->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="kota_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Kota Lokasi</label>
                    <select name="kota_id" id="kota_id" required 
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                        <option value="">-- Pilih Kota --</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('kota_id', $outlet->kota_id) == $city->id ? 'selected' : '' }}>
                                {{ $city->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_pic" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama PIC Toko</label>
                    <input type="text" name="nama_pic" id="nama_pic" value="{{ old('nama_pic', $outlet->nama_pic) }}" required 
                           placeholder="Contoh: Rian Pratama" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="whatsapp" class="block text-xs font-bold text-slate-400 uppercase mb-2">WhatsApp Outlet</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $outlet->whatsapp) }}" required 
                           placeholder="Contoh: 628987654321" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                    <span class="block text-[10px] text-slate-500 mt-1">Tanpa spasi, diawali kode negara (628...).</span>
                </div>
            </div>

            <div>
                <label for="alamat_lengkap" class="block text-xs font-bold text-slate-400 uppercase mb-2">Alamat Lengkap Outlet</label>
                <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" required 
                          placeholder="Jalan Raya Juanda No. 12, Sidoarjo..." 
                          class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">{{ old('alamat_lengkap', $outlet->alamat_lengkap) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="latitude" class="block text-xs font-bold text-slate-400 uppercase mb-2">Latitude (Koordinat GPS)</label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $outlet->latitude) }}" 
                           placeholder="Contoh: -7.375682" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="longitude" class="block text-xs font-bold text-slate-400 uppercase mb-2">Longitude (Koordinat GPS)</label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $outlet->longitude) }}" 
                           placeholder="Contoh: 112.768932" 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>

                <div>
                    <label for="google_maps_url" class="block text-xs font-bold text-slate-400 uppercase mb-2">Link Google Maps (Share Link)</label>
                    <input type="url" name="google_maps_url" id="google_maps_url" value="{{ old('google_maps_url', $outlet->google_maps_url) }}" 
                           placeholder="https://maps.app.goo.gl/..." 
                           class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                </div>
            </div>

            <!-- Delivery Mode -->
            <div class="border-t border-slate-800/80 pt-6">
                <label for="delivery_mode" class="block text-xs font-bold text-slate-400 uppercase mb-2">Metode Pengiriman Utama</label>
                <select name="delivery_mode" id="delivery_mode" required onchange="handleDeliveryModeChange()"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                    <option value="SELF_DELIVERY" {{ old('delivery_mode', $outlet->delivery_mode) === 'SELF_DELIVERY' ? 'selected' : '' }}>🛵 Pengiriman Mandiri oleh Outlet (Self-Delivery)</option>
                    <option value="RECOMMENDED_SHIPPING_CONTACT" {{ old('delivery_mode', $outlet->delivery_mode) === 'RECOMMENDED_SHIPPING_CONTACT' ? 'selected' : '' }}>📋 Menggunakan Kontak Kurir/Ekspedisi Rekomendasi</option>
                    <option value="PICKUP_ONLY" {{ old('delivery_mode', $outlet->delivery_mode) === 'PICKUP_ONLY' ? 'selected' : '' }}>🚶 Hanya Ambil di Tempat (Pickup Only)</option>
                </select>
            </div>

            <!-- Shipping Contacts Selection (Dynamic visibility) -->
            <div id="shipping-contacts-container" class="hidden bg-slate-950/60 border border-slate-850 p-6 rounded-2xl space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-white">Pilih Kontak Pengiriman Wilayah Rekomendasi</h3>
                    <p class="text-xs text-slate-500 mt-1">Pilih kurir lokal yang beroperasi untuk membantu pengiriman outlet ini. Urutkan berdasarkan prioritas rekomendasi.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($shippingContacts as $contact)
                        <label class="flex items-start gap-3 bg-slate-900/60 p-3 rounded-xl border border-slate-800 cursor-pointer select-none hover:border-slate-700">
                            <input type="checkbox" name="shipping_contacts[]" value="{{ $contact->id }}" 
                                   {{ in_array($contact->id, $selectedContacts) ? 'checked' : '' }}
                                   class="mt-0.5 rounded border-slate-850 text-amber-500 bg-slate-950">
                            <div>
                                <span class="block text-xs font-bold text-slate-200">{{ $contact->nama }}</span>
                                <span class="block text-[10px] text-slate-500">WA: {{ $contact->whatsapp }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Features & Status -->
            <div class="border-t border-slate-800/80 pt-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 text-sm text-slate-300 select-none cursor-pointer">
                        <input type="checkbox" name="featured" value="1" {{ $outlet->featured ? 'checked' : '' }} class="rounded border-slate-850 text-amber-500 bg-slate-950">
                        Featured / Toko Rekomendasi
                    </label>

                    <label class="flex items-center gap-2 text-sm text-slate-300 select-none cursor-pointer">
                        <input type="checkbox" name="is_mitra" value="1" {{ $outlet->is_mitra ? 'checked' : '' }} class="rounded border-slate-850 text-amber-500 bg-slate-950">
                        Mitra Resmi BentoCat
                    </label>

                    <label class="flex items-center gap-2 text-sm text-slate-300 select-none cursor-pointer">
                        <input type="checkbox" name="is_hidden" value="1" {{ $outlet->is_hidden ? 'checked' : '' }} class="rounded border-slate-850 text-amber-500 bg-slate-950">
                        Sembunyikan dari Pencarian
                    </label>

                    <label class="flex items-center gap-2 text-sm text-slate-300 select-none cursor-pointer">
                        <span class="text-xs font-bold text-slate-400 uppercase mr-1">Status:</span>
                        <select name="status" class="bg-slate-950 border border-slate-800 rounded-lg px-2 py-1 text-xs text-slate-200">
                            <option value="AKTIF" {{ $outlet->status === 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                            <option value="STOK_KOSONG" {{ $outlet->status === 'STOK_KOSONG' ? 'selected' : '' }}>STOK HABIS</option>
                            <option value="TUTUP" {{ $outlet->status === 'TUTUP' ? 'selected' : '' }}>TUTUP</option>
                            <option value="NONAKTIF" {{ $outlet->status === 'NONAKTIF' ? 'selected' : '' }}>NONAKTIF / SEMBUNYI</option>
                        </select>
                    </label>
                </div>

                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.outlets.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
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
@endsection

@section('scripts')
<script>
    function handleDeliveryModeChange() {
        const mode = document.getElementById('delivery_mode').value;
        const container = document.getElementById('shipping-contacts-container');
        if (mode === 'RECOMMENDED_SHIPPING_CONTACT') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    // Call on load
    document.addEventListener('DOMContentLoaded', () => {
        handleDeliveryModeChange();
        
        document.getElementById('provinsi_id').addEventListener('change', function() {
            const provinceId = this.value;
            const citySelect = document.getElementById('kota_id');
            citySelect.innerHTML = '<option value="">-- Loading Kota --</option>';
            if (!provinceId) {
                citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
                return;
            }
            
            fetch(`/api/cities-by-province/${provinceId}?admin=1`)
                .then(res => res.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
                    data.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.nama;
                        citySelect.appendChild(option);
                    });
                })
                .catch(err => {
                    console.error(err);
                    citySelect.innerHTML = '<option value="">-- Gagal memuat kota --</option>';
                });
        });
    });
</script>
@endsection
