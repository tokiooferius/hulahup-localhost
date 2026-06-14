<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Food-TYU — Platform digital kantin Telkom University Purwokerto. Pesan makanan dari kantin kampus tanpa antri, cepat, praktis, dan aman.">
    <title>Food-TYU — Kantin Digital Tel-U Purwokerto</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-foodtyu.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --navy:  #0B2D5C;
            --navy2: #122C4F;
            --blue:  #2D6A8F;
            --soft-blue: #7AB8FF;
            --cream: #FAF7EE;
            --pink:  #F5A8D0;
            --pink2: #F472B6;
            --white: #FFFFFF;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { background: var(--cream); overflow-x: hidden; }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            transition: all .4s cubic-bezier(.4,0,.2,1);
            padding: 18px 0;
        }
        .navbar.scrolled {
            background: rgba(11,45,92,0.95);
            backdrop-filter: blur(20px);
            padding: 10px 0;
            box-shadow: 0 4px 30px rgba(0,0,0,.25);
        }
        .nav-link {
            color: rgba(255,255,255,.75);
            font-weight: 600; font-size: 14px;
            transition: color .2s;
            text-decoration: none;
        }
        .nav-link:hover { color: #fff; }

        /* ===== HERO ===== */
        .hero-bg {
            background: 
                radial-gradient(circle, rgba(255, 255, 255, 0.12) 1px, transparent 1px),
                linear-gradient(135deg, #0B2D5C 0%, #1a4a7a 40%, #2D6A8F 70%, #8b3ea5 100%);
            background-size: 30px 30px, auto;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }
        .hero-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .25;
            animation: blobDrift 12s ease-in-out infinite;
        }
        @keyframes blobDrift {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(40px,-30px) scale(1.1); }
            66%      { transform: translate(-20px,20px) scale(.9); }
        }
        .dots-bg {
            background-image: radial-gradient(circle, rgba(255,255,255,.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        /* Float animation */
        @keyframes floatY {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-18px); }
        }
        @keyframes floatRotate {
            0%,100% { transform: translateY(0) rotate(0deg); }
            50%      { transform: translateY(-12px) rotate(2deg); }
        }
        .float-slow  { animation: floatY 6s ease-in-out infinite; }
        .float-med   { animation: floatRotate 5s ease-in-out infinite; }
        .float-fast  { animation: floatY 4s ease-in-out infinite; }

        /* Glow */
        .glow-pink { box-shadow: 0 0 40px rgba(245,168,208,.45), 0 0 80px rgba(245,168,208,.2); }
        .glow-blue { box-shadow: 0 0 40px rgba(122,184,255,.35); }

        /* Typewriter cursor */
        .cursor-blink { animation: blink 1s step-end infinite; }
        @keyframes blink { 50% { opacity:0; } }

        /* ===== COUNTER ===== */
        @keyframes countUp { from { opacity:0; transform:translateY(15px); } to { opacity:1; transform:translateY(0); } }
        .counter-animate { animation: countUp .6s ease forwards; }

        /* ===== CARDS ===== */
        .card-hover {
            transition: transform .35s cubic-bezier(.4,0,.2,1), box-shadow .35s;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(11,45,92,.18);
        }

        /* ===== MARQUEE ===== */
        @keyframes marquee  { from { transform:translateX(0); } to { transform:translateX(-50%); } }
        @keyframes marqueeR { from { transform:translateX(-50%); } to { transform:translateX(0); } }
        .marquee-l { animation: marquee 22s linear infinite; }
        .marquee-r { animation: marqueeR 22s linear infinite; }

        /* ===== GLASS ===== */
        .glass {
            background: rgba(255,255,255,.12);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.2);
        }
        .glass-dark {
            background: rgba(11,45,92,.65);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(122,184,255,.2);
        }

        /* ===== GRADIENT TEXT ===== */
        .grad-text {
            background: linear-gradient(135deg, #7AB8FF, #F5A8D0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .grad-text-warm {
            background: linear-gradient(135deg, #FAF7EE, #F5A8D0, #7AB8FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Section reveal */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity .7s ease, transform .7s ease;
        }
        .reveal.visible { opacity:1; transform:translateY(0); }

        /* ===== PHONE MOCKUP ===== */
        .phone-wrap {
            position: relative;
            width: 260px;
            height: 520px;
        }
        .phone-frame {
            width: 100%; height: 100%;
            background: linear-gradient(160deg,#1a2a4a,#0d1f3a);
            border-radius: 40px;
            border: 6px solid rgba(255,255,255,.15);
            overflow: hidden;
            position: relative;
            box-shadow: 0 30px 80px rgba(0,0,0,.5), inset 0 0 0 1px rgba(255,255,255,.05);
        }
        .phone-notch {
            width: 80px; height: 20px;
            background: #000;
            border-radius: 0 0 14px 14px;
            margin: 0 auto;
            position: relative; z-index: 2;
        }
        .phone-screen {
            background: var(--cream);
            height: calc(100% - 20px);
            border-radius: 0 0 34px 34px;
            overflow: hidden;
            padding: 12px;
        }

        /* Testimonial */
        .testi-card {
            background: white;
            border-radius: 24px;
            padding: 28px;
            border: 1px solid rgba(11,45,92,.06);
            transition: transform .3s, box-shadow .3s;
        }
        .testi-card:hover { transform:translateY(-5px); box-shadow:0 15px 40px rgba(11,45,92,.1); }

        /* Pulse ring */
        @keyframes pulseRing {
            0%  { transform: scale(1); opacity:.5; }
            100%{ transform: scale(1.8); opacity:0; }
        }
        .pulse-ring::before {
            content:''; position:absolute; inset:0;
            border-radius:50%;
            border: 2px solid var(--pink);
            animation: pulseRing 2s ease-out infinite;
        }

        /* Stats ticker */
        @keyframes slideIn { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        .stat-tick { animation: slideIn .4s ease; }

        /* Feature icon glow */
        .feat-icon { transition: transform .3s, box-shadow .3s; }
        .feat-icon:hover { transform: scale(1.1) rotate(5deg); }

        /* Mobile menu */
        #mobileMenu { transition: transform .35s cubic-bezier(.4,0,.2,1), opacity .35s; }
        #mobileMenu.hidden { transform:translateY(-20px); opacity:0; pointer-events:none; }
        #mobileMenu.open { transform:translateY(0); opacity:1; pointer-events:auto; }

        /* Scroll indicator */
        @keyframes scrollBounce {
            0%,100% { transform:translateY(0); }
            50%      { transform:translateY(8px); }
        }
        .scroll-indicator { animation: scrollBounce 1.8s ease infinite; }
    </style>
</head>
<body>

<!-- ================================================================
     NAVBAR
================================================================ -->
<nav class="navbar" id="navbar">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2.5 group">
            <div class="w-9 h-9 bg-white/15 rounded-xl flex items-center justify-center group-hover:bg-white/25 transition">
                <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Food-TYU" class="w-6 h-6 object-contain">
            </div>
            <span class="text-white font-black text-xl italic tracking-tight">Food-TYU<span class="text-[var(--pink)] text-2xl">.</span></span>
        </a>

        <!-- Desktop Links -->
        <div class="hidden md:flex items-center gap-8">
            <a href="#features" class="nav-link">Fitur</a>
            <a href="#canteens" class="nav-link">Kantin</a>
            <a href="#menus" class="nav-link">Menu</a>
            <a href="#testimonials" class="nav-link">Testimoni</a>
            <a href="#location" class="nav-link">Lokasi</a>
        </div>

        <!-- CTA -->
        <div class="hidden md:flex items-center gap-3">
            <a href="/login" class="nav-link px-5 py-2 rounded-full border border-white/25 hover:border-white/50 hover:bg-white/10 transition">Masuk</a>
            <a href="/signup" class="bg-[var(--pink)] hover:bg-pink-400 text-white font-bold px-5 py-2.5 rounded-full transition hover:shadow-lg hover:scale-105 text-sm">
                Daftar Gratis
            </a>
        </div>

        <!-- Mobile hamburger -->
        <button class="md:hidden text-white p-2" onclick="toggleMobileMenu()" id="hamburgerBtn">
            <i class="fas fa-bars text-xl" id="hamburgerIcon"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div class="hidden md:hidden" id="mobileMenu">
        <div class="mx-4 mt-3 bg-[var(--navy)]/95 backdrop-blur-xl rounded-2xl border border-white/10 p-4 space-y-1">
            <a href="#features" class="block text-white/80 hover:text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-white/10 transition" onclick="closeMobileMenu()">Fitur</a>
            <a href="#canteens" class="block text-white/80 hover:text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-white/10 transition" onclick="closeMobileMenu()">Kantin</a>
            <a href="#menus" class="block text-white/80 hover:text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-white/10 transition" onclick="closeMobileMenu()">Menu</a>
            <a href="#testimonials" class="block text-white/80 hover:text-white font-semibold py-2.5 px-4 rounded-xl hover:bg-white/10 transition" onclick="closeMobileMenu()">Testimoni</a>
            <div class="flex gap-2 pt-3 border-t border-white/10">
                <a href="/login" class="flex-1 text-center text-white border border-white/25 py-2.5 rounded-xl font-bold hover:bg-white/10 transition text-sm">Masuk</a>
                <a href="/signup" class="flex-1 text-center bg-[var(--pink)] text-white py-2.5 rounded-xl font-bold hover:bg-pink-400 transition text-sm">Daftar</a>
            </div>
        </div>
    </div>
</nav>


<!-- ================================================================
     HERO SECTION
================================================================ -->
<section class="hero-bg pt-24 pb-0" id="hero">
    <!-- Blobs -->
    <div class="hero-blob w-96 h-96 bg-[var(--pink)] -top-20 -right-20"></div>
    <div class="hero-blob w-80 h-80 bg-[var(--soft-blue)] bottom-20 -left-20" style="animation-delay:-5s"></div>
    <div class="hero-blob w-60 h-60 bg-purple-500 top-1/3 right-1/4" style="animation-delay:-9s"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[85vh] pb-16">

            <!-- Left: Copy -->
            <div class="text-white">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 glass rounded-full px-4 py-2 mb-6">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-white/80">🏫 Telkom University Purwokerto</span>
                </div>

                <!-- Headline -->
                <h1 class="text-5xl md:text-6xl xl:text-7xl font-black leading-[1.08] tracking-tighter mb-6">
                    <span class="block text-white">Stop Antri,</span>
                    <span class="block grad-text-warm">Pesan Lewat</span>
                    <span class="block text-white italic">Food-TYU<span class="text-[var(--pink)]">.</span></span>
                </h1>

                <!-- Typewriter sub -->
                <p class="text-white/70 text-lg md:text-xl font-medium leading-relaxed mb-10 max-w-lg">
                    Platform digital kantin kampus Tel-U Purwokerto. Pesan makanan favorit kamu <span class="text-[var(--pink)] font-bold">real-time</span>, bayar digital, dan ambil tanpa antre. 🚀
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4 mb-12">
                    <a href="/signup" id="ctaSignup"
                       class="group flex items-center gap-3 bg-white text-[var(--navy)] font-black px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
                        <span class="w-9 h-9 bg-[var(--pink)] rounded-xl flex items-center justify-center group-hover:rotate-12 transition">
                            <i class="fas fa-bolt text-white text-sm"></i>
                        </span>
                        Mulai Pesan Sekarang
                    </a>
                    <a href="/login" id="ctaLogin"
                       class="flex items-center gap-3 glass text-white font-bold px-7 py-4 rounded-2xl hover:bg-white/20 transition-all">
                        <i class="fas fa-sign-in-alt"></i>
                        Sudah Punya Akun
                    </a>
                </div>

                <!-- Mini stats -->
                <div class="flex flex-wrap gap-6">
                    <div>
                        <p class="text-3xl font-black text-white" id="heroStat1">{{ $activeCanteenCount ?: $totalCanteenCount }}</p>
                        <p class="text-xs text-white/50 font-bold uppercase tracking-widest">Kantin Aktif</p>
                    </div>
                    <div class="w-px bg-white/15"></div>
                    <div>
                        <p class="text-3xl font-black text-white" id="heroStat2">{{ $totalMenuCount }}+</p>
                        <p class="text-xs text-white/50 font-bold uppercase tracking-widest">Pilihan Menu</p>
                    </div>
                    <div class="w-px bg-white/15"></div>
                    <div>
                        <p class="text-3xl font-black text-white">⚡</p>
                        <p class="text-xs text-white/50 font-bold uppercase tracking-widest">Realtime Order</p>
                    </div>
                    <div class="w-px bg-white/15"></div>
                    <div>
                        <p class="text-3xl font-black text-[var(--pink)]">GRATIS</p>
                        <p class="text-xs text-white/50 font-bold uppercase tracking-widest">Daftar Akun</p>
                    </div>
                </div>
            </div>

            <!-- Right: Phone Mockup + Floating elements -->
            <div class="flex items-center justify-center relative">
                <!-- Glow ring -->
                <div class="absolute w-72 h-72 bg-[var(--pink)]/20 rounded-full blur-3xl float-slow"></div>
                <div class="absolute w-64 h-64 bg-[var(--soft-blue)]/20 rounded-full blur-3xl float-med" style="animation-delay:-3s; left:30%"></div>

                <!-- Phone -->
                <div class="phone-wrap float-med relative z-10">
                    <div class="phone-frame">
                        <div class="phone-notch"></div>
                        <div class="phone-screen">
                            <!-- App mockup inside phone -->
                            <div class="flex items-center gap-2 mb-3">
                                <img src="{{ asset('images/logo-foodtyu.png') }}" class="w-6 h-6 object-contain">
                                <span class="font-black text-[var(--navy)] text-sm">Food-TYU</span>
                            </div>
                            <div class="bg-gradient-to-r from-[var(--navy)] to-[var(--blue)] rounded-2xl p-3 mb-3">
                                <p class="text-white/60 text-[9px] font-bold uppercase tracking-wider">Saldo TyU-Pay</p>
                                <p class="text-white font-black text-lg">Rp 150.000</p>
                                <p class="text-[var(--pink)] text-[9px] font-bold">● Aktif · Mahasiswa</p>
                            </div>
                            <!-- Menu cards in phone -->
                            <p class="text-[9px] font-black text-[var(--navy)] uppercase tracking-widest mb-2">Menu Populer</p>
                            <div class="space-y-2">
                                @php
                                    $demoMenus = [
                                        ['emoji'=>'🍜','name'=>'Mie Ayam Spesial','price'=>'Rp 15.000','tag'=>'Terlaris'],
                                        ['emoji'=>'🍚','name'=>'Nasi Goreng Kantin A','price'=>'Rp 13.000','tag'=>'Hot'],
                                        ['emoji'=>'🥤','name'=>'Es Teh Manis','price'=>'Rp 5.000','tag'=>'Segar'],
                                    ];
                                @endphp
                                @foreach($demoMenus as $dm)
                                <div class="flex items-center gap-2 bg-white rounded-xl p-2 shadow-sm">
                                    <span class="text-xl">{{ $dm['emoji'] }}</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-[var(--navy)] text-[9px] truncate">{{ $dm['name'] }}</p>
                                        <p class="text-green-600 font-black text-[9px]">{{ $dm['price'] }}</p>
                                    </div>
                                    <span class="text-[8px] bg-[var(--pink)]/20 text-pink-600 px-1.5 py-0.5 rounded-full font-bold whitespace-nowrap">{{ $dm['tag'] }}</span>
                                </div>
                                @endforeach
                            </div>
                            <!-- Order button -->
                            <button class="w-full mt-3 bg-[var(--navy)] text-white font-black text-[10px] py-2.5 rounded-xl">
                                + Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Floating badges around phone -->
                <div class="absolute -top-4 -left-4 glass rounded-2xl px-4 py-2.5 float-slow" style="animation-delay:-1s">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🎉</span>
                        <div>
                            <p class="text-white font-black text-xs">Order Selesai!</p>
                            <p class="text-white/60 text-[10px]">Mie Ayam · Kantin A</p>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-2 -right-4 glass rounded-2xl px-4 py-2.5 float-fast" style="animation-delay:-2s">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">⚡</span>
                        <div>
                            <p class="text-white font-black text-xs">Pesanan Siap!</p>
                            <p class="text-white/60 text-[10px]">Estimasi 8 menit</p>
                        </div>
                    </div>
                </div>

                <div class="absolute top-1/2 -right-10 glass rounded-2xl px-3 py-2 float-slow" style="animation-delay:-4s">
                    <div class="text-center">
                        <p class="text-white font-black text-sm">⭐ 4.9</p>
                        <p class="text-white/60 text-[9px]">Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="flex flex-col items-center pb-8 relative z-10">
        <p class="text-white/40 text-xs font-bold uppercase tracking-widest mb-2">Scroll untuk explore</p>
        <div class="scroll-indicator w-6 h-10 border-2 border-white/25 rounded-full flex items-start justify-center pt-2">
            <div class="w-1 h-2 bg-white/50 rounded-full"></div>
        </div>
    </div>

    <!-- Wave divider -->
    <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" class="block w-full -mb-1" style="fill:var(--cream)">
        <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z"/>
    </svg>
</section>


<!-- ================================================================
     MARQUEE STRIP
================================================================ -->
<section class="py-4 bg-[var(--cream)] overflow-hidden">
    <div class="flex whitespace-nowrap marquee-l gap-0">
        @foreach(range(1,3) as $_)
        <span class="inline-flex items-center gap-2 px-6 text-[var(--navy)]/40 text-sm font-bold uppercase tracking-widest">🍜 Mie Ayam</span>
        <span class="inline-flex items-center gap-2 px-6 text-[var(--navy)]/40 text-sm font-bold uppercase tracking-widest">⚡ Tanpa Antri</span>
        <span class="inline-flex items-center gap-2 px-6 text-[var(--navy)]/40 text-sm font-bold uppercase tracking-widest">🍚 Nasi Goreng</span>
        <span class="inline-flex items-center gap-2 px-6 text-[var(--navy)]/40 text-sm font-bold uppercase tracking-widest">💳 QRIS & TyU-Pay</span>
        <span class="inline-flex items-center gap-2 px-6 text-[var(--navy)]/40 text-sm font-bold uppercase tracking-widest">🎟️ Pakai Voucher</span>
        <span class="inline-flex items-center gap-2 px-6 text-[var(--navy)]/40 text-sm font-bold uppercase tracking-widest">🏫 Tel-U Purwokerto</span>
        <span class="inline-flex items-center gap-2 px-6 text-[var(--navy)]/40 text-sm font-bold uppercase tracking-widest">🔒 Bayar Aman</span>
        <span class="inline-flex items-center gap-2 px-6 text-[var(--navy)]/40 text-sm font-bold uppercase tracking-widest">🥤 Minuman Segar</span>
        @endforeach
    </div>
</section>


<!-- ================================================================
     LIVE STATISTICS
================================================================ -->
<section class="py-20 px-6 bg-[var(--cream)]" id="stats">
    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-14 reveal">
            <span class="inline-block bg-[var(--navy)]/8 text-[var(--navy)] font-black text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-4">📊 Platform Stats</span>
            <h2 class="text-4xl md:text-5xl font-black text-[var(--navy)] tracking-tight">
                Dipercaya Seluruh <span class="grad-text">Civitas Kampus</span>
            </h2>
            <p class="text-slate-500 mt-4 max-w-xl mx-auto font-medium">Angka nyata yang membuktikan Food-TYU jadi pilihan utama kantin digital Telkom University Purwokerto.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 reveal">
        @php
            $stats = [
                ['icon'=>'🏪','val'=> ($activeCanteenCount ?: $totalCanteenCount) . '','label'=>'Kantin Aktif','sub'=>'Siap melayani sekarang','color'=>'from-[#0B2D5C] to-[#2D6A8F]'],
                ['icon'=>'🍽️','val'=> $totalMenuCount . '+','label'=>'Pilihan Menu','sub'=>'Update tiap hari','color'=>'from-[#2D6A8F] to-[#7AB8FF]'],
                ['icon'=>'⚡','val'=>'< 15','label'=>'Menit Proses','sub'=>'Rata-rata waktu siap','color'=>'from-[#a855f7] to-[#F5A8D0]'],
                ['icon'=>'⭐','val'=>'4.9','label'=>'Rating Rata-rata','sub'=>'Dari pengguna aktif','color'=>'from-[#F5A8D0] to-[#F472B6]'],
            ];
        @endphp
        @foreach($stats as $i => $s)
        <div class="card-hover bg-white rounded-3xl p-7 text-center border border-[var(--navy)]/5 shadow-sm relative overflow-hidden" style="transition-delay:{{ $i*0.1 }}s">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $s['color'] }}"></div>
            <div class="text-4xl mb-3">{{ $s['icon'] }}</div>
            <p class="text-4xl font-black text-[var(--navy)] mb-1">{{ $s['val'] }}</p>
            <p class="font-black text-[var(--navy)]/80 text-sm mb-1">{{ $s['label'] }}</p>
            <p class="text-slate-400 text-xs font-medium">{{ $s['sub'] }}</p>
        </div>
        @endforeach
        </div>

        <!-- Live ticker dengan data realtime -->
        <div class="mt-10 glass-dark rounded-2xl p-5 flex flex-wrap items-center gap-4 reveal">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-white/70 text-xs font-bold uppercase tracking-widest">Live Aktivitas</span>
            </div>
            <div class="flex-1 overflow-hidden">
                <div id="liveTicker" class="text-white/90 text-sm font-semibold stat-tick">
                    @if($latestOrder && $latestOrder->canteen)
                        @php
                            $tickerItems = is_array($latestOrder->items) ? $latestOrder->items : (json_decode($latestOrder->items, true) ?: []);
                            $firstItem = $tickerItems[0] ?? null;
                            $menuName = $firstItem['name'] ?? $firstItem['menu_name'] ?? 'menu lezat';
                            $buyerName = $latestOrder->user?->name ?? 'Mahasiswa';
                        @endphp
                        🍽️ <strong>{{ $buyerName }}</strong> baru saja memesan <strong>{{ $menuName }}</strong> dari {{ $latestOrder->canteen->name }}
                    @else
                        🍜 Seseorang baru saja memesan <strong>Mie Ayam Spesial</strong> dari Kantin Barokah
                    @endif
                </div>
            </div>
            <span class="text-[var(--pink)] text-xs font-black">REALTIME</span>
        </div>
    </div>
</section>


<!-- ================================================================
     POPULAR CANTEENS
================================================================ -->
<section class="py-20 px-6" id="canteens" style="background: linear-gradient(180deg, var(--cream) 0%, #EEF4FF 100%)">
    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-12 reveal">
            <div>
                <span class="inline-block bg-[var(--navy)]/8 text-[var(--navy)] font-black text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-4">🏪 Jelajahi</span>
                <h2 class="text-4xl md:text-5xl font-black text-[var(--navy)] tracking-tight">
                    Kantin <span class="grad-text">Populer</span>
                </h2>
                <p class="text-slate-500 mt-2 font-medium max-w-md">Tiga kantin terbaik di kampus Tel-U Purwokerto dengan menu beragam dan pelayanan cepat.</p>
            </div>
            <a href="/login" class="mt-4 md:mt-0 inline-flex items-center gap-2 text-[var(--navy)] font-black text-sm border-2 border-[var(--navy)]/20 hover:border-[var(--navy)] px-5 py-2.5 rounded-xl transition-all hover:bg-[var(--navy)] hover:text-white">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @php
            $canteensData = $canteens ?? collect();
        @endphp

        @if($canteensData->isEmpty())
        <!-- Demo cards when no canteen data -->
        <div class="grid md:grid-cols-3 gap-8 reveal">
            @php
                $demoCanteens = [
                    ['name'=>'Kantin A — Gedung B','desc'=>'Spesialis mie ayam, bakso, dan nasi goreng. Favorit mahasiswa teknik!','emoji'=>'🍜','rating'=>'4.9','menu'=>'18','open'=>true,'color'=>'from-orange-400 to-red-500'],
                    ['name'=>'Kantin B — Gedung C','desc'=>'Menu sehat dan bergizi. Pilihan sayur, soto, dan lauk pauk lengkap.','emoji'=>'🍲','rating'=>'4.8','menu'=>'22','open'=>true,'color'=>'from-green-400 to-teal-500'],
                    ['name'=>'Kantin C — Lapangan','desc'=>'Minuman segar, jajanan kampus, dan snack ringan untuk istirahat.','emoji'=>'🥤','rating'=>'4.7','menu'=>'14','open'=>false,'color'=>'from-blue-400 to-indigo-500'],
                ];
            @endphp
            @foreach($demoCanteens as $i => $dc)
            <div class="card-hover bg-white rounded-3xl overflow-hidden border border-[var(--navy)]/5 shadow-sm" style="transition-delay:{{ $i*0.1 }}s">
                <div class="h-48 bg-gradient-to-br {{ $dc['color'] }} relative flex items-center justify-center">
                    <span class="text-7xl opacity-90">{{ $dc['emoji'] }}</span>
                    <div class="absolute top-4 right-4">
                        <span class="text-xs font-black px-3 py-1.5 rounded-full {{ $dc['open'] ? 'bg-green-400/20 text-green-100 border border-green-400/30' : 'bg-red-400/20 text-red-100 border border-red-400/30' }}">
                            {{ $dc['open'] ? '● Buka' : '● Tutup' }}
                        </span>
                    </div>
                    <div class="absolute top-4 left-4 glass rounded-xl px-2.5 py-1.5 flex items-center gap-1.5">
                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                        <span class="text-white font-black text-xs">{{ $dc['rating'] }}</span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-black text-[var(--navy)] text-lg mb-2">{{ $dc['name'] }}</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-4">{{ $dc['desc'] }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-bold"><i class="fas fa-utensils mr-1.5"></i>{{ $dc['menu'] }} menu tersedia</span>
                        <a href="/login" class="bg-[var(--navy)] text-white text-xs font-black px-4 py-2 rounded-xl hover:bg-[var(--blue)] transition">
                            Lihat Menu →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        <!-- Real canteen data -->
        <div class="grid md:grid-cols-{{ min(3, $canteensData->count()) }} gap-8 reveal">
            @foreach($canteensData as $i => $canteen)
            @php
                $imgSrc = $canteen->image ?? $canteen->logo_url ?? null;
                $imgUrl = $imgSrc ? asset($imgSrc) : null;
                $fallback = 'https://ui-avatars.com/api/?name='.urlencode($canteen->name).'&background=0B2D5C&color=fff&size=400&bold=true&font-size=0.35';
                $menuCount = $canteen->menus_count ?? $canteen->menus()->where('is_available', true)->count();
                $isOpen = isset($canteen->status) ? in_array($canteen->status, ['buka', 'active', 'open']) : true;
            @endphp
            <div class="card-hover bg-white rounded-3xl overflow-hidden border border-[var(--navy)]/5 shadow-sm" style="transition-delay:{{ $i*0.1 }}s">
                <div class="h-48 relative overflow-hidden">
                    <img src="{{ $imgUrl ?? $fallback }}" alt="{{ $canteen->name }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent"></div>
                    <div class="absolute top-4 right-4">
                        <span class="text-xs font-black px-3 py-1.5 rounded-full {{ $isOpen ? 'bg-green-400/20 text-green-100 border border-green-400/30' : 'bg-red-400/20 text-red-100 border border-red-400/30' }}">{{ $isOpen ? '● Buka' : '● Tutup' }}</span>
                    </div>
                    <div class="absolute top-4 left-4 glass rounded-xl px-2.5 py-1.5 flex items-center gap-1.5">
                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                        <span class="text-white font-black text-xs">4.9</span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-black text-[var(--navy)] text-lg mb-2">{{ $canteen->name }}</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-4">{{ $canteen->description ?? 'Kantin terpercaya dengan menu lezat dan pelayanan cepat.' }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400 font-bold"><i class="fas fa-utensils mr-1.5"></i>{{ $menuCount }} menu tersedia</span>
                        <a href="/login" class="bg-[var(--navy)] text-white text-xs font-black px-4 py-2 rounded-xl hover:bg-[var(--blue)] transition">
                            Lihat Menu →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>


<!-- ================================================================
     TRENDING MENUS TODAY
================================================================ -->
<section class="py-20 px-6 bg-[var(--cream)]" id="menus">
    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-14 reveal">
            <span class="inline-block bg-red-50 text-red-500 font-black text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-4 border border-red-100">🔥 Trending Hari Ini</span>
            <h2 class="text-4xl md:text-5xl font-black text-[var(--navy)] tracking-tight">
                Menu <span class="grad-text">Paling Laris</span>
            </h2>
            <p class="text-slate-500 mt-4 max-w-xl mx-auto font-medium">Pilihan menu terpopuler yang disukai mahasiswa Tel-U setiap harinya.</p>
        </div>

        @php
            // $trendingMenus sudah disiapkan dari route dengan data penjualan realtime
            $trendingMenus = $trendingMenus ?? collect();
        @endphp

        @if($trendingMenus->isEmpty())
        <!-- Demo menu cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 reveal">
            @php
                $demoMenuItems = [
                    ['emoji'=>'🍜','name'=>'Mie Ayam Spesial','price'=>'Rp 15.000','rating'=>'4.9','sold'=>'128'],
                    ['emoji'=>'🍚','name'=>'Nasi Goreng Spesial','price'=>'Rp 13.000','rating'=>'4.8','sold'=>'98'],
                    ['emoji'=>'🍗','name'=>'Ayam Geprek','price'=>'Rp 18.000','rating'=>'4.9','sold'=>'87'],
                    ['emoji'=>'🥤','name'=>'Es Teh Manis','price'=>'Rp 5.000','rating'=>'4.7','sold'=>'220'],
                    ['emoji'=>'🍲','name'=>'Soto Ayam','price'=>'Rp 12.000','rating'=>'4.8','sold'=>'75'],
                    ['emoji'=>'🥙','name'=>'Lontong Sayur','price'=>'Rp 10.000','rating'=>'4.6','sold'=>'64'],
                ];
            @endphp
            @foreach($demoMenuItems as $i => $dm)
            <div class="card-hover bg-white rounded-2xl p-4 text-center border border-[var(--navy)]/5 shadow-sm" style="transition-delay:{{ $i*0.08 }}s">
                <div class="text-5xl mb-3">{{ $dm['emoji'] }}</div>
                <p class="font-black text-[var(--navy)] text-sm mb-1 line-clamp-2">{{ $dm['name'] }}</p>
                <p class="text-green-600 font-black text-sm mb-2">{{ $dm['price'] }}</p>
                <div class="flex items-center justify-center gap-1 mb-2">
                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                    <span class="text-xs font-bold text-slate-600">{{ $dm['rating'] }}</span>
                </div>
                <span class="text-[9px] bg-red-50 text-red-500 border border-red-100 font-black px-2 py-0.5 rounded-full">🔥 {{ $dm['sold'] }}x terjual</span>
            </div>
            @endforeach
        </div>
        @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 reveal">
            @foreach($trendingMenus as $i => $menu)
            @php
                // Cari gambar menu dengan berbagai kemungkinan path
                $menuImg = null;
                if (!empty($menu->image_url)) {
                    $menuImg = str_starts_with($menu->image_url, 'http') ? $menu->image_url : asset('storage/' . ltrim($menu->image_url, '/'));
                } elseif (!empty($menu->image)) {
                    $menuImg = str_starts_with($menu->image, 'http') ? $menu->image : asset('storage/menus/' . $menu->image);
                }
                $soldCount = $menu->sold_count ?? 0;
                $categoryEmoji = match($menu->category ?? '') {
                    'heavy' => '🍽️', 'beverage' => '🥤', 'snack' => '🍿', default => '🍽️'
                };
            @endphp
            <div class="card-hover bg-white rounded-2xl overflow-hidden border border-[var(--navy)]/5 shadow-sm" style="transition-delay:{{ $i*0.08 }}s">
                <!-- Image or emoji -->
                @if($menuImg)
                <div class="w-full h-28 overflow-hidden relative">
                    <img src="{{ $menuImg }}" alt="{{ $menu->name }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div style="display:none;" class="absolute inset-0 bg-gradient-to-br from-[var(--navy)]/10 to-[var(--blue)]/20 items-center justify-center text-4xl">{{ $categoryEmoji }}</div>
                    @if($soldCount > 0)
                    <div class="absolute top-2 right-2">
                        <span class="text-[9px] bg-red-500 text-white font-black px-2 py-0.5 rounded-full shadow">🔥 {{ $soldCount }}x</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="w-full h-28 bg-gradient-to-br from-[var(--navy)]/5 to-[var(--blue)]/10 flex items-center justify-center relative">
                    <span class="text-4xl">{{ $categoryEmoji }}</span>
                    @if($soldCount > 0)
                    <div class="absolute top-2 right-2">
                        <span class="text-[9px] bg-red-500 text-white font-black px-2 py-0.5 rounded-full shadow">🔥 {{ $soldCount }}x</span>
                    </div>
                    @endif
                </div>
                @endif
                <div class="p-3 text-center">
                    <p class="font-black text-[var(--navy)] text-xs mb-1 line-clamp-2 leading-tight">{{ $menu->name }}</p>
                    <p class="text-green-600 font-black text-sm mb-1">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    @if($menu->canteen)
                    <p class="text-[9px] text-slate-400 font-semibold mb-1 truncate">{{ $menu->canteen->name }}</p>
                    @endif
                    <div class="flex items-center justify-center gap-1">
                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                        <span class="text-xs font-bold text-slate-600">{{ $menu->rating ?? '4.8' }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="text-center mt-10 reveal">
            <a href="/login" class="inline-flex items-center gap-3 bg-[var(--navy)] text-white font-black px-8 py-4 rounded-2xl hover:bg-[var(--blue)] transition-all hover:scale-105 shadow-lg">
                <i class="fas fa-utensils"></i>
                Lihat Semua Menu
            </a>
        </div>
    </div>
</section>


<!-- ================================================================
     FEATURE HIGHLIGHTS
================================================================ -->
<section class="py-24 px-6 relative overflow-hidden" id="features" style="background: linear-gradient(135deg, #0B2D5C 0%, #1a3a6a 50%, #2D6A8F 100%)">
    <!-- Background decoration -->
    <div class="absolute inset-0 dots-bg opacity-30"></div>
    <div class="hero-blob w-80 h-80 bg-[var(--pink)] top-0 right-0"></div>
    <div class="hero-blob w-64 h-64 bg-[var(--soft-blue)] bottom-0 left-0" style="animation-delay:-6s"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center mb-16 reveal">
            <span class="inline-block glass text-white/80 font-black text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-4">✨ Keunggulan</span>
            <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight">
                Kenapa Pilih <span class="grad-text">Food-TYU?</span>
            </h2>
            <p class="text-white/60 mt-4 max-w-xl mx-auto font-medium">Didesain khusus untuk kebutuhan civitas akademika Telkom University Purwokerto.</p>
        </div>

        @php
            $features = [
                ['icon'=>'fa-bolt','emoji'=>'⚡','title'=>'Pesan Real-Time','desc'=>'Sistem pemesanan langsung terhubung ke dapur kantin. Status pesanan update otomatis tanpa refresh.','color'=>'from-yellow-400 to-orange-500'],
                ['icon'=>'fa-credit-card','emoji'=>'💳','title'=>'QRIS & TyU-Pay','desc'=>'Bayar dengan QRIS atau gunakan saldo deposit TyU-Pay. Aman, cepat, dan tidak perlu uang tunai.','color'=>'from-blue-400 to-indigo-500'],
                ['icon'=>'fa-ticket-alt','emoji'=>'🎟️','title'=>'Voucher & Diskon','desc'=>'Nikmati berbagai voucher diskon khusus mahasiswa Tel-U. Hemat lebih banyak setiap hari.','color'=>'from-pink-400 to-rose-500'],
                ['icon'=>'fa-bell','emoji'=>'🔔','title'=>'Notifikasi Pintar','desc'=>'Dapat notifikasi real-time saat pesanan diproses, siap diambil, atau ada promosi spesial.','color'=>'from-green-400 to-teal-500'],
                ['icon'=>'fa-robot','emoji'=>'🤖','title'=>'AI Chatbot Assistant','desc'=>'Bingung mau makan apa? Tanya asisten AI Food-TYU! Ditenagai Gemini AI, siap kasih rekomendasi menu sesuai mood dan budget kamu.','color'=>'from-violet-400 to-purple-600'],
                ['icon'=>'fa-chart-bar','emoji'=>'📊','title'=>'Dashboard Kantin','desc'=>'Pemilik kantin punya dashboard lengkap untuk kelola menu, pesanan, laporan penjualan, dan keuangan.','color'=>'from-purple-400 to-violet-500'],
                ['icon'=>'fa-shield-alt','emoji'=>'🔒','title'=>'Transaksi Aman','desc'=>'Data dan transaksi terlindungi. Terintegrasi dengan sistem keamanan berlapis dan enkripsi penuh.','color'=>'from-red-400 to-rose-600'],
            ];
        @endphp

        <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($features as $i => $f)
            <div class="glass rounded-3xl p-7 card-hover group reveal {{ $loop->last ? 'md:col-span-2 lg:col-span-1 xl:col-span-1' : '' }}" style="transition-delay:{{ $i*0.1 }}s">
                <div class="feat-icon w-14 h-14 bg-gradient-to-br {{ $f['color'] }} rounded-2xl flex items-center justify-center text-2xl mb-5 shadow-lg">
                    {{ $f['emoji'] }}
                </div>
                <h3 class="text-white font-black text-xl mb-3">{{ $f['title'] }}</h3>
                <p class="text-white/60 text-sm font-medium leading-relaxed">{{ $f['desc'] }}</p>
                @if($f['emoji'] === '🤖')
                <div class="mt-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-green-400 text-xs font-bold">Powered by Gemini AI</span>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Wave bottom -->
    <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" class="absolute bottom-0 left-0 right-0 w-full" style="fill:var(--cream)">
        <path d="M0,40 C480,80 960,0 1440,40 L1440,80 L0,80 Z"/>
    </svg>
</section>


<!-- ================================================================
     HOW IT WORKS
================================================================ -->
<section class="py-24 px-6 bg-[var(--cream)]">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-16 reveal">
            <span class="inline-block bg-[var(--navy)]/8 text-[var(--navy)] font-black text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-4">🚀 Cara Pakai</span>
            <h2 class="text-4xl md:text-5xl font-black text-[var(--navy)] tracking-tight">
                Mudah dalam <span class="grad-text">3 Langkah</span>
            </h2>
            <p class="text-slate-500 mt-4 max-w-md mx-auto font-medium">Tidak perlu belajar lama. Langsung pesan dan nikmati makananmu!</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 reveal">
            @php
                $steps = [
                    ['no'=>'01','emoji'=>'📱','title'=>'Daftar & Login','desc'=>'Buat akun gratis dengan NIM (mahasiswa) atau email. Verifikasi langsung selesai.','color'=>'var(--navy)'],
                    ['no'=>'02','emoji'=>'🛒','title'=>'Pilih & Pesan','desc'=>'Pilih kantin, lihat menu lengkap, tambah ke keranjang, dan tentukan waktu ambil.','color'=>'var(--blue)'],
                    ['no'=>'03','emoji'=>'🎉','title'=>'Bayar & Ambil','desc'=>'Bayar dengan QRIS atau saldo TyU-Pay. Terima notifikasi saat pesanan siap!','color'=>'#a855f7'],
                ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="relative" style="transition-delay:{{ $i*0.15 }}s">
                @if($i < 2)
                <div class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-[var(--navy)]/20 to-transparent -translate-y-px z-0" style="width:calc(100% - 40px); left:calc(50% + 40px)"></div>
                @endif
                <div class="bg-white rounded-3xl p-8 text-center border border-[var(--navy)]/5 shadow-sm card-hover relative z-10">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-5 shadow-lg" style="background: linear-gradient(135deg, {{ $step['color'] }}, {{ $step['color'] }}88)">
                        {{ $step['emoji'] }}
                    </div>
                    <span class="text-xs font-black text-[var(--navy)]/30 uppercase tracking-widest">Langkah {{ $step['no'] }}</span>
                    <h3 class="text-[var(--navy)] font-black text-xl mt-2 mb-3">{{ $step['title'] }}</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ================================================================
     STUDENT TESTIMONIALS
================================================================ -->
<section class="py-24 px-6" id="testimonials" style="background: linear-gradient(180deg, var(--cream) 0%, #EEF4FF 100%)">
    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-14 reveal">
            <span class="inline-block bg-yellow-50 text-yellow-600 font-black text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-4 border border-yellow-100">⭐ Testimoni</span>
            <h2 class="text-4xl md:text-5xl font-black text-[var(--navy)] tracking-tight">
                Kata Mereka <span class="grad-text">Tentang Food-TYU</span>
            </h2>
            <p class="text-slate-500 mt-4 max-w-xl mx-auto font-medium">Ribuan mahasiswa Tel-U sudah merasakan manfaat pesan makanan tanpa antri.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 reveal">
            @php
                $testis = [
                    ['name'=>'Rina Aulia','nim'=>'Mahasiswa S1 Informatika','avatar'=>'RA','color'=>'#0B2D5C','rating'=>5,'text'=>'Food-TYU beneran ngubah hidup di kampus! Gak perlu ngantri panjang lagi waktu jam makan siang. Pesan dari kelas, tinggal ambil. 10/10 banget!'],
                    ['name'=>'Budi Santoso','nim'=>'Mahasiswa S1 Teknik Elektro','avatar'=>'BS','color'=>'#2D6A8F','rating'=>5,'text'=>'TyU-Pay nya keren, gak perlu bawa uang tunai lagi. Voucher mahasiswanya juga lumayan banget buat hemat. Recommended buat semua mahasiswa Tel-U!'],
                    ['name'=>'Sari Dewi','nim'=>'Mahasiswi S1 Manajemen','avatar'=>'SD','color'=>'#a855f7','rating'=>5,'text'=>'UI-nya bersih dan gampang banget dipake. Dalam 2 menit pesanan udah masuk kantin. Notifikasinya juga tepat waktu. Mantap Food-TYU! 🙌'],
                    ['name'=>'Dimas Putra','nim'=>'Mahasiswa S1 Bisnis Digital','avatar'=>'DP','color'=>'#F472B6','rating'=>5,'text'=>'Pemilik kantin di kampus bilang bisnisnya jadi lebih lancar karena pesanan masuk lewat Food-TYU. Kita sebagai pembeli juga puas!'],
                    ['name'=>'Nadia Fitriani','nim'=>'Mahasiswi S1 Sistem Informasi','avatar'=>'NF','color'=>'#059669','rating'=>5,'text'=>'Fitur notifikasi real-timenya top! Tahu persis kapan makanan siap diambil. Gak ada lagi momen nungguin sambil kelaparan di lorong kantin haha.'],
                    ['name'=>'Rizky Firmansyah','nim'=>'Mahasiswa S1 Ilmu Komunikasi','avatar'=>'RF','color'=>'#f59e0b','rating'=>5,'text'=>'Awalnya skeptis, tapi setelah nyoba langsung ketagihan. Pembayarannya aman, pesanan cepat diproses, dan menunya banyak banget pilihannya!'],
                ];
            @endphp
            @foreach($testis as $i => $t)
            <div class="testi-card" style="transition-delay:{{ $i*0.08 }}s">
                <!-- Stars -->
                <div class="flex gap-1 mb-4">
                    @for($s=0; $s<$t['rating']; $s++)
                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                    @endfor
                </div>
                <!-- Quote -->
                <p class="text-slate-600 text-sm font-medium leading-relaxed mb-6">"{{ $t['text'] }}"</p>
                <!-- User -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black text-sm" style="background: {{ $t['color'] }}">
                        {{ $t['avatar'] }}
                    </div>
                    <div>
                        <p class="font-black text-[var(--navy)] text-sm">{{ $t['name'] }}</p>
                        <p class="text-slate-400 text-xs font-medium">{{ $t['nim'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Rating summary -->
        <div class="mt-14 bg-[var(--navy)] rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center gap-8 reveal">
            <div class="text-center md:text-left">
                <p class="text-8xl font-black text-white">4.9</p>
                <div class="flex justify-center md:justify-start gap-1 my-2">
                    @for($s=0; $s<5; $s++)
                    <i class="fas fa-star text-yellow-400 text-xl"></i>
                    @endfor
                </div>
                <p class="text-white/60 font-medium">Rating rata-rata platform</p>
            </div>
            <div class="h-px md:h-20 md:w-px bg-white/15 w-full md:w-auto"></div>
            <div class="flex-1 space-y-3">
                @foreach([['pct'=>85,'stars'=>5],['pct'=>10,'stars'=>4],['pct'=>4,'stars'=>3],['pct'=>1,'stars'=>2]] as $r)
                <div class="flex items-center gap-3">
                    <span class="text-white/60 text-xs font-bold w-4">{{ $r['stars'] }}</span>
                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                    <div class="flex-1 bg-white/10 rounded-full h-2">
                        <div class="bg-yellow-400 h-2 rounded-full transition-all" style="width:{{ $r['pct'] }}%"></div>
                    </div>
                    <span class="text-white/60 text-xs font-bold w-8">{{ $r['pct'] }}%</span>
                </div>
                @endforeach
            </div>
            <div class="text-center">
                <p class="text-5xl font-black text-[var(--pink)]">500+</p>
                <p class="text-white/60 font-medium mt-1">Pengguna aktif</p>
            </div>
        </div>
    </div>
</section>


<!-- ================================================================
     CAMPUS LOCATION
================================================================ -->
<section class="py-24 px-6 bg-[var(--cream)]" id="location">
    <div class="max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left: Info -->
            <div class="reveal">
                <span class="inline-block bg-[var(--navy)]/8 text-[var(--navy)] font-black text-xs uppercase tracking-widest px-4 py-2 rounded-full mb-6">📍 Lokasi</span>
                <h2 class="text-4xl md:text-5xl font-black text-[var(--navy)] tracking-tight mb-6">
                    Kampus <span class="grad-text">Tel-U Purwokerto</span>
                </h2>
                <p class="text-slate-500 text-lg font-medium leading-relaxed mb-8">
                    Food-TYU hadir khusus untuk civitas akademika Telkom University Purwokerto. Nikmati kemudahan pesan makanan dari kantin kampus di mana saja!
                </p>

                <div class="space-y-4 mb-8">
                    @php
                        $infos = [
                            ['icon'=>'fa-map-marker-alt','label'=>'Alamat','val'=>'Jl. D.I. Panjaitan No.128, Purwokerto, Banyumas, Jawa Tengah 53147','color'=>'var(--navy)'],
                            ['icon'=>'fa-clock','label'=>'Jam Operasional','val'=>'Senin – Jumat: 07.00 – 17.00 WIB','color'=>'var(--blue)'],
                            ['icon'=>'fa-store','label'=>'Jumlah Kantin','val'=>'3 Kantin aktif tersebar di area kampus','color'=>'#a855f7'],
                        ];
                    @endphp
                    @foreach($infos as $info)
                    <div class="flex gap-4 p-4 bg-white rounded-2xl border border-[var(--navy)]/5 shadow-sm">
                        <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center" style="background: {{ $info['color'] }}15">
                            <i class="fas {{ $info['icon'] }} text-sm" style="color: {{ $info['color'] }}"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">{{ $info['label'] }}</p>
                            <p class="font-semibold text-[var(--navy)] text-sm">{{ $info['val'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <a href="https://maps.google.com/?q=Telkom+University+Purwokerto" target="_blank"
                   class="inline-flex items-center gap-3 bg-[var(--navy)] text-white font-black px-7 py-4 rounded-2xl hover:bg-[var(--blue)] transition-all hover:scale-105 shadow-lg">
                    <i class="fas fa-map-marker-alt"></i>
                    Buka di Google Maps
                    <i class="fas fa-external-link-alt text-sm opacity-70"></i>
                </a>
            </div>

            <!-- Right: Map embed -->
            <div class="reveal">
                <div class="rounded-3xl overflow-hidden shadow-2xl border-4 border-white relative">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.9!2d109.2435!3d-7.4341!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655e5c2b0e57b1%3A0x4b29f5c45ea9aed5!2sTelkom%20University%20Purwokerto!5e0!3m2!1sid!2sid!4v1686000000000!5m2!1sid!2sid"
                        width="100%" height="400" style="border:0; display:block;"
                        allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                    <!-- Overlay badge -->
                    <div class="absolute top-4 left-4 bg-white rounded-xl px-4 py-3 shadow-lg flex items-center gap-3">
                        <img src="{{ asset('images/logo-foodtyu.png') }}" class="w-8 h-8 object-contain">
                        <div>
                            <p class="font-black text-[var(--navy)] text-sm">Food-TYU</p>
                            <p class="text-slate-400 text-xs font-medium">Tel-U Purwokerto</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ================================================================
     PAYMENT METHODS STRIP
================================================================ -->
<section class="py-12 px-6" style="background: linear-gradient(135deg, #0B2D5C, #1a3a6a)">
    <div class="max-w-5xl mx-auto text-center reveal">
        <p class="text-white/50 text-xs font-black uppercase tracking-widest mb-6">💳 Metode Pembayaran</p>
        <div class="flex flex-wrap justify-center items-center gap-4">
            @php
                $payments = [
                    ['label'=>'QRIS','icon'=>'fa-qrcode','desc'=>'Scan & Pay'],
                    ['label'=>'TyU-Pay','icon'=>'fa-wallet','desc'=>'Saldo Digital'],
                    ['label'=>'Midtrans','icon'=>'fa-credit-card','desc'=>'Secure Gateway'],
                    ['label'=>'Transfer Bank','icon'=>'fa-university','desc'=>'BCA, BNI, BRI'],
                    ['label'=>'GoPay','icon'=>'fa-mobile-alt','desc'=>'e-Wallet'],
                    ['label'=>'OVO','icon'=>'fa-dollar-sign','desc'=>'e-Wallet'],
                ];
            @endphp
            @foreach($payments as $p)
            <div class="glass rounded-2xl px-5 py-3 flex items-center gap-2.5">
                <i class="fas {{ $p['icon'] }} text-[var(--soft-blue)]"></i>
                <div class="text-left">
                    <p class="text-white font-black text-sm">{{ $p['label'] }}</p>
                    <p class="text-white/40 text-[10px] font-bold">{{ $p['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ================================================================
     CTA BANNER
================================================================ -->
<section class="py-24 px-6 bg-[var(--cream)]">
    <div class="max-w-4xl mx-auto">
        <div class="relative overflow-hidden rounded-[40px] p-12 md:p-16 text-center" style="background: linear-gradient(135deg, #0B2D5C 0%, #2D6A8F 50%, #7AB8FF 100%)">
            <!-- Blobs -->
            <div class="hero-blob absolute w-60 h-60 bg-[var(--pink)] -top-10 -right-10 opacity-30"></div>
            <div class="hero-blob absolute w-48 h-48 bg-white -bottom-10 -left-10 opacity-10" style="animation-delay:-3s"></div>
            <div class="absolute inset-0 dots-bg opacity-20"></div>

            <div class="relative z-10">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-3xl flex items-center justify-center border border-white/30 float-slow shadow-xl">
                        <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Food-TYU" class="w-12 h-12 object-contain">
                    </div>
                </div>
                <h2 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4">
                    Siap <span class="text-[var(--pink)]">Pesan</span> Sekarang?
                </h2>
                <p class="text-white/70 text-lg font-medium mb-10 max-w-2xl mx-auto">
                    Bergabung bersama mahasiswa Tel-U Purwokerto yang sudah menikmati kemudahan makan siang tanpa antri. Daftar gratis, langsung bisa pesan!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/signup?type=mahasiswa" id="ctaMahasiswa"
                       class="flex items-center justify-center gap-3 bg-white text-[var(--navy)] font-black px-8 py-4 rounded-2xl shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
                        <i class="fas fa-graduation-cap text-[var(--blue)]"></i>
                        Daftar sebagai Mahasiswa
                    </a>
                    <a href="/signup?type=umum" id="ctaUmum"
                       class="flex items-center justify-center gap-3 glass text-white font-black px-8 py-4 rounded-2xl hover:bg-white/20 transition-all">
                        <i class="fas fa-user"></i>
                        Daftar Umum
                    </a>
                </div>
                <p class="text-white/40 text-xs font-bold mt-6 uppercase tracking-widest">Gratis · Tanpa Biaya Bulanan · Langsung Bisa Pakai</p>
            </div>
        </div>
    </div>
</section>


<!-- ================================================================
     FOOTER
================================================================ -->
<footer style="background: #060f1e;">
    <!-- Top footer -->
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid md:grid-cols-4 gap-10 mb-12">
            <!-- Brand -->
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-11 h-11 bg-white/10 rounded-xl flex items-center justify-center">
                        <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Food-TYU" class="w-7 h-7 object-contain">
                    </div>
                    <span class="text-white font-black text-2xl italic">Food-TYU<span class="text-[var(--pink)]">.</span></span>
                </div>
                <p class="text-white/50 text-sm font-medium leading-relaxed mb-6 max-w-sm">
                    Platform digital kantin Telkom University Purwokerto. Pesan makanan tanpa antri, bayar digital, dan nikmati kampus lebih produktif.
                </p>
                <!-- Social -->
                <div class="flex gap-3">
                    @foreach([['icon'=>'fa-instagram','href'=>'#'],['icon'=>'fa-twitter','href'=>'#'],['icon'=>'fa-tiktok','href'=>'#'],['icon'=>'fa-envelope','href'=>'mailto:foodtyu@telkomuniversity.ac.id']] as $social)
                    <a href="{{ $social['href'] }}" class="w-10 h-10 bg-white/10 hover:bg-[var(--pink)] rounded-xl flex items-center justify-center text-white/60 hover:text-white transition-all">
                        <i class="fab {{ $social['icon'] }} text-sm"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Links -->
            <div>
                <p class="text-white font-black text-sm uppercase tracking-widest mb-4">Platform</p>
                <div class="space-y-3">
                    @foreach(['Beranda'=>'#hero','Fitur'=>'#features','Kantin'=>'#canteens','Menu'=>'#menus','Testimoni'=>'#testimonials'] as $label => $href)
                    <a href="{{ $href }}" class="block text-white/50 hover:text-[var(--pink)] text-sm font-medium transition">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            <div>
                <p class="text-white font-black text-sm uppercase tracking-widest mb-4">Akun</p>
                <div class="space-y-3">
                    <a href="/login" class="block text-white/50 hover:text-[var(--pink)] text-sm font-medium transition">Masuk</a>
                    <a href="/signup?type=mahasiswa" class="block text-white/50 hover:text-[var(--pink)] text-sm font-medium transition">Daftar Mahasiswa</a>
                    <a href="/signup?type=umum" class="block text-white/50 hover:text-[var(--pink)] text-sm font-medium transition">Daftar Umum</a>
                </div>
                <p class="text-white font-black text-sm uppercase tracking-widest mt-6 mb-4">Kontak</p>
                <div class="space-y-2">
                    <p class="text-white/50 text-sm font-medium"><i class="fas fa-envelope mr-2 text-[var(--pink)]"></i>foodtyu@telkomuniversity.ac.id</p>
                    <p class="text-white/50 text-sm font-medium"><i class="fas fa-map-marker-alt mr-2 text-[var(--pink)]"></i>Purwokerto, Jawa Tengah</p>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-white/30 text-xs font-medium">© {{ date('Y') }} Food-TYU. Platform Kantin Digital Telkom University Purwokerto.</p>
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                <p class="text-white/30 text-xs font-medium">Sistem berjalan normal</p>
            </div>
            <p class="text-white/30 text-xs font-medium">Made with ❤️ for Tel-U Purwokerto</p>
        </div>
    </div>
</footer>


<!-- ================================================================
     JAVASCRIPT
================================================================ -->
<script>
    // ===== NAVBAR SCROLL =====
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 60);
    });

    // ===== MOBILE MENU =====
    const mobileMenu = document.getElementById('mobileMenu');
    const hamburgerIcon = document.getElementById('hamburgerIcon');
    let menuOpen = false;

    function toggleMobileMenu() {
        menuOpen = !menuOpen;
        mobileMenu.classList.toggle('hidden', !menuOpen);
        // Trigger reflow for animation
        if (menuOpen) {
            mobileMenu.classList.remove('hidden');
            requestAnimationFrame(() => mobileMenu.classList.add('open'));
        } else {
            mobileMenu.classList.remove('open');
            setTimeout(() => mobileMenu.classList.add('hidden'), 350);
        }
        hamburgerIcon.className = menuOpen ? 'fas fa-times text-xl' : 'fas fa-bars text-xl';
    }
    function closeMobileMenu() {
        menuOpen = false;
        mobileMenu.classList.remove('open');
        setTimeout(() => mobileMenu.classList.add('hidden'), 350);
        hamburgerIcon.className = 'fas fa-bars text-xl';
    }

    // ===== SCROLL REVEAL =====
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    // ===== LIVE TICKER =====
    const tickers = [
        '🍜 Seseorang baru saja memesan <strong>Mie Ayam Spesial</strong> dari Kantin A',
        '⚡ Pesanan <strong>Nasi Goreng + Es Teh</strong> selesai dalam 9 menit!',
        '🎟️ <strong>Voucher DISC20</strong> baru saja digunakan oleh mahasiswa Informatika',
        '🏪 Kantin B baru saja membuka menu <strong>Soto Betawi Spesial</strong>',
        '💳 Top-up TyU-Pay berhasil — saldo tersedia untuk pesan segera',
        '🎉 Pesanan #2847 sudah siap diambil di <strong>Kantin C</strong>',
    ];
    let tickerIdx = 0;
    const tickerEl = document.getElementById('liveTicker');

    setInterval(() => {
        tickerIdx = (tickerIdx + 1) % tickers.length;
        tickerEl.style.opacity = '0';
        tickerEl.style.transform = 'translateY(-8px)';
        setTimeout(() => {
            tickerEl.innerHTML = tickers[tickerIdx];
            tickerEl.style.transition = 'all .4s ease';
            tickerEl.style.opacity = '1';
            tickerEl.style.transform = 'translateY(0)';
        }, 300);
    }, 4000);

    // ===== SMOOTH ACTIVE SECTION HIGHLIGHT =====
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                navLinks.forEach(link => {
                    link.style.color = link.getAttribute('href') === '#' + entry.target.id
                        ? '#fff' : 'rgba(255,255,255,.65)';
                });
            }
        });
    }, { threshold: 0.4 });

    sections.forEach(s => sectionObserver.observe(s));

    // ===== PARALLAX BLOBS (subtle) =====
    document.addEventListener('mousemove', (e) => {
        const x = (e.clientX / window.innerWidth - .5) * 20;
        const y = (e.clientY / window.innerHeight - .5) * 20;
        document.querySelectorAll('.hero-blob').forEach((b, i) => {
            const factor = (i % 2 === 0 ? 1 : -1) * (i + 1) * 0.4;
            b.style.transform = `translate(${x * factor}px, ${y * factor}px)`;
        });
    });
</script>
</body>
</html>