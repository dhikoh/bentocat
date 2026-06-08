<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | BentoCat Lead Intelligence</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4 -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: #FCFBF9 !important;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
        
        /* Redefine Tailwind v4 variables for bright theme inside admin */
        :root {
            --color-slate-950: #FCFBF9 !important; /* Page BG */
            --color-slate-900: #ffffff !important; /* Sidebar & Card BG */
            --color-slate-850: #f1f5f9 !important;
            --color-slate-800: #e2e8f0 !important; /* Light border */
            --color-slate-700: #475569 !important;
            --color-slate-600: #64748b !important;
            --color-slate-500: #94a3b8 !important; /* Muted text */
            --color-slate-450: #94a3b8 !important;
            --color-slate-400: #334155 !important;
            --color-slate-350: #1e293b !important;
            --color-slate-300: #1e293b !important; /* Primary text */
            --color-slate-200: #0f172a !important; /* Dark text */
            --color-slate-100: #0f172a !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f59e0b;
        }

        /* Force text-white to be dark slate inside light admin */
        .text-white {
            color: #0f172a !important;
        }

        /* Active / Status text overrides */
        .text-amber-400 {
            color: #d97706 !important;
        }
        .text-emerald-400 {
            color: #059669 !important;
        }
        .text-rose-400 {
            color: #dc2626 !important;
        }
        .text-purple-400 {
            color: #7c3aed !important;
        }
        .text-blue-400 {
            color: #2563eb !important;
        }

        /* Form overrides for consistent light inputs */
        input, select, textarea {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border-color: #cbd5e1 !important;
        }
        input::placeholder, textarea::placeholder {
            color: #94a3b8 !important;
        }
        
        /* Exception: keep white text on badge or dark buttons */
        .bg-gradient-to-r.from-amber-500.to-orange-600 span,
        .bg-emerald-500 span,
        .bg-amber-500 span,
        .bg-rose-500 span,
        .bg-slate-800,
        .bg-slate-800 span {
            color: #ffffff !important;
        }
        
        .bg-slate-800 {
            background-color: #1e293b !important;
        }
        .bg-slate-800:hover {
            background-color: #334155 !important;
        }
    </style>
    @yield('styles')
</head>
<body class="h-full flex flex-col md:flex-row overflow-hidden bg-slate-950">
    
    <!-- Mobile Header -->
    <header class="md:hidden bg-slate-900 border-b border-slate-800 flex items-center justify-between px-4 py-3 shrink-0">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="BentoCat Logo" class="h-8 w-auto">
        </div>
        <button id="mobile-menu-btn" class="text-slate-400 hover:text-amber-500 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900/95 border-r border-slate-800 flex flex-col transition-transform duration-300 transform -translate-x-full md:translate-x-0 md:static md:inset-auto md:shrink-0 backdrop-blur-md">
        <!-- Brand logo -->
        <div class="px-6 py-5 border-b border-slate-800/80 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="BentoCat Logo" class="h-9 w-auto">
                <div>
                    <span class="block text-[9px] text-slate-500 uppercase tracking-widest font-semibold">Lead Intelligence</span>
                </div>
            </div>
            <button id="close-sidebar-btn" class="md:hidden text-slate-500 hover:text-rose-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.dashboard') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">📊</span> Dashboard
            </a>
            
            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Regional & Partners</p>
            </div>
            <a href="{{ route('admin.regions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.regions.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">🗺️</span> Wilayah (Kota/Prov)
            </a>
            <a href="{{ route('admin.distributors.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.distributors.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">🏢</span> Distributor Utama
            </a>
            <a href="{{ route('admin.outlets.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.outlets.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">🏪</span> Petshop / Outlet
            </a>
            <a href="{{ route('admin.shipping-contacts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.shipping-contacts.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">🛵</span> Kontak Pengiriman
            </a>

            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Produk & Konten</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.products.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">📦</span> Katalog & Varian
            </a>
            <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.articles.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">✍️</span> CMS Artikel (Blog)
            </a>

            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Lead Intelligence</p>
            </div>
            <a href="{{ route('admin.leads.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.leads.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">👥</span> Database Leads
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.customers.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">🤝</span> Database Pelanggan
            </a>
            
            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Aktivitas Marketing</p>
            </div>
            @if(Auth::user() && Auth::user()->role === 'marketing')
            <a href="{{ route('admin.my-logs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.my-logs.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">📝</span> Log Kerja Saya
            </a>
            @endif
            @if(Auth::user() && Auth::user()->role === 'superadmin')
            <a href="{{ route('admin.marketing-logs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.marketing-logs.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">📋</span> Log Kerja Marketing
            </a>
            @endif

            @if(Auth::user() && in_array(Auth::user()->role, ['superadmin', 'marketing']))
            <div class="pt-4 pb-1">
                <p class="px-4 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Sistem</p>
            </div>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.settings.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">⚙️</span> Pengaturan Web
            </a>
            @if(Auth::user()->role === 'superadmin')
            <a href="{{ route('admin.audit.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium {{ Route::is('admin.audit.*') ? 'bg-amber-500/10 border-l-4 border-amber-500 text-amber-400 font-bold' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200' }}">
                <span class="text-lg">🔍</span> Audit & Kesehatan Bisnis
            </a>
            @endif
            @endif
        </nav>

        <!-- User profile section -->
        <div class="p-4 border-t border-slate-800/80 shrink-0">
            <div class="flex items-center justify-between bg-slate-950/60 p-3 rounded-xl border border-slate-800">
                <div class="overflow-hidden">
                    <span class="block text-xs font-bold text-slate-200 truncate">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <span class="block text-[9px] font-semibold text-amber-400 tracking-wider uppercase">{{ Auth::user()->role ?? 'superadmin' }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-slate-500 hover:text-rose-500 hover:bg-rose-500/10 rounded-lg transition-colors" title="Logout">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-slate-950 p-4 md:p-8">
        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center gap-3 shadow-lg">
                <span>✅</span>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 flex items-center gap-3 shadow-lg">
                <span>⚠️</span>
                <p class="font-medium text-sm">{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Sidebar Toggle logic for mobile
        const sidebar = document.getElementById('sidebar');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
            });
        }

        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
