@extends('layouts.client')

@section('title', 'Jual Pasir Kucing BentoCat Terdekat di ' . $city->nama . ' - Gumpal Premium')
@section('meta_description', 'Temukan distributor resmi dan outlet petshop terdekat yang menjual pasir kucing BentoCat Premium di ' . $city->nama . '. Harga murah, hemat ongkir!')

@section('schema')
@php
    $schemaGraph = [
        [
            '@type' => 'BreadcrumbList',
            '@id' => url()->current() . '#breadcrumb',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => url('/')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Pasir Kucing di ' . $city->nama,
                    'item' => url()->current()
                ]
            ]
        ]
    ];

    if ($outlets->count() > 0) {
        $itemListElements = [];
        foreach ($outlets as $index => $outlet) {
            $itemListElements[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'LocalBusiness',
                    'name' => $outlet->nama_outlet,
                    'image' => asset('images/logo.png'),
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $outlet->alamat_lengkap,
                        'addressLocality' => $city->nama,
                        'addressRegion' => $province->nama,
                        'addressCountry' => 'ID'
                    ],
                    'telephone' => $outlet->whatsapp
                ]
            ];
        }
        $schemaGraph[] = [
            '@type' => 'ItemList',
            '@id' => url()->current() . '#itemList',
            'name' => 'Petshop Resmi BentoCat di ' . $city->nama,
            'numberOfItems' => $outlets->count(),
            'itemListElement' => $itemListElements
        ];
    } elseif ($distributor) {
        $schemaGraph[] = [
            '@type' => 'LocalBusiness',
            'name' => 'Distributor BentoCat ' . $city->nama . ' - ' . $distributor->nama,
            'image' => asset('images/logo.png'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $distributor->alamat,
                'addressLocality' => $city->nama,
                'addressRegion' => $province->nama,
                'addressCountry' => 'ID'
            ],
            'telephone' => $distributor->whatsapp
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => $schemaGraph
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-20">

    <!-- SEO Title & Headings -->
    <div class="text-center space-y-6 max-w-3xl mx-auto">
        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/20 text-amber-700 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider select-none font-outfit">
            <span class="animate-pulse text-amber-500">📍</span> Pasir Kucing Premium {{ $city->nama }}
        </span>
        <h1 class="font-outfit font-black text-4xl sm:text-5xl text-slate-900 tracking-tight leading-tight">
            Agen & Petshop Resmi Jual Pasir Kucing BentoCat di <span class="bg-gradient-to-r from-amber-500 to-amber-600 bg-clip-text text-transparent">{{ $city->nama }}</span>
        </h1>
        <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
            Dapatkan pasir kucing bentonit wangi gumpal berkualitas ekspor merk BentoCat di kota Anda. Beli langsung dari petshop mitra kami untuk menghindari ongkos kirim marketplace yang mahal.
        </p>
    </div>

    <!-- Active outlets in this city -->
    <div class="space-y-8">
        <div class="flex items-center justify-between border-b border-[#e5e0d8] pb-4">
            <h2 class="font-outfit font-black text-xl text-slate-900 flex items-center gap-2">
                <span>Daftar Toko & Petshop Penjual Resmi</span>
                <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ $outlets->count() }}</span>
            </h2>
        </div>

        <div class="space-y-4">
            @forelse($outlets as $outlet)
                <div class="bg-white border {{ !$outlet->is_mitra ? 'border-[#e5e0d8]/80 opacity-90 shadow-sm' : ($outlet->featured ? 'border-amber-500/80 shadow-md shadow-amber-500/5 bg-amber-50/5' : 'border-[#e5e0d8]/80 shadow-sm') }} p-6 sm:p-8 rounded-3xl flex flex-col md:flex-row md:items-center justify-between gap-6 hover:shadow-md hover:border-amber-500/30 transition-premium">
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="p-2 bg-amber-500/10 rounded-xl">
                                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                            <h3 class="font-outfit font-extrabold text-lg text-slate-900">{{ $outlet->nama_outlet }}</h3>
                            
                            @if($outlet->is_mitra)
                                @if($outlet->featured)
                                    <span class="inline-flex items-center gap-1 bg-amber-500/10 border border-amber-500/20 text-amber-700 text-[9px] font-bold px-2.5 py-0.5 rounded-lg uppercase tracking-wider">
                                        ⭐ Rekomendasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 text-[9px] font-bold px-2.5 py-0.5 rounded-lg uppercase tracking-wider">
                                        ✓ Mitra Aktif
                                    </span>
                                @endif
                            @endif
                        </div>

                        <!-- Address with pin icon -->
                        <div class="flex items-start gap-2 max-w-2xl text-slate-655">
                            <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-xs sm:text-sm leading-relaxed">{{ $outlet->alamat_lengkap }}</span>
                        </div>

                        <!-- Delivery mode badge with icons -->
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="inline-flex items-center gap-1.5 bg-[#FAF8F5] border border-[#e5e0d8] text-slate-600 px-3 py-1 rounded-xl text-xs font-semibold">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10c0 .6.4 1 1 1h1m-6 0a2 2 0 002 2h10a2 2 0 002-2m-1-1h1a1 1 0 001-1v-4a1 1 0 00-.29-.707l-2-2A1 1 0 0016 7h-3" />
                                </svg>
                                <span>Mode Kirim: 
                                    @if($outlet->delivery_mode === 'RECOMMENDED_SHIPPING_CONTACT')
                                        <span class="text-indigo-700 font-bold">Kurir Eksternal 🛵</span>
                                    @elseif($outlet->delivery_mode === 'SELF_DELIVERY')
                                        <span class="text-amber-700 font-bold">Pengantaran Toko 🚚</span>
                                    @elseif($outlet->delivery_mode === 'PICKUP_ONLY')
                                        <span class="text-emerald-700 font-bold">Ambil Sendiri 🏪</span>
                                    @else
                                        <span class="text-slate-700 font-bold">Semua Mode</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- WA Direct button with active logging -->
                    @php
                        if ($outlet->is_mitra) {
                             $message = "Halo {$outlet->nama_outlet}, saya melihat toko Anda terdaftar di website BentoCat {$city->nama}. Apakah stok pasir BentoCat sedang ready?";
                        } else {
                             $message = "Halo {$outlet->nama_outlet}, saya melihat toko Anda di bentocat.id. Apakah Anda menyediakan pasir kucing BentoCat? Saya sangat tertarik untuk membelinya.";
                        }
                        $waUrl = "https://wa.me/" . $outlet->formatted_whatsapp . "?text=" . urlencode($message);
                    @endphp
                    <div class="shrink-0 flex items-center justify-end w-full md:w-auto">
                        <a href="{{ $waUrl }}" target="_blank" class="w-full md:w-auto bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-6 py-3.5 rounded-2xl transition-premium shadow-md hover:shadow-lg flex items-center justify-center gap-2 select-none border-none">
                            <svg class="w-4 h-4 text-slate-950" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.488 1.459 5.421 1.46h.005c5.732 0 10.396-4.661 10.399-10.393.002-2.777-1.078-5.388-3.044-7.356C17.463 3.9 14.858 2.81 12.013 2.81c-5.733 0-10.4 4.662-10.403 10.395-.001 1.88.493 3.717 1.432 5.323L2.04 20.73l2.25-.59c1.61.882 3.41 1.346 5.257 1.347h.1z"/>
                            </svg>
                            <span>Chat WhatsApp Toko</span>
                        </a>
                    </div>
                </div>
            @empty
                <!-- Fallback distributor info -->
                <div class="bg-white border border-[#e5e0d8] p-12 rounded-[2.5rem] text-center shadow-md space-y-6 max-w-xl mx-auto">
                    <span class="text-4xl block">🐈</span>
                    <div class="space-y-2">
                        <h4 class="font-outfit font-black text-slate-900 text-lg">Belum Ada Petshop Mitra Terdaftar</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                            Kami belum memiliki mitra petshop offline terdaftar di area ini. Silakan hubungi distributor utama untuk pembelian eceran/grosir langsung dari gudang.
                        </p>
                    </div>

                    @if($distributor)
                        <div class="bg-[#FAF8F5] max-w-md mx-auto p-6 rounded-3xl border border-[#e5e0d8] text-left space-y-4 shadow-inner">
                            <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-widest">Distributor Regional:</span>
                            <div class="text-xs space-y-1.5">
                                <strong class="block text-sm text-slate-900 font-bold">{{ $distributor->nama }}</strong>
                                <span class="block text-slate-600">WhatsApp: {{ $distributor->whatsapp }}</span>
                                <span class="block text-slate-600">Alamat: {{ $distributor->alamat }}</span>
                            </div>
                            @php
                                $messageDist = "Halo {$distributor->nama}, saya ingin bertanya tentang ketersediaan pasir kucing BentoCat di wilayah {$city->nama}.";
                                $waDistUrl = "https://wa.me/" . $distributor->formatted_whatsapp . "?text=" . urlencode($messageDist);
                            @endphp
                            <a href="{{ $waDistUrl }}" target="_blank" class="w-full bg-white border border-[#e5e0d8] hover:border-amber-500/50 hover:text-amber-700 text-slate-700 text-xs font-bold py-3 rounded-xl transition-premium flex items-center justify-center gap-1.5 shadow-sm">
                                <span>Hubungi Distributor</span> 💬
                            </a>
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Distributor wilayah belum terdaftar.</p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick action to lead search form -->
    <div class="border-t border-[#e5e0d8] pt-12 text-center space-y-4">
        <h3 class="font-outfit font-bold text-lg text-slate-900">Ingin koordinat Anda disinkronkan secara presisi?</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">Gunakan formulir pencarian interaktif di halaman utama untuk menghitung rute kurir dan melacak jarak km terdekat.</p>
        <a href="{{ route('home') }}#cari-outlet" class="inline-block bg-white hover:bg-slate-50 border border-[#e5e0d8] text-amber-700 hover:text-amber-800 font-bold text-xs px-6 py-3.5 rounded-xl shadow-sm transition-premium">
            Gunakan Geolocation Pencarian Utama 📍
        </a>
    </div>

</div>
@endsection
