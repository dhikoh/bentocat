@extends('layouts.client')

@section('title', 'BentoCat - Premium Cat Litter Discovery & Lead Intelligence')

@section('content')
<div class="space-y-24">

    <!-- 1. Hero Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8 md:pt-24 flex flex-col items-center text-center space-y-6">
        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider select-none">
            🐾 BentoCat Premium Bentonite Cat Litter
        </span>
        <h1 class="font-outfit font-black text-4xl sm:text-6xl lg:text-7xl tracking-tight text-white max-w-4xl leading-[1.1]">
            Hemat Ongkir, Beli Pasir Kucing di <span class="bg-gradient-to-r from-amber-400 to-amber-600 bg-clip-text text-transparent">Petshop Terdekat</span> Anda!
        </h1>
        <p class="text-slate-400 text-sm sm:text-lg max-w-2xl leading-relaxed">
            Hindari potongan besar marketplace yang membuat harga jadi mahal. Temukan outlet resmi penjual BentoCat di kota Anda dan nikmati harga wajar petshop lokal.
        </p>
        <div class="pt-4 flex flex-col sm:flex-row gap-4 justify-center w-full max-w-md">
            <a href="#cari-outlet" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all text-sm sm:text-base">
                Cari Outlet Terdekat 📍
            </a>
            <a href="#katalog" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 font-semibold px-8 py-3.5 rounded-xl transition-all text-sm sm:text-base">
                Lihat Katalog Produk
            </a>
        </div>
    </section>

    <!-- 2. Keunggulan Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-slate-900/40 border border-slate-900 p-8 rounded-3xl backdrop-blur-sm space-y-4">
                <span class="text-3xl">⚡</span>
                <h3 class="font-outfit font-bold text-lg text-white">Gumpalan Instan & Kuat</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Menyerap cairan dalam hitungan detik untuk mengunci urin seketika. Gumpalan padat tidak mudah pecah saat diserok, menjaga pasir bersih tetap higienis.
                </p>
            </div>
            <div class="bg-slate-900/40 border border-slate-900 p-8 rounded-3xl backdrop-blur-sm space-y-4">
                <span class="text-3xl">🌸</span>
                <h3 class="font-outfit font-bold text-lg text-white">Kontrol Bau Ekstra (Scented)</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Dilengkapi dengan aroma wangi premium (Lavender, Coffee, Apple) yang menyebarkan kesegaran alami dan menetralkan bau amonia kotoran secara maksimal.
                </p>
            </div>
            <div class="bg-slate-900/40 border border-slate-900 p-8 rounded-3xl backdrop-blur-sm space-y-4">
                <span class="text-3xl">🍃</span>
                <h3 class="font-outfit font-bold text-lg text-white">Minim Debu (Dust-Free)</h3>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Diproses melalui penyaringan ganda sehingga aman bagi pernapasan kucing peliharaan maupun pemilik rumah. Mencegah noda jejak kaki kucing di lantai.
                </p>
            </div>
        </div>
    </section>

    <!-- 3. Katalog Section -->
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24 space-y-12">
        <div class="text-center space-y-3">
            <h2 class="font-outfit font-black text-3xl sm:text-4xl text-white">Katalog BentoCat Premium</h2>
            <p class="text-sm text-slate-400 max-w-xl mx-auto">Varian pasir kucing bentonit premium dengan penawaran kualitas gumpalan tinggi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($products as $product)
                <div class="bg-slate-900/30 border border-slate-900 rounded-3xl overflow-hidden group hover:border-amber-500/30 transition-all flex flex-col justify-between">
                    <div class="p-6 space-y-4">
                        <div class="aspect-square bg-slate-950 rounded-2xl overflow-hidden border border-slate-900 flex items-center justify-center text-5xl">
                            @if($product->thumbnail)
                                <img src="{{ $product->thumbnail }}" alt="{{ $product->nama }}" class="w-full h-full object-cover">
                            @else
                                🐈
                            @endif
                        </div>
                        <div>
                            <h3 class="font-outfit font-bold text-lg text-white group-hover:text-amber-400 transition-all">{{ $product->nama }}</h3>
                            <span class="block text-[10px] text-slate-500 font-mono mt-0.5">ID: PROD-00{{ $product->id }}</span>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            {{ $product->deskripsi ? strip_tags($product->deskripsi) : 'Pasir bentonit wangi gumpal kualitas premium.' }}
                        </p>
                    </div>
                    <div class="p-6 border-t border-slate-900 bg-slate-950/40">
                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Varian Tersedia:</span>
                        <div class="flex flex-wrap gap-1">
                            @forelse($product->variants->whereNull('parent_id') as $v1)
                                <span class="bg-slate-900 text-slate-300 text-[10px] font-medium px-2 py-0.5 rounded border border-slate-800">{{ $v1->nama }}</span>
                            @empty
                                <span class="text-xs text-slate-600 italic">Varian standar saja.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-slate-500 italic">
                    Belum ada katalog produk terdaftar.
                </div>
            @endforelse
        </div>
    </section>

    <!-- 4. Lead Capture Form & Search -->
    <section id="cari-outlet" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24 space-y-8">
        <div class="text-center space-y-3">
            <h2 class="font-outfit font-black text-3xl sm:text-4xl text-white">Temukan Petshop Penjual</h2>
            <p class="text-sm text-slate-400 max-w-xl mx-auto">Silakan isi formulir pencarian singkat di bawah untuk diarahkan ke petshop terdekat dengan koordinat lokasi Anda.</p>
        </div>

        <div class="bg-slate-900/40 border border-slate-900 p-6 sm:p-10 rounded-3xl backdrop-blur-md">
            
            <!-- Global error notifications -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-xs text-rose-400 space-y-1">
                    @foreach($errors->all() as $error)
                        <p>⚠️ {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('search-outlet') }}" method="POST" id="lead-form" class="space-y-6">
                @csrf
                
                <!-- Honeypot -->
                <div style="display: none;">
                    <input type="text" name="email_check" id="email_check" tabindex="-1" autocomplete="off">
                </div>

                <!-- Hidden GPS Coordinates -->
                <input type="hidden" name="latitude" id="latitude_input">
                <input type="hidden" name="longitude" id="longitude_input">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Client Name -->
                    <div>
                        <label for="nama" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nama Lengkap Anda</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama panggilan..." class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                    </div>

                    <!-- Client WhatsApp -->
                    <div>
                        <label for="whatsapp" class="block text-xs font-bold text-slate-400 uppercase mb-2">Nomor WhatsApp Aktif</label>
                        <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" required placeholder="Contoh: 08123456789" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">
                        <span class="block text-[10px] text-slate-500 mt-1">Kami menggunakan nomor ini untuk generate link obrolan ke WhatsApp outlet.</span>
                    </div>
                </div>

                <!-- Client Address -->
                <div>
                    <label for="alamat" class="block text-xs font-bold text-slate-400 uppercase mb-2">Alamat Pengiriman / Rumah Anda</label>
                    <textarea name="alamat" id="alamat" rows="3" required placeholder="Tuliskan alamat lengkap pengiriman untuk perhitungan ongkir kurir lokal..." class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none transition-all">{{ old('alamat') }}</textarea>
                </div>

                <!-- Geolocation trigger -->
                <div class="bg-slate-950/60 p-4 rounded-2xl border border-slate-850 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="space-y-1 text-center sm:text-left">
                        <span class="block text-xs font-bold text-white">Izinkan Geolocation (GPS Browser)</span>
                        <span class="block text-[10px] text-slate-500">Membantu sistem menyortir petshop dari jarak km terdekat secara matematis.</span>
                    </div>
                    <button type="button" id="gps-btn" onclick="requestGPS()" class="bg-slate-900 border border-slate-850 hover:border-amber-500/50 hover:text-amber-400 text-slate-300 font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all shrink-0">
                        <span>Bagikan Koordinat</span> 📍
                    </button>
                </div>

                <!-- Regions Selector Dropdowns -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Province Select -->
                    <div>
                        <label for="provinsi_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Provinsi Tujuan</label>
                        <select name="provinsi_id" id="provinsi_id" required onchange="loadCities(this.value)" class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-3 py-2.5 text-sm text-slate-300 focus:outline-none transition-all">
                            <option value="">Pilih Provinsi...</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- City Select -->
                    <div>
                        <label for="kota_id" class="block text-xs font-bold text-slate-400 uppercase mb-2">Kota / Kabupaten</label>
                        <select name="kota_id" id="kota_id" required disabled class="w-full bg-slate-950 border border-slate-800 focus:border-amber-500 rounded-xl px-3 py-2.5 text-sm text-slate-300 focus:outline-none transition-all disabled:opacity-40">
                            <option value="">Pilih Kota...</option>
                        </select>
                    </div>
                </div>

                <!-- Product Selection & Variant cascades -->
                <div class="bg-slate-950/20 p-5 rounded-2xl border border-slate-900 space-y-4">
                    <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wider border-b border-slate-850 pb-2">Produk Yang Dicari</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Product Selection -->
                        <div class="md:col-span-2">
                            <label for="produk_id" class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Model Pasir</label>
                            <select name="produk_id" id="produk_id" required onchange="onProductChange(this.value)" class="w-full bg-slate-950 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none">
                                <option value="">Pilih Produk...</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Variant Lvl 1: Kategori -->
                        <div>
                            <label for="varian_level_1" class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Kategori / Seri</label>
                            <select name="varian_level_1" id="varian_level_1" disabled onchange="onLevel1Change(this.value)" class="w-full bg-slate-950 border border-slate-850 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none disabled:opacity-40">
                                <option value="">Pilih Kategori...</option>
                            </select>
                        </div>

                        <!-- Variant Lvl 2: Aroma -->
                        <div>
                            <label for="varian_level_2" class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Aroma / Scent</label>
                            <select name="varian_level_2" id="varian_level_2" disabled onchange="onLevel2Change(this.value)" class="w-full bg-slate-950 border border-slate-855 focus:border-amber-500 rounded-xl px-3 py-2 text-xs text-slate-300 focus:outline-none disabled:opacity-40">
                                <option value="">Pilih Aroma...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Turnstile widget -->
                @if(env('TURNSTILE_SITE_KEY'))
                    <div class="flex justify-center pt-2">
                        <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}"></div>
                    </div>
                @endif

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3.5 rounded-xl shadow-lg shadow-amber-500/10 transition-all text-sm uppercase tracking-wide">
                        Cari Outlet Terdekat & Hubungi WA 🐾
                    </button>
                </div>

            </form>
        </div>
    </section>

    <!-- 5. Artikel & SEO Blog Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-3">
                <h2 class="font-outfit font-black text-3xl text-white">Tips & Edukasi Perawatan Kucing</h2>
                <p class="text-sm text-slate-400 max-w-md">Ketahui tips merawat kotak pasir kucing, menjaga kesegaran rumah, dan memilih jenis pasir terbaik.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-xs font-bold text-amber-500 hover:underline shrink-0">Lihat Semua Artikel →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <article class="bg-slate-900/20 border border-slate-900 rounded-3xl overflow-hidden group hover:border-amber-500/20 transition-all flex flex-col justify-between">
                    <div class="p-5 space-y-4">
                        <div class="aspect-[16/10] bg-slate-950 rounded-2xl overflow-hidden border border-slate-900 flex items-center justify-center text-3xl font-serif text-slate-700">
                            📚
                        </div>
                        <div class="space-y-2">
                            <span class="block text-[10px] text-slate-500">{{ $article->published_at ? $article->published_at->format('d M Y') : 'Draft' }}</span>
                            <h3 class="font-outfit font-bold text-base text-white group-hover:text-amber-400 transition-all">
                                <a href="{{ route('blog.show', $article->slug) }}">{{ $article->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-400 leading-relaxed line-clamp-3">
                                {{ $article->summary ?: 'Baca info selengkapnya tentang artikel perawatan kucing ini...' }}
                            </p>
                        </div>
                    </div>
                    <div class="p-5 border-t border-slate-900 bg-slate-950/20 flex justify-between items-center text-xs">
                        <span class="text-slate-500">Penulis: {{ $article->author->name }}</span>
                        <a href="{{ route('blog.show', $article->slug) }}" class="font-bold text-amber-500 group-hover:underline">Baca Lengkap</a>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12 text-slate-500 italic">Belum ada tulisan artikel yang dipublikasikan.</div>
            @endforelse
        </div>
    </section>

</div>
@endsection

@section('scripts')
@if(env('TURNSTILE_SITE_KEY'))
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif

<script>
    // Injected Products Data for Cascading Variants Selection
    const productsData = {!! json_encode($products) !!};

    function requestGPS() {
        const btn = document.getElementById('gps-btn');
        if (!navigator.geolocation) {
            alert('Peramban Anda tidak mendukung penangkapan lokasi Geolocation.');
            return;
        }

        btn.innerText = "Mengambil Lokasi...";
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                document.getElementById('latitude_input').value = pos.coords.latitude;
                document.getElementById('longitude_input').value = pos.coords.longitude;
                btn.innerHTML = "Koordinat Berhasil Disinkronkan ✓";
                btn.className = "bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold px-4 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all shrink-0";
            },
            (err) => {
                btn.innerText = "Koordinat Gagal Ditemukan ❌";
                btn.disabled = false;
                console.error(err);
                alert('Gagal mengambil koordinat lokasi. Periksa izin akses lokasi peramban Anda.');
            },
            { enableHighAccuracy: true, timeout: 5000 }
        );
    }

    function loadCities(provinceId) {
        const citySelect = document.getElementById('kota_id');
        citySelect.innerHTML = '<option value="">Memuat...</option>';
        citySelect.disabled = true;

        if (!provinceId) {
            citySelect.innerHTML = '<option value="">Pilih Kota...</option>';
            return;
        }

        fetch(`/api/cities-by-province/${provinceId}`)
            .then(res => res.json())
            .then(data => {
                citySelect.innerHTML = '<option value="">Pilih Kota...</option>';
                data.forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city.id;
                    opt.innerText = city.nama;
                    citySelect.appendChild(opt);
                });
                citySelect.disabled = false;
            })
            .catch(err => {
                console.error("Error loading cities:", err);
                citySelect.innerHTML = '<option value="">Gagal Memuat Kota</option>';
            });
    }

    // Dynamic Select Cascading logic for variants
    function onProductChange(productId) {
        const lvl1Select = document.getElementById('varian_level_1');
        const lvl2Select = document.getElementById('varian_level_2');

        lvl1Select.innerHTML = '<option value="">Pilih Kategori...</option>';
        lvl1Select.disabled = true;
        lvl2Select.innerHTML = '<option value="">Pilih Aroma...</option>';
        lvl2Select.disabled = true;

        if (!productId) return;

        const product = productsData.find(p => p.id == productId);
        if (!product || !product.variants) return;

        // Level 1: Root variants (parent_id is null)
        const lvl1Variants = product.variants.filter(v => v.parent_id === null);

        if (lvl1Variants.length > 0) {
            lvl1Variants.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.nama;
                opt.dataset.id = v.id;
                opt.innerText = v.nama;
                lvl1Select.appendChild(opt);
            });
            lvl1Select.disabled = false;
        }
    }

    function onLevel1Change(value) {
        const lvl1Select = document.getElementById('varian_level_1');
        const lvl2Select = document.getElementById('varian_level_2');

        lvl2Select.innerHTML = '<option value="">Pilih Aroma...</option>';
        lvl2Select.disabled = true;

        const selectedOpt = lvl1Select.options[lvl1Select.selectedIndex];
        const parentId = selectedOpt.dataset.id;
        if (!parentId) return;

        const productId = document.getElementById('produk_id').value;
        const product = productsData.find(p => p.id == productId);
        if (!product) return;

        // Level 2: Scent variants (parent_id matches level 1 variant id)
        const lvl2Variants = product.variants.filter(v => v.parent_id == parentId);

        if (lvl2Variants.length > 0) {
            lvl2Variants.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.nama;
                opt.dataset.id = v.id;
                opt.innerText = v.nama;
                lvl2Select.appendChild(opt);
            });
            lvl2Select.disabled = false;
        }
    }

    function onLevel2Change(value) {
        // Safe to add level 3 if size lists are needed later.
    }
</script>
@endsection
