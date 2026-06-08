@extends('layouts.client')

@section('title', \App\Models\Setting::get('site_name', 'BentoCat') . ' - Pasir Kucing Bentonit Premium')

@section('content')
<div class="space-y-32 pb-20">

    <!-- 1. Hero Section: Double-Column Layout -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left Column: Content & Call to Action -->
            <div class="lg:col-span-7 space-y-8 text-left">
                <!-- Tagline Badge -->
                <span class="inline-flex items-center gap-2 bg-amber-500/10 border border-amber-500/20 text-amber-700 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider select-none font-outfit">
                    {{ \App\Models\Setting::get('hero_badge_text', '🐾 BentoCat Premium Bentonite Cat Litter') }}
                </span>
                
                <!-- Main Heading -->
                <h1 class="font-outfit font-black text-4xl sm:text-5xl lg:text-6xl tracking-tight text-slate-900 leading-[1.15]">
                    {!! str_replace('Petshop Terdekat', '<span class="bg-gradient-to-r from-amber-500 to-amber-600 bg-clip-text text-transparent">Petshop Terdekat</span>', \App\Models\Setting::get('hero_title', 'Pasir Kucing Premium, Sahabat Terbaik Kucing Anda!')) !!}
                </h1>
                
                <!-- Subtitle / Description -->
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-xl">
                    {{ \App\Models\Setting::get('hero_subtitle', 'Hemat Ongkir! Cari petshop resmi terdekat di kota Anda dengan harga lokal wajar tanpa markup tinggi marketplace.') }}
                </p>
                
                <!-- CTA Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 w-full max-w-md">
                    <button onclick="openSearchModal()" class="bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold px-8 py-4 rounded-2xl shadow-lg shadow-amber-500/20 hover:shadow-amber-500/35 transition-premium text-sm uppercase tracking-wide cursor-pointer flex items-center justify-center gap-2">
                        <span>{{ \App\Models\Setting::get('cta_primary_text', 'Cari Toko Terdekat 📍') }}</span>
                    </button>
                    <a href="#katalog" class="bg-white hover:bg-slate-50 border border-[#e5e0d8] text-slate-700 font-bold px-8 py-4 rounded-2xl transition-premium text-sm uppercase tracking-wide text-center">
                        {{ \App\Models\Setting::get('cta_secondary_text', 'Lihat Katalog Produk') }}
                    </a>
                </div>

                <!-- Floating Product Box -->
                <div class="bg-white/85 backdrop-blur-md border border-[#e5e0d8]/80 p-5 rounded-3xl shadow-lg flex items-center gap-4 max-w-md transition-premium hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-16 h-16 rounded-2xl bg-amber-500/5 border border-amber-500/10 flex items-center justify-center p-1 shrink-0 overflow-hidden">
                        <img src="{{ asset(\App\Models\Setting::get('hero_product_image', 'images/product_default.png')) }}" alt="Product Image" class="w-full h-full object-contain">
                    </div>
                    <div class="space-y-1">
                        <span class="block font-outfit font-black text-sm text-slate-900">{{ \App\Models\Setting::get('hero_product_title', 'BentoCat Premium') }}</span>
                        <p class="text-[11px] text-slate-500 leading-tight">{{ \App\Models\Setting::get('hero_product_desc', 'Odor Control • Instant Clumping • 99% Dust Free') }}</p>
                        <a href="#katalog" class="text-[10px] font-bold text-amber-650 hover:text-amber-700 transition-premium">Detail Varian &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Media & Floating Badges -->
            <div class="lg:col-span-5 relative mt-10 lg:mt-0 flex justify-center">
                <!-- Outer Glow Backdrop -->
                <div class="absolute inset-0 bg-gradient-to-tr from-amber-500/10 to-teal-500/5 rounded-full blur-3xl opacity-60"></div>
                
                <!-- Media Frame Wrapper -->
                <div class="relative w-full max-w-[360px] aspect-[4/5] bg-white border-4 border-white rounded-[2.8rem] shadow-2xl overflow-hidden z-10 group">
                    @if(\App\Models\Setting::get('hero_media_type', 'image') === 'video')
                        <video src="{{ asset(\App\Models\Setting::get('hero_media_path', 'images/hero_default.png')) }}" autoplay muted loop playsinline class="w-full h-full object-cover rounded-[2.5rem] group-hover:scale-102 transition-premium"></video>
                    @else
                        <img src="{{ asset(\App\Models\Setting::get('hero_media_path', 'images/hero_default.png')) }}" alt="BentoCat Mascot" class="w-full h-full object-cover rounded-[2.5rem] group-hover:scale-102 transition-premium">
                    @endif
                </div>

                <!-- Floating Badge 1 (Top Left) -->
                <div class="absolute -top-4 -left-4 bg-white border border-[#e5e0d8] px-4 py-2.5 rounded-full shadow-md flex items-center gap-2 text-xs font-black text-slate-800 z-20 animate-blob">
                    <span class="text-amber-500">⭐</span>
                    <span>{{ \App\Models\Setting::get('hero_badge_1_text', 'Vet Approved') }}</span>
                </div>

                <!-- Floating Badge 2 (Right Middle) -->
                <div class="absolute top-1/3 -right-6 bg-white border border-[#e5e0d8] px-4 py-2.5 rounded-full shadow-md flex items-center gap-2 text-xs font-black text-slate-800 z-20 animate-blob" style="animation-delay: 3s;">
                    <span class="text-emerald-500">🍃</span>
                    <span>{{ \App\Models\Setting::get('hero_badge_2_text', 'Healthy & Natural') }}</span>
                </div>

                <!-- Floating Info Badge 3 (Bottom Right Card) -->
                <div class="absolute -bottom-6 -right-2 bg-slate-900 border border-slate-850 p-4 rounded-3xl shadow-lg max-w-[210px] text-left z-20 transition-premium hover:scale-105">
                    <span class="block text-[10px] font-bold text-amber-500 uppercase tracking-wider mb-1">
                        {{ \App\Models\Setting::get('hero_badge_3_title', 'Complete Care for Every Stage') }}
                    </span>
                    <p class="text-[10px] text-slate-400 leading-normal">
                        {{ \App\Models\Setting::get('hero_badge_3_desc', 'Dari kitten hingga senior, menjaga kebersihan litter box tetap steril.') }}
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- 2. Keunggulan Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <style>
            /* --- GENERAL UTILITIES --- */
            .glow-effect {
                filter: url(#glow);
            }

            /* --- 1. MOLECULAR BONDING (BENTONITE CLUMPING) --- */
            .droplet {
                animation: dropletFall 5s infinite cubic-bezier(0.4, 0, 0.2, 1);
                will-change: transform, opacity;
            }
            .ripple-ring {
                animation: rippleWave 5s infinite cubic-bezier(0.1, 0.8, 0.3, 1);
                transform-origin: 100px 95px;
                will-change: transform, opacity;
            }
            .clump-grain {
                transition: transform 0.5s ease, fill 0.5s ease;
                animation: grainClump 5s infinite cubic-bezier(0.4, 0, 0.2, 1);
                will-change: transform, fill, stroke;
            }
            .grain-a { transform-origin: 60px 80px; --target-x: 28px; --target-y: 10px; }
            .grain-b { transform-origin: 80px 115px; --target-x: 14px; --target-y: -11px; }
            .grain-c { transform-origin: 100px 75px; --target-x: 0px; --target-y: 11px; }
            .grain-d { transform-origin: 120px 110px; --target-x: -14px; --target-y: -6px; }
            .grain-e { transform-origin: 140px 80px; --target-x: -28px; --target-y: 10px; }
            .grain-f { transform-origin: 100px 125px; --target-x: 0px; --target-y: -15px; }

            .bond-mesh {
                stroke-dasharray: 40;
                stroke-dashoffset: 40;
                animation: meshActivate 5s infinite cubic-bezier(0.4, 0, 0.2, 1);
                will-change: stroke-dashoffset, opacity;
            }
            .clump-glow {
                transform-origin: 100px 98px;
                animation: glowActivate 5s infinite cubic-bezier(0.1, 0.8, 0.3, 1);
                will-change: transform, opacity;
            }
            .status-text-inactive {
                animation: textInactive 5s infinite steps(1);
            }
            .status-text-locked {
                animation: textLocked 5s infinite steps(1);
            }

            @keyframes dropletFall {
                0% { transform: translate3d(0, -30px, 0) scaleY(1.3); opacity: 0; }
                5% { opacity: 0.8; }
                18% { transform: translate3d(0, 80px, 0) scaleY(1); opacity: 1; }
                20% { transform: translate3d(0, 95px, 0) scaleY(0.7); opacity: 1; }
                22%, 100% { transform: translate3d(0, 95px, 0) scaleY(0); opacity: 0; }
            }
            @keyframes rippleWave {
                0%, 20% { transform: scale3d(0, 0, 1); opacity: 0; }
                21% { transform: scale3d(0, 0, 1); opacity: 1; stroke-width: 3; }
                40% { transform: scale3d(3.5, 3.5, 1); opacity: 0; stroke-width: 0.5; }
                100% { transform: scale3d(3.5, 3.5, 1); opacity: 0; }
            }
            @keyframes grainClump {
                0%, 20% {
                    transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
                    fill: url(#dryGrainGrad);
                    stroke: #cbd5e1;
                }
                30%, 80% {
                    transform: translate3d(var(--target-x), var(--target-y), 0) scale3d(1.2, 1.2, 1);
                    fill: url(#wetGrainGrad);
                    stroke: #3b82f6;
                    filter: drop-shadow(0 2px 4px rgba(59,130,246,0.3));
                }
                88%, 100% {
                    transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
                    fill: url(#dryGrainGrad);
                    stroke: #cbd5e1;
                }
            }
            @keyframes meshActivate {
                0%, 20% { stroke-dashoffset: 40; opacity: 0; }
                30%, 80% { stroke-dashoffset: 0; opacity: 0.8; }
                88%, 100% { stroke-dashoffset: 40; opacity: 0; }
            }
            @keyframes glowActivate {
                0%, 20% { transform: scale3d(0.6, 0.6, 1); opacity: 0; }
                30%, 80% { transform: scale3d(1.15, 1.15, 1); opacity: 0.18; }
                88%, 100% { transform: scale3d(0.6, 0.6, 1); opacity: 0; }
            }
            @keyframes textInactive {
                0%, 19% { opacity: 1; }
                20%, 87% { opacity: 0; }
                88%, 100% { opacity: 1; }
            }
            @keyframes textLocked {
                0%, 19% { opacity: 0; }
                20%, 87% { opacity: 1; }
                88%, 100% { opacity: 0; }
            }

            /* --- 2. DUSTING ANIMATIONS --- */
            .sieve-plate {
                animation: sieveVibrate 0.15s infinite linear;
                will-change: transform;
            }
            .granule {
                transform-origin: center;
                opacity: 0;
                will-change: transform, opacity;
            }
            .granule-1 {
                animation: granulePath1 4s infinite cubic-bezier(0.25, 1, 0.5, 1);
            }
            .granule-2 {
                animation: granulePath2 4s infinite cubic-bezier(0.25, 1, 0.5, 1);
                animation-delay: 0.8s;
            }
            .granule-3 {
                animation: granulePath3 4s infinite cubic-bezier(0.25, 1, 0.5, 1);
                animation-delay: 1.6s;
            }
            .dust-particle {
                transform-origin: center;
                opacity: 0;
                will-change: transform, opacity;
            }
            .dust-1 {
                animation: dustPath1 4s infinite ease-in;
            }
            .dust-2 {
                animation: dustPath2 4s infinite ease-in;
                animation-delay: 0.6s;
            }
            .dust-3 {
                animation: dustPath3 4s infinite ease-in;
                animation-delay: 1.2s;
            }
            .suction-current {
                stroke-dasharray: 6 3;
                animation: suctionDash 1s infinite linear;
                will-change: stroke-dashoffset;
            }

            @keyframes sieveVibrate {
                0%, 100% { transform: translate3d(0, 0, 0); }
                25% { transform: translate3d(-0.5px, 0.5px, 0); }
                75% { transform: translate3d(0.5px, -0.5px, 0); }
            }
            @keyframes granulePath1 {
                0% { transform: translate3d(45px, -15px, 0) scale3d(0.6, 0.6, 1); opacity: 0; }
                5% { opacity: 1; transform: translate3d(45px, -15px, 0) scale3d(1, 1, 1); }
                18% { transform: translate3d(48px, 48px, 0) rotate(15deg); }
                22% { transform: translate3d(50px, 58px, 0) scale3d(0.95, 0.95, 1); }
                35% { transform: translate3d(54px, 88px, 0) rotate(45deg); }
                38% { transform: translate3d(56px, 94px, 0) scale3d(0.9, 0.9, 1); }
                55% { transform: translate3d(58px, 126px, 0) rotate(90deg); opacity: 1; }
                85% { opacity: 1; transform: translate3d(58px, 126px, 0) rotate(90deg); }
                95%, 100% { opacity: 0; transform: translate3d(58px, 126px, 0) rotate(90deg); }
            }
            @keyframes granulePath2 {
                0% { transform: translate3d(85px, -15px, 0) scale3d(0.6, 0.6, 1); opacity: 0; }
                5% { opacity: 1; transform: translate3d(85px, -15px, 0) scale3d(1, 1, 1); }
                20% { transform: translate3d(88px, 48px, 0) rotate(-10deg); }
                24% { transform: translate3d(90px, 58px, 0) scale3d(0.95, 0.95, 1); }
                38% { transform: translate3d(92px, 88px, 0) rotate(-30deg); }
                41% { transform: translate3d(94px, 94px, 0) scale3d(0.9, 0.9, 1); }
                60% { transform: translate3d(98px, 128px, 0) rotate(-60deg); opacity: 1; }
                85% { opacity: 1; transform: translate3d(98px, 128px, 0) rotate(-60deg); }
                95%, 100% { opacity: 0; transform: translate3d(98px, 128px, 0) rotate(-60deg); }
            }
            @keyframes granulePath3 {
                0% { transform: translate3d(125px, -15px, 0) scale3d(0.6, 0.6, 1); opacity: 0; }
                5% { opacity: 1; transform: translate3d(125px, -15px, 0) scale3d(1, 1, 1); }
                22% { transform: translate3d(122px, 48px, 0) rotate(20deg); }
                26% { transform: translate3d(120px, 58px, 0) scale3d(0.95, 0.95, 1); }
                40% { transform: translate3d(116px, 88px, 0) rotate(40deg); }
                43% { transform: translate3d(114px, 94px, 0) scale3d(0.9, 0.9, 1); }
                62% { transform: translate3d(110px, 127px, 0) rotate(80deg); opacity: 1; }
                85% { opacity: 1; transform: translate3d(110px, 127px, 0) rotate(80deg); }
                95%, 100% { opacity: 0; transform: translate3d(110px, 127px, 0) rotate(80deg); }
            }
            @keyframes dustPath1 {
                0% { transform: translate3d(55px, 10px, 0) scale3d(0.5, 0.5, 1); opacity: 0; }
                10% { opacity: 1; transform: translate3d(58px, 25px, 0) scale3d(1, 1, 1); }
                30% { transform: translate3d(65px, 45px, 0); opacity: 1; }
                45% { transform: translate3d(110px, 20px, 0) scale3d(0.6, 0.6, 1); opacity: 0.8; }
                55% { transform: translate3d(165px, 15px, 0) scale3d(0.2, 0.2, 1); opacity: 0; }
                100% { opacity: 0; }
            }
            @keyframes dustPath2 {
                0% { transform: translate3d(95px, 5px, 0) scale3d(0.5, 0.5, 1); opacity: 0; }
                10% { opacity: 1; transform: translate3d(98px, 20px, 0) scale3d(1, 1, 1); }
                28% { transform: translate3d(105px, 40px, 0); opacity: 1; }
                43% { transform: translate3d(130px, 22px, 0) scale3d(0.6, 0.6, 1); opacity: 0.8; }
                53% { transform: translate3d(168px, 18px, 0) scale3d(0.2, 0.2, 1); opacity: 0; }
                100% { opacity: 0; }
            }
            @keyframes dustPath3 {
                0% { transform: translate3d(135px, 12px, 0) scale3d(0.5, 0.5, 1); opacity: 0; }
                10% { opacity: 1; transform: translate3d(132px, 28px, 0) scale3d(1, 1, 1); }
                32% { transform: translate3d(122px, 48px, 0); opacity: 1; }
                47% { transform: translate3d(145px, 24px, 0) scale3d(0.6, 0.6, 1); opacity: 0.8; }
                57% { transform: translate3d(170px, 20px, 0) scale3d(0.2, 0.2, 1); opacity: 0; }
                100% { opacity: 0; }
            }
            @keyframes suctionDash {
                to { stroke-dashoffset: -18; }
            }

            /* --- 3. ODOR ANIMATIONS --- */
            .carbon-orbit {
                transform-origin: 100px 70px;
                animation: orbitRotate 15s infinite linear;
                will-change: transform;
            }
            .odor-molecule {
                transform-origin: center;
                opacity: 0;
                will-change: transform, opacity;
            }
            .odor-1 {
                animation: odorSuck1 5s infinite cubic-bezier(0.25, 1, 0.5, 1);
            }
            .odor-2 {
                animation: odorSuck2 5s infinite cubic-bezier(0.25, 1, 0.5, 1);
                animation-delay: 1.2s;
            }
            .odor-3 {
                animation: odorSuck3 5s infinite cubic-bezier(0.25, 1, 0.5, 1);
                animation-delay: 2.4s;
            }
            .trap-pulse {
                transform-origin: center;
                animation: trapLock1 5s infinite cubic-bezier(0.175, 0.885, 0.32, 1.275);
                will-change: transform, opacity, stroke;
            }
            .trap-pulse-1 { transform-origin: 75px 55px; }
            .trap-pulse-2 { transform-origin: 125px 55px; }
            .trap-pulse-3 { transform-origin: 100px 95px; }
            .trap-pulse-2-delay {
                animation-delay: 1.2s;
            }
            .trap-pulse-3-delay {
                animation-delay: 2.4s;
            }
            .scent-sparkle {
                transform-origin: center;
                opacity: 0;
                will-change: transform, opacity;
            }
            .scent-1 {
                animation: scentFloat1 5s infinite ease-out;
                animation-delay: 0.8s;
            }
            .scent-2 {
                animation: scentFloat2 5s infinite ease-out;
                animation-delay: 2.0s;
            }
            .scent-3 {
                animation: scentFloat1 5s infinite ease-out;
                animation-delay: 3.2s;
            }

            @keyframes orbitRotate {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            @keyframes odorSuck1 {
                0% { transform: translate3d(25px, 110px, 0) scale3d(0, 0, 1); opacity: 0; }
                10% { opacity: 0.9; transform: translate3d(30px, 95px, 0) scale3d(1, 1, 1); }
                30% { transform: translate3d(65px, 60px, 0) scale3d(0.8, 0.8, 1); opacity: 0.9; }
                36%, 100% { transform: translate3d(75px, 55px, 0) scale3d(0, 0, 1); opacity: 0; }
            }
            @keyframes odorSuck2 {
                0% { transform: translate3d(145px, 110px, 0) scale3d(0, 0, 1); opacity: 0; }
                10% { opacity: 0.9; transform: translate3d(140px, 95px, 0) scale3d(1, 1, 1); }
                30% { transform: translate3d(130px, 60px, 0) scale3d(0.8, 0.8, 1); opacity: 0.9; }
                36%, 100% { transform: translate3d(125px, 55px, 0) scale3d(0, 0, 1); opacity: 0; }
            }
            @keyframes odorSuck3 {
                0% { transform: translate3d(85px, 125px, 0) scale3d(0, 0, 1); opacity: 0; }
                10% { opacity: 0.9; transform: translate3d(88px, 115px, 0) scale3d(1, 1, 1); }
                30% { transform: translate3d(95px, 100px, 0) scale3d(0.8, 0.8, 1); opacity: 0.9; }
                36%, 100% { transform: translate3d(100px, 95px, 0) scale3d(0, 0, 1); opacity: 0; }
            }
            @keyframes trapLock1 {
                0%, 28% { transform: scale3d(1.6, 1.6, 1); opacity: 0; stroke: #a855f7; }
                34% { transform: scale3d(1, 1, 1); opacity: 1; stroke: #a855f7; stroke-width: 2.5; }
                45% { transform: scale3d(1, 1, 1); opacity: 0.4; stroke: #3b82f6; stroke-width: 1.5; }
                80% { opacity: 0.4; }
                88%, 100% { opacity: 0; transform: scale3d(1.6, 1.6, 1); }
            }
            @keyframes scentFloat1 {
                0%, 35% { transform: translate3d(100px, 70px, 0) scale3d(0, 0, 1); opacity: 0; }
                42% { opacity: 1; transform: translate3d(80px, 35px, 0) scale3d(1.2, 1.2, 1); }
                70% { opacity: 1; transform: translate3d(65px, 10px, 0) scale3d(1, 1, 1); }
                85% { opacity: 0; transform: translate3d(55px, -15px, 0) scale3d(0.8, 0.8, 1); }
                100% { opacity: 0; }
            }
            @keyframes scentFloat2 {
                0%, 38% { transform: translate3d(100px, 70px, 0) scale3d(0, 0, 1); opacity: 0; }
                45% { opacity: 1; transform: translate3d(120px, 32px, 0) scale3d(1.2, 1.2, 1); }
                72% { opacity: 1; transform: translate3d(135px, 8px, 0) scale3d(1, 1, 1); }
                87% { opacity: 0; transform: translate3d(145px, -18px, 0) scale3d(0.8, 0.8, 1); }
                100% { opacity: 0; }
        </style>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1: Molecular Bonding -->
            <div class="bg-slate-100/50 border border-slate-200/60 p-2 rounded-[2.5rem] transition-premium hover:-translate-y-2 hover:shadow-xl hover:shadow-blue-500/5 group">
                <div class="bg-white border border-[#e5e0d8]/40 p-8 rounded-[calc(2.5rem-0.5rem)] flex flex-col justify-between h-full space-y-6 shadow-[0_4px_12px_rgba(0,0,0,0.01)] group-hover:border-blue-500/30 transition-premium">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-xl group-hover:scale-110 transition-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 w-7 h-7">
                                    <path d="M12 2L21 7V17L12 22L3 17V7Z" />
                                    <path d="M12 22V12" />
                                    <path d="M12 12L21 7" />
                                    <path d="M12 12L3 7" />
                                    <circle cx="12" cy="12" r="2" fill="currentColor" />
                                    <circle cx="12" cy="2" r="1.5" fill="currentColor" />
                                    <circle cx="21" cy="7" r="1.5" fill="currentColor" />
                                    <circle cx="21" cy="17" r="1.5" fill="currentColor" />
                                    <circle cx="12" cy="22" r="1.5" fill="currentColor" />
                                    <circle cx="3" cy="17" r="1.5" fill="currentColor" />
                                    <circle cx="3" cy="7" r="1.5" fill="currentColor" />
                                </svg>
                            </div>
                            <span class="text-[10px] font-mono uppercase tracking-wider text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full font-bold">Instan & Padat</span>
                        </div>
                        
                        <div class="w-full h-32 rounded-2xl bg-slate-50/50 border border-slate-100 flex items-center justify-center p-2 overflow-hidden relative group-hover:bg-blue-50/30 transition-all duration-500">
                            <svg viewBox="0 0 200 150" class="w-full h-full" preserveAspectRatio="xMidYMid meet">
                                <defs>
                                    <pattern id="subtleGrid" width="16" height="16" patternUnits="userSpaceOnUse">
                                        <path d="M 16 0 L 0 0 0 16" fill="none" stroke="#f1f5f9" stroke-width="0.75" />
                                    </pattern>
                                    <radialGradient id="dropletGrad" cx="30%" cy="30%" r="70%">
                                        <stop offset="0%" stop-color="#93c5fd" />
                                        <stop offset="50%" stop-color="#3b82f6" />
                                        <stop offset="100%" stop-color="#1d4ed8" />
                                    </radialGradient>
                                    <!-- Bentonite Clay Grain Gradients -->
                                    <linearGradient id="dryGrainGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#f1f5f9" />
                                        <stop offset="50%" stop-color="#cbd5e1" />
                                        <stop offset="100%" stop-color="#94a3b8" />
                                    </linearGradient>
                                    <linearGradient id="wetGrainGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#64748b" />
                                        <stop offset="50%" stop-color="#475569" />
                                        <stop offset="100%" stop-color="#334155" />
                                    </linearGradient>
                                    <!-- Organic Sand Grain Templates -->
                                    <path id="sand1" d="M -10 -8 C -5 -12, 5 -12, 10 -8 C 12 -3, 8 8, 0 10 C -8 10, -12 5, -12 -3 Z" />
                                    <path id="sand2" d="M -8 -10 C 2 -12, 10 -8, 8 2 C 5 10, -5 12, -10 5 C -12 -2, -10 -8, -8 -10 Z" />
                                    <path id="sand3" d="M -10 -5 C -8 -10, 5 -10, 8 -3 C 10 5, 2 10, -5 10 C -10 8, -12 2, -10 -5 Z" />
                                </defs>

                                <rect width="100%" height="100%" fill="url(#subtleGrid)" />

                                <!-- Clump Glow Shield -->
                                <ellipse cx="100" cy="98" rx="30" ry="20" fill="#3b82f6" opacity="0" class="clump-glow" style="filter: blur(6px);" />

                                <!-- Molecular grid lines (kisi molekul) active when clumped -->
                                <g stroke="#60a5fa" stroke-width="1.5" stroke-linecap="round" opacity="0">
                                    <line x1="100" y1="86" x2="88" y2="90" class="bond-mesh" />
                                    <line x1="100" y1="86" x2="112" y2="90" class="bond-mesh" />
                                    <line x1="88" y1="90" x2="94" y2="104" class="bond-mesh" />
                                    <line x1="112" y1="90" x2="106" y2="104" class="bond-mesh" />
                                    <line x1="94" y1="104" x2="100" y2="110" class="bond-mesh" />
                                    <line x1="106" y1="104" x2="100" y2="110" class="bond-mesh" />
                                    <line x1="88" y1="90" x2="100" y2="110" class="bond-mesh" />
                                    <line x1="112" y1="90" x2="100" y2="110" class="bond-mesh" />
                                    <line x1="100" y1="86" x2="100" y2="110" class="bond-mesh" />
                                </g>

                                <!-- Dry background/surrounding granules (spread wider to fill card) -->
                                <use href="#sand1" x="35" y="105" fill="url(#dryGrainGrad)" stroke="#cbd5e1" stroke-width="0.5" />
                                <use href="#sand2" x="50" y="125" fill="url(#dryGrainGrad)" stroke="#cbd5e1" stroke-width="0.5" />
                                <use href="#sand3" x="65" y="85" fill="url(#dryGrainGrad)" stroke="#cbd5e1" stroke-width="0.5" />
                                <use href="#sand1" x="80" y="135" fill="url(#dryGrainGrad)" stroke="#cbd5e1" stroke-width="0.5" />
                                <use href="#sand2" x="120" y="135" fill="url(#dryGrainGrad)" stroke="#cbd5e1" stroke-width="0.5" />
                                <use href="#sand3" x="135" y="85" fill="url(#dryGrainGrad)" stroke="#cbd5e1" stroke-width="0.5" />
                                <use href="#sand1" x="150" y="125" fill="url(#dryGrainGrad)" stroke="#cbd5e1" stroke-width="0.5" />
                                <use href="#sand2" x="165" y="105" fill="url(#dryGrainGrad)" stroke="#cbd5e1" stroke-width="0.5" />
                                <use href="#sand3" x="100" y="55" fill="url(#dryGrainGrad)" stroke="#cbd5e1" stroke-width="0.5" />

                                <!-- Active clumping granules -->
                                <g class="clump-grain grain-a">
                                    <use href="#sand1" x="60" y="80" stroke-width="0.75" />
                                </g>
                                <g class="clump-grain grain-b">
                                    <use href="#sand2" x="80" y="115" stroke-width="0.75" />
                                </g>
                                <g class="clump-grain grain-c">
                                    <use href="#sand3" x="100" y="75" stroke-width="0.75" />
                                </g>
                                <g class="clump-grain grain-d">
                                    <use href="#sand1" x="120" y="110" stroke-width="0.75" />
                                </g>
                                <g class="clump-grain grain-e">
                                    <use href="#sand2" x="140" y="80" stroke-width="0.75" />
                                </g>
                                <g class="clump-grain grain-f">
                                    <use href="#sand3" x="100" y="125" stroke-width="0.75" />
                                </g>

                                <!-- Falling liquid droplet -->
                                <path d="M 0 0 C -3 -5, -3 -10, 0 -12 C 3 -10, 3 -5, 0 0 Z" fill="url(#dropletGrad)" class="droplet" transform="translate(100, 0)" />

                                <!-- Ripple wave on impact -->
                                <ellipse cx="100" cy="95" rx="6" ry="3" fill="none" stroke="#60a5fa" stroke-width="2" class="ripple-ring" />
                                <ellipse cx="100" cy="95" rx="10" ry="5" fill="none" stroke="#3b82f6" stroke-width="1.5" class="ripple-ring" style="animation-delay: 0.15s;" />

                                <!-- Status texts -->
                                <text x="100" y="142" text-anchor="middle" font-size="8" fill="#94a3b8" font-weight="black" letter-spacing="1.2" class="status-text-inactive">PASIR TERURAI</text>
                                <text x="100" y="142" text-anchor="middle" font-size="8" fill="#2563eb" font-weight="black" letter-spacing="1.2" class="status-text-locked" opacity="0">GUMPALAN KUAT</text>
                            </svg>
                        </div>

                        <h3 class="font-outfit font-black text-xl text-slate-900 group-hover:text-blue-600 transition-premium">
                            {{ \App\Models\Setting::get('feature_1_title', 'Molecular Bonding') }}
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed font-outfit">
                            {!! \App\Models\Setting::get('feature_1_desc', 'Butiran pasir membentuk <strong>ikatan kisi molekul</strong> yang kuat saat bereaksi dengan cairan. Tidak mudah pecah.') !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Feature 2: Zero-Dust Tech -->
            <div class="bg-slate-100/50 border border-slate-200/60 p-2 rounded-[2.5rem] transition-premium hover:-translate-y-2 hover:shadow-xl hover:shadow-cyan-500/5 group">
                <div class="bg-white border border-[#e5e0d8]/40 p-8 rounded-[calc(2.5rem-0.5rem)] flex flex-col justify-between h-full space-y-6 shadow-[0_4px_12px_rgba(0,0,0,0.01)] group-hover:border-cyan-500/30 transition-premium">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center text-xl group-hover:scale-110 transition-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-500 w-7 h-7"><path d="M12.8 19.6A2 2 0 1 0 14 16H2"/><path d="M17.5 8a2.5 2.5 0 1 1 2 4H2"/><path d="M9.8 4.4A2 2 0 1 1 11 8H2"/></svg>
                            </div>
                            <span class="text-[10px] font-mono uppercase tracking-wider text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-full font-bold">99.9% Bebas Debu</span>
                        </div>

                        <div class="w-full h-32 rounded-2xl bg-slate-50/50 border border-slate-100 flex items-center justify-center p-2 overflow-hidden relative group-hover:bg-cyan-50/30 transition-all duration-500">
                            <svg viewBox="0 0 200 150" class="w-full h-full" preserveAspectRatio="xMidYMid meet">
                                <defs>
                                    <linearGradient id="sieveGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#e2e8f0" />
                                        <stop offset="100%" stop-color="#94a3b8" />
                                    </linearGradient>
                                    <linearGradient id="cleanZoneGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#22d3ee" stop-opacity="0.25" />
                                        <stop offset="100%" stop-color="#22d3ee" stop-opacity="0" />
                                    </linearGradient>
                                    <radialGradient id="granuleGrad" cx="30%" cy="30%" r="70%">
                                        <stop offset="0%" stop-color="#cbd5e1" />
                                        <stop offset="60%" stop-color="#94a3b8" />
                                        <stop offset="100%" stop-color="#475569" />
                                    </radialGradient>
                                    <radialGradient id="dustGrad" cx="30%" cy="30%" r="70%">
                                        <stop offset="0%" stop-color="#fda4af" />
                                        <stop offset="100%" stop-color="#e11d48" />
                                    </radialGradient>
                                </defs>

                                <path d="M 160 10 C 170 15, 175 25, 185 25 L 205 25 L 205 0 L 160 0 Z" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="1" />
                                <circle cx="180" cy="12" r="15" fill="#e2e8f0" opacity="0.4" />
                                <path d="M 60 25 C 90 28, 130 18, 170 12" fill="none" stroke="#a5f3fc" stroke-width="1.5" class="suction-current" />
                                <path d="M 90 35 C 115 32, 140 22, 172 14" fill="none" stroke="#a5f3fc" stroke-width="1.5" class="suction-current" style="animation-delay: 0.3s;" />
                                <path d="M 120 50 C 135 42, 150 26, 174 16" fill="none" stroke="#a5f3fc" stroke-width="1" class="suction-current" style="animation-delay: 0.6s;" />

                                <rect x="15" y="95" width="170" height="40" fill="url(#cleanZoneGrad)" rx="8" />

                                <g class="sieve-plate">
                                    <rect x="15" y="55" width="170" height="6" fill="url(#sieveGrad)" rx="2" />
                                    <circle cx="35" cy="58" r="2" fill="#334155" />
                                    <circle cx="55" cy="58" r="2.5" fill="#334155" />
                                    <circle cx="75" cy="58" r="2.5" fill="#334155" />
                                    <circle cx="95" cy="58" r="2.5" fill="#334155" />
                                    <circle cx="115" cy="58" r="2.5" fill="#334155" />
                                    <circle cx="135" cy="58" r="2.5" fill="#334155" />
                                    <circle cx="155" cy="58" r="2.5" fill="#334155" />
                                    <circle cx="175" cy="58" r="2" fill="#334155" />
                                </g>

                                <g class="sieve-plate" style="animation-delay: 0.07s;">
                                    <rect x="15" y="95" width="170" height="4" fill="url(#sieveGrad)" rx="1.5" />
                                    <circle cx="25" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="35" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="45" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="55" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="65" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="75" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="85" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="95" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="105" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="115" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="125" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="135" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="145" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="155" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="165" cy="97" r="1.2" fill="#334155" />
                                    <circle cx="175" cy="97" r="1.2" fill="#334155" />
                                </g>

                                <text x="155" y="42" text-anchor="end" font-size="8" fill="#e11d48" font-weight="black" letter-spacing="0.5">EXHAUST VACUUM</text>
                                <text x="180" y="132" text-anchor="end" font-size="8" fill="#0891b2" font-weight="black" letter-spacing="1">CLEAN ZONE</text>

                                <g class="granule granule-1">
                                    <circle cx="0" cy="0" r="4.5" fill="url(#granuleGrad)" />
                                    <path d="M -2.5 -1.5 A 1.5 1.5 0 0 1 1 -1.5" fill="none" stroke="#fff" stroke-width="0.5" opacity="0.4" />
                                </g>
                                <g class="granule granule-2">
                                    <circle cx="0" cy="0" r="5.5" fill="url(#granuleGrad)" />
                                    <path d="M -3 -2 A 2 2 0 0 1 1.5 -2" fill="none" stroke="#fff" stroke-width="0.5" opacity="0.4" />
                                </g>
                                <g class="granule granule-3">
                                    <circle cx="0" cy="0" r="4" fill="url(#granuleGrad)" />
                                    <path d="M -2 -1 A 1 1 0 0 1 1 -1" fill="none" stroke="#fff" stroke-width="0.5" opacity="0.4" />
                                </g>

                                <circle cx="0" cy="0" r="1.8" fill="url(#dustGrad)" class="dust-particle dust-1" />
                                <circle cx="0" cy="0" r="2.2" fill="url(#dustGrad)" class="dust-particle dust-2" />
                                <circle cx="0" cy="0" r="1.5" fill="url(#dustGrad)" class="dust-particle dust-3" />

                                <g transform="translate(100, 135)" opacity="0.85">
                                    <path d="M -50 0 C -40 -6, -20 -10, 0 -10 C 20 -10, 40 -6, 50 0 Z" fill="#64748b" />
                                    <circle cx="-25" cy="-3" r="3.5" fill="#475569" />
                                    <circle cx="-10" cy="-6" r="4.5" fill="#334155" />
                                    <circle cx="10" cy="-7" r="4" fill="#475569" />
                                    <circle cx="28" cy="-4" r="3.5" fill="#334155" />
                                </g>
                            </svg>
                        </div>

                        <h3 class="font-outfit font-black text-xl text-slate-900 group-hover:text-cyan-600 transition-premium">
                            {{ \App\Models\Setting::get('feature_2_title', 'Zero-Dust Tech') }}
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed font-outfit">
                            {!! \App\Models\Setting::get('feature_2_desc', 'Sistem filtrasi ganda memisahkan butiran pasir dari <strong>mikro-partikel debu</strong> berbahaya.') !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Feature 3: Odor Encapsulation -->
            <div class="bg-slate-100/50 border border-slate-200/60 p-2 rounded-[2.5rem] transition-premium hover:-translate-y-2 hover:shadow-xl hover:shadow-purple-500/5 group">
                <div class="bg-white border border-[#e5e0d8]/40 p-8 rounded-[calc(2.5rem-0.5rem)] flex flex-col justify-between h-full space-y-6 shadow-[0_4px_12px_rgba(0,0,0,0.01)] group-hover:border-purple-500/30 transition-premium">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-xl group-hover:scale-110 transition-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-purple-500 w-7 h-7"><path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 5.5-2a1 1 0 0 1 1 0c1 .8 3.5 2 5.5 2a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                            </div>
                            <span class="text-[10px] font-mono uppercase tracking-wider text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full font-bold">Kontrol Bau Maksimal</span>
                        </div>

                        <div class="w-full h-32 rounded-2xl bg-slate-50/50 border border-slate-100 flex items-center justify-center p-2 overflow-hidden relative group-hover:bg-purple-50/30 transition-all duration-500">
                            <svg viewBox="0 0 200 150" class="w-full h-full" preserveAspectRatio="xMidYMid meet">
                                <defs>
                                    <linearGradient id="carbonGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#475569" />
                                        <stop offset="40%" stop-color="#1e293b" />
                                        <stop offset="100%" stop-color="#0f172a" />
                                    </linearGradient>
                                    <radialGradient id="odorGrad" cx="30%" cy="30%" r="70%">
                                        <stop offset="0%" stop-color="#86efac" />
                                        <stop offset="60%" stop-color="#22c55e" />
                                        <stop offset="100%" stop-color="#14532d" />
                                    </radialGradient>
                                    <radialGradient id="odorGlow" cx="50%" cy="50%" r="50%">
                                        <stop offset="0%" stop-color="#22c55e" stop-opacity="0.4" />
                                        <stop offset="100%" stop-color="#22c55e" stop-opacity="0" />
                                    </radialGradient>
                                    <linearGradient id="scentGrad" x1="0%" y1="100%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#a855f7" />
                                        <stop offset="50%" stop-color="#ec4899" />
                                        <stop offset="100%" stop-color="#f472b6" />
                                    </linearGradient>
                                </defs>

                                <g class="carbon-orbit" opacity="0.25">
                                    <ellipse cx="100" cy="70" rx="42" ry="16" fill="none" stroke="#64748b" stroke-width="0.75" stroke-dasharray="4 2" transform="rotate(30 100 70)" />
                                    <ellipse cx="100" cy="70" rx="42" ry="16" fill="none" stroke="#64748b" stroke-width="0.75" stroke-dasharray="4 2" transform="rotate(-30 100 70)" />
                                    <circle cx="65" cy="50" r="2" fill="#3b82f6" />
                                    <circle cx="135" cy="90" r="2" fill="#3b82f6" />
                                    <circle cx="65" cy="90" r="2" fill="#10b981" />
                                    <circle cx="135" cy="50" r="2" fill="#10b981" />
                                </g>

                                <g class="odor-molecule odor-1">
                                    <circle cx="0" cy="0" r="12" fill="url(#odorGlow)" />
                                    <circle cx="-4" cy="2" r="3.5" fill="url(#odorGrad)" />
                                    <circle cx="3" cy="-3" r="3" fill="url(#odorGrad)" />
                                    <circle cx="4" cy="4" r="2.5" fill="url(#odorGrad)" />
                                </g>
                                <g class="odor-molecule odor-2">
                                    <circle cx="0" cy="0" r="14" fill="url(#odorGlow)" />
                                    <circle cx="-3" cy="-3" r="4" fill="url(#odorGrad)" />
                                    <circle cx="3" cy="2" r="3" fill="url(#odorGrad)" />
                                    <circle cx="-2" cy="4" r="2" fill="url(#odorGrad)" />
                                </g>
                                <g class="odor-molecule odor-3">
                                    <circle cx="0" cy="0" r="12" fill="url(#odorGlow)" />
                                    <circle cx="3" cy="3" r="3.5" fill="url(#odorGrad)" />
                                    <circle cx="-3" cy="-2" r="3" fill="url(#odorGrad)" />
                                </g>

                                <g transform="translate(100, 70)">
                                    <circle cx="0" cy="0" r="26" fill="url(#carbonGrad)" stroke="#475569" stroke-width="1.5" />
                                    <circle cx="-14" cy="-10" r="6" fill="#090d16" />
                                    <circle cx="14" cy="-10" r="6" fill="#090d16" />
                                    <circle cx="0" cy="16" r="6" fill="#090d16" />
                                    <text x="0" y="4" text-anchor="middle" font-size="10" font-weight="black" fill="#334155" letter-spacing="1">C</text>
                                </g>

                                <circle cx="86" cy="60" r="8" fill="none" stroke="#a855f7" stroke-width="2.5" class="trap-pulse trap-pulse-1" opacity="0" />
                                <circle cx="114" cy="60" r="8" fill="none" stroke="#a855f7" stroke-width="2.5" class="trap-pulse trap-pulse-2 trap-pulse-2-delay" opacity="0" />
                                <circle cx="100" cy="86" r="8" fill="none" stroke="#a855f7" stroke-width="2.5" class="trap-pulse trap-pulse-3 trap-pulse-3-delay" opacity="0" />

                                <g class="scent-sparkle scent-1">
                                    <path d="M 0 -6 L 1 -2 L 5 -1 L 1 0 L 0 4 L -1 0 L -5 -1 L -1 -2 Z" fill="url(#scentGrad)" />
                                </g>
                                <g class="scent-sparkle scent-2">
                                    <path d="M 0 -5 L 1 -1.5 L 4 -1 L 1 0 L 0 3.5 L -1 0 L -4 -1 L -1 -1.5 Z" fill="url(#scentGrad)" />
                                </g>
                                <g class="scent-sparkle scent-3">
                                    <path d="M 0 -4 L 0.8 -1.2 L 3 -0.8 L 0.8 0 L 0 2.8 L -0.8 0 L -3 -0.8 L -0.8 -1.2 Z" fill="url(#scentGrad)" />
                                </g>

                                <text x="100" y="142" text-anchor="middle" font-size="8" fill="#a855f7" font-weight="black" letter-spacing="1.2">ENCAPSULATION ACTIVE</text>
                            </svg>
                        </div>

                        <h3 class="font-outfit font-black text-xl text-slate-900 group-hover:text-purple-600 transition-premium">
                            {{ \App\Models\Setting::get('feature_3_title', 'Odor Encapsulation') }}
                        </h3>
                        <p class="text-xs text-slate-500 leading-relaxed font-outfit">
                            {!! \App\Models\Setting::get('feature_3_desc', 'Molekul bau (amonia) <strong>dikurung aktif</strong> oleh karbon aktif, bukan sekedar ditutupi parfum.') !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- 3. Katalog Section -->
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-28 space-y-12">
        <div class="text-center space-y-3">
            <h2 class="font-outfit font-black text-3xl sm:text-4xl text-slate-900">Katalog BentoCat Premium</h2>
            <p class="text-sm text-slate-500 max-w-xl mx-auto">Varian pasir kucing bentonit premium dengan penawaran kualitas gumpalan tinggi.</p>
        </div>

        @if($products->count() === 1)
            @php $product = $products->first(); @endphp
            <div class="max-w-4xl mx-auto bg-white border border-[#e5e0d8]/80 rounded-[2.5rem] overflow-hidden group hover:border-amber-500/50 hover:shadow-xl transition-premium p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                    <!-- Left: Large Image Container -->
                    <div class="md:col-span-5 aspect-square bg-white rounded-3xl overflow-hidden border border-slate-100 flex items-center justify-center p-6 relative">
                        @if($product->thumbnail)
                            <img src="{{ asset($product->thumbnail) }}" alt="{{ $product->nama }}" class="w-full h-full object-contain group-hover:scale-[1.03] transition-premium">
                        @else
                            <span class="text-6xl">🐈</span>
                        @endif
                    </div>
                    
                    <!-- Right: Product Information & CTAs -->
                    <div class="md:col-span-7 space-y-5 flex flex-col justify-between h-full py-2">
                        <div class="space-y-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
                                Produk Unggulan
                            </span>
                            <h3 class="font-outfit font-black text-2xl md:text-3xl text-slate-900 group-hover:text-amber-600 transition-premium">
                                {{ $product->nama }}
                            </h3>
                            <span class="inline-block text-xs text-slate-400 font-mono">ID: PROD-00{{ $product->id }}</span>
                            <p class="text-xs text-slate-500 leading-relaxed pt-2">
                                {{ $product->deskripsi ? strip_tags($product->deskripsi) : 'Pasir bentonit wangi gumpal kualitas premium.' }}
                            </p>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Varian Tersedia:</span>
                            <div class="flex flex-wrap gap-2">
                                @forelse($product->variants->whereNull('parent_id') as $v1)
                                    <span class="bg-[#FAF8F5] hover:bg-amber-50 hover:text-amber-700 text-slate-600 text-xs font-bold px-3 py-1.5 rounded-xl border border-slate-200/50 transition-premium cursor-default">{{ $v1->nama }}</span>
                                @empty
                                    <span class="text-xs text-slate-400 italic">Varian standar saja.</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="pt-4">
                            <button onclick="openSearchModal()" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-3.5 px-6 rounded-xl shadow-md shadow-amber-500/10 transition-premium text-xs uppercase tracking-wider cursor-pointer">
                                <span>📍</span> Cari Toko Terdekat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($products as $product)
                    <div class="bg-white border border-[#e5e0d8]/80 rounded-[2rem] overflow-hidden group hover:border-amber-500/50 hover:shadow-lg transition-premium flex flex-col justify-between">
                        <div class="p-6 space-y-4">
                            <div class="aspect-square bg-white rounded-2xl overflow-hidden border border-slate-100 flex items-center justify-center p-4">
                                @if($product->thumbnail)
                                    <img src="{{ asset($product->thumbnail) }}" alt="{{ $product->nama }}" class="w-full h-full object-contain group-hover:scale-103 transition-premium">
                                @else
                                    <span class="text-5xl">🐈</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-outfit font-bold text-lg text-slate-900 group-hover:text-amber-600 transition-premium">{{ $product->nama }}</h3>
                                <span class="block text-[10px] text-slate-400 font-mono mt-0.5">ID: PROD-00{{ $product->id }}</span>
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{ $product->deskripsi ? strip_tags($product->deskripsi) : 'Pasir bentonit wangi gumpal kualitas premium.' }}
                            </p>
                        </div>
                        <div class="p-6 border-t border-slate-100 bg-[#FAF8F5]/50">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Varian Tersedia:</span>
                            <div class="flex flex-wrap gap-1">
                                @forelse($product->variants->whereNull('parent_id') as $v1)
                                    <span class="bg-white text-slate-600 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200/60">{{ $v1->nama }}</span>
                                @empty
                                    <span class="text-xs text-slate-400 italic">Varian standar saja.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 text-slate-400 italic">
                        Belum ada katalog produk terdaftar.
                    </div>
                @endforelse
            </div>
        @endif
    </section>

    <!-- 4. Discovery Search Modal & FAB -->
    <!-- Floating Action Button (FAB) -->
    <button onclick="openSearchModal()" class="fixed bottom-6 right-6 z-40 bg-amber-500 hover:bg-amber-600 active:scale-95 hover:scale-105 text-slate-950 font-black py-4 px-6 rounded-full shadow-2xl shadow-amber-500/20 transition-premium flex items-center gap-2.5 group cursor-pointer border-none">
        <span class="text-lg">📍</span>
        <span class="text-xs uppercase tracking-wider font-bold">Cari Toko BentoCat</span>
        <span class="inline-block animate-ping absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
    </button>

    <!-- Search Modal Overlay -->
    <div id="search-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <!-- Backdrop blur -->
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" onclick="closeSearchModal()"></div>

        <!-- Modal Centerer -->
        <div class="flex min-h-screen items-center justify-center p-4 sm:p-6 md:p-8">
            <div class="relative w-full max-w-2xl transform overflow-hidden rounded-[2.5rem] bg-[#FAF8F5] border border-[#e5e0d8] p-6 sm:p-10 text-left shadow-2xl transition-premium space-y-6">
                
                <!-- Close Button -->
                <button type="button" onclick="closeSearchModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-800 transition-premium text-xl border-none bg-transparent cursor-pointer">
                    ✕
                </button>

                <!-- Modal Header -->
                <div class="space-y-2 pr-8">
                    <h3 class="font-outfit font-black text-2xl sm:text-3xl text-slate-900 flex items-center gap-2">
                        <span>Cari Toko BentoCat Terdekat</span> 🐾
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Masukkan kota dan produk pilihan Anda untuk menemukan petshop mitra resmi terdekat dengan harga terbaik.
                    </p>
                </div>

                <!-- Global error notifications inside Modal -->
                @if($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-600 space-y-1">
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

                    <!-- Geolocation trigger (GPS Browser) -->
                    <div class="bg-white p-4 rounded-2xl border border-[#e5e0d8] flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="space-y-1 text-center sm:text-left">
                            <span class="block text-xs font-bold text-slate-850">Bagikan Lokasi Akurat (GPS)</span>
                            <span class="block text-[10px] text-slate-500">Membantu mengurutkan petshop resmi terdekat di layar hasil secara real-time.</span>
                        </div>
                        <button type="button" id="gps-btn" onclick="requestGPS()" class="bg-[#FAF8F5] border border-[#e5e0d8] hover:border-amber-500/50 hover:text-amber-600 text-slate-650 font-bold px-4 py-2.5 rounded-xl text-[10px] flex items-center gap-1.5 transition-premium shadow-sm shrink-0 cursor-pointer">
                            <span>Bagikan Koordinat</span> 📍
                        </button>
                    </div>

                    <!-- Regions Selector Dropdowns -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Province Select -->
                        <div>
                            <label for="provinsi_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Provinsi Tujuan</label>
                            <select name="provinsi_id" id="provinsi_id" required onchange="loadCities(this.value)" class="w-full bg-white border border-[#e5e0d8] focus:border-amber-500 rounded-xl px-3 py-3 text-xs text-slate-800 focus:outline-none transition-premium">
                                <option value="">Pilih Provinsi...</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City Select -->
                        <div>
                            <label for="kota_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kota / Kabupaten</label>
                            <select name="kota_id" id="kota_id" required disabled onchange="onCityChange(this.value)" class="w-full bg-white border border-[#e5e0d8] focus:border-amber-500 rounded-xl px-3 py-3 text-xs text-slate-800 focus:outline-none transition-premium disabled:opacity-45">
                                <option value="">Pilih Kota...</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Petshop List Preview (Shown after choosing city) -->
                    <div id="petshop-preview-wrapper" class="hidden bg-white p-4 rounded-2xl border border-[#e5e0d8] space-y-3">
                        <div class="flex justify-between items-center border-b border-slate-100 pb-1.5">
                            <span class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider">🏪 Petshop Terdaftar di Wilayah Ini:</span>
                            <span id="petshop-preview-count" class="text-[10px] font-black text-amber-700 bg-amber-50 px-2 py-0.5 rounded">0 Mitra</span>
                        </div>
                        <div id="petshop-preview-list" class="space-y-2 max-h-36 overflow-y-auto pr-1 text-xs text-slate-700">
                            <!-- Populated via AJAX -->
                        </div>
                        <div id="petshop-direct-link-container" class="pt-2 border-t border-slate-100 text-[10px] text-slate-500 leading-relaxed">
                            <!-- Link to city landing page direct browse -->
                        </div>
                    </div>

                    <!-- Product Selection & Variant cascades -->
                    <div class="bg-amber-500/5 p-5 rounded-2xl border border-amber-500/10 space-y-4">
                        <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider border-b border-amber-500/10 pb-1.5">Produk Yang Dicari</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <!-- Product Selection -->
                            <div class="col-span-12 md:col-span-6" id="wrapper_produk_id">
                                <label for="produk_id" class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Pilih Produk</label>
                                <select name="produk_id" id="produk_id" required onchange="onProductChange(this.value)" class="w-full bg-white border border-[#e5e0d8] focus:border-amber-500 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:outline-none">
                                    <option value="">Pilih Produk...</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Variant Lvl 1 -->
                            <div class="col-span-12 md:col-span-6 hidden" id="wrapper_varian_level_1">
                                <label for="varian_level_1" id="label_varian_level_1" class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Kategori / Seri</label>
                                <select name="varian_level_1" id="varian_level_1" disabled onchange="onLevel1Change(this.value)" class="w-full bg-white border border-[#e5e0d8] focus:border-amber-500 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:outline-none disabled:opacity-40">
                                    <option value="" id="placeholder_varian_level_1">Pilih Kategori...</option>
                                </select>
                            </div>

                            <!-- Variant Lvl 2 -->
                            <div class="col-span-12 md:col-span-6 hidden" id="wrapper_varian_level_2">
                                <label for="varian_level_2" id="label_varian_level_2" class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Aroma / Scent</label>
                                <select name="varian_level_2" id="varian_level_2" disabled onchange="onLevel2Change(this.value)" class="w-full bg-white border border-[#e5e0d8] focus:border-amber-500 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:outline-none disabled:opacity-40">
                                    <option value="" id="placeholder_varian_level_2">Pilih Aroma...</option>
                                </select>
                            </div>

                            <!-- Variant Lvl 3 -->
                            <div class="col-span-12 md:col-span-6 hidden" id="wrapper_varian_level_3">
                                <label for="varian_level_3" id="label_varian_level_3" class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Ukuran / Kemasan</label>
                                <select name="varian_level_3" id="varian_level_3" disabled class="w-full bg-white border border-[#e5e0d8] focus:border-amber-500 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:outline-none disabled:opacity-40">
                                    <option value="" id="placeholder_varian_level_3">Pilih Ukuran...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Turnstile widget -->
                    @if(env('TURNSTILE_SITE_KEY'))
                        <div class="flex justify-center pt-1">
                            <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}"></div>
                        </div>
                    @endif

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-4 rounded-xl shadow-lg shadow-amber-500/10 hover:shadow-amber-500/25 transition-premium text-xs uppercase tracking-wider cursor-pointer">
                            Temukan Toko Terdekat 🔍
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- 5. Artikel & SEO Blog Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-3">
                <h2 class="font-outfit font-black text-3xl text-slate-900">Tips & Edukasi Perawatan Kucing</h2>
                <p class="text-sm text-slate-500 max-w-md">Ketahui tips merawat kotak pasir kucing, menjaga kesegaran rumah, dan memilih jenis pasir terbaik.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-xs font-bold text-amber-650 hover:text-amber-700 hover:underline shrink-0">Lihat Semua Artikel →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($articles as $article)
                <article class="bg-white border border-[#e5e0d8]/80 rounded-[2rem] overflow-hidden group hover:border-amber-500/50 shadow-sm hover:shadow-md transition-premium flex flex-col justify-between">
                    <div class="p-6 space-y-4">
                        <div class="aspect-[16/10] bg-slate-50 rounded-2xl overflow-hidden border border-slate-100 flex items-center justify-center text-4xl">
                            📚
                        </div>
                        <div class="space-y-2">
                            <span class="block text-[10px] text-slate-400 font-bold uppercase">{{ $article->published_at ? $article->published_at->format('d M Y') : 'Draft' }}</span>
                            <h3 class="font-outfit font-bold text-base text-slate-900 group-hover:text-amber-650 transition-premium">
                                <a href="{{ route('blog.show', $article->slug) }}">{{ $article->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                                {{ $article->summary ?: 'Baca info selengkapnya tentang artikel perawatan kucing ini...' }}
                            </p>
                        </div>
                    </div>
                    <div class="p-6 border-t border-slate-100 bg-[#FAF8F5]/40 flex justify-between items-center text-xs">
                        <span class="text-slate-400">Penulis: <strong class="text-slate-700">{{ $article->author->name }}</strong></span>
                        <a href="{{ route('blog.show', $article->slug) }}" class="font-bold text-amber-650 hover:underline">Baca Lengkap &rarr;</a>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12 text-slate-400 italic">Belum ada tulisan artikel yang dipublikasikan.</div>
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

    function openSearchModal() {
        const modal = document.getElementById('search-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeSearchModal() {
        const modal = document.getElementById('search-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Auto-open modal on validation errors or hash trigger
    function checkHashAndOpen() {
        if (window.location.hash === '#cari-outlet' || window.location.hash === '#search') {
            openSearchModal();
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        checkHashAndOpen();
        @if($errors->any() || old('nama'))
            openSearchModal();
        @endif

        // Intercept clicks on links pointing to #cari-outlet to open modal instantly
        document.querySelectorAll('a[href*="#cari-outlet"]').forEach(link => {
            link.addEventListener('click', (e) => {
                const isHomepage = window.location.pathname === '/' || window.location.pathname.endsWith('/index.php');
                if (isHomepage) {
                    openSearchModal();
                }
            });
        });
    });

    window.addEventListener('hashchange', checkHashAndOpen);

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
                btn.className = "bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold px-4 py-2.5 rounded-xl text-[10px] flex items-center gap-1.5 transition-premium shrink-0";
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

    let citiesCache = [];

    function loadCities(provinceId) {
        const citySelect = document.getElementById('kota_id');
        const previewWrapper = document.getElementById('petshop-preview-wrapper');
        previewWrapper.classList.add('hidden'); // Reset preview when province changes
        citySelect.innerHTML = '<option value="">Memuat...</option>';
        citySelect.disabled = true;

        if (!provinceId) {
            citySelect.innerHTML = '<option value="">Pilih Kota...</option>';
            return;
        }

        fetch(`/api/cities-by-province/${provinceId}`)
            .then(res => res.json())
            .then(data => {
                citiesCache = data; // Store in cache for slugs/names
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

    function onCityChange(cityId) {
        const previewWrapper = document.getElementById('petshop-preview-wrapper');
        const previewList = document.getElementById('petshop-preview-list');
        const previewCount = document.getElementById('petshop-preview-count');
        const linkContainer = document.getElementById('petshop-direct-link-container');

        if (!cityId) {
            previewWrapper.classList.add('hidden');
            return;
        }

        // Find city details in cache
        const city = citiesCache.find(c => c.id == cityId);
        const citySlug = city ? city.slug : '';
        const cityName = city ? city.nama : 'Kota Ini';

        previewList.innerHTML = '<div class="text-[11px] text-slate-400 italic">Memuat daftar petshop...</div>';
        previewWrapper.classList.remove('hidden');

        fetch(`/api/outlets-by-city/${cityId}`)
            .then(res => res.json())
            .then(outlets => {
                previewList.innerHTML = '';
                previewCount.innerText = `${outlets.length} Mitra`;

                if (outlets.length === 0) {
                    previewList.innerHTML = `
                        <div class="p-3 bg-rose-50/50 border border-rose-100 rounded-xl text-rose-700 text-[11px] leading-relaxed">
                            ⚠️ Belum ada petshop resmi di kota ini. 
                            Anda akan secara otomatis diarahkan ke <strong>Distributor Utama Regional</strong> untuk pengiriman langsung dari gudang.
                        </div>
                    `;
                    linkContainer.innerHTML = '';
                } else {
                    outlets.forEach(outlet => {
                        const item = document.createElement('div');
                        item.className = "p-2.5 bg-white rounded-xl border border-slate-200/50 shadow-sm flex items-start gap-2 text-[11px]";
                        
                        const badge = outlet.featured 
                            ? `<span class="bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded text-[8px] font-bold">Featured ⭐</span>` 
                            : ``;
                        const typeBadge = outlet.is_mitra 
                            ? `<span class="bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded text-[8px] font-bold">Mitra Resmi 🐾</span>` 
                            : `<span class="bg-slate-100 text-slate-650 px-1.5 py-0.5 rounded text-[8px] font-bold">Retailer</span>`;

                        item.innerHTML = `
                            <span class="text-base shrink-0">🏪</span>
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <strong class="text-slate-800">${outlet.nama_outlet}</strong>
                                    ${badge}
                                    ${typeBadge}
                                </div>
                                <p class="text-slate-500 text-[10px] leading-tight">${outlet.alamat_lengkap}</p>
                            </div>
                        `;
                        previewList.appendChild(item);
                    });

                    // Add Direct Browse Link
                    if (citySlug) {
                        linkContainer.innerHTML = `
                            💡 Ingin beli eceran langsung ke petshop? Anda juga bisa 
                            <a href="/kota/${citySlug}" target="_blank" class="text-amber-650 hover:text-amber-755 hover:underline font-bold inline-flex items-center gap-0.5">
                                Kunjungi Halaman Wilayah ${cityName} ↗
                            </a>
                            tanpa mengisi formulir di atas.
                        `;
                    } else {
                        linkContainer.innerHTML = '';
                    }
                }
            })
            .catch(err => {
                console.error("Error loading preview outlets:", err);
                previewList.innerHTML = '<div class="text-[11px] text-rose-650">Gagal memuat daftar petshop.</div>';
            });
    }

    // Dynamic Select Cascading logic for variants
    function onProductChange(productId) {
        const lvl1Select = document.getElementById('varian_level_1');
        const lvl2Select = document.getElementById('varian_level_2');
        const lvl3Select = document.getElementById('varian_level_3');
        const wrap1 = document.getElementById('wrapper_varian_level_1');
        const wrap2 = document.getElementById('wrapper_varian_level_2');
        const wrap3 = document.getElementById('wrapper_varian_level_3');
 
        // Reset all select elements & hide wrappers
        wrap1.classList.add('hidden');
        wrap2.classList.add('hidden');
        wrap3.classList.add('hidden');

        lvl1Select.innerHTML = '<option value="" id="placeholder_varian_level_1">Pilih Kategori...</option>';
        lvl1Select.disabled = true;
        lvl2Select.innerHTML = '<option value="" id="placeholder_varian_level_2">Pilih Aroma...</option>';
        lvl2Select.disabled = true;
        lvl3Select.innerHTML = '<option value="" id="placeholder_varian_level_3">Pilih Ukuran...</option>';
        lvl3Select.disabled = true;

        updateGridWidths(false);

        if (!productId) return;

        const product = productsData.find(p => p.id == productId);
        if (!product || !product.variants) return;

        // Level 1: Root variants (parent_id is null)
        const lvl1Variants = product.variants.filter(v => v.parent_id === null);

        if (lvl1Variants.length > 0) {
            // Update label & placeholder
            const label1 = product.label_level_1 || 'Kategori';
            document.getElementById('label_varian_level_1').innerText = label1;
            document.getElementById('placeholder_varian_level_1').innerText = `Pilih ${label1}...`;

            lvl1Variants.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.nama;
                opt.dataset.id = v.id;
                opt.innerText = v.nama;
                lvl1Select.appendChild(opt);
            });
            lvl1Select.disabled = false;
            wrap1.classList.remove('hidden');
            updateGridWidths(true);
        }
    }

    function onLevel1Change(value) {
        const lvl1Select = document.getElementById('varian_level_1');
        const lvl2Select = document.getElementById('varian_level_2');
        const lvl3Select = document.getElementById('varian_level_3');
        const wrap2 = document.getElementById('wrapper_varian_level_2');
        const wrap3 = document.getElementById('wrapper_varian_level_3');

        // Reset level 2 & 3
        wrap2.classList.add('hidden');
        wrap3.classList.add('hidden');

        lvl2Select.innerHTML = '<option value="" id="placeholder_varian_level_2">Pilih Aroma...</option>';
        lvl2Select.disabled = true;
        lvl3Select.innerHTML = '<option value="" id="placeholder_varian_level_3">Pilih Ukuran...</option>';
        lvl3Select.disabled = true;

        if (!value) return;

        const selectedOpt = lvl1Select.options[lvl1Select.selectedIndex];
        const parentId = selectedOpt.dataset.id;
        if (!parentId) return;

        const productId = document.getElementById('produk_id').value;
        const product = productsData.find(p => p.id == productId);
        if (!product) return;

        // Level 2: Child variants (parent_id matches level 1 variant id)
        const lvl2Variants = product.variants.filter(v => v.parent_id == parentId);

        if (lvl2Variants.length > 0) {
            const label2 = product.label_level_2 || 'Aroma';
            document.getElementById('label_varian_level_2').innerText = label2;
            document.getElementById('placeholder_varian_level_2').innerText = `Pilih ${label2}...`;

            lvl2Variants.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.nama;
                opt.dataset.id = v.id;
                opt.innerText = v.nama;
                lvl2Select.appendChild(opt);
            });
            lvl2Select.disabled = false;
            wrap2.classList.remove('hidden');
        }
    }

    function onLevel2Change(value) {
        const lvl2Select = document.getElementById('varian_level_2');
        const lvl3Select = document.getElementById('varian_level_3');
        const wrap3 = document.getElementById('wrapper_varian_level_3');

        // Reset level 3
        wrap3.classList.add('hidden');
        lvl3Select.innerHTML = '<option value="" id="placeholder_varian_level_3">Pilih Ukuran...</option>';
        lvl3Select.disabled = true;

        if (!value) return;

        const selectedOpt = lvl2Select.options[lvl2Select.selectedIndex];
        const parentId = selectedOpt.dataset.id;
        if (!parentId) return;

        const productId = document.getElementById('produk_id').value;
        const product = productsData.find(p => p.id == productId);
        if (!product) return;

        // Level 3: Child variants (parent_id matches level 2 variant id)
        const lvl3Variants = product.variants.filter(v => v.parent_id == parentId);

        if (lvl3Variants.length > 0) {
            const label3 = product.label_level_3 || 'Ukuran';
            document.getElementById('label_varian_level_3').innerText = label3;
            document.getElementById('placeholder_varian_level_3').innerText = `Pilih ${label3}...`;

            lvl3Variants.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.nama;
                opt.dataset.id = v.id;
                opt.innerText = v.nama;
                lvl3Select.appendChild(opt);
            });
            lvl3Select.disabled = false;
            wrap3.classList.remove('hidden');
        }
    }

    function updateGridWidths(hasVariants) {
        const prodWrapper = document.getElementById('wrapper_produk_id');
        if (hasVariants) {
            prodWrapper.classList.remove('col-span-12');
            prodWrapper.classList.add('col-span-12', 'md:col-span-6');
        } else {
            prodWrapper.classList.remove('md:col-span-6');
            prodWrapper.classList.add('col-span-12');
        }
    }
</script>
@endsection
