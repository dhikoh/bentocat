<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('seo_meta_title', \App\Models\Setting::get('site_name', 'BentoCat') . ' - Premium Cat Litter Discovery'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('seo_meta_description', \App\Models\Setting::get('site_description', 'Temukan outlet resmi terdekat yang menjual BentoCat Premium Bentonite Cat Litter dengan harga lokal terjangkau.')))">
    <meta name="keywords" content="@yield('meta_keywords', 'pasir kucing, pasir bentonit, pasir gumpal, pasir kucing premium, bentocat')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset(\App\Models\Setting::get('site_favicon', 'favicon.ico')) }}">

    @if(\App\Models\Setting::get('meta_verification_id'))
    <meta name="facebook-domain-verification" content="{{ \App\Models\Setting::get('meta_verification_id') }}" />
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('title', \App\Models\Setting::get('seo_meta_title', \App\Models\Setting::get('site_name', 'BentoCat')))" />
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('seo_meta_description', \App\Models\Setting::get('site_description', 'BentoCat')))" />
    @if(\App\Models\Setting::get('seo_og_image'))
    <meta property="og:image" content="{{ asset(\App\Models\Setting::get('seo_og_image')) }}" />
    @else
    <meta property="og:image" content="{{ asset('images/logo.png') }}" />
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="@yield('title', \App\Models\Setting::get('seo_twitter_title', \App\Models\Setting::get('seo_meta_title', \App\Models\Setting::get('site_name', 'BentoCat'))))" />
    <meta property="twitter:description" content="@yield('meta_description', \App\Models\Setting::get('seo_twitter_description', \App\Models\Setting::get('seo_meta_description', \App\Models\Setting::get('site_description', 'BentoCat'))))" />
    @if(\App\Models\Setting::get('seo_og_image'))
    <meta property="twitter:image" content="{{ asset(\App\Models\Setting::get('seo_og_image')) }}" />
    @else
    <meta property="twitter:image" content="{{ asset('images/logo.png') }}" />
    @endif

    @if(\App\Models\Setting::get('ga_id'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ \App\Models\Setting::get('ga_id') }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ \App\Models\Setting::get('ga_id') }}');
    </script>
    @endif

    @if(\App\Models\Setting::get('gtm_id'))
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ \App\Models\Setting::get('gtm_id') }}');</script>
    <!-- End Google Tag Manager -->
    @endif

    @if(\App\Models\Setting::get('meta_pixel_id'))
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ \App\Models\Setting::get('meta_pixel_id') }}');
    fbq('track', 'PageView');
    </script>
    <!-- End Meta Pixel Code -->
    @endif

    <!-- Google Fonts: Outfit (For Headlines) & Plus Jakarta Sans (For Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Compiled via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styling Custom Premium -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF8F5; /* Warm Ivory/Cream */
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(245, 158, 11, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(13, 148, 136, 0.04) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23d97706' fill-opacity='0.025'%3E%3Cpath d='M15 15c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm4-4c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-8 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 8c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1zm-16 0c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1zm6 3c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4zM55 55c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm4-4c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-8 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 8c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1zm-16 0c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1zm6 3c-2.2 0-4 1.8-4 4s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4z'/%3E%3C/g%3E%3C/svg%3E");
            background-attachment: fixed;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* Smooth Transition */
        .transition-premium {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Glassmorphism */
        .glass-nav {
            background: rgba(250, 248, 245, 0.85);
            backdrop-filter: blur(12px);
            border: 1px border rgba(229, 224, 216, 0.5);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #FAF8F5;
        }
        ::-webkit-scrollbar-thumb {
            background: #E5E0D8;
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #F59E0B;
        }

        /* Blob Animations */
        @keyframes float-blob {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-15px) scale(1.05); }
        }
        .animate-blob {
            animation: float-blob 8s ease-in-out infinite;
        }

        /* Playful Floating Animations */
        @keyframes float-gentle-1 {
            0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
            50% { transform: translateY(-20px) rotate(8deg) scale(1.05); }
        }
        @keyframes float-gentle-2 {
            0%, 100% { transform: translateY(0px) rotate(0deg) scale(1.02); }
            50% { transform: translateY(-15px) rotate(-12deg) scale(0.98); }
        }
        @keyframes float-gentle-3 {
            0%, 100% { transform: translateY(0px) rotate(0deg) scale(0.95); }
            50% { transform: translateY(-25px) rotate(15deg) scale(1.03); }
        }
        .animate-float-1 {
            animation: float-gentle-1 10s ease-in-out infinite;
        }
        .animate-float-2 {
            animation: float-gentle-2 12s ease-in-out infinite;
        }
        .animate-float-3 {
            animation: float-gentle-3 14s ease-in-out infinite;
        }
    </style>
    @yield('head')
    @stack('styles')
    @yield('schema')
</head>
<body class="text-slate-800 min-h-screen flex flex-col antialiased selection:bg-amber-100 selection:text-amber-900">
    @if(\App\Models\Setting::get('gtm_id'))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ \App\Models\Setting::get('gtm_id') }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif

    <!-- Floating Background Blobs & Playful Doodles -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-[-1]">
        <!-- Core soft blobs -->
        <div class="absolute top-1/4 -left-12 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl animate-blob hidden md:block"></div>
        <div class="absolute bottom-1/4 -right-12 w-96 h-96 bg-teal-500/4 rounded-full blur-3xl animate-blob hidden md:block" style="animation-delay: 4s;"></div>

        <!-- Left Side Floating Doodles (Desktop Only) -->
        <div class="hidden lg:block">
            <!-- Playful Cat Head -->
            <div class="absolute left-8 top-[18%] animate-float-1 opacity-20">
                <svg class="w-14 h-14 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5c.67 0 1.35.09 2 .26L18.5 2 20 7.5c1.24 1.3 2 3.05 2 5 0 4.97-4.03 9-9 9s-9-4.03-9-9c0-1.95.76-3.7 2-5L5.5 2l4.5 3.26c.65-.17 1.33-.26 2-.26z" />
                    <circle cx="9" cy="13" r="1" fill="currentColor"/>
                    <circle cx="15" cy="13" r="1" fill="currentColor"/>
                    <path d="M12 15.5c-.5 0-.75-.25-.75-.5 0-.5.5-.75.75-.75s.75.25.75.75c0 .25-.25.5-.75.5z" fill="currentColor"/>
                </svg>
            </div>
            <!-- Paw Print Left -->
            <div class="absolute left-16 top-[48%] animate-float-2 opacity-15">
                <svg class="w-10 h-10 text-teal-600" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="16" r="4" />
                    <circle cx="7" cy="10" r="2" />
                    <circle cx="11" cy="7" r="2" />
                    <circle cx="16" cy="8" r="2" />
                    <circle cx="20" cy="12" r="1.8" />
                </svg>
            </div>
            <!-- Fish Skeleton Left -->
            <div class="absolute left-10 top-[76%] animate-float-3 opacity-15">
                <svg class="w-12 h-12 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 12c4 4 14 4 18 0-4-4-14-4-18 0z" />
                    <path d="M18 12l2.5 2.5M18 12l2.5-2.5" />
                    <path d="M14 8v8M10 8v8M6 9v6" />
                </svg>
            </div>
        </div>

        <!-- Right Side Floating Doodles (Desktop Only) -->
        <div class="hidden lg:block">
            <!-- Yarn Ball Right -->
            <div class="absolute right-10 top-[22%] animate-float-3 opacity-15">
                <svg class="w-12 h-12 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 3a9 9 0 0 1 0 18" />
                    <path d="M3 12a9 9 0 0 1 18 0" />
                    <path d="M5.5 5.5c2.5 2.5 2.5 10.5 0 13" />
                    <path d="M18.5 5.5c-2.5 2.5-2.5 10.5 0 13" />
                </svg>
            </div>
            <!-- Playful Cat Sitting Right -->
            <div class="absolute right-16 top-[52%] animate-float-1 opacity-20">
                <svg class="w-14 h-14 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 18a4 4 0 0 0-8 0v2h8v-2z" />
                    <path d="M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                    <path d="M9.5 4.5l-1-2 2 .5zM14.5 4.5l1-2-2 .5z" />
                    <path d="M20 14c0-2-1.5-4-3.5-4h-9C5.5 10 4 12 4 14" />
                </svg>
            </div>
            <!-- Paw Print Right -->
            <div class="absolute right-8 top-[82%] animate-float-2 opacity-15">
                <svg class="w-10 h-10 text-teal-600" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="16" r="4" />
                    <circle cx="7" cy="10" r="2" />
                    <circle cx="11" cy="7" r="2" />
                    <circle cx="16" cy="8" r="2" />
                    <circle cx="20" cy="12" r="1.8" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Header Navbar: Floating Pill Design -->
    <header class="sticky top-0 z-40 w-full pt-4 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto h-20 px-6 rounded-3xl glass-nav shadow-lg shadow-amber-900/3 flex items-center justify-between border border-[#e5e0d8]/60">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group transition-premium">
                <img src="{{ asset(\App\Models\Setting::get('site_logo', 'images/logo.png')) }}" alt="Logo" class="h-10 w-auto group-hover:scale-105 transition-premium">
            </a>

            <!-- Desktop Nav Menu (Capsule Links) -->
            <nav class="hidden md:flex items-center bg-[#FAF8F5]/90 border border-slate-200/40 rounded-full px-2 py-1.5 shadow-inner shadow-slate-100/50 gap-1">
                <a href="{{ route('home') }}#katalog" class="px-5 py-2 text-xs font-bold text-slate-600 hover:text-amber-600 rounded-full hover:bg-white transition-premium">Katalog Produk</a>
                <a href="{{ route('home') }}#cari-outlet" class="px-5 py-2 text-xs font-bold text-slate-600 hover:text-amber-600 rounded-full hover:bg-white transition-premium">Cari Petshop</a>
                <a href="{{ route('petshop.list') }}" class="px-5 py-2 text-xs font-bold text-slate-600 hover:text-amber-600 rounded-full hover:bg-white transition-premium">List Wilayah</a>
                <a href="{{ route('blog.index') }}" class="px-5 py-2 text-xs font-bold text-slate-600 hover:text-amber-600 rounded-full hover:bg-white transition-premium">Tips & Edukasi</a>
            </nav>

            <!-- Right Buttons -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-premium">
                    Admin Panel
                </a>
                <a href="{{ route('home') }}#cari-outlet" class="bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold px-5 py-2.5 rounded-2xl shadow-md shadow-amber-500/10 hover:shadow-lg transition-premium">
                    Cari Toko 📍
                </a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-toggle" type="button" class="md:hidden p-2 text-slate-500 hover:text-slate-800 focus:outline-none transition-premium">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path id="menu-icon-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Container -->
        <div id="mobile-menu" class="hidden md:hidden mt-2 bg-white/95 backdrop-blur-md border border-slate-100 rounded-3xl p-5 space-y-4 shadow-xl">
            <a href="{{ route('home') }}#katalog" class="block text-sm font-semibold text-slate-700 hover:text-amber-600 py-1 transition-premium">Katalog Produk</a>
            <a href="{{ route('home') }}#cari-outlet" class="block text-sm font-semibold text-slate-700 hover:text-amber-600 py-1 transition-premium">Cari Petshop</a>
            <a href="{{ route('petshop.list') }}" class="block text-sm font-semibold text-slate-700 hover:text-amber-600 py-1 transition-premium">List Wilayah</a>
            <a href="{{ route('blog.index') }}" class="block text-sm font-semibold text-slate-700 hover:text-amber-600 py-1 transition-premium">Tips & Edukasi</a>
            <div class="border-t border-slate-100 pt-4 flex flex-col gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-500 text-center py-2">Admin Panel</a>
                <a href="{{ route('home') }}#cari-outlet" class="bg-amber-500 text-slate-950 text-center py-3 rounded-2xl text-sm font-bold shadow-md shadow-amber-500/10">Cari Toko Terdekat 📍</a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Area -->
    <footer class="bg-slate-950 text-slate-400 mt-20 border-t border-slate-900 rounded-t-[3rem] overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 py-16 space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset(\App\Models\Setting::get('site_logo', 'images/logo.png')) }}" alt="Logo" class="h-10 w-auto filter brightness-0 invert">
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        Platform Discovery & Lead Routing resmi untuk produk pasir kucing premium BentoCat. Membantu pemilik kucing menemukan outlet petshop offline terdekat untuk mendapatkan harga terbaik.
                    </p>
                </div>
                <div>
                    <h3 class="font-outfit font-bold text-sm text-slate-200 mb-5 uppercase tracking-wider">Navigasi Utama</h3>
                    <ul class="space-y-3 text-xs">
                        <li><a href="{{ route('home') }}#katalog" class="hover:text-amber-400 transition-premium">Katalog Pasir</a></li>
                        <li><a href="{{ route('home') }}#cari-outlet" class="hover:text-amber-400 transition-premium">Cari Petshop Resmi</a></li>
                        <li><a href="{{ route('petshop.list') }}" class="hover:text-amber-400 transition-premium">Daftar Wilayah</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-amber-400 transition-premium">Tips & Edukasi</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-outfit font-bold text-sm text-slate-200 mb-5 uppercase tracking-wider">Hukum & Kebijakan</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Seluruh transaksi dan komunikasi pembayaran disepakati secara mandiri antara pembeli dan outlet penjual terkait melalui jalur WhatsApp. BentoCat tidak memproses transaksi pembayaran secara langsung di platform ini.
                    </p>
                </div>
            </div>
            
            <div class="border-t border-slate-900 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'BentoCat') }} Indonesia. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4">
                    @if($ig = \App\Models\Setting::get('social_instagram'))
                        <a href="{{ $ig }}" target="_blank" class="hover:text-white transition-premium">Instagram</a>
                    @endif
                    @if($fb = \App\Models\Setting::get('social_facebook'))
                        <a href="{{ $fb }}" target="_blank" class="hover:text-white transition-premium">Facebook</a>
                    @endif
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script Toggle -->
    <script>
        const btn = document.getElementById('mobile-menu-toggle');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('menu-icon-open');
        const iconClose = document.getElementById('menu-icon-close');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
