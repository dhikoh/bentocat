@extends('layouts.client')

@section('title', 'Daftar Lengkap Petshop & Outlet Resmi BentoCat Seluruh Indonesia')
@section('meta_description', 'Cari dan temukan daftar lengkap petshop mitra resmi BentoCat terdekat di kota Anda. Dapatkan pasir kucing bentonit premium asli berkualitas ekspor.')
@section('schema')
@php
    $provinceElements = [];
    foreach ($provinces as $index => $province) {
        $cityElements = [];
        foreach ($province->cities as $cityIndex => $city) {
            $cityElements[] = [
                '@type' => 'ListItem',
                'position' => $cityIndex + 1,
                'item' => [
                    '@type' => 'WebPage',
                    'name' => 'Petshop BentoCat di ' . $city->nama,
                    'url' => route('city.landing', $city->slug)
                ]
            ];
        }
        $provinceElements[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => [
                '@type' => 'ItemList',
                'name' => $province->nama,
                'numberOfItems' => $province->cities->count(),
                'itemListElement' => $cityElements
            ]
        ];
    }

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
                    'name' => 'Direktori Petshop',
                    'item' => url()->current()
                ]
            ]
        ],
        [
            '@type' => 'ItemList',
            '@id' => url()->current() . '#itemList',
            'name' => 'Daftar Petshop BentoCat Resmi Indonesia',
            'description' => 'Cari petshop mitra resmi BentoCat terdekat di berbagai provinsi dan kota.',
            'numberOfItems' => $provinces->count(),
            'itemListElement' => $provinceElements
        ]
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => $schemaGraph
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection


@section('head')
<style>
    .province-toggle, .city-toggle {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .province-chevron, .city-chevron {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rotate-180 {
        transform: rotate(180deg);
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 space-y-16">

    <!-- Hero Section -->
    <div class="text-center space-y-6 max-w-3xl mx-auto">
        <span class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/20 text-amber-700 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider select-none font-outfit">
            🌐 Direktori Nasional BentoCat
        </span>
        <h1 class="font-outfit font-black text-4xl sm:text-5xl text-slate-900 tracking-tight leading-tight">
            Daftar Resmi Petshop & Mitra <span class="bg-gradient-to-r from-amber-500 to-amber-600 bg-clip-text text-transparent">BentoCat</span>
        </h1>
        <p class="text-slate-650 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
            Temukan lokasi toko hewan peliharaan terdekat di wilayah Anda yang menjual pasir kucing BentoCat Premium wangi gumpal asli berkualitas ekspor.
        </p>

        <!-- Dynamic Real-time Directory Search -->
        <div class="max-w-md mx-auto relative pt-4">
            <div class="relative">
                <input type="text" id="directory-search" placeholder="Cari wilayah, kota, atau nama petshop..." class="w-full pl-12 pr-10 py-4 bg-white border border-[#e5e0d8] focus:border-amber-500 rounded-2xl text-xs sm:text-sm shadow-sm transition-premium focus:outline-none text-slate-700 placeholder-slate-400">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <button id="clear-search" class="hidden absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors font-bold text-lg border-none bg-transparent cursor-pointer">
                    &times;
                </button>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium">Menyaring data dan membuka menu collapse secara otomatis saat Anda mengetik.</p>
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden text-center py-16 bg-white border border-[#e5e0d8] rounded-[2rem] space-y-4 max-w-4xl mx-auto">
        <span class="text-4xl">😿</span>
        <h3 class="font-outfit font-bold text-lg text-slate-800">Petshop Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto">Tidak ada petshop atau kota yang cocok dengan kata kunci pencarian Anda. Silakan coba kata kunci lain.</p>
    </div>

    <!-- Collapsible Provinces & Cities Directory Container -->
    <div class="max-w-4xl mx-auto space-y-4" id="directory-container">
        @foreach($provinces as $province)
            <!-- Province Card Wrapper -->
            <div class="province-wrapper border border-[#e5e0d8]/80 rounded-[2rem] bg-white shadow-sm overflow-hidden transition-premium" data-province-name="{{ $province->nama }}">
                
                <!-- Province Toggle Header -->
                <button type="button" class="w-full flex items-center justify-between p-6 text-left font-outfit font-black text-lg sm:text-xl text-slate-900 bg-[#FAF8F5]/40 hover:bg-[#FAF8F5]/80 transition-premium focus:outline-none province-toggle cursor-pointer border-none">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🗺️</span>
                        <span>{{ $province->nama }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-bold bg-white border border-[#e5e0d8] text-slate-500 px-3 py-1 rounded-full shrink-0">
                            {{ $province->cities->flatMap->outlets->count() }} Outlet
                        </span>
                        <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300 province-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>

                <!-- Province Content Container (Collapsible) -->
                <div class="province-content hidden border-t border-[#e5e0d8]/60 p-6 space-y-4 bg-white">
                    @foreach($province->cities as $city)
                        <!-- City Wrapper -->
                        <div class="city-wrapper border border-[#e5e0d8]/50 rounded-[1.5rem] bg-[#FAF8F5]/30 overflow-hidden" data-city-name="{{ $city->nama }}">
                            
                            <!-- City Toggle Header -->
                            <button type="button" class="w-full flex items-center justify-between px-5 py-4 text-left font-outfit font-extrabold text-sm sm:text-base text-slate-700 hover:bg-[#FAF8F5]/60 transition-premium focus:outline-none city-toggle cursor-pointer border-none">
                                <div class="flex items-center gap-2">
                                    <span class="text-amber-500 text-xs">📍</span>
                                    <span>{{ $city->nama }}</span>
                                    <a href="{{ route('city.landing', $city->slug) }}" class="text-[11px] text-amber-600 hover:text-amber-700 capitalize font-bold tracking-normal transition-premium ml-2 stop-propagation" title="Lihat halaman kota">
                                        (Info Wilayah &rarr;)
                                    </a>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold bg-white border border-[#e5e0d8] text-slate-500 px-2.5 py-0.5 rounded-full shrink-0">
                                        {{ $city->outlets->count() }}
                                    </span>
                                    <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-300 city-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            <!-- City Content Container (Collapsible) -->
                            <div class="city-content hidden border-t border-[#e5e0d8]/40 p-4 space-y-3 bg-white">
                                @foreach($city->outlets as $outlet)
                                    @php
                                        $message = "Halo " . $outlet->nama_outlet . ", saya ingin bertanya ketersediaan pasir kucing BentoCat di toko Anda.";
                                        $waUrl = "https://wa.me/" . $outlet->formatted_whatsapp . "?text=" . urlencode($message);
                                    @endphp
                                    <div class="outlet-item bg-[#FAF8F5]/40 hover:bg-[#FAF8F5]/80 border border-slate-100 p-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-premium" data-outlet-name="{{ $outlet->nama_outlet }}">
                                        
                                        <!-- Outlet Info -->
                                        <div class="space-y-1.5 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h4 class="font-bold text-slate-900 text-sm outlet-name">{{ $outlet->nama_outlet }}</h4>
                                                
                                                @if($outlet->featured)
                                                    <span class="inline-flex items-center gap-0.5 bg-amber-100 text-amber-800 text-[9px] font-bold px-2 py-0.5 rounded-lg border border-amber-200/20" title="Rekomendasi BentoCat">
                                                        ⭐ Rekomendasi
                                                    </span>
                                                @endif

                                                @if($outlet->is_mitra)
                                                    <span class="inline-flex items-center gap-0.5 bg-emerald-500/10 text-emerald-800 text-[9px] font-bold px-2 py-0.5 rounded-lg border border-emerald-500/20" title="Mitra BentoCat Resmi">
                                                        🤝 Mitra Resmi
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-500 leading-relaxed outlet-address">{{ $outlet->alamat_lengkap }}</p>
                                        </div>

                                        <!-- Outlet Actions -->
                                        <div class="flex items-center gap-2 self-start sm:self-center shrink-0">
                                            @if($outlet->google_maps_url)
                                                <a href="{{ $outlet->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="p-2.5 bg-white border border-[#e5e0d8] hover:border-slate-300 text-slate-400 hover:text-slate-700 rounded-xl transition-premium shadow-sm flex items-center justify-center" title="Petunjuk Arah Google Maps">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                            @endif

                                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" 
                                               class="flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-md shadow-emerald-500/10 transition-premium whitespace-nowrap btn-chat-wa cursor-pointer"
                                               data-outlet-id="{{ $outlet->id }}"
                                               data-distributor-id="{{ $outlet->distributor_id }}"
                                               data-provinsi-id="{{ $province->id }}"
                                               data-kota-id="{{ $city->id }}"
                                               data-wa-url="{{ $waUrl }}">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.457L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.968C16.688 2.008 14.195.979 11.999.979c-5.441 0-9.87 4.372-9.874 9.802-.001 1.785.47 3.524 1.364 5.086l-.994 3.633 3.737-.968c1.524.832 3.084 1.25 4.525 1.25zm8.932-6.843c-.274-.136-1.62-.8-1.87-.893-.249-.093-.43-.139-.61.139-.18.27-.7.893-.857 1.076-.159.182-.317.205-.591.069-.275-.136-1.16-.427-2.21-1.366-.817-.729-1.37-1.628-1.53-1.9-.16-.272-.016-.42.12-.557.123-.122.274-.32.412-.478.136-.159.182-.272.274-.453.092-.18.046-.341-.022-.478-.068-.137-.61-1.472-.836-2.018-.22-.53-.443-.458-.61-.466-.157-.008-.339-.009-.521-.009-.18 0-.476.068-.724.341-.249.272-.952.931-.952 2.271s.975 2.634 1.112 2.816c.137.182 1.92 2.925 4.65 4.103.65.28 1.157.446 1.553.571.654.207 1.25.178 1.72.108.523-.078 1.62-.663 1.847-1.302.228-.638.228-1.185.16-1.302-.069-.118-.249-.185-.523-.321z" />
                                                </svg>
                                                Chat WA
                                            </a>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>

    <!-- Contact Info Callout -->
    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 sm:p-14 text-center max-w-4xl mx-auto space-y-6 shadow-xl relative overflow-hidden">
        <div class="absolute -top-12 -left-12 w-48 h-48 bg-amber-500/10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl"></div>
        
        <h3 class="font-outfit font-black text-2xl sm:text-3xl text-white relative z-10">Ingin Menjadi Petshop Mitra Kami?</h3>
        <p class="text-xs sm:text-sm font-medium leading-relaxed max-w-xl mx-auto text-slate-400 relative z-10">
            Dapatkan keuntungan suplai produk langsung, promosi gratis di website nasional kami, serta prioritas rujukan konsumen dengan mendaftarkan petshop Anda sebagai Mitra Resmi BentoCat.
        </p>
        <div class="pt-4 relative z-10">
            <a href="https://wa.me/{{ \App\Models\Setting::get('contact_whatsapp', '6287777717300') }}?text=Halo%20BentoCat,%20saya%2520tertarik%2520untuk%2520mendaftarkan%2520petshop%2520saya%2520sebagai%2520mitra%2520resmi." target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-4 rounded-2xl text-xs uppercase tracking-wider transition-premium shadow-lg">
                <span>Daftar Mitra via WhatsApp</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

</div>

<!-- Scroll to Top Button -->
<button id="btn-back-to-top" class="fixed bottom-6 right-6 z-50 hidden bg-amber-500 hover:bg-amber-600 text-slate-950 p-3 rounded-full shadow-lg shadow-amber-500/20 hover:shadow-xl transition-premium focus:outline-none border-none cursor-pointer">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<!-- Lead Capture WhatsApp Modal Popup -->
<div id="lead-capture-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md">
    <div class="bg-[#FAF8F5] rounded-[2rem] p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-6 relative border border-[#e5e0d8] transform scale-95 opacity-0 transition-premium" id="lead-modal-box">
        
        <!-- Close Button -->
        <button type="button" id="close-lead-modal" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 font-bold text-2xl focus:outline-none transition-colors border-none bg-transparent cursor-pointer">
            &times;
        </button>
        
        <!-- Modal Icon & Header -->
        <div class="text-center space-y-2.5">
            <span class="text-4xl block">💬</span>
            <h3 class="font-outfit font-black text-xl text-slate-900 leading-tight">Hubungi Petshop</h3>
            <p class="text-xs text-slate-500 leading-relaxed px-2">
                Gunakan nomor WhatsApp aktif Anda untuk mendapatkan kesempatan klaim promo atau e-coupon BentoCat di petshop mitra resmi kami apabila program promo sedang berjalan.
            </p>
        </div>

        <!-- Capture Form -->
        <form id="lead-capture-form" class="space-y-4">
            <!-- Name Input -->
            <div class="space-y-1">
                <label for="customer-name" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" id="customer-name" required placeholder="Masukkan nama Anda..." class="w-full px-4 py-3 bg-white border border-[#e5e0d8] focus:border-amber-500 rounded-xl text-xs sm:text-sm transition-premium focus:outline-none text-slate-700 placeholder-slate-400">
            </div>

            <!-- WhatsApp Input -->
            <div class="space-y-1">
                <label for="customer-whatsapp" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">Nomor WhatsApp</label>
                <input type="tel" id="customer-whatsapp" required placeholder="Contoh: 081234567890" class="w-full px-4 py-3 bg-white border border-[#e5e0d8] focus:border-amber-500 rounded-xl text-xs sm:text-sm transition-premium focus:outline-none text-slate-700 placeholder-slate-400">
                <p id="whatsapp-error-msg" class="hidden text-[10px] text-red-500 font-semibold mt-1"></p>
            </div>

            <!-- Hidden context fields -->
            <input type="hidden" id="modal-outlet-id" value="">
            <input type="hidden" id="modal-distributor-id" value="">
            <input type="hidden" id="modal-provinsi-id" value="">
            <input type="hidden" id="modal-kota-id" value="">
            <input type="hidden" id="modal-wa-url" value="">

            <!-- Submit button -->
            <button type="submit" id="submit-lead-btn" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-xl text-xs uppercase tracking-wider shadow-md shadow-emerald-500/10 transition-premium flex items-center justify-center gap-2 select-none border-none cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.457L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.968C16.688 2.008 14.195.979 11.999.979c-5.441 0-9.87 4.372-9.874 9.802-.001 1.785.47 3.524 1.364 5.086l-.994 3.633 3.737-.968c1.524.832 3.084 1.25 4.525 1.25zm8.932-6.843c-.274-.136-1.62-.8-1.87-.893-.249-.093-.43-.139-.61.139-.18.27-.7.893-.857 1.076-.159.182-.317.205-.591.069-.275-.136-1.16-.427-2.21-1.366-.817-.729-1.37-1.628-1.53-1.9-.16-.272-.016-.42.12-.557.123-.122.274-.32.412-.478.136-.159.182-.272.274-.453.092-.18.046-.341-.022-.478-.068-.137-.61-1.472-.836-2.018-.22-.53-.443-.458-.61-.466-.157-.008-.339-.009-.521-.009-.18 0-.476.068-.724.341-.249.272-.952.931-.952 2.271s.975 2.634 1.112 2.816c.137.182 1.92 2.925 4.65 4.103.65.28 1.157.446 1.553.571.654.207 1.25.178 1.72.108.523-.078 1.62-.663 1.847-1.302.228-.638.228-1.185.16-1.302-.069-.118-.249-.185-.523-.321z" />
                </svg>
                <span id="btn-text">Lanjutkan ke WhatsApp</span>
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('directory-search');
    const clearSearchBtn = document.getElementById('clear-search');
    const emptyState = document.getElementById('empty-state');
    const provinceWrappers = document.querySelectorAll('.province-wrapper');
    const backToTopBtn = document.getElementById('btn-back-to-top');

    // Lead Modal Elements
    const leadModal = document.getElementById('lead-capture-modal');
    const leadModalBox = document.getElementById('lead-modal-box');
    const closeLeadBtn = document.getElementById('close-lead-modal');
    const leadForm = document.getElementById('lead-capture-form');
    const submitBtn = document.getElementById('submit-lead-btn');
    const submitBtnText = document.getElementById('btn-text');
    
    const inputName = document.getElementById('customer-name');
    const inputWhatsapp = document.getElementById('customer-whatsapp');
    const whatsappErrorMsg = document.getElementById('whatsapp-error-msg');

    const modalOutletId = document.getElementById('modal-outlet-id');
    const modalDistributorId = document.getElementById('modal-distributor-id');
    const modalProvinsiId = document.getElementById('modal-provinsi-id');
    const modalKotaId = document.getElementById('modal-kota-id');
    const modalWaUrl = document.getElementById('modal-wa-url');

    // 1. Accordion Toggle Handlers
    document.querySelectorAll('.province-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const chevron = this.querySelector('.province-chevron');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        });
    });

    document.querySelectorAll('.city-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (e.target.closest('.stop-propagation')) {
                return;
            }
            const content = this.nextElementSibling;
            const chevron = this.querySelector('.city-chevron');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        });
    });

    // 2. Real-time Search and Auto-Expansion
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const isSearching = query.length > 0;

            if (isSearching) {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }

            let anyVisible = false;

            provinceWrappers.forEach(provWrapper => {
                const provinceName = provWrapper.getAttribute('data-province-name').toLowerCase();
                const provinceMatches = provinceName.includes(query);
                
                const provToggle = provWrapper.querySelector('.province-toggle');
                const provContent = provWrapper.querySelector('.province-content');
                const provChevron = provWrapper.querySelector('.province-chevron');
                
                let provHasMatch = false;
                const cityWrappers = provWrapper.querySelectorAll('.city-wrapper');

                cityWrappers.forEach(cityWrapper => {
                    const cityName = cityWrapper.getAttribute('data-city-name').toLowerCase();
                    const cityMatches = cityName.includes(query);
                    
                    const cityToggle = cityWrapper.querySelector('.city-toggle');
                    const cityContent = cityWrapper.querySelector('.city-content');
                    const cityChevron = cityWrapper.querySelector('.city-chevron');
                    
                    let cityHasMatch = false;
                    const outlets = cityWrapper.querySelectorAll('.outlet-item');

                    outlets.forEach(outlet => {
                        const outletName = outlet.getAttribute('data-outlet-name').toLowerCase();
                        const address = outlet.querySelector('.outlet-address').textContent.toLowerCase();
                        const outletMatches = outletName.includes(query) || address.includes(query);

                        if (provinceMatches || cityMatches || outletMatches) {
                            outlet.classList.remove('hidden');
                            cityHasMatch = true;
                        } else {
                            outlet.classList.add('hidden');
                        }
                    });

                    if (cityHasMatch || cityMatches) {
                        cityWrapper.classList.remove('hidden');
                        provHasMatch = true;

                        // Auto-expand city if searching
                        if (isSearching) {
                            cityContent.classList.remove('hidden');
                            cityChevron.classList.add('rotate-180');
                        } else {
                            cityContent.classList.add('hidden');
                            cityChevron.classList.remove('rotate-180');
                        }
                    } else {
                        cityWrapper.classList.add('hidden');
                        cityContent.classList.add('hidden');
                        cityChevron.classList.remove('rotate-180');
                    }
                });

                if (provHasMatch || provinceMatches) {
                    provWrapper.classList.remove('hidden');
                    anyVisible = true;

                    // Auto-expand province if searching
                    if (isSearching) {
                        provContent.classList.remove('hidden');
                        provChevron.classList.add('rotate-180');
                    } else {
                        provContent.classList.add('hidden');
                        provChevron.classList.remove('rotate-180');
                    }
                } else {
                    provWrapper.classList.add('hidden');
                    provContent.classList.add('hidden');
                    provChevron.classList.remove('rotate-180');
                }
            });

            // Show empty state if no province cards are visible
            if (anyVisible) {
                emptyState.classList.add('hidden');
            } else {
                emptyState.classList.remove('hidden');
            }
        });

        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            });
        }
    }

    // 3. Scroll to top button visibility & behavior
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopBtn.classList.remove('hidden');
        } else {
            backToTopBtn.classList.add('hidden');
        }
    });

    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // 4. Lead Capture Modal Event Handlers
    document.querySelectorAll('.btn-chat-wa').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            // Set hidden data values
            modalOutletId.value = this.getAttribute('data-outlet-id') || '';
            modalDistributorId.value = this.getAttribute('data-distributor-id') || '';
            modalProvinsiId.value = this.getAttribute('data-provinsi-id') || '';
            modalKotaId.value = this.getAttribute('data-kota-id') || '';
            modalWaUrl.value = this.getAttribute('data-wa-url') || '';

            // Auto-fill from LocalStorage if user previously filled their profile
            const savedName = localStorage.getItem('customer_name');
            const savedWhatsapp = localStorage.getItem('customer_whatsapp');
            if (savedName) inputName.value = savedName;
            if (savedWhatsapp) inputWhatsapp.value = savedWhatsapp;

            // Show Modal with Animation
            leadModal.classList.remove('hidden');
            setTimeout(() => {
                leadModalBox.classList.remove('scale-95', 'opacity-0');
                leadModalBox.classList.add('scale-100', 'opacity-100');
            }, 10);
        });
    });

    function closeModal() {
        leadModalBox.classList.remove('scale-100', 'opacity-100');
        leadModalBox.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            leadModal.classList.add('hidden');
            whatsappErrorMsg.classList.add('hidden');
            whatsappErrorMsg.textContent = '';
        }, 200);
    }

    closeLeadBtn.addEventListener('click', closeModal);

    leadModal.addEventListener('click', function(e) {
        if (e.target === leadModal) {
            closeModal();
        }
    });

    // 5. Form Validation & AJAX Submission
    leadForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const nameVal = inputName.value.trim();
        const rawWhatsappVal = inputWhatsapp.value.trim();
        const cleanWhatsappVal = rawWhatsappVal.replace(/[^0-9]/g, '');

        // Validation 1: Name length
        if (nameVal.length < 3) {
            showError("Nama Lengkap minimal 3 karakter.");
            return;
        }

        // Validation 2: WhatsApp length & prefix
        if (cleanWhatsappVal.length < 9 || cleanWhatsappVal.length > 14) {
            showError("Nomor WhatsApp harus berukuran 9-14 digit.");
            return;
        }
        if (!cleanWhatsappVal.startsWith('08') && !cleanWhatsappVal.startsWith('628')) {
            showError("Nomor WhatsApp harus diawali dengan 08 atau 628.");
            return;
        }

        // Validation 3: Anti-spam repeating pattern (e.g. 1111111)
        const repeatingPattern = /(.)\1{5,}/;
        if (repeatingPattern.test(cleanWhatsappVal)) {
            showError("Nomor WhatsApp tidak valid (terdeteksi pola berulang).");
            return;
        }

        // Validation 4: Anti-spam sequential pattern (e.g. 123456789)
        const sequentialPattern = /12345678|23456789|0812345678/;
        if (sequentialPattern.test(cleanWhatsappVal)) {
            showError("Nomor WhatsApp tidak valid (terdeteksi pola berurutan).");
            return;
        }

        whatsappErrorMsg.classList.add('hidden');

        // Save valid profile details locally to browser storage for future visits
        localStorage.setItem('customer_name', nameVal);
        localStorage.setItem('customer_whatsapp', cleanWhatsappVal);

        // Prepare request body
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        // Disable button & show spinner/loading state
        submitBtn.disabled = true;
        submitBtn.classList.remove('bg-emerald-500', 'hover:bg-emerald-600');
        submitBtn.classList.add('bg-slate-400', 'cursor-not-allowed');
        submitBtnText.textContent = "Mengirim...";

        const payload = {
            nama: nameVal,
            whatsapp: cleanWhatsappVal,
            provinsi_id: modalProvinsiId.value,
            kota_id: modalKotaId.value,
            produk_id: "",
            outlet_id: modalOutletId.value,
            distributor_id: modalDistributorId.value,
            action_type: 'CLICK_WA_OUTLET'
        };

        fetch('/api/leads/create-and-log', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP error " + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Re-enable submit button
            resetSubmitButton();
            closeModal();
            // Open WA link
            window.open(modalWaUrl.value, '_blank');
        })
        .catch(error => {
            console.error('AJAX logging error:', error);
            // Fallback: Proceed to WhatsApp anyway so user journey is never blocked on connection timeout
            resetSubmitButton();
            closeModal();
            window.open(modalWaUrl.value, '_blank');
        });
    });

    function showError(msg) {
        whatsappErrorMsg.textContent = msg;
        whatsappErrorMsg.classList.remove('hidden');
    }

    function resetSubmitButton() {
        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-slate-400', 'cursor-not-allowed');
        submitBtn.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
        submitBtnText.textContent = "Lanjutkan ke WhatsApp";
    }
});
</script>
@endsection
