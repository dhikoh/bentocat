<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BentoCat - Premium Cat Litter Discovery')</title>
    <meta name="description" content="@yield('meta_description', 'Temukan outlet resmi terdekat yang menjual BentoCat Premium Bentonite Cat Litter dengan harga lokal terjangkau.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Leaflet Map CSS (For optional outlet location displays) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Tailwind CSS v4 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- HSL Ambient Background Effect & Scrollbar Styling -->
    <style>
        body {
            background-color: #030712; /* slate-950 */
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(245, 158, 11, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.05) 0%, transparent 40%);
            background-attachment: fixed;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #090d16;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f59e0b;
        }
    </style>
    @yield('head')
</head>
<body class="text-slate-100 min-h-screen flex flex-col font-sans antialiased selection:bg-amber-500 selection:text-slate-950">

    <!-- Header Navbar -->
    <header class="sticky top-0 z-40 w-full bg-slate-950/80 backdrop-blur-md border-b border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <span class="text-2xl">🐱</span>
                <span class="font-outfit font-black text-2xl tracking-tight bg-gradient-to-r from-amber-400 to-amber-600 bg-clip-text text-transparent group-hover:from-amber-300 group-hover:to-amber-500 transition-all">
                    BentoCat
                </span>
            </a>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}#katalog" class="text-sm font-semibold text-slate-300 hover:text-white transition-all">Katalog Pasir</a>
                <a href="{{ route('home') }}#cari-outlet" class="text-sm font-semibold text-slate-300 hover:text-white transition-all">Cari Petshop</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition-all">Tips & Edukasi</a>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold bg-slate-900 border border-slate-800 text-slate-400 px-4 py-2 rounded-xl hover:border-amber-500/50 hover:text-amber-400 transition-all">
                    Dashboard Admin
                </a>
            </nav>

            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-toggle" type="button" class="md:hidden p-2 text-slate-400 hover:text-white focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path id="menu-icon-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Container -->
        <div id="mobile-menu" class="hidden md:hidden bg-slate-950 border-b border-slate-900 px-4 py-4 space-y-3">
            <a href="{{ route('home') }}#katalog" class="block text-sm font-semibold text-slate-300 hover:text-white py-2">Katalog Pasir</a>
            <a href="{{ route('home') }}#cari-outlet" class="block text-sm font-semibold text-slate-300 hover:text-white py-2">Cari Petshop</a>
            <a href="{{ route('blog.index') }}" class="block text-sm font-semibold text-slate-300 hover:text-white py-2">Tips & Edukasi</a>
            <a href="{{ route('admin.dashboard') }}" class="block text-xs font-bold text-center bg-slate-900 border border-slate-800 text-slate-400 py-2.5 rounded-xl">
                Dashboard Admin
            </a>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Area -->
    <footer class="bg-slate-950 border-t border-slate-900 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🐱</span>
                        <span class="font-outfit font-black text-xl tracking-tight text-white">BentoCat</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                        Platform Discovery & Lead Routing resmi untuk produk pasir kucing premium BentoCat. Membantu pemilik kucing menemukan outlet petshop offline terdekat untuk mendapatkan harga terbaik.
                    </p>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-300 mb-4 uppercase tracking-wider">Navigasi Utama</h3>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}#katalog" class="hover:text-white transition-all">Katalog Pasir</a></li>
                        <li><a href="{{ route('home') }}#cari-outlet" class="hover:text-white transition-all">Cari Petshop Resmi</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-all">Tips & Artikel Edukasi</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-slate-300 mb-4 uppercase tracking-wider">Hukum & Kebijakan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Seluruh transaksi dan pembayaran disepakati secara mandiri antara pembeli dan outlet penjual terkait melalui jalur WhatsApp. BentoCat tidak memproses pembayaran langsung.
                    </p>
                </div>
            </div>
            <div class="border-t border-slate-900 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} BentoCat Indonesia. Hak Cipta Dilindungi Undang-Undang.</p>
                <p>Made with 🐾 for Cat Owners.</p>
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
</body>
</html>
