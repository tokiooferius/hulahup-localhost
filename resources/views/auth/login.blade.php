<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - Food-TYU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .left-panel {
            background: linear-gradient(135deg, #122C4F 0%, #2D6A8F 55%, #F472B6 100%);
        }

        .blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: blobMorph 8s ease-in-out infinite;
        }
        .blob2 { animation-delay: -4s; border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; }
        @keyframes blobMorph {
            0%,100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            33%      { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; }
            66%      { border-radius: 50% 50% 30% 70% / 40% 60% 40% 60%; }
        }

        .dots-bg {
            background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px);
            background-size: 26px 26px;
        }

        /* 3D-floating Logo */
        .float-logo {
            animation: floatLogo 6s ease-in-out infinite;
        }
        @keyframes floatLogo {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }

        /* Glow Text Effect */
        .glow-text {
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.25), 0 0 30px rgba(244, 114, 182, 0.35);
            animation: textGlow 3.5s ease-in-out infinite alternate;
        }
        @keyframes textGlow {
            from { text-shadow: 0 0 10px rgba(255, 255, 255, 0.2), 0 0 20px rgba(244, 114, 182, 0.3); }
            to { text-shadow: 0 0 20px rgba(255, 255, 255, 0.4), 0 0 40px rgba(244, 114, 182, 0.6); }
        }

        /* Pulse ring */
        @keyframes pulseRing {
            0%   { transform: scale(1); opacity:.5; }
            100% { transform: scale(1.6); opacity:0; }
        }
        .pulse { animation: pulseRing 2.2s ease-out infinite; }

        /* Input */
        .input-field {
            border: 2px solid #e5e7eb;
            transition: all .25s ease;
        }
        .input-field:focus {
            border-color: #2d6a8f;
            box-shadow: 0 0 0 4px rgba(45,106,143,.12);
            outline: none;
            background: #fff;
        }

        /* Button ripple */
        .btn-primary { position: relative; overflow: hidden; }
        .btn-primary::after {
            content:''; position:absolute; inset:0;
            background: radial-gradient(circle, rgba(255,255,255,.25) 0%, transparent 70%);
            opacity:0; transition: opacity .3s;
        }
        .btn-primary:hover::after { opacity:1; }

        /* Marquee */
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        .marquee-track { animation: marquee 18s linear infinite; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex">

    <!-- LEFT — Visual Panel -->
    <div class="hidden lg:flex w-1/2 left-panel flex-col relative overflow-hidden min-h-screen">
        <div class="dots-bg absolute inset-0"></div>
        <div class="blob blob2 absolute w-80 h-80 bg-white/5 -top-16 -left-16"></div>
        <div class="blob absolute w-72 h-72 bg-[#FBCFE8]/15 bottom-12 -right-12" style="animation-delay:-3s"></div>

        <!-- Top nav -->
        <div class="relative z-10 px-10 pt-8">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black italic text-white">Food-TYU.</span>
                <span class="text-[10px] bg-white/15 text-white/80 px-2 py-0.5 rounded-full font-bold uppercase tracking-widest border border-white/20">TEL-U</span>
            </div>
        </div>

        <!-- Center content -->
        <div class="relative z-10 flex-1 flex flex-col items-center justify-center px-10 py-8">

            <!-- Logo Pulse & Image Container -->
            <div class="relative mb-6 float-logo">
                <div class="pulse absolute inset-0 rounded-[36px] border-2 border-pink-400/30"></div>
                <div class="bg-white/15 border border-white/20 rounded-[36px] p-7 backdrop-blur-md text-center shadow-2xl flex items-center justify-center">
                    <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Food-TYU Logo" class="w-32 h-32 object-contain drop-shadow-[0_8px_16px_rgba(244,114,182,0.25)]">
                </div>
            </div>

            <!-- Animated Typography Banner -->
            <div class="text-center mt-6 mb-8 max-w-sm">
                <h2 class="text-2xl font-black tracking-tight text-white mb-3 leading-tight">
                    <span class="text-pink-200 block text-xs font-bold uppercase tracking-widest mb-1.5">Stop Antri - antri,</span>
                    <span class="glow-text bg-gradient-to-r from-white via-pink-100 to-white bg-clip-text text-transparent text-3xl font-black">Gunakan Food-TYU Sekarang!</span>
                </h2>
                <p class="text-white/60 text-xs font-medium leading-relaxed">
                    Pesan makanan dari kantin kampus jadi lebih mudah, cepat, dan higienis tanpa perlu berdiri di barisan antrean.
                </p>
            </div>

            <!-- Stats row -->
            <div class="flex gap-4 w-full max-w-xs">
                <div class="flex-1 bg-white/10 border border-white/15 rounded-2xl p-3 text-center">
                    <p class="text-white font-black text-xl">3</p>
                    <p class="text-white/50 text-[10px] font-semibold uppercase tracking-wider">Kantin</p>
                </div>
                <div class="flex-1 bg-white/10 border border-white/15 rounded-2xl p-3 text-center">
                    <p class="text-white font-black text-xl">50+</p>
                    <p class="text-white/50 text-[10px] font-semibold uppercase tracking-wider">Menu</p>
                </div>
                <div class="flex-1 bg-white/10 border border-white/15 rounded-2xl p-3 text-center">
                    <p class="text-white font-black text-xl">⚡</p>
                    <p class="text-white/50 text-[10px] font-semibold uppercase tracking-wider">Realtime</p>
                </div>
            </div>
        </div>

        <!-- Bottom marquee -->
        <div class="relative z-10 pb-6 overflow-hidden">
            <div class="flex whitespace-nowrap marquee-track">
                @foreach(range(1,2) as $_)
                    <span class="text-white/20 text-xs font-bold uppercase tracking-widest px-4">🍜 Pesan Sekarang</span>
                    <span class="text-white/20 text-xs font-bold uppercase tracking-widest px-4">⚡ Tanpa Antri</span>
                    <span class="text-white/20 text-xs font-bold uppercase tracking-widest px-4">🔒 Bayar Aman</span>
                    <span class="text-white/20 text-xs font-bold uppercase tracking-widest px-4">🎟️ Pakai Voucher</span>
                    <span class="text-white/20 text-xs font-bold uppercase tracking-widest px-4">🏪 3 Kantin Tersedia</span>
                    <span class="text-white/20 text-xs font-bold uppercase tracking-widest px-4">💳 QRIS & TyU-Pay</span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- RIGHT — Form Panel -->
    <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center min-h-screen overflow-y-auto px-10 py-12">

        <!-- Mobile logo -->
        <div class="lg:hidden mb-8 text-center flex flex-col items-center gap-2">
            <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Food-TYU Logo" class="w-16 h-16 object-contain">
            <span class="text-3xl font-black italic text-[#122C4F]">Food-TYU.</span>
        </div>

        <div class="max-w-sm mx-auto w-full">

            <div class="mb-8">
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Selamat datang! 👋</h2>
                <p class="text-slate-400 mt-1.5 text-sm font-medium">Masuk ke akun Food-TYU kamu.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-5 flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <p class="text-sm font-bold">{{ session('error') }}</p>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-4" id="loginForm">
                @csrf

                <!-- Username -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Username</label>
                    <div class="relative">
                        <i class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                        <input type="text" name="username" id="usernameInput"
                            placeholder="Masukkan username kamu"
                            class="input-field w-full pl-11 pr-4 py-3.5 rounded-2xl text-sm bg-slate-50"
                            value="{{ old('username') }}" required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Password</label>
                        <a href="#" class="text-xs font-bold text-[#2d6a8f] hover:text-[#F472B6] hover:underline">Lupa Password?</a>
                    </div>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                        <input type="password" name="password" id="passInput"
                            placeholder="••••••••"
                            class="input-field w-full pl-11 pr-12 py-3.5 rounded-2xl text-sm bg-slate-50" required>
                        <button type="button" onclick="togglePass()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-[#F472B6] transition">
                            <i class="fas fa-eye text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember me -->
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <div class="relative">
                        <input type="checkbox" name="remember" class="sr-only peer" id="rememberCb">
                        <div class="w-5 h-5 rounded-md border-2 border-slate-200 peer-checked:bg-[#F472B6] peer-checked:border-[#F472B6] transition flex items-center justify-center" id="rememberBox">
                            <i class="fas fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100" id="rememberCheck"></i>
                        </div>
                    </div>
                    <span class="text-sm text-slate-500 font-medium">Ingat saya selama 30 hari</span>
                </label>

                <!-- Submit -->
                <button type="submit" id="submitBtn"
                    class="btn-primary w-full bg-gradient-to-r from-[#122C4F] via-[#2d6a8f] to-[#F472B6] text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-900/20 hover:shadow-xl hover:scale-[1.01] active:scale-95 transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk Sekarang
                </button>

                <!-- Divider -->
                <div class="flex items-center gap-3 my-1">
                    <div class="flex-1 h-px bg-slate-100"></div>
                    <span class="text-xs text-slate-300 font-bold uppercase tracking-wider">atau</span>
                    <div class="flex-1 h-px bg-slate-100"></div>
                </div>

                <!-- SSO Button -->
                <button type="button"
                    onclick="alert('🚧 Login SSO Tel-U sedang dalam pengembangan.\nSilakan gunakan login biasa untuk saat ini.')"
                    class="w-full bg-slate-50 border-2 border-slate-100 hover:border-[#F472B6] hover:bg-pink-50/10 py-3.5 rounded-2xl font-bold flex items-center justify-center gap-3 transition-all text-slate-600 text-sm group">
                    <div class="w-7 h-7 bg-white rounded-lg shadow-sm flex items-center justify-center">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-4 h-4">
                    </div>
                    Login dengan SSO Tel-U
                    <span class="text-[10px] bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full font-black border border-yellow-200">SOON</span>
                </button>
            </form>

            <!-- Daftar section -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <p class="text-center text-sm text-slate-400 font-medium mb-4">Belum punya akun?</p>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Daftar Umum -->
                    <a href="/signup?type=umum"
                        class="group flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-slate-100 hover:border-[#F472B6] hover:bg-pink-50/10 transition-all text-center cursor-pointer">
                        <div class="w-10 h-10 bg-slate-100 group-hover:bg-[#F472B6] rounded-xl flex items-center justify-center transition-all">
                            <i class="fas fa-user text-slate-500 group-hover:text-white text-sm transition-all"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-700 text-sm group-hover:text-[#122C4F]">Daftar Umum</p>
                            <p class="text-[10px] text-slate-400">Untuk semua orang</p>
                        </div>
                    </a>
                    <!-- Daftar Mahasiswa -->
                    <a href="/signup?type=mahasiswa"
                        class="group flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-slate-100 hover:border-[#F472B6] hover:bg-pink-50/10 transition-all text-center cursor-pointer">
                        <div class="w-10 h-10 bg-slate-100 group-hover:bg-[#F472B6] rounded-xl flex items-center justify-center transition-all">
                            <i class="fas fa-graduation-cap text-slate-500 group-hover:text-white text-sm transition-all"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-700 text-sm group-hover:text-[#122C4F]">Mahasiswa Tel-U</p>
                            <p class="text-[10px] text-slate-400">Pakai NIM kamu</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

</body>
<script>
    function togglePass() {
        const input = document.getElementById('passInput');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash text-sm';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye text-sm';
        }
    }

    // Checkbox visual
    document.getElementById('rememberCb').addEventListener('change', function() {
        document.getElementById('rememberCheck').style.opacity = this.checked ? '1' : '0';
    });

    // Submit loading state
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
        btn.disabled = true;
    });
</script>
</html>
