<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Food-TYU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .right-panel {
            background: linear-gradient(135deg, #0B2D5C 0%, #2D6A8F 55%, #F472B6 100%);
            position: sticky;
            top: 0;
            height: 100vh;
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

        /* Input focus glow */
        .input-field {
            transition: all 0.25s ease;
            border: 2px solid #e5e7eb;
        }
        .input-field:focus {
            border-color: #2D6A8F;
            box-shadow: 0 0 0 4px rgba(45,106,143,0.12);
            outline: none;
        }

        /* Step indicator */
        .step-dot {
            transition: all 0.3s ease;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        .step-dot.active {
            width: 28px;
            border-radius: 6px;
        }

        /* Progress bar */
        #progressBar {
            transition: width 0.4s cubic-bezier(.4,0,.2,1);
        }

        /* Blob */
        .blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: blobMorph 7s ease-in-out infinite;
        }
        @keyframes blobMorph {
            0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            33%  { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; }
            66%  { border-radius: 50% 50% 30% 70% / 40% 60% 40% 60%; }
        }

        /* Form step transition */
        .form-step { display: none; }
        .form-step.active { display: block; animation: fadeSlide 0.35s ease; }
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateX(18px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Password strength */
        .strength-bar div {
            transition: width 0.4s ease, background 0.4s ease;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-stretch overflow-x-hidden">

    <!-- LEFT — Form Panel -->
    <div class="w-full lg:w-1/2 bg-white flex flex-col min-h-screen overflow-y-auto">
        <!-- Top brand bar -->
        <div class="px-6 md:px-10 pt-8 pb-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 group">
                <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Food-TYU" class="w-7 h-7 object-contain">
                <span class="text-2xl font-black italic text-[#0B2D5C] group-hover:text-[#2d6a8f] transition">Food-TYU<span class="text-[#F472B6]">.</span></span>
                <span class="text-[10px] bg-blue-100 text-[#0B2D5C] px-2 py-0.5 rounded-full font-bold uppercase tracking-widest">TEL-U</span>
            </a>
            <a href="/login" class="text-sm text-slate-500 hover:text-[#2d6a8f] font-semibold transition">
                Sudah punya akun? <span class="text-[#2d6a8f] font-bold underline underline-offset-2">Masuk</span>
            </a>
        </div>

        <!-- Progress bar container -->
        <div class="px-6 md:px-10 mt-4">
            <div class="h-1 bg-slate-100 rounded-full overflow-hidden">
                <div id="progressBar" class="h-full bg-gradient-to-r from-[#2d6a8f] to-[#3a7fa8] rounded-full" style="width: 33%"></div>
            </div>
            <div class="flex justify-between mt-2 mb-6">
                <span class="text-xs text-slate-400 font-medium" id="stepLabel">Langkah 1 dari 3</span>
                <div class="flex gap-1.5 items-center">
                    <div class="step-dot w-2 h-2 rounded-full bg-[#2d6a8f] active" id="dot1"></div>
                    <div class="step-dot w-2 h-2 rounded-full bg-slate-200" id="dot2"></div>
                    <div class="step-dot w-2 h-2 rounded-full bg-slate-200" id="dot3"></div>
                </div>
            </div>
        </div>

        <!-- Form Wrapper -->
        <div class="flex-1 px-6 md:px-10 pb-10">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-5 text-sm">
                    <p class="font-bold mb-1">⚠️ Ada yang perlu diperbaiki:</p>
                    @foreach ($errors->all() as $error)
                        <p class="text-xs">• {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="/signup" method="POST" id="signupForm">
                @csrf

                <!-- ===== STEP 1: Identitas ===== -->
                <div class="form-step active" id="step1">
                    <!-- Type selector badges -->
                    <div class="flex gap-2 mb-5">
                        <button type="button" onclick="setType('umum')" id="btnUmum"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border-2 transition-all border-[#2d6a8f] bg-[#2d6a8f] text-white">
                            <i class="fas fa-user text-xs"></i> Umum
                        </button>
                        <button type="button" onclick="setType('mahasiswa')" id="btnMhs"
                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border-2 transition-all border-slate-200 text-slate-500 hover:border-[#2d6a8f]">
                            <i class="fas fa-graduation-cap text-xs"></i> Mahasiswa Tel-U
                        </button>
                    </div>

                    <h2 class="text-3xl font-black text-slate-800 mb-1" id="step1Title">Halo, siapa kamu? 👋</h2>
                    <p class="text-slate-400 text-sm mb-6" id="step1Sub">Isi data identitas untuk mulai memesan di kantin Tel-U.</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="text" name="name" placeholder="Nama sesuai identitas"
                                    class="input-field w-full pl-11 pr-4 py-3.5 rounded-2xl text-sm bg-slate-50"
                                    value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Username</label>
                            <div class="relative">
                                <i class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="text" name="username" placeholder="username unik kamu"
                                    class="input-field w-full pl-11 pr-4 py-3.5 rounded-2xl text-sm bg-slate-50"
                                    value="{{ old('username') }}" required>
                            </div>
                        </div>
                        <div id="nimField" style="display: none;">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5" id="nimLabel">NIM Mahasiswa</label>
                            <div class="relative">
                                <i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="text" name="nim" id="nimInput" placeholder="Contoh: 103112430001"
                                    class="input-field w-full pl-11 pr-4 py-3.5 rounded-2xl text-sm bg-slate-50"
                                    value="{{ old('nim') }}">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1 pl-1" id="nimHint">Wajib 12 digit angka untuk mahasiswa Tel-U</p>
                        </div>
                    </div>

                    <button type="button" onclick="nextStep(2)"
                        class="mt-8 w-full bg-[#0B2D5C] hover:bg-[#2d6a8f] text-white font-bold py-4 rounded-2xl transition-all active:scale-95 flex items-center justify-center gap-2">
                        Lanjut <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

                <!-- ===== STEP 2: Kontak & Detail ===== -->
                <div class="form-step" id="step2">
                    <h2 class="text-3xl font-black text-slate-800 mb-1">Info Kontak & Lokasi 📱</h2>
                    <p class="text-slate-400 text-sm mb-6 font-medium">Lengkapi kontak dan lokasi untuk mempermudah transaksi kantin.</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="email" name="email" placeholder="contoh@gmail.com"
                                    class="input-field w-full pl-11 pr-4 py-3.5 rounded-2xl text-sm bg-slate-50"
                                    value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nomor WhatsApp</label>
                            <div class="relative">
                                <i class="fab fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-green-400 text-sm"></i>
                                <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                                    class="input-field w-full pl-11 pr-4 py-3.5 rounded-2xl text-sm bg-slate-50"
                                    value="{{ old('phone') }}" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Alamat / Lokasi Pengantaran</label>
                            <div class="relative">
                                <i class="fas fa-map-marker-alt absolute left-4 top-4 text-slate-300 text-sm"></i>
                                <textarea name="address" rows="3" placeholder="Contoh: Gedung Rektorat Lantai 2, atau Kostan Jl. Telekomunikasi"
                                    class="input-field w-full pl-11 pr-4 py-3 rounded-2xl text-sm bg-slate-50" required>{{ old('address') }}</textarea>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 pl-1">Minimal 10 karakter detail alamat yang valid.</p>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" onclick="nextStep(1)"
                            class="px-6 py-4 rounded-2xl border-2 border-slate-200 text-slate-500 font-bold hover:border-slate-300 transition">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="button" onclick="nextStep(3)"
                            class="flex-1 bg-[#0B2D5C] hover:bg-[#2d6a8f] text-white font-bold py-4 rounded-2xl transition-all shadow-md uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                            Lanjut <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ===== STEP 3: Keamanan ===== -->
                <div class="form-step" id="step3">
                    <h2 class="text-3xl font-black text-slate-800 mb-1">Keamanan Akun 🔒</h2>
                    <p class="text-slate-400 text-sm mb-6 font-medium">Buat password yang kuat untuk menjaga akun Anda.</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Password</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
                                    oninput="checkStrength(this.value)"
                                    class="input-field w-full pl-11 pr-12 py-3.5 rounded-2xl text-sm bg-slate-50" required>
                                <button type="button" onclick="togglePass('password', this)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            <!-- Password Strength Indicator -->
                            <div class="mt-2.5">
                                <div class="flex gap-1 mb-1 strength-bar">
                                    <div id="s1" class="h-1 flex-1 rounded-full bg-slate-100"></div>
                                    <div id="s2" class="h-1 flex-1 rounded-full bg-slate-100"></div>
                                    <div id="s3" class="h-1 flex-1 rounded-full bg-slate-100"></div>
                                    <div id="s4" class="h-1 flex-1 rounded-full bg-slate-100"></div>
                                </div>
                                <span id="strengthLabel" class="text-[11px] font-bold text-slate-400">Masukkan password</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1.5">Konfirmasi Password</label>
                            <div class="relative">
                                <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password"
                                    class="input-field w-full pl-11 pr-12 py-3.5 rounded-2xl text-sm bg-slate-50" required>
                                <button type="button" onclick="togglePass('password_confirmation', this)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms checkbox -->
                    <label class="flex items-start gap-3 mt-5 cursor-pointer group">
                        <div class="relative mt-0.5">
                            <input type="checkbox" id="terms" class="sr-only peer" required>
                            <div class="w-5 h-5 rounded-md border-2 border-slate-200 peer-checked:bg-[#F472B6] peer-checked:border-[#F472B6] transition flex items-center justify-center">
                                <i class="fas fa-check text-white text-[10px] hidden peer-checked:block" id="checkIcon"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Saya setuju dengan <a href="#" class="text-[#2d6a8f] font-bold hover:text-[#F472B6] hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-[#2d6a8f] font-bold hover:text-[#F472B6] hover:underline">Kebijakan Privasi</a> Food-TYU.
                        </p>
                    </label>

                    <div class="flex gap-3 mt-8">
                        <button type="button" onclick="nextStep(2)"
                            class="px-6 py-4 rounded-2xl border-2 border-slate-200 text-slate-500 font-bold hover:border-[#F472B6] transition">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="submit" id="submitBtn"
                            class="flex-1 bg-gradient-to-r from-[#0B2D5C] via-[#2d6a8f] to-[#F472B6] text-white font-black py-4 rounded-2xl transition-all hover:scale-[1.01] active:scale-95 shadow-lg shadow-blue-900/20 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-rocket"></i> Buat Akun!
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- RIGHT — Sticky Visual Panel (full height, 50% width on large screens) -->
    <div class="hidden lg:flex w-1/2 right-panel flex-col items-center justify-center p-12 relative overflow-hidden">
        <!-- Blob bg -->
        <div class="blob absolute w-96 h-96 bg-white/5 top-10 -right-20"></div>
        <div class="blob absolute w-64 h-64 bg-[#FBCFE8]/15 bottom-10 -left-10" style="animation-delay:-3s"></div>

        <!-- Dots grid -->
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

        <!-- Main content -->
        <div class="relative z-10 text-center w-full max-w-sm">
            <!-- Logo with pulse -->
            <div class="relative inline-block mb-6 float-logo">
                <div class="pulse absolute inset-0 rounded-[36px] border-2 border-pink-400/30"></div>
                <div class="bg-white/15 border border-white/20 rounded-[36px] p-7 backdrop-blur-md text-center shadow-2xl flex items-center justify-center">
                    <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Food-TYU Logo" class="w-32 h-32 object-contain drop-shadow-[0_8px_16px_rgba(244,114,182,0.25)]">
                </div>
            </div>

            <!-- Animated Typography Banner -->
            <div class="text-center mt-6 mb-8 w-full">
                <h2 class="text-2xl font-black tracking-tight text-white mb-3 leading-tight">
                    <span class="text-pink-200 block text-xs font-bold uppercase tracking-widest mb-1.5">Stop Antri - antri,</span>
                    <span class="glow-text bg-gradient-to-r from-white via-pink-100 to-white bg-clip-text text-transparent text-3xl font-black">Gunakan Food-TYU Sekarang!</span>
                </h2>
                <p class="text-white/60 text-xs font-medium leading-relaxed px-4">
                    Pesan makanan dari kantin kampus jadi lebih mudah, cepat, dan higienis tanpa perlu berdiri di barisan antrean.
                </p>
            </div>

            <!-- Feature pills -->
            <div class="space-y-3">
                <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-2xl px-4 py-3 text-left">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-bolt text-yellow-300 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Pesan tanpa antri</p>
                        <p class="text-white/50 text-xs">Langsung dari HP kamu</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-2xl px-4 py-3 text-left">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-bell text-green-300 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Notifikasi real-time</p>
                        <p class="text-white/50 text-xs">Tahu kapan pesanan siap</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-2xl px-4 py-3 text-left">
                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-shield-alt text-blue-300 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Bayar pakai QRIS & TyU-Pay</p>
                        <p class="text-white/50 text-xs">Aman & terenkripsi Midtrans</p>
                    </div>
                </div>
            </div>

            <!-- Signup count -->
            <div class="mt-6 flex items-center justify-center gap-2">
                <div class="flex -space-x-2">
                    <div class="w-7 h-7 rounded-full bg-yellow-400 border-2 border-white/30 flex items-center justify-center text-xs font-bold text-yellow-900">A</div>
                    <div class="w-7 h-7 rounded-full bg-green-400 border-2 border-white/30 flex items-center justify-center text-xs font-bold text-green-900">B</div>
                    <div class="w-7 h-7 rounded-full bg-pink-400 border-2 border-white/30 flex items-center justify-center text-xs font-bold text-pink-900">C</div>
                    <div class="w-7 h-7 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center text-[10px] text-white font-bold">+</div>
                </div>
                <p class="text-white/60 text-xs font-medium">Bergabung dengan mahasiswa Tel-U</p>
            </div>
        </div>
    </div>

</body>

<script>
    let currentStep = 1;
    let userType = new URLSearchParams(window.location.search).get('type') || 'umum';

    function setType(type) {
        userType = type;
        const btnU = document.getElementById('btnUmum');
        const btnM = document.getElementById('btnMhs');
        const nimField = document.getElementById('nimField');
        const title = document.getElementById('step1Title');
        const sub   = document.getElementById('step1Sub');
        const nimInput = document.getElementById('nimInput');

        if (type === 'mahasiswa') {
            btnM.className = 'flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border-2 transition-all border-[#2d6a8f] bg-[#2d6a8f] text-white';
            btnU.className = 'flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border-2 transition-all border-slate-200 text-slate-500 hover:border-[#2d6a8f]';
            nimField.style.display = '';
            nimInput.required = true;
            title.textContent = 'Halo, Mahasiswa Tel-U! 🎓';
            sub.textContent   = 'Daftar dengan NIM untuk akses khusus mahasiswa.';
        } else {
            btnU.className = 'flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border-2 transition-all border-[#2d6a8f] bg-[#2d6a8f] text-white';
            btnM.className = 'flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border-2 transition-all border-slate-200 text-slate-500 hover:border-[#2d6a8f]';
            nimField.style.display = 'none';
            nimInput.required = false;
            title.textContent = 'Halo, siapa kamu? 👋';
            sub.textContent   = 'Isi data identitas untuk mulai memesan di kantin Tel-U.';
        }
    }

    // Init berdasarkan URL param
    document.addEventListener('DOMContentLoaded', () => setType(userType));

    function nextStep(step) {
        // Validasi step 1
        if (step === 2 && currentStep === 1) {
            const name = document.querySelector('[name="name"]').value.trim();
            const username = document.querySelector('[name="username"]').value.trim();
            const nim = document.querySelector('[name="nim"]').value.trim();
            if (!name || !username) {
                shake(document.getElementById('step1'));
                return;
            }
            if (userType === 'mahasiswa' && (!nim || nim.length !== 12 || isNaN(nim))) {
                alert('NIM Mahasiswa harus diisi berupa 12 digit angka!');
                shake(document.getElementById('step1'));
                return;
            }
        }
        // Validasi step 2
        if (step === 3 && currentStep === 2) {
            const email = document.querySelector('[name="email"]').value.trim();
            const phone = document.querySelector('[name="phone"]').value.trim();
            const address = document.querySelector('[name="address"]').value.trim();
            if (!email || !phone || !address) {
                shake(document.getElementById('step2'));
                return;
            }
            if (address.length < 10) {
                alert('Alamat pengantaran minimal 10 karakter agar pengantaran jelas!');
                shake(document.getElementById('step2'));
                return;
            }
        }

        document.getElementById('step' + currentStep).classList.remove('active');
        document.getElementById('step' + step).classList.add('active');
        currentStep = step;

        // Update progress
        const progress = { 1: '33%', 2: '66%', 3: '100%' };
        document.getElementById('progressBar').style.width = progress[step];
        document.getElementById('stepLabel').textContent = `Langkah ${step} dari 3`;

        // Update dots
        [1,2,3].forEach(i => {
            const dot = document.getElementById('dot'+i);
            dot.classList.remove('active', 'bg-[#2d6a8f]', 'bg-slate-200', 'bg-green-400');
            if (i < step) {
                dot.classList.add('bg-green-400', 'active');
                dot.innerHTML = '';
            } else if (i === step) {
                dot.classList.add('bg-[#2d6a8f]', 'active');
            } else {
                dot.classList.add('bg-slate-200');
            }
        });

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function shake(el) {
        el.style.animation = 'none';
        el.offsetHeight; /* trigger reflow */
        el.style.animation = 'shakeAnim 0.3s ease';
    }

    // Add keyframe for shake animation in JS dynamically
    const styleSheet = document.createElement("style");
    styleSheet.innerText = `
        @keyframes shakeAnim {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
    `;
    document.head.appendChild(styleSheet);

    function checkStrength(val) {
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = ['', 'bg-red-400', 'bg-yellow-400', 'bg-blue-400', 'bg-green-400'];
        const labels = ['', '😬 Terlalu lemah', '😐 Lumayan', '🙂 Cukup kuat', '💪 Sangat kuat!'];

        [1,2,3,4].forEach(i => {
            const bar = document.getElementById('s'+i);
            bar.className = 'h-1 flex-1 rounded-full transition-all duration-300 ' + (i <= score ? colors[score] : 'bg-slate-100');
        });
        document.getElementById('strengthLabel').textContent = val ? labels[score] : 'Masukkan password';
    }

    function togglePass(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash text-sm';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye text-sm';
        }
    }

    // Checkbox visual fix
    document.getElementById('terms').addEventListener('change', function() {
        const icon = document.getElementById('checkIcon');
        icon.style.display = this.checked ? 'block' : 'none';
    });
</script>
</html>
