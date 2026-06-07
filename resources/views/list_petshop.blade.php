@extends('layouts.client')

@section('title', 'Daftar Lengkap Petshop & Outlet Resmi BentoCat Seluruh Indonesia')
@section('meta_description', 'Cari dan temukan daftar lengkap petshop mitra resmi BentoCat terdekat di kota Anda. Dapatkan pasir kucing bentonit premium asli berkualitas ekspor.')

@section('head')
<style>
    .province-toggle, .city-toggle {
        transition: background-color 0.2s ease;
    }
    .province-chevron, .city-chevron {
        transition: transform 0.2s ease;
    }
    .rotate-180 {
        transform: rotate(180deg);
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 space-y-12">

    <!-- Hero Section -->
    <div class="text-center space-y-6 max-w-3xl mx-auto">
        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/25 text-amber-700 text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider">
            🌐 Direktori Nasional BentoCat
        </span>
        <h1 class="font-outfit font-black text-4xl sm:text-5xl text-slate-900 tracking-tight leading-tight">
            Daftar Resmi Petshop & Mitra <span class="bg-gradient-to-r from-amber-500 to-amber-600 bg-clip-text text-transparent">BentoCat</span>
        </h1>
        <p class="text-sm sm:text-base text-slate-605 leading-relaxed max-w-2xl mx-auto">
            Temukan lokasi toko hewan peliharaan terdekat di wilayah Anda yang menjual pasir kucing BentoCat Premium wangi gumpal asli berkualitas ekspor.
        </p>

        <!-- Dynamic Real-time Directory Search -->
        <div class="max-w-md mx-auto relative pt-4">
            <div class="relative">
                <input type="text" id="directory-search" placeholder="Cari wilayah, kota, atau nama petshop..." class="w-full pl-11 pr-10 py-3.5 bg-white border border-slate-200 focus:border-amber-500 rounded-2xl text-sm shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-amber-500/10 text-slate-700 placeholder-slate-400">
                <span class="absolute left-4 top-4 text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <button id="clear-search" class="hidden absolute right-4 top-4 text-slate-450 hover:text-slate-650 transition-colors font-bold text-lg">
                    &times;
                </button>
            </div>
            <p class="text-[11px] text-slate-450 mt-2">Menyaring data dan membuka menu collapse secara instan saat Anda mengetik.</p>
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden text-center py-16 bg-slate-50 border border-slate-200/60 rounded-3xl space-y-4 max-w-4xl mx-auto">
        <span class="text-4xl">😿</span>
        <h3 class="font-outfit font-bold text-lg text-slate-800">Petshop Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto">Tidak ada petshop atau kota yang cocok dengan kata kunci pencarian Anda. Silakan coba kata kunci lain.</p>
    </div>

    <!-- Collapsible Provinces & Cities Directory Container -->
    <div class="max-w-4xl mx-auto space-y-4" id="directory-container">
        @foreach($provinces as $province)
            <!-- Province Card Wrapper -->
            <div class="province-wrapper border border-slate-200/80 rounded-2xl bg-white shadow-sm overflow-hidden" data-province-name="{{ $province->nama }}">
                
                <!-- Province Toggle Header -->
                <button type="button" class="w-full flex items-center justify-between p-5 text-left font-outfit font-black text-lg sm:text-xl text-slate-800 bg-slate-50/50 hover:bg-slate-50/80 transition-colors focus:outline-none province-toggle">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🗺️</span>
                        <span>{{ $province->nama }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold bg-white border border-slate-200 text-slate-500 px-3 py-1 rounded-full shrink-0">
                            {{ $province->cities->flatMap->outlets->count() }} Outlet
                        </span>
                        <svg class="w-5 h-5 text-slate-400 transform transition-transform duration-300 province-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>

                <!-- Province Content Container (Collapsible) -->
                <div class="province-content hidden border-t border-slate-100 p-5 space-y-4 bg-white">
                    @foreach($province->cities as $city)
                        <!-- City Wrapper -->
                        <div class="city-wrapper border border-slate-150/80 rounded-xl bg-slate-50/15 overflow-hidden" data-city-name="{{ $city->nama }}">
                            
                            <!-- City Toggle Header -->
                            <button type="button" class="w-full flex items-center justify-between px-4 py-3.5 text-left font-outfit font-extrabold text-sm sm:text-base text-slate-700 hover:bg-slate-100/50 transition-colors focus:outline-none city-toggle">
                                <div class="flex items-center gap-2">
                                    <span class="text-amber-500 text-xs">📍</span>
                                    <span>{{ $city->nama }}</span>
                                    <a href="{{ route('city.landing', $city->slug) }}" class="text-[11px] text-amber-600 hover:text-amber-700 capitalize font-bold tracking-normal transition-colors ml-1 stop-propagation" title="Lihat halaman kota">
                                        (Lihat Info Wilayah &rarr;)
                                    </a>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold bg-white border border-slate-200 text-slate-500 px-2.5 py-0.5 rounded-full shrink-0">
                                        {{ $city->outlets->count() }}
                                    </span>
                                    <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-300 city-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </button>

                            <!-- City Content Container (Collapsible) -->
                            <div class="city-content hidden border-t border-slate-100 p-4 space-y-3 bg-white">
                                @foreach($city->outlets as $outlet)
                                    @php
                                        $message = "Halo " . $outlet->nama_outlet . ", saya ingin bertanya ketersediaan pasir kucing BentoCat di toko Anda.";
                                        $waUrl = "https://wa.me/" . $outlet->formatted_whatsapp . "?text=" . urlencode($message);
                                    @endphp
                                    <div class="outlet-item bg-slate-50/40 hover:bg-slate-50 border border-slate-100/80 p-3.5 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-200" data-outlet-name="{{ $outlet->nama_outlet }}">
                                        
                                        <!-- Outlet Info -->
                                        <div class="space-y-1.5 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h4 class="font-bold text-slate-800 text-sm outlet-name">{{ $outlet->nama_outlet }}</h4>
                                                
                                                @if($outlet->featured)
                                                    <span class="inline-flex items-center gap-0.5 bg-amber-500/10 border border-amber-500/25 text-amber-750 text-[10px] font-extrabold px-2 py-0.5 rounded-full" title="Rekomendasi BentoCat">
                                                        ⭐ Rekomendasi
                                                    </span>
                                                @endif

                                                @if($outlet->is_mitra)
                                                    <span class="inline-flex items-center gap-0.5 bg-emerald-500/10 border border-emerald-500/25 text-emerald-750 text-[10px] font-extrabold px-2 py-0.5 rounded-full" title="Mitra BentoCat Resmi">
                                                        🤝 Mitra Resmi
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-500 leading-relaxed outlet-address">{{ $outlet->alamat_lengkap }}</p>
                                        </div>

                                        <!-- Outlet Actions -->
                                        <div class="flex items-center gap-2 self-start sm:self-center shrink-0">
                                            @if($outlet->google_maps_url)
                                                <a href="{{ $outlet->google_maps_url }}" target="_blank" rel="noopener noreferrer" class="p-2 bg-white border border-slate-200 hover:border-slate-350 text-slate-500 hover:text-slate-800 rounded-xl transition-all shadow-sm flex items-center justify-center" title="Petunjuk Arah Google Maps">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                            @endif

                                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-4 py-2.5 rounded-xl text-xs shadow-sm shadow-emerald-500/10 transition-all select-none whitespace-nowrap">
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
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-3xl p-8 sm:p-12 text-slate-950 text-center max-w-4xl mx-auto space-y-6 shadow-xl">
        <h3 class="font-outfit font-black text-2xl sm:text-3xl">Ingin Menjadi Petshop Mitra Kami?</h3>
        <p class="text-xs sm:text-sm font-medium leading-relaxed max-w-2xl mx-auto opacity-90">
            Dapatkan keuntungan suplai produk langsung, promosi gratis di website nasional kami, serta prioritas rujukan konsumen dengan mendaftarkan petshop Anda sebagai Mitra Resmi BentoCat.
        </p>
        <div class="pt-2">
            <a href="https://wa.me/628123456789?text=Halo%20BentoCat,%20saya%2520tertarik%2520untuk%2520mendaftarkan%2520petshop%2520saya%2520sebagai%2520mitra%2520resmi." target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-slate-950 hover:bg-slate-900 text-white font-bold px-8 py-3.5 rounded-2xl text-sm transition-all shadow-lg hover:shadow-xl">
                <span>Daftar Mitra via WhatsApp</span>
                <span>&rarr;</span>
            </a>
        </div>
    </div>

</div>

<!-- Scroll to Top Button -->
<button id="btn-back-to-top" class="fixed bottom-6 right-6 z-50 hidden bg-amber-500 hover:bg-amber-600 text-slate-950 p-3 rounded-full shadow-lg shadow-amber-500/20 hover:shadow-xl transition-all focus:outline-none">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('directory-search');
    const clearSearchBtn = document.getElementById('clear-search');
    const emptyState = document.getElementById('empty-state');
    const provinceWrappers = document.querySelectorAll('.province-wrapper');
    const backToTopBtn = document.getElementById('btn-back-to-top');

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
});
</script>
@endsection
