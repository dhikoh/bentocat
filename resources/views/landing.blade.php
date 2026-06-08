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
                    <div class="w-16 h-16 rounded-2xl bg-white border border-[#e5e0d8] flex items-center justify-center p-1 shrink-0 overflow-hidden shadow-sm">
                        <img src="{{ asset(\App\Models\Setting::get('hero_product_image', 'images/product_default.png')) }}" alt="Product Image" class="w-full h-full object-contain bg-white">
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

            /* --- 1. MOLECULAR BONDING (SCOPED) --- */
            .mb-wrapper {
                width: 100%;
                aspect-ratio: 1 / 1;
                margin: 0 auto;
                background: radial-gradient(circle at 50% 50%, #242436 0%, #0d0d16 100%);
                border-radius: 24px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), inset 0 0 0 1px rgba(255, 255, 255, 0.05);
                overflow: hidden;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .molecular-svg {
                width: 100%;
                height: 100%;
                display: block;
            }
            .lattice-system {
                transform-origin: 250px 250px;
                animation: mb-slowRotate 24s infinite linear;
                will-change: transform;
            }
            .lattice-group {
                transform-origin: 250px 250px;
                animation: mb-latticeCompact 5s infinite ease-in-out;
                will-change: transform;
            }
            .mb-bond {
                stroke-linecap: round;
                stroke-linejoin: round;
                animation: mb-bondGlow 5s infinite ease-in-out;
                will-change: stroke, stroke-width, filter;
            }
            .mb-granule {
                animation: mb-granuleAnim 5s infinite ease-in-out;
                will-change: transform, filter;
            }
            .mb-g-top { transform-origin: 250px 110px; }
            .mb-g-top-right { transform-origin: 371px 180px; }
            .mb-g-bottom-right { transform-origin: 371px 320px; }
            .mb-g-bottom { transform-origin: 250px 390px; }
            .mb-g-bottom-left { transform-origin: 129px 320px; }
            .mb-g-top-left { transform-origin: 129px 180px; }
            .mb-g-center { transform-origin: 250px 250px; }
            .mb-droplet {
                fill: #00d4ff;
                filter: drop-shadow(0 0 8px #00d4ff);
                animation: mb-dropFall 5s infinite ease-in;
                will-change: transform, opacity;
            }
            .mb-shockwave {
                fill: none;
                stroke: #00d4ff;
                animation: mb-ripple 5s infinite ease-out;
                will-change: transform, opacity;
            }
            .mb-shield-layer {
                transform-origin: 250px 250px;
                animation: mb-shieldAppear 5s infinite ease-in-out;
                will-change: transform, opacity, fill, stroke;
            }
            @keyframes mb-slowRotate {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            @keyframes mb-dropFall {
                0% { transform: translate3d(0, -150px, 0) scale(1); opacity: 0; }
                2% { opacity: 1; }
                10% { transform: translate3d(0, 180px, 0) scale(0.9); opacity: 1; }
                11%, 100% { opacity: 0; transform: translate3d(0, 180px, 0) scale(0); }
            }
            @keyframes mb-ripple {
                0%, 10% { r: 0; opacity: 1; stroke-width: 12; }
                18% { r: 160; opacity: 0; stroke-width: 1; }
                100% { r: 160; opacity: 0; stroke-width: 0; }
            }
            @keyframes mb-latticeCompact {
                0%, 10% { transform: scale(1); }
                14%, 80% { transform: scale(0.38); }
                85%, 100% { transform: scale(1); }
            }
            @keyframes mb-bondGlow {
                0%, 10% { stroke: #3a3a4c; stroke-width: 4px; filter: none; }
                14%, 80% { stroke: #00d4ff; stroke-width: 15px; filter: drop-shadow(0 0 10px #00d4ff); }
                85%, 100% { stroke: #3a3a4c; stroke-width: 4px; filter: none; }
            }
            @keyframes mb-shieldAppear {
                0%, 10% { opacity: 0; transform: scale(1); fill: rgba(0, 212, 255, 0); stroke: rgba(0, 212, 255, 0); }
                14%, 80% { opacity: 1; transform: scale(2.5); fill: rgba(0, 212, 255, 0.15); stroke: rgba(0, 212, 255, 0.8); stroke-width: 2px; }
                85%, 100% { opacity: 0; transform: scale(1); fill: rgba(0, 212, 255, 0); stroke: rgba(0, 212, 255, 0); }
            }
            @keyframes mb-granuleAnim {
                0%, 10% { transform: scale(1); filter: url(#rocky) brightness(1); }
                14%, 80% { transform: scale(2.5); filter: url(#rocky) brightness(1.2); }
                85%, 100% { transform: scale(1); filter: url(#rocky) brightness(1); }
            }

            /* --- 2. ZERO-DUST TECH (SCOPED) --- */
            .zd-wrapper {
                width: 100%;
                aspect-ratio: 1 / 1;
                margin: 0 auto;
                background: #121318; 
                position: relative;
                overflow: hidden;
                border-radius: 24px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.5), inset 0 0 0 1px rgba(255,255,255,0.05);
                display: flex;
                justify-content: center;
                align-items: center;
                container-type: size;
            }
            .zd-ambient-light {
                position: absolute;
                width: 80%;
                height: 80%;
                background: radial-gradient(circle, rgba(74, 144, 226, 0.15) 0%, transparent 60%);
                top: 10%;
                left: 10%;
                pointer-events: none;
            }
            .zd-glass-tube {
                position: absolute;
                width: 32%;
                height: 100%;
                left: 34%;
                background: linear-gradient(90deg, 
                    rgba(255,255,255,0.02) 0%, 
                    rgba(255,255,255,0.1) 10%, 
                    rgba(255,255,255,0.01) 50%, 
                    rgba(255,255,255,0.08) 90%, 
                    rgba(255,255,255,0.02) 100%);
                border-left: 2px solid rgba(255,255,255,0.15);
                border-right: 2px solid rgba(255,255,255,0.25);
                box-shadow: inset 10px 0 20px rgba(0,0,0,0.3), inset -10px 0 20px rgba(0,0,0,0.3);
                backdrop-filter: blur(2px);
                z-index: 10;
            }
            .zd-filter-mesh {
                position: absolute;
                top: 40%;
                width: 100%;
                height: 12px;
                background: repeating-linear-gradient(
                    45deg,
                    #444 0px,
                    #444 2px,
                    transparent 2px,
                    transparent 6px
                );
                border-top: 2px solid #777;
                border-bottom: 2px solid #555;
                box-shadow: 0 5px 10px rgba(0,0,0,0.4);
                z-index: 15;
            }
            .zd-vacuum-system {
                position: absolute;
                top: 50%;
                left: 66%;
                width: 25%;
                height: 20%;
                background: linear-gradient(to right, rgba(30,32,40,0.9), #1a1c23);
                border: 2px solid rgba(255,255,255,0.1);
                border-left: none;
                border-radius: 0 12px 12px 0;
                box-shadow: 10px 10px 20px rgba(0,0,0,0.5), inset 0 0 15px rgba(0,0,0,0.8);
                z-index: 5;
                overflow: hidden;
                display: flex;
                align-items: center;
            }
            .zd-vacuum-air {
                position: absolute;
                width: 100%;
                height: 100%;
                background: repeating-linear-gradient(
                    90deg,
                    transparent 0px,
                    transparent 10px,
                    rgba(74, 144, 226, 0.1) 15px,
                    transparent 20px
                );
                animation: zd-suckAir 1s linear infinite;
            }
            .zd-vacuum-light {
                position: absolute;
                right: 10%;
                width: 6px;
                height: 40%;
                background: #4a90e2;
                border-radius: 4px;
                box-shadow: 0 0 10px #4a90e2, 0 0 20px #4a90e2;
            }
            .zd-particle-container {
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                z-index: 12;
                pointer-events: none;
            }
            .zd-sand {
                position: absolute;
                width: clamp(8px, 2.5%, 16px);
                aspect-ratio: 1/1;
                background: radial-gradient(circle at 30% 30%, #e2d1bc, #b39f88, #7a6b58);
                box-shadow: inset -2px -2px 4px rgba(0,0,0,0.5), inset 1px 1px 3px rgba(255,255,255,0.4), 0 2px 4px rgba(0,0,0,0.3);
                top: 0;
                left: 50%;
                animation: zd-dropSand var(--duration) linear infinite;
                animation-delay: var(--delay);
                will-change: transform;
            }
            .zd-dust {
                position: absolute;
                width: clamp(3px, 1%, 6px);
                aspect-ratio: 1/1;
                background: #eef1f5;
                border-radius: 50%;
                filter: blur(1px);
                opacity: 0;
                box-shadow: 0 0 3px rgba(255,255,255,0.8);
                mix-blend-mode: screen;
                top: 0;
                left: 50%;
                animation: zd-dropDust var(--duration) ease-in infinite;
                animation-delay: var(--delay);
                will-change: transform, opacity;
            }
            @keyframes zd-suckAir {
                0% { transform: translateX(0); }
                100% { transform: translateX(20px); }
            }
            @keyframes zd-dropSand {
                0% {
                    transform: translate3d(var(--startX), -5cqh, 0) rotate(0deg);
                }
                100% {
                    transform: translate3d(var(--startX), 105cqh, 0) rotate(var(--rot));
                }
            }
            @keyframes zd-dropDust {
                0% {
                    transform: translate3d(var(--startX), -5cqh, 0) scale(1);
                    opacity: 0;
                }
                10% {
                    opacity: 0.7;
                }
                45% {
                    transform: translate3d(var(--startX), 48cqh, 0) scale(1);
                    opacity: 0.7;
                }
                70% {
                    transform: translate3d(calc(var(--startX) + 20cqw), 55cqh, 0) scale(0.6);
                    opacity: 0.3;
                }
                100% {
                    transform: translate3d(calc(var(--startX) + 35cqw), 60cqh, 0) scale(0);
                    opacity: 0;
                }
            }

            /* --- 3. ODOR ENCAPSULATION (SCOPED) --- */
            .oe-wrapper {
                width: 100%;
                aspect-ratio: 1 / 1;
                margin: 0 auto;
                background: #121318; 
                position: relative;
                overflow: hidden;
                border-radius: 24px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.5), inset 0 0 0 1px rgba(255,255,255,0.05);
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                touch-action: none;
                --oe-mouseX: 50cqw;
                --oe-mouseY: 50cqh;
                container-type: size;
            }
            .oe-ambient-light {
                position: absolute;
                width: 100%;
                height: 100%;
                background: radial-gradient(circle at 50% 50%, rgba(74, 144, 226, 0.1) 0%, transparent 70%);
                pointer-events: none;
                z-index: 1;
            }
            .oe-shield {
                position: absolute;
                width: 35%;
                aspect-ratio: 1;
                border: 2px solid rgba(74, 144, 226, 0.6);
                border-radius: 50%;
                box-shadow: 0 0 30px rgba(74, 144, 226, 0.2), inset 0 0 20px rgba(74, 144, 226, 0.2);
                z-index: 5;
                animation: oe-pulseShield 4s ease-out infinite;
            }
            .oe-shield.delay {
                animation-delay: 2s;
            }
            @keyframes oe-pulseShield {
                0% { transform: scale(0.8); opacity: 0; border-width: 1px; }
                40% { opacity: 1; border-color: rgba(74, 144, 226, 0.8); }
                100% { transform: scale(1.8); opacity: 0; border-width: 4px; }
            }
            .oe-center-core {
                position: absolute;
                width: 100%;
                height: 100%;
                z-index: 10;
                transform-style: preserve-3d;
                transform: translate(calc(var(--oe-mouseX) - 50%), calc(var(--oe-mouseY) - 50%));
            }
            .oe-floating-core {
                position: absolute;
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                transform-style: preserve-3d;
                animation: oe-floatCarbon 6s ease-in-out infinite;
            }
            .oe-atom-ring {
                position: absolute;
                width: 55%;
                aspect-ratio: 1;
                border: 2px solid rgba(74, 144, 226, 0.25);
                border-radius: 50%;
                box-shadow: 0 0 15px rgba(74, 144, 226, 0.15), inset 0 0 15px rgba(74, 144, 226, 0.15);
            }
            .oe-atom-ring::before {
                content: '';
                position: absolute;
                width: 8px;
                height: 8px;
                background: #89c4ff;
                border-radius: 50%;
                box-shadow: 0 0 10px #4a90e2, 0 0 20px #4a90e2, 0 0 30px #fff;
                top: -5px;
                left: calc(50% - 4px);
            }
            .oe-ring-1 { animation: oe-spinRing1 6s linear infinite; }
            .oe-ring-2 { animation: oe-spinRing2 8s linear infinite; }
            .oe-ring-3 { animation: oe-spinRing3 10s linear infinite; }
            @keyframes oe-spinRing1 {
                0% { transform: rotateX(65deg) rotateY(45deg) rotateZ(0deg); }
                100% { transform: rotateX(65deg) rotateY(45deg) rotateZ(360deg); }
            }
            @keyframes oe-spinRing2 {
                0% { transform: rotateX(65deg) rotateY(-45deg) rotateZ(0deg); }
                100% { transform: rotateX(65deg) rotateY(-45deg) rotateZ(360deg); }
            }
            @keyframes oe-spinRing3 {
                0% { transform: rotateX(75deg) rotateY(0deg) rotateZ(0deg); }
                100% { transform: rotateX(75deg) rotateY(0deg) rotateZ(360deg); }
            }
            .oe-carbon-granule {
                position: relative;
                width: 30%;
                aspect-ratio: 1;
                background: linear-gradient(135deg, #3a3d45 0%, #1a1c23 50%, #0a0b0e 100%);
                border-radius: 50%;
                border: 2px solid #2a2d35;
                box-shadow: 
                    inset 10px 10px 20px rgba(255,255,255,0.05), 
                    inset -10px -10px 20px rgba(0,0,0,0.8), 
                    0 10px 30px rgba(0,0,0,0.5),
                    0 0 30px rgba(74, 144, 226, 0.15);
                z-index: 10;
                display: flex;
                justify-content: center;
                align-items: center;
                transform: translateZ(0);
            }
            .oe-carbon-granule::after {
                content: '';
                position: absolute;
                width: 70%;
                height: 70%;
                border: 2px dashed rgba(74, 144, 226, 0.4);
                border-radius: 50%;
                animation: oe-spinTech 15s linear infinite;
            }
            @keyframes oe-floatCarbon {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            @keyframes oe-spinTech {
                100% { transform: rotate(360deg); }
            }
            .oe-molecule-container {
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                z-index: 8;
                pointer-events: none;
            }
            .oe-odor-molecule {
                position: absolute;
                width: var(--size);
                aspect-ratio: 1/1;
                background: radial-gradient(circle, #d4a373, #a98467);
                border-radius: 50%;
                box-shadow: 0 0 10px rgba(212, 163, 115, 0.6);
                filter: blur(1px);
                opacity: 0;
                top: 0;
                left: 0;
                animation: oe-encapsulateOdor var(--duration) ease-in-out infinite;
                animation-delay: var(--delay);
                pointer-events: none;
                will-change: transform, opacity;
            }
            @keyframes oe-encapsulateOdor {
                0% {
                    transform: translate3d(var(--startX), var(--startY), 0) scale(0.5);
                    opacity: 0;
                }
                15% {
                    opacity: 0.8;
                    transform: translate3d(var(--startX), var(--startY), 0) scale(1.2);
                }
                50% {
                    transform: translate3d(var(--midX), var(--midY), 0) scale(1);
                    opacity: 0.9;
                    background: radial-gradient(circle, #d4a373, #a98467);
                    box-shadow: 0 0 10px rgba(212, 163, 115, 0.6);
                }
                75% {
                    transform: translate3d(calc(var(--midX) * 0.3 + var(--oe-mouseX) * 0.7), calc(var(--midY) * 0.3 + var(--oe-mouseY) * 0.7), 0) scale(0.6);
                    opacity: 0.8;
                    background: radial-gradient(circle, #89c4ff, #4a90e2);
                    box-shadow: 0 0 20px #4a90e2;
                    filter: blur(1.5px);
                }
                90% {
                    transform: translate3d(var(--oe-mouseX), var(--oe-mouseY), 0) scale(0.2);
                    opacity: 0.4;
                }
                100% {
                    transform: translate3d(var(--oe-mouseX), var(--oe-mouseY), 0) scale(0);
                    opacity: 0;
                }
            }
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
                        
                        <div class="mb-wrapper">
                            <svg class="molecular-svg" viewBox="60 60 380 380" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <filter id="rocky" x="-30%" y="-30%" width="160%" height="160%">
                                        <feTurbulence type="fractalNoise" baseFrequency="0.04" numOctaves="4" result="noise" />
                                        <feDisplacementMap in="SourceGraphic" in2="noise" scale="12" xChannelSelector="R" yChannelSelector="G" result="displaced" />
                                        <feDropShadow dx="3" dy="6" stdDeviation="4" flood-color="#000000" flood-opacity="0.6" />
                                    </filter>
                                    <radialGradient id="sand-gradient" cx="35%" cy="35%" r="65%">
                                        <stop offset="0%" stop-color="#e3cdb5" />
                                        <stop offset="60%" stop-color="#ab937d" />
                                        <stop offset="100%" stop-color="#695543" />
                                    </radialGradient>
                                </defs>
                                <g class="lattice-system">
                                    <g class="lattice-group">
                                        <polygon class="mb-shield-layer" points="250,110 371,180 371,320 250,390 129,320 129,180" />
                                        <g class="bonds-group">
                                            <line x1="250" y1="110" x2="371" y2="180" class="mb-bond" />
                                            <line x1="371" y1="180" x2="371" y2="320" class="mb-bond" />
                                            <line x1="371" y1="320" x2="250" y2="390" class="mb-bond" />
                                            <line x1="250" y1="390" x2="129" y2="320" class="mb-bond" />
                                            <line x1="129" y1="320" x2="129" y2="180" class="mb-bond" />
                                            <line x1="129" y1="180" x2="250" y2="110" class="mb-bond" />
                                            <line x1="250" y1="250" x2="250" y2="110" class="mb-bond" />
                                            <line x1="250" y1="250" x2="371" y2="180" class="mb-bond" />
                                            <line x1="250" y1="250" x2="371" y2="320" class="mb-bond" />
                                            <line x1="250" y1="250" x2="250" y2="390" class="mb-bond" />
                                            <line x1="250" y1="250" x2="129" y2="320" class="mb-bond" />
                                            <line x1="250" y1="250" x2="129" y2="180" class="mb-bond" />
                                        </g>
                                        <g class="granules-group">
                                            <circle cx="250" cy="110" r="28" fill="url(#sand-gradient)" class="mb-granule mb-g-top" />
                                            <circle cx="371" cy="180" r="28" fill="url(#sand-gradient)" class="mb-granule mb-g-top-right" />
                                            <circle cx="371" cy="320" r="28" fill="url(#sand-gradient)" class="mb-granule mb-g-bottom-right" />
                                            <circle cx="250" cy="390" r="28" fill="url(#sand-gradient)" class="mb-granule mb-g-bottom" />
                                            <circle cx="129" cy="320" r="28" fill="url(#sand-gradient)" class="mb-granule mb-g-bottom-left" />
                                            <circle cx="129" cy="180" r="28" fill="url(#sand-gradient)" class="mb-granule mb-g-top-left" />
                                            <circle cx="250" cy="250" r="38" fill="url(#sand-gradient)" class="mb-granule mb-g-center" />
                                        </g>
                                    </g>
                                </g>
                                <g class="effects-overlay">
                                    <circle class="mb-shockwave" cx="250" cy="250" r="0" />
                                    <path class="mb-droplet" d="M250,45 C265,70 265,95 250,95 C235,95 235,70 250,45 Z" />
                                </g>
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

                        <div class="zd-wrapper">
                            <div class="zd-ambient-light"></div>
                            <div class="zd-glass-tube">
                                <div class="zd-filter-mesh"></div>
                            </div>
                            <div class="zd-vacuum-system">
                                <div class="zd-vacuum-air"></div>
                                <div class="zd-vacuum-light"></div>
                            </div>
                            <div class="zd-particle-container" id="zd-particle-system"></div>
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

                        <div class="oe-wrapper">
                            <div class="oe-ambient-light"></div>
                            <div class="oe-center-core">
                                <div class="oe-floating-core">
                                    <div class="oe-shield"></div>
                                    <div class="oe-shield delay"></div>
                                    <div class="oe-atom-ring oe-ring-1"></div>
                                    <div class="oe-atom-ring oe-ring-2"></div>
                                    <div class="oe-atom-ring oe-ring-3"></div>
                                    <div class="oe-carbon-granule"></div>
                                </div>
                            </div>
                            <div class="oe-molecule-container" id="oe-molecule-system"></div>
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

        // Zero-Dust Tech Particle System Initializer
        const zdContainer = document.getElementById('zd-particle-system');
        if (zdContainer) {
            const totalSand = 22; 
            const totalDust = 60; 

            for(let i = 0; i < totalSand; i++) {
                let sand = document.createElement('div');
                sand.className = 'zd-sand';
                let startX = (Math.random() * 28 - 14).toFixed(2);
                let delay = (Math.random() * 4).toFixed(2);
                let duration = (2 + Math.random() * 1.5).toFixed(2);
                let rotation = Math.floor(Math.random() * 360);
                let br1 = Math.floor(30 + Math.random() * 40);
                let br2 = Math.floor(30 + Math.random() * 40);
                let br3 = Math.floor(30 + Math.random() * 40);
                let br4 = Math.floor(30 + Math.random() * 40);
                
                sand.style.setProperty('--startX', `${startX}cqw`);
                sand.style.setProperty('--delay', `${delay}s`);
                sand.style.setProperty('--duration', `${duration}s`);
                sand.style.setProperty('--rot', `${rotation}deg`);
                sand.style.borderRadius = `${br1}% ${100-br1}% ${br2}% ${100-br2}% / ${br3}% ${br4}% ${100-br4}% ${100-br3}%`;
                
                zdContainer.appendChild(sand);
            }

            for(let i = 0; i < totalDust; i++) {
                let dust = document.createElement('div');
                dust.className = 'zd-dust';
                let startX = (Math.random() * 28 - 14).toFixed(2);
                let delay = (Math.random() * 4).toFixed(2);
                let duration = (2.5 + Math.random() * 2).toFixed(2);
                
                dust.style.setProperty('--startX', `${startX}cqw`);
                dust.style.setProperty('--delay', `${delay}s`);
                dust.style.setProperty('--duration', `${duration}s`);
                
                zdContainer.appendChild(dust);
            }
        }

        // Odor Encapsulation Particle System & Mouse Interactivity Initializer
        const oeContainer = document.getElementById('oe-molecule-system');
        const oeWrapper = document.querySelector('.oe-wrapper');
        if (oeContainer && oeWrapper) {
            const totalMolecules = 40;

            for(let i = 0; i < totalMolecules; i++) {
                let molecule = document.createElement('div');
                molecule.className = 'oe-odor-molecule';
                let size = (Math.random() * 8 + 6).toFixed(1) + 'px';
                let angle = Math.random() * Math.PI * 2;
                let startRadius = 60 + Math.random() * 20; 
                let startX = 50 + startRadius * Math.cos(angle);
                let startY = 50 + startRadius * Math.sin(angle);
                let midAngle = angle + (Math.random() * 0.8 - 0.4);
                let midRadius = 30 + Math.random() * 10;
                let midX = 50 + midRadius * Math.cos(midAngle);
                let midY = 50 + midRadius * Math.sin(midAngle);
                let delay = (Math.random() * 5).toFixed(2);
                let duration = (4 + Math.random() * 3).toFixed(2);
                
                molecule.style.setProperty('--size', size);
                molecule.style.setProperty('--startX', `${startX.toFixed(2)}cqw`);
                molecule.style.setProperty('--startY', `${startY.toFixed(2)}cqh`);
                molecule.style.setProperty('--midX', `${midX.toFixed(2)}cqw`);
                molecule.style.setProperty('--midY', `${midY.toFixed(2)}cqh`);
                molecule.style.setProperty('--delay', `${delay}s`);
                molecule.style.setProperty('--duration', `${duration}s`);
                
                oeContainer.appendChild(molecule);
            }

            let targetX = 50, targetY = 50;
            let currentX = 50, currentY = 50;

            const updateMousePosition = (e) => {
                let clientX, clientY;
                if (e.touches && e.touches.length > 0) {
                    clientX = e.touches[0].clientX;
                    clientY = e.touches[0].clientY;
                } else {
                    clientX = e.clientX;
                    clientY = e.clientY;
                }
                
                const rect = oeWrapper.getBoundingClientRect();
                targetX = ((clientX - rect.left) / rect.width) * 100;
                targetY = ((clientY - rect.top) / rect.height) * 100;
                targetX = Math.max(10, Math.min(90, targetX));
                targetY = Math.max(10, Math.min(90, targetY));
            };

            const resetMousePosition = () => {
                targetX = 50;
                targetY = 50;
            };

            oeWrapper.addEventListener('mousemove', updateMousePosition);
            oeWrapper.addEventListener('touchmove', (e) => {
                e.preventDefault();
                updateMousePosition(e);
            }, { passive: false });
            
            oeWrapper.addEventListener('mouseleave', resetMousePosition);
            oeWrapper.addEventListener('touchend', resetMousePosition);

            function animateInteraction() {
                currentX += (targetX - currentX) * 0.08; 
                currentY += (targetY - currentY) * 0.08;
                oeWrapper.style.setProperty('--oe-mouseX', `${currentX.toFixed(2)}cqw`);
                oeWrapper.style.setProperty('--oe-mouseY', `${currentY.toFixed(2)}cqh`);
                requestAnimationFrame(animateInteraction);
            }
            
            oeWrapper.className = 'oe-wrapper'; // Ensure class does not change
            animateInteraction();
        }
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
