<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('site_name', 'BentoCat') . ' - Premium Cat Litter Discovery')</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('site_description', 'Temukan outlet resmi terdekat yang menjual BentoCat Premium Bentonite Cat Litter dengan harga lokal terjangkau.'))">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset(\App\Models\Setting::get('site_favicon', 'favicon.ico')) }}">

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
                radial-gradient(circle at 10% 20%, rgba(245, 158, 11, 0.05) 0%, transparent 45%),
                radial-gradient(circle at 90% 80%, rgba(13, 148, 136, 0.03) 0%, transparent 45%);
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
    </style>
    @yield('head')
    @stack('styles')
</head>
<body class="text-slate-800 min-h-screen flex flex-col antialiased selection:bg-amber-100 selection:text-amber-900">

    <!-- Floating Background Blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-[-1]">
        <div class="absolute top-1/4 -left-12 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl animate-blob"></div>
        <div class="absolute bottom-1/4 -right-12 w-96 h-96 bg-teal-500/4 rounded-full blur-3xl animate-blob" style="animation-delay: 4s;"></div>
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
