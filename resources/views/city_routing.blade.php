@extends('layouts.client')

@section('title', 'Jual Pasir Kucing BentoCat Terdekat di ' . $city->nama . ' - Gumpal Premium')
@section('meta_description', 'Temukan distributor resmi dan outlet petshop terdekat yang menjual pasir kucing BentoCat Premium di ' . $city->nama . '. Harga murah, hemat ongkir!')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">

    <!-- SEO Title & Headings -->
    <div class="text-center space-y-4 max-w-3xl mx-auto">
        <span class="inline-flex items-center gap-1 bg-amber-500/10 border border-amber-500/20 text-amber-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            📍 Pasir Kucing Premium {{ $city->nama }}
        </span>
        <h1 class="font-outfit font-black text-4xl sm:text-5xl text-slate-900 tracking-tight leading-tight">
            Agen & Petshop Resmi Jual Pasir Kucing BentoCat di <span class="bg-gradient-to-r from-amber-500 to-amber-600 bg-clip-text text-transparent">{{ $city->nama }}</span>
        </h1>
        <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
            Dapatkan pasir kucing bentonit wangi gumpal berkualitas ekspor merk BentoCat di kota Anda. Beli langsung dari petshop mitra kami untuk menghindari ongkos kirim marketplace yang mahal.
        </p>
    </div>

    <!-- Active outlets in this city -->
    <div class="space-y-6">
        <h2 class="font-outfit font-bold text-lg text-slate-800 border-b border-slate-200/80 pb-3">
            Daftar Toko & Petshop Penjual Resmi ({{ $outlets->count() }})
        </h2>

        @forelse($outlets as $outlet)
            <div class="bg-white border {{ !$outlet->is_mitra ? 'border-slate-200/80 opacity-90 shadow-sm' : ($outlet->featured ? 'border-amber-500 shadow-md bg-amber-50/10' : 'border-slate-100 shadow-md') }} p-6 rounded-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-6 hover:shadow-lg transition-all">
                <div class="space-y-2">
                    <h3 class="font-outfit font-bold text-lg text-slate-900 flex items-center gap-2">
                        <span>🏪 {{ $outlet->nama_outlet }}</span>
                        @if($outlet->is_mitra)
                            @if($outlet->featured)
                                <span class="bg-amber-50 border border-amber-100 text-amber-700 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase">Rekomendasi</span>
                            @endif
                        @else
                            <span class="bg-slate-100 border border-slate-200 text-slate-550 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase">Mitra Non-Aktif</span>
                        @endif
                    </h3>
                    <p class="text-xs text-slate-550 max-w-xl">{{ $outlet->alamat_lengkap }}</p>
                    <div class="flex gap-4 text-[10px] text-slate-450">
                        <span>Mode Kirim: 
                            @if(!$outlet->is_mitra)
                                Belum Menyediakan BentoCat
                            @elseif($outlet->delivery_mode === 'SELF_DELIVERY')
                                Pengantaran Toko
                            @elseif($outlet->delivery_mode === 'RECOMMENDED_SHIPPING_CONTACT')
                                Kurir Eksternal
                            @elseif($outlet->delivery_mode === 'PICKUP_ONLY')
                                Ambil Sendiri
                            @else
                                Semua Mode
                            @endif
                        </span>
                    </div>
                </div>

                <!-- WA Direct (logs via simulated lead trigger if not filled, or simple wa.me link directly for guest routing) -->
                @php
                    if ($outlet->is_mitra) {
                         $message = "Halo {$outlet->nama_outlet}, saya melihat toko Anda terdaftar di website BentoCat {$city->nama}. Apakah stok pasir BentoCat sedang ready?";
                    } else {
                         $message = "Halo {$outlet->nama_outlet}, saya melihat toko Anda di bentocat.id. Apakah Anda menyediakan pasir kucing BentoCat? Saya sangat tertarik untuk membelinya.";
                    }
                    $waUrl = "https://wa.me/" . $outlet->formatted_whatsapp . "?text=" . urlencode($message);
                @endphp
                <a href="{{ $waUrl }}" target="_blank" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-1.5 shrink-0">
                    <span>Chat WhatsApp Toko</span> 💬
                </a>
            @empty
                <!-- Fallback distributor info -->
                <div class="bg-white border border-slate-100 p-8 rounded-3xl text-center shadow-md space-y-6">
                    <span class="text-3xl">🐈</span>
                    <div class="space-y-1.5">
                        <h4 class="font-bold text-slate-900 text-base">Belum Ada Petshop Mitra Terdaftar</h4>
                        <p class="text-xs text-slate-655 max-w-md mx-auto leading-relaxed">
                            Kami belum memiliki mitra petshop offline terdaftar di area ini. Silakan hubungi distributor utama untuk pembelian eceran/grosir langsung dari gudang.
                        </p>
                    </div>

                    @if($distributor)
                        <div class="bg-slate-50 max-w-sm mx-auto p-5 rounded-2xl border border-slate-100 text-left space-y-3 shadow-sm">
                            <span class="block text-[9px] font-bold text-slate-400 uppercase">Distributor Regional:</span>
                            <div class="text-xs space-y-1">
                                <strong class="block text-slate-900">{{ $distributor->nama }}</strong>
                                <span class="block text-slate-600">WhatsApp: {{ $distributor->whatsapp }}</span>
                                <span class="block text-slate-600">Alamat: {{ $distributor->alamat }}</span>
                            </div>
                            @php
                                $messageDist = "Halo {$distributor->nama}, saya ingin bertanya tentang ketersediaan pasir kucing BentoCat di wilayah {$city->nama}.";
                                $waDistUrl = "https://wa.me/" . $distributor->formatted_whatsapp . "?text=" . urlencode($messageDist);
                            @endphp
                            <a href="{{ $waDistUrl }}" target="_blank" class="w-full bg-white border border-slate-200 hover:border-amber-500/30 hover:text-amber-650 text-slate-650 text-xs font-bold py-2.5 rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                <span>Hubungi Distributor</span> 💬
                            </a>
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Distributor wilayah belum terdaftar.</p>
                    @endif
                </div>
            @endforelse
    </div>

    <!-- Quick action to lead search form -->
    <div class="border-t border-slate-200/80 pt-12 text-center space-y-4">
        <h3 class="font-outfit font-bold text-lg text-slate-900">Ingin koordinat Anda disinkronkan secara presisi?</h3>
        <p class="text-xs text-slate-600 max-w-sm mx-auto">Gunakan formulir pencarian interaktif di halaman utama untuk menghitung rute kurir dan melacak jarak km terdekat.</p>
        <a href="{{ route('home') }}#cari-outlet" class="inline-block bg-white hover:bg-slate-50 border border-slate-200 text-amber-650 hover:text-amber-700 font-bold text-xs px-6 py-3 rounded-xl shadow-sm transition-all">
            Gunakan Geolocation Pencarian Utama 📍
        </a>
    </div>

</div>
@endsection
