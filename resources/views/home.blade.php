@extends('layouts.pembeli')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

    @section('head-scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endsection

@section('extra-css')

        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FBF9E4; } /* Pearl Perfect */
        .sidebar-active { background-color: #FBF9E4; color: #122C4F !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .food-card:hover img { transform: scale(1.1); }
        
        @keyframes bounce-in {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        .cart-item-anim { animation: bounce-in 0.4s ease-out; }
        .bg-midnight { background-color: #122C4F; }
        .text-pearl { color: #FBF9E4; }

        /* Tambahkan ini di dalam tag <style> kamu */
        main {
            max-width: 1400px; /* Membatasi agar tidak terlalu lebar di monitor besar */
            margin: 0 auto;
        }

        /* Biar kartu makanan ukurannya konsisten */
        .food-card img {
            transition: all 0.3s ease;
        }

        /* Tambahkan ini di dalam tag <style> kamu */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.4s ease forwards;
        }
    
@endsection

@section('content')
<div class="no-scrollbar">
        <header class="flex justify-between items-center mb-10">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-foodtyu.png') }}" class="w-10 h-10 object-contain shadow-sm rounded-lg bg-white p-1">
                <div>
                    <h2 class="text-3xl font-black text-[#122C4F]">Hi, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-slate-500 font-medium text-sm">Laper ngerjain tugas? Pesan makan yuk!</p>
                </div>
            </div>
        </header>

        <section class="bg-gradient-to-r from-[#5B88B2] to-[#122C4F] rounded-[45px] p-12 text-white flex justify-between items-center mb-12 shadow-xl relative overflow-hidden group">
            <div class="relative z-10 max-w-md">
                @if(Auth::user()->role === 'mahasiswa')
                    {{-- Banner Khusus Mahasiswa --}}
                    <span class="bg-[#FBF9E4] text-[#122C4F] px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">🎓 Promo Student</span>
                    <h3 class="text-4xl font-black mt-5 mb-3 italic tracking-tighter text-[#FBF9E4]">Diskon Acak Hari Ini!</h3>
                    <p class="text-lg opacity-90 font-medium leading-relaxed mb-6">Khusus buat kamu mahasiswa Tel-U yang lagi ngerjain tugas. Ambil voucher terbaik dan hemat belanja di kantin.</p>
                    <button onclick="openVoucherModal()" class="bg-green-400 hover:bg-green-500 text-gray-900 px-6 py-2.5 rounded-xl font-bold transition-all shadow-lg">
                        VOUCHER TERPASANG ✓
                    </button>
                @else
                    {{-- Banner untuk User Biasa --}}
                    <span class="bg-[#FBF9E4] text-[#122C4F] px-5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">👤 User Member</span>
                    <h3 class="text-4xl font-black mt-5 mb-3 italic tracking-tighter text-[#FBF9E4]">Selamat Datang!</h3>
                    <p class="text-lg opacity-90 font-medium leading-relaxed mb-6">Nikmati hidangan lezat kantin Tel-U dengan pemesanan praktis tanpa antre. Cepat, mudah, dan terjangkau.</p>
                    <button onclick="scrollToMenu()" class="bg-blue-400 hover:bg-blue-500 text-gray-900 px-6 py-2.5 rounded-xl font-bold transition-all shadow-lg">
                        LIHAT MENU SEKARANG →
                    </button>
                @endif
            </div>
            <i class="fa-solid fa-ticket text-[250px] absolute -right-10 -bottom-10 opacity-10 rotate-12 group-hover:rotate-0 transition-all duration-700"></i>
        </section>

        <div class="mb-6">
            <div class="relative max-w-2xl mx-auto">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="searchInput" 
                       placeholder="Mau makan seblak atau apa hari ini?" 
                       class="w-full pl-12 pr-4 py-4 rounded-2xl border-none shadow-sm focus:ring-2 focus:ring-blue-300 outline-none transition-all"
                       autocomplete="off">
                
                <div id="searchPopup" class="hidden absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 max-h-60 overflow-y-auto">
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-4 mb-8">
            <div class="flex flex-wrap gap-3">
                <button onclick="filterMenu('all')" id="btn-all" class="filter-btn px-8 py-3 bg-midnight text-white rounded-full text-xs font-bold shadow-md transition-all">All</button>
                <button onclick="filterMenu('heavy')" id="btn-heavy" class="filter-btn px-8 py-3 bg-white text-slate-400 rounded-full text-xs font-bold shadow-sm border border-slate-100 hover:bg-slate-50 transition-all">Makanan Berat</button>
                <button onclick="filterMenu('beverage')" id="btn-beverage" class="filter-btn px-8 py-3 bg-white text-slate-400 rounded-full text-xs font-bold shadow-sm border border-slate-100 hover:bg-slate-50 transition-all">Minuman</button>
                <button onclick="filterMenu('snack')" id="btn-snack" class="filter-btn px-8 py-3 bg-white text-slate-400 rounded-full text-xs font-bold shadow-sm border border-slate-100 hover:bg-slate-50 transition-all">Cemilan</button>
                <button onclick="openKantinModal()" class="px-8 py-3 bg-slate-900 text-white rounded-full text-xs font-bold shadow-md transition-all hover:bg-slate-700">Kategori Kantin</button>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100 ml-auto">
                <p class="text-[10px] uppercase font-bold text-slate-400">Saldo TyU-Pay</p>
                <p class="text-lg font-black text-midnight">RP {{ number_format(Auth::user()->balance, 0, ',', '.') }}</p>
            </div>
        </div>

        <div id="menuSection" class="scroll-mt-24">
            <h2 class="text-lg font-bold mb-4" id="sectionTitle">Menu Kantin</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="foodContainer">
            @forelse($menus as $menu)
                <div data-category="{{ $menu->category }}" data-canton="{{ $menu->category }}" class="menu-item food-item bg-white p-4 rounded-[32px] shadow-sm hover:shadow-md transition hover:-translate-y-2 hover:shadow-xl transition-all duration-300" data-name="{{ $menu->name }}" data-base-price="{{ $menu->price }}">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                            🏪 {{ $menu->canteen->name ?? 'Kantin' }}
                        </span>
                        <span class="text-xs text-gray-500 font-medium">
                            👩‍🍳 {{ $menu->canteen->ibuKantin->name ?? '-' }}
                        </span>
                    </div>
                    <img src="{{ $menu->image_url ?: 'https://via.placeholder.com/300' }}" alt="{{ $menu->name }}" class="w-full h-44 object-cover rounded-[24px] mb-4 cursor-pointer hover:opacity-75 transition" onclick="showMenuDetailFromHome({{ json_encode($menu) }})">
                    <div class="flex justify-between items-start">
                        <div class="cursor-pointer hover:text-orange-500 transition" onclick="showMenuDetailFromHome({{ json_encode($menu) }})">
                            <h3 class="menu-name font-bold text-gray-800 text-lg">{{ $menu->name }}</h3>
                            <p class="text-xs text-gray-500 line-clamp-2">{{ $menu->description }}</p>
                        </div>
                        <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-1 rounded-full font-bold">⭐ {{ $menu->rating ?? '4.7' }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <span class="font-extrabold text-[#122C4F] product-price">RP {{ number_format($menu->price, 0, ',', '.') }}</span>
                        <div class="flex gap-2">
                            <button onclick="event.stopPropagation(); showMenuDetailFromHome({{ json_encode($menu) }})" class="bg-blue-500 hover:bg-blue-600 text-white w-10 h-10 rounded-2xl flex items-center justify-center transition-transform" title="Detail">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <button onclick="addToCart('{{ $menu->name }}', {{ $menu->price }}, '{{ $menu->image_url ?: 'https://via.placeholder.com/300' }}', {{ $menu->canteen_id }}); toggleCart()" class="bg-[#122C4F] text-white w-10 h-10 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:scale-105 active:scale-95 transition-transform">+</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500 font-medium">Belum ada menu tersedia saat ini</p>
                </div>
            @endforelse
        </div>

        <div id="recommendationSection" class="hidden mt-12 pt-8 border-t-2 border-dashed border-gray-200">
            <h2 class="text-lg font-bold mb-4 text-gray-400">Rekomendasi Minuman Segar 🥤</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-4 rounded-2xl shadow-sm opacity-80">
                    <img src="{{ asset('images/esteh.png') }}" class="rounded-xl w-full">
                    <h3 class="font-bold mt-2">Es Teh Manis</h3>
                    <p class="text-blue-500 font-bold">RP 5.000</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let items = document.querySelectorAll('.food-item');
            let container = document.getElementById('foodContainer');
            let recSection = document.getElementById('recommendationSection');
            let title = document.getElementById('sectionTitle');
            let hasMatch = false;

            items.forEach(item => {
                let text = item.getAttribute('data-name').toLowerCase();
                if (text.includes(filter)) {
                    item.style.display = ""; // Tampilkan
                    item.classList.add('animate-fade-up'); // Opsional: tambah animasi naik
                    hasMatch = true;
                } else {
                    item.style.display = "none"; // Sembunyikan
                }
            });

            // Logika ketika user mengetik (Munculkan Rekomendasi)
            if (filter.length > 0) {
                title.innerText = "Hasil Pencarian untuk: " + this.value;
                recSection.classList.remove('hidden');
                // Smooth scroll sedikit agar user sadar ada konten baru di bawah
            } else {
                title.innerText = "Menu Kantin";
                recSection.classList.add('hidden');
            }
        });
    </script>

<aside id="cartSidebar" class="fixed right-0 top-0 h-full w-[350px] bg-white shadow-2xl z-[60] transition-transform duration-500 translate-x-full border-l border-slate-100 flex flex-col p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-[#122C4F]">Keranjang Saya</h3>
            <button onclick="toggleCart()" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <span id="cartCount" class="bg-slate-100 text-slate-500 px-3 py-1 rounded-lg text-xs font-bold mb-6 inline-block">0 Items</span>
        
        <div id="cartItems" class="flex-1 space-y-8 overflow-y-auto no-scrollbar pr-2">
            <div class="text-center py-20 opacity-20">
                <i class="fa-solid fa-basket-shopping text-6xl mb-4"></i>
                <p class="text-sm font-bold uppercase tracking-widest">Belum ada pesanan</p>
            </div>
        </div>

        <div class="mt-10 pt-8 border-t-2 border-dashed border-slate-100">
            <div class="space-y-4 mb-8">
                <div class="flex justify-between text-[13px] text-slate-500 font-bold uppercase tracking-wider">
                    <span>Subtotal</span>
                    <span id="subtotal" class="text-midnight">RP 0</span>
                </div>
                <div class="flex justify-between items-end pt-4 border-t border-slate-50">
                    <span class="text-lg font-black text-midnight italic">Total</span>
                    <span id="totalPrice" class="text-3xl font-black text-[#5B88B2] tracking-tighter">RP 0</span>
                </div>
            </div>
            
            <div class="mt-6">
                <p class="font-bold text-gray-800 mb-3">Metode Pembayaran</p>
                <div class="grid grid-cols-1 gap-2">
                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment" value="qris" checked>
                            <span>QRIS</span>
                        </div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" class="h-4">
                    </label>

                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment" value="ewallet">
                            <span>E-Wallet (OVO/Dana/GoPay)</span>
                        </div>
                    </label>

                    <label class="flex items-center justify-between p-3 border rounded-xl cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment" value="bank">
                            <span>Transfer Bank</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- ===== LOKASI PENGAMBILAN ===== -->
            <div class="mt-5 bg-blue-50 rounded-2xl p-4 border border-blue-100">
                <p class="font-bold text-[#122C4F] mb-3 flex items-center gap-2 text-sm">
                    <i class="fas fa-map-marker-alt text-blue-500"></i> Lokasi Pengambilan
                </p>

                <!-- Toggle Pickup / Delivery -->
                <div class="flex gap-2 mb-3">
                    <button type="button" id="btnPickup"
                        onclick="setDeliveryType('pickup')"
                        class="flex-1 py-2 text-xs font-bold rounded-xl bg-[#122C4F] text-white transition">
                        🏃 Ambil Sendiri
                    </button>
                    <button type="button" id="btnDeliver"
                        onclick="setDeliveryType('deliver')"
                        class="flex-1 py-2 text-xs font-bold rounded-xl bg-white border border-slate-200 text-slate-600 transition hover:border-blue-400">
                        🛵 Antar ke Lokasi
                    </button>
                </div>

                <!-- Pickup message -->
                <div id="pickupMsg" class="text-xs text-green-700 bg-green-50 border border-green-200 rounded-xl px-3 py-2 font-medium">
                    ✅ Ambil di kantin — pengelola kantin akan siapkan pesanan kamu
                </div>

                <!-- Deliver form -->
                <div id="deliverForm" class="hidden space-y-2">
                    <select id="pickupArea"
                        class="w-full text-xs px-3 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-300 outline-none bg-white">
                        <option value="">— Pilih Area / Gedung —</option>
                        <optgroup label="🏫 Area Kelas">
                            <option value="Gedung A – Lantai 1">Gedung A – Lantai 1</option>
                            <option value="Gedung A – Lantai 2">Gedung A – Lantai 2</option>
                            <option value="Gedung A – Lantai 3">Gedung A – Lantai 3</option>
                            <option value="Gedung B – Lantai 1">Gedung B – Lantai 1</option>
                            <option value="Gedung B – Lantai 2">Gedung B – Lantai 2</option>
                            <option value="Gedung C – Lantai Dasar">Gedung C – Lantai Dasar</option>
                            <option value="Gedung D – Basement">Gedung D – Basement</option>
                        </optgroup>
                        <optgroup label="🔬 Area Lab">
                            <option value="Lab Informatika – Lantai 3">Lab Informatika – Lantai 3</option>
                            <option value="Lab Komputer – Gedung E">Lab Komputer – Gedung E</option>
                            <option value="Lab Jaringan">Lab Jaringan</option>
                        </optgroup>
                        <optgroup label="🌿 Area Umum">
                            <option value="Halaman Utama Kampus">Halaman Utama Kampus</option>
                            <option value="Ruang Makan Mahasiswa">Ruang Makan Mahasiswa</option>
                            <option value="Perpustakaan – Lantai 1">Perpustakaan – Lantai 1</option>
                            <option value="Area Parkir Selatan">Area Parkir Selatan</option>
                            <option value="Taman Belakang Kampus">Taman Belakang Kampus</option>
                        </optgroup>
                    </select>
                    <input type="text" id="pickupDetail"
                        placeholder="Detail: Ruang 301, meja kiri, nomor kursi..."
                        class="w-full text-xs px-3 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-300 outline-none bg-white">
                </div>
            </div>
            
            <button onclick="openPaymentModal()" class="w-full bg-[#5B88B2] text-white py-5 rounded-[25px] font-black text-sm shadow-xl shadow-blue-100 hover:bg-midnight transition-all uppercase tracking-widest active:scale-95">
                Lanjut ke Pembayaran
            </button>
        </div>
    </aside>

    <!-- ===== MODAL PEMBAYARAN BARU ===== -->
    <div id="paymentModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[35px] shadow-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-[#122C4F]">Pilih Metode Pembayaran</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 w-8 h-8 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Ringkasan total -->
            <div class="bg-slate-50 rounded-2xl p-4 mb-5 flex justify-between items-center">
                <span class="text-slate-500 font-medium text-sm">Total Pembayaran</span>
                <span class="font-black text-xl text-[#122C4F]" id="modalTotalDisplay">RP 0</span>
            </div>

            <!-- Metode pembayaran — klik langsung proses -->
            <div class="space-y-3">
                <!-- QRIS → langsung Midtrans Snap -->
                <button onclick="selectAndPay('qris')"
                    class="w-full p-4 border-2 border-slate-100 rounded-2xl flex justify-between items-center hover:border-[#5B88B2] hover:bg-blue-50 transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-qrcode text-2xl text-[#5B88B2]"></i>
                        <div class="text-left">
                            <span class="font-bold text-slate-700 block">QRIS</span>
                            <p class="text-xs text-slate-400">GoPay • Dana • OVO • ShopeePay</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-[#5B88B2]"></i>
                </button>

                <!-- E-Wallet → langsung Midtrans Snap -->
                <button onclick="selectAndPay('ewallet')"
                    class="w-full p-4 border-2 border-slate-100 rounded-2xl flex justify-between items-center hover:border-[#5B88B2] hover:bg-blue-50 transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-wallet text-2xl text-[#5B88B2]"></i>
                        <div class="text-left">
                            <span class="font-bold text-slate-700 block">E-Wallet</span>
                            <p class="text-xs text-slate-400">Shopee • GoPay • Dana</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-[#5B88B2]"></i>
                </button>

                <!-- Saldo TyU-Pay → potong saldo langsung -->
                <button onclick="selectAndPay('saldo')"
                    class="w-full p-4 border-2 border-slate-100 rounded-2xl flex justify-between items-center hover:border-green-400 hover:bg-green-50 transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-piggy-bank text-2xl text-green-500"></i>
                        <div class="text-left">
                            <span class="font-bold text-slate-700 block">Saldo TyU-Pay</span>
                            <p class="text-xs text-slate-400" id="balance-indicator">
                                Saldo: Rp {{ number_format(Auth::user()->balance ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-300 group-hover:text-green-400"></i>
                </button>
            </div>

            <!-- Loading state -->
            <div id="paymentLoadingState" class="hidden mt-5 text-center py-4">
                <i class="fa-solid fa-spinner fa-spin text-2xl text-[#5B88B2] mb-2"></i>
                <p class="text-sm font-bold text-slate-600">Menghubungkan ke Midtrans...</p>
            </div>

            <p class="text-center text-xs text-slate-400 mt-5">
                🔒 Pembayaran aman & terenkripsi oleh Midtrans
            </p>
        </div>
    </div>

    <!-- hidden confirm btn untuk backward compat -->
    <button id="confirm-payment-btn" class="hidden"></button>

    <div id="kantinModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[40px] p-8 text-center">
            <h3 class="text-xl font-black text-[#122C4F] mb-6">Pilih Kategori Kantin</h3>
            <div class="grid grid-cols-2 gap-4">
                <button onclick="filterMenu('heavy'); closeKantinModal()" class="p-6 bg-orange-50 rounded-3xl hover:bg-orange-100 transition">
                    <i class="fa-solid fa-bowl-food text-2xl text-orange-500 mb-2"></i>
                    <p class="text-xs font-bold">Makanan Berat</p>
                </button>
                <button onclick="filterMenu('beverage'); closeKantinModal()" class="p-6 bg-blue-50 rounded-3xl hover:bg-blue-100 transition">
                    <i class="fa-solid fa-wine-glass text-2xl text-blue-500 mb-2"></i>
                    <p class="text-xs font-bold">Minuman Segar</p>
                </button>
                <button onclick="filterMenu('snack'); closeKantinModal()" class="p-6 bg-yellow-50 rounded-3xl hover:bg-yellow-100 transition">
                    <i class="fa-solid fa-cookie text-2xl text-yellow-500 mb-2"></i>
                    <p class="text-xs font-bold">Cemilan/Snack</p>
                </button>
                <button onclick="filterMenu('all'); closeKantinModal()" class="p-6 bg-slate-50 rounded-3xl hover:bg-slate-100 transition">
                    <i class="fa-solid fa-border-all text-2xl text-slate-500 mb-2"></i>
                    <p class="text-xs font-bold">Semua Menu</p>
                </button>
            </div>
        </div>
    </div>

    <!-- Avatar Upload Form (Hidden) -->
    <form id="avatarForm" class="hidden">
        @csrf
        <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" onchange="submitAvatarAjax(this)">
    </form>

    <!-- Profile Modal - fixed height with scroll -->
    <div id="profileModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-3">
        <div class="bg-white w-full max-w-sm rounded-[28px] shadow-2xl flex flex-col" style="max-height:92vh;">

            <!-- Header sticky -->
            <div class="flex justify-between items-center px-6 pt-5 pb-3 border-b border-slate-100 shrink-0">
                <h3 class="font-black text-[#122C4F] text-base">👤 Profil Saya</h3>
                <button onclick="closeProfileModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Scrollable body -->
            <div class="overflow-y-auto flex-1 px-6 py-4">

                <!-- Avatar upload -->
                <div class="flex flex-col items-center mb-5">
                    <div class="relative group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                        <div id="avatarContainer" class="w-20 h-20 rounded-full overflow-hidden border-4 border-blue-100 group-hover:opacity-80 transition-all relative">
                            @if(Auth::user()->avatar)
                                <img id="avatarImg" src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div id="avatarInitial" class="w-full h-full bg-[#4A7292] text-white flex items-center justify-center text-xl font-black">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="absolute bottom-0 right-0 bg-orange-500 w-6 h-6 rounded-full flex items-center justify-center shadow group-hover:scale-110 transition-transform">
                            <i class="fas fa-camera text-white text-[9px]"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1.5 font-medium">Ketuk foto untuk mengubah</p>
                    <div id="avatarUploadMsg" class="hidden mt-2 text-xs font-bold px-3 py-1.5 rounded-xl"></div>
                </div>

                <!-- General Alert Message inside Modal -->
                <div id="profileModalAlert" class="hidden mb-4 p-3.5 rounded-2xl border text-xs font-bold flex items-center gap-2.5">
                    <i id="profileModalAlertIcon" class="fa-solid"></i>
                    <span id="profileModalAlertText"></span>
                </div>

                <!-- Info Fields (View Mode) -->
                <div id="profileViewFields" class="space-y-2.5">
                    <div class="bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Nama Lengkap</p>
                        <p class="text-sm font-bold text-[#122C4F]">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Username</p>
                        <p class="text-sm font-bold text-[#122C4F]">{{ Auth::user()->username }}</p>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Email</p>
                        <p class="text-xs font-medium text-[#122C4F] break-all">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">No. Telepon</p>
                        <p class="text-sm font-bold text-[#122C4F]">{{ Auth::user()->phone ?? '-' }}</p>
                    </div>
                    @if(Auth::user()->nim)
                    <div class="bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">NIM</p>
                        <p class="text-sm font-bold text-[#122C4F]">{{ Auth::user()->nim }}</p>
                    </div>
                    @endif
                    <div class="bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Status</p>
                        <span class="inline-block px-3 py-1 bg-blue-100 text-[#2d6a8f] font-bold rounded-full text-xs capitalize">
                            {{ Auth::user()->role === 'ibu_kantin' ? 'Mitra Kantin' : ucfirst(Auth::user()->role ?? 'User') }}
                        </span>
                    </div>
                    @if(Auth::user()->address)
                    <div class="bg-slate-50 px-4 py-3 rounded-2xl border border-slate-100">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Alamat</p>
                        <p class="text-xs text-[#122C4F] leading-relaxed">{{ Auth::user()->address }}</p>
                    </div>
                    @endif

                    {{-- Saldo --}}
                    <div class="bg-[#122C4F] px-4 py-3 rounded-2xl">
                        <p class="text-[9px] text-white/50 font-bold uppercase tracking-widest mb-0.5">Saldo TyU-Pay</p>
                        <p class="text-lg font-black text-white">Rp {{ number_format(Auth::user()->balance ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Info Fields (Edit Mode) -->
                <div id="profileEditFields" class="hidden space-y-3.5">
                    <div>
                        <label class="text-[9px] text-slate-400 font-bold uppercase tracking-widest block mb-1">Nama Lengkap</label>
                        <input type="text" id="editName" value="{{ Auth::user()->name }}" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm font-bold text-[#122C4F] focus:outline-none focus:border-[#122C4F] transition bg-slate-50 focus:bg-white">
                        <span class="text-[10px] text-red-500 font-bold mt-1 block hidden" id="error-name"></span>
                    </div>
                    <div>
                        <label class="text-[9px] text-slate-400 font-bold uppercase tracking-widest block mb-1">Username</label>
                        <input type="text" id="editUsername" value="{{ Auth::user()->username }}" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm font-bold text-[#122C4F] focus:outline-none focus:border-[#122C4F] transition bg-slate-50 focus:bg-white">
                        <span class="text-[10px] text-red-500 font-bold mt-1 block hidden" id="error-username"></span>
                    </div>
                    <div>
                        <label class="text-[9px] text-slate-400 font-bold uppercase tracking-widest block mb-1">Email</label>
                        <input type="email" id="editEmail" value="{{ Auth::user()->email }}" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm font-bold text-[#122C4F] focus:outline-none focus:border-[#122C4F] transition bg-slate-50 focus:bg-white">
                        <span class="text-[10px] text-red-500 font-bold mt-1 block hidden" id="error-email"></span>
                    </div>
                    <div>
                        <label class="text-[9px] text-slate-400 font-bold uppercase tracking-widest block mb-1">No. Telepon</label>
                        <input type="text" id="editPhone" value="{{ Auth::user()->phone }}" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm font-bold text-[#122C4F] focus:outline-none focus:border-[#122C4F] transition bg-slate-50 focus:bg-white" placeholder="Contoh: 08123456789">
                        <span class="text-[10px] text-red-500 font-bold mt-1 block hidden" id="error-phone"></span>
                    </div>
                    <div>
                        <label class="text-[9px] text-slate-400 font-bold uppercase tracking-widest block mb-1">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" id="editNim" value="{{ Auth::user()->nim }}" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm font-bold text-[#122C4F] focus:outline-none focus:border-[#122C4F] transition bg-slate-50 focus:bg-white" placeholder="Optional">
                        <span class="text-[10px] text-red-500 font-bold mt-1 block hidden" id="error-nim"></span>
                    </div>
                    <div>
                        <label class="text-[9px] text-slate-400 font-bold uppercase tracking-widest block mb-1">Alamat</label>
                        <textarea id="editAddress" rows="2" class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 text-sm text-[#122C4F] focus:outline-none focus:border-[#122C4F] transition bg-slate-50 focus:bg-white" placeholder="Masukkan alamat lengkap">{{ Auth::user()->address }}</textarea>
                        <span class="text-[10px] text-red-500 font-bold mt-1 block hidden" id="error-address"></span>
                    </div>
                </div>
            </div>

            <!-- Footer sticky -->
            <div class="px-6 py-4 border-t border-slate-100 space-y-2 shrink-0">
                <!-- View Mode Buttons -->
                <div id="profileViewButtons" class="space-y-2">
                    <button onclick="handleEditProfile()" class="w-full bg-[#122C4F] text-white py-3 rounded-2xl font-bold text-sm hover:bg-[#2d6a8f] transition flex items-center justify-center gap-2">
                        <i class="fas fa-pen text-xs"></i> Edit Profil
                    </button>
                    <button onclick="closeProfileModal()" class="w-full bg-slate-100 text-slate-600 py-2.5 rounded-2xl font-bold text-sm hover:bg-slate-200 transition">
                        Tutup
                    </button>
                </div>
                <!-- Edit Mode Buttons -->
                <div id="profileEditButtons" class="hidden space-y-2">
                    <button onclick="submitProfileUpdate()" id="btnSaveProfile" class="w-full bg-[#122C4F] text-white py-3 rounded-2xl font-bold text-sm hover:bg-[#2d6a8f] transition flex items-center justify-center gap-2">
                        <i class="fas fa-save text-xs"></i> Simpan Perubahan
                    </button>
                    <button onclick="toggleProfileEditMode(false)" class="w-full bg-slate-100 text-slate-600 py-2.5 rounded-2xl font-bold text-sm hover:bg-slate-200 transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="successModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-sm rounded-[35px] shadow-2xl p-8 text-center animate-in zoom-in duration-300">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-check text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-[#122C4F] mb-2">Pesanan Berhasil!</h3>
            <p class="text-slate-500 mb-8">Pesananmu sedang diproses oleh kantin. Silakan cek menu Riwayat.</p>
            
            <button onclick="location.reload()" class="w-full bg-[#122C4F] text-white py-4 rounded-2xl font-black">
                KEMBALI KE HOME
            </button>
        </div>
    </div>

    <div id="voucherModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-md rounded-[35px] shadow-2xl p-8 animate-in zoom-in duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-[#122C4F]">Pilih Voucher Hari Ini</h3>
                <button onclick="closeVoucherModal()" class="text-slate-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div id="modalVoucherList" class="space-y-3 max-h-96 overflow-y-auto">
            </div>
            
            <button onclick="closeVoucherModal()" class="w-full mt-6 text-slate-400 text-xs font-bold uppercase tracking-widest py-3">
                Tutup
            </button>
        </div>
    </div>

    <script>
        let cart = [];
        let deliveryType = 'pickup'; // 'pickup' atau 'deliver'

        function setDeliveryType(type) {
            deliveryType = type;
            const btnPickup  = document.getElementById('btnPickup');
            const btnDeliver = document.getElementById('btnDeliver');
            const pickupMsg  = document.getElementById('pickupMsg');
            const deliverForm = document.getElementById('deliverForm');

            if (type === 'pickup') {
                btnPickup.classList.add('bg-[#122C4F]', 'text-white');
                btnPickup.classList.remove('bg-white', 'border', 'border-slate-200', 'text-slate-600');
                btnDeliver.classList.remove('bg-[#122C4F]', 'text-white');
                btnDeliver.classList.add('bg-white', 'border', 'border-slate-200', 'text-slate-600');
                pickupMsg.classList.remove('hidden');
                deliverForm.classList.add('hidden');
            } else {
                btnDeliver.classList.add('bg-[#122C4F]', 'text-white');
                btnDeliver.classList.remove('bg-white', 'border', 'border-slate-200', 'text-slate-600');
                btnPickup.classList.remove('bg-[#122C4F]', 'text-white');
                btnPickup.classList.add('bg-white', 'border', 'border-slate-200', 'text-slate-600');
                pickupMsg.classList.add('hidden');
                deliverForm.classList.remove('hidden');
            }
        }

        function getLokasiNote() {
            if (deliveryType === 'pickup') {
                return '📍 Ambil Sendiri di Kantin';
            }
            const area   = document.getElementById('pickupArea')?.value || '';
            const detail = document.getElementById('pickupDetail')?.value?.trim() || '';
            if (!area) return '📍 Antar – (lokasi belum dipilih)';
            return '📍 Antar ke: ' + area + (detail ? ' – ' + detail : '');
        }

        // Profile Modal Functions
        function openProfileModal() {
            const modal = document.getElementById('profileModal');
            if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        }

        function closeProfileModal() {
            const modal = document.getElementById('profileModal');
            if (modal) { 
                modal.classList.add('hidden'); 
                modal.classList.remove('flex'); 
                toggleProfileEditMode(false); // Reset to view mode on close
            }
        }

        function triggerUpload() { document.getElementById('avatarInput').click(); }

        function submitAvatarAjax(input) {
            if (!input.files?.[0]) return;
            const file = input.files[0];
            if (file.size > 3 * 1024 * 1024) {
                showAvatarMsg('❌ Ukuran file terlalu besar! Maks 3MB.', 'error'); return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                const c = document.getElementById('avatarContainer');
                if (c) c.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            };
            reader.readAsDataURL(file);
            showAvatarMsg('⏳ Mengupload...', 'info');
            const fd = new FormData();
            fd.append('avatar', file);
            fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
            fetch('/profile/upload-avatar-ajax', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showAvatarMsg('✅ ' + data.message, 'success');
                        const topbarAv = document.getElementById('topbarAvatarArea');
                        if (topbarAv && data.avatar_url)
                            topbarAv.innerHTML = `<img src="${data.avatar_url}?t=${Date.now()}" style="width:26px;height:26px;border-radius:8px;object-fit:cover;">`;
                    } else { showAvatarMsg('❌ ' + (data.message || 'Gagal upload'), 'error'); }
                })
                .catch(() => showAvatarMsg('❌ Gagal upload, coba lagi.', 'error'));
        }

        function submitAvatarForm() { submitAvatarAjax(document.getElementById('avatarInput')); }

        function showAvatarMsg(text, type) {
            const el = document.getElementById('avatarUploadMsg');
            if (!el) return;
            el.textContent = text;
            el.className = 'mt-2 text-xs font-bold px-3 py-1.5 rounded-xl ' + (
                type === 'success' ? 'bg-green-100 text-green-700' :
                type === 'error'   ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700');
            el.classList.remove('hidden');
        }

        function toggleProfileEditMode(active) {
            const viewFields = document.getElementById('profileViewFields');
            const editFields = document.getElementById('profileEditFields');
            const viewButtons = document.getElementById('profileViewButtons');
            const editButtons = document.getElementById('profileEditButtons');
            const alertBox = document.getElementById('profileModalAlert');

            if (active) {
                if (alertBox) alertBox.classList.add('hidden');
                clearFieldErrors();
                
                if (viewFields) viewFields.classList.add('hidden');
                if (editFields) editFields.classList.remove('hidden');
                if (viewButtons) viewButtons.classList.add('hidden');
                if (editButtons) editButtons.classList.remove('hidden');
            } else {
                if (viewFields) viewFields.classList.remove('hidden');
                if (editFields) editFields.classList.add('hidden');
                if (viewButtons) viewButtons.classList.remove('hidden');
                if (editButtons) editButtons.classList.add('hidden');
                if (alertBox) alertBox.classList.add('hidden');

                const inputs = ['editName', 'editUsername', 'editEmail', 'editPhone', 'editNim', 'editAddress'];
                inputs.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.value = el.defaultValue;
                        el.classList.remove('border-red-500');
                    }
                });
                clearFieldErrors();
            }
        }

        function clearFieldErrors() {
            const fields = ['name', 'username', 'email', 'phone', 'nim', 'address'];
            fields.forEach(field => {
                const errSpan = document.getElementById('error-' + field);
                const inputEl = document.getElementById('edit' + field.charAt(0).toUpperCase() + field.slice(1));
                if (errSpan) {
                    errSpan.classList.add('hidden');
                    errSpan.textContent = '';
                }
                if (inputEl) {
                    inputEl.classList.remove('border-red-500');
                    inputEl.classList.add('border-slate-200');
                }
            });
        }

        function handleEditProfile() {
            toggleProfileEditMode(true);
        }

        function submitProfileUpdate() {
            const name = document.getElementById('editName')?.value || '';
            const username = document.getElementById('editUsername')?.value || '';
            const email = document.getElementById('editEmail')?.value || '';
            const phone = document.getElementById('editPhone')?.value || '';
            const nim = document.getElementById('editNim')?.value || '';
            const address = document.getElementById('editAddress')?.value || '';
            const btnSave = document.getElementById('btnSaveProfile');

            clearFieldErrors();
            const alertBox = document.getElementById('profileModalAlert');
            if (alertBox) alertBox.classList.add('hidden');

            if (!name.trim()) {
                showFieldError('name', 'Nama lengkap wajib diisi!');
                return;
            }
            if (!username.trim()) {
                showFieldError('username', 'Username wajib diisi!');
                return;
            }
            if (!email.trim()) {
                showFieldError('email', 'Email wajib diisi!');
                return;
            }

            if (btnSave) {
                btnSave.disabled = true;
                btnSave.innerHTML = `<i class="fa-solid fa-spinner fa-spin text-xs"></i> Menyimpan...`;
            }

            const fd = new FormData();
            fd.append('name', name);
            fd.append('username', username);
            fd.append('email', email);
            fd.append('phone', phone);
            fd.append('nim', nim);
            fd.append('address', address);
            fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

            fetch('/profile/update', {
                method: 'POST',
                body: fd,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json();
                if (response.ok && data.success) {
                    showProfileModalAlert('✅ ' + data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            showFieldError(key, data.errors[key][0]);
                        });
                        showProfileModalAlert('❌ Harap periksa kembali isian form Anda.', 'danger');
                    } else {
                        showProfileModalAlert('❌ ' + (data.message || 'Gagal memperbarui profil.'), 'danger');
                    }
                }
            })
            .catch(error => {
                console.error(error);
                showProfileModalAlert('❌ Terjadi kesalahan koneksi, silakan coba lagi.', 'danger');
            })
            .finally(() => {
                if (btnSave) {
                    btnSave.disabled = false;
                    btnSave.innerHTML = `<i class="fas fa-save text-xs"></i> Simpan Perubahan`;
                }
            });
        }

        function showFieldError(field, msg) {
            const errSpan = document.getElementById('error-' + field);
            const inputEl = document.getElementById('edit' + field.charAt(0).toUpperCase() + field.slice(1));
            if (errSpan) {
                errSpan.textContent = msg;
                errSpan.classList.remove('hidden');
            }
            if (inputEl) {
                inputEl.classList.remove('border-slate-200');
                inputEl.classList.add('border-red-500');
            }
        }

        function showProfileModalAlert(msg, type) {
            const alertBox = document.getElementById('profileModalAlert');
            const alertText = document.getElementById('profileModalAlertText');
            const alertIcon = document.getElementById('profileModalAlertIcon');
            if (!alertBox) return;

            alertText.textContent = msg;
            alertBox.classList.remove('hidden');
            
            if (type === 'success') {
                alertBox.className = 'mb-4 p-3.5 rounded-2xl border text-xs font-bold flex items-center gap-2.5 bg-green-50 border-green-200 text-green-700';
                if (alertIcon) {
                    alertIcon.className = 'fa-solid fa-circle-check text-green-500 text-sm';
                }
            } else {
                alertBox.className = 'mb-4 p-3.5 rounded-2xl border text-xs font-bold flex items-center gap-2.5 bg-red-50 border-red-200 text-red-700';
                if (alertIcon) {
                    alertIcon.className = 'fa-solid fa-circle-exclamation text-red-500 text-sm';
                }
            }
        }

        function scrollToMenu() {
            const menuSection = document.getElementById('menuSection');
            if (menuSection) {
                menuSection.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Close modal ketika klik di luar modal
        document.getElementById('profileModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProfileModal();
            }
        });

        function addToCart(name, price, img, canteenId) {
            const existingItem = cart.find(item => item.name === name && item.canteen_id === canteenId);
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ name, price, img, canteen_id: canteenId, qty: 1 });
            }
            updateCartUI();
        }

        function updateCartUI() {
            const cartItems = document.getElementById('cartItems');
            const cartCount = document.getElementById('cartCount');
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('totalPrice');

            if (cart.length === 0) {
                cartItems.innerHTML = `
                    <div class="text-center py-20 opacity-20">
                        <i class="fa-solid fa-basket-shopping text-6xl mb-4"></i>
                        <p class="text-sm font-bold uppercase tracking-widest">Belum ada pesanan</p>
                    </div>`;
            } else {
                cartItems.innerHTML = '';
            }

            let subtotal = 0;
            cart.forEach((item, index) => {
                subtotal += item.price * item.qty;
                cartItems.innerHTML += `
                    <div class="flex items-center gap-5 group cart-item-anim">
                        <div class="w-20 h-20 bg-slate-100 rounded-[25px] overflow-hidden shrink-0 shadow-sm border border-slate-50">
                            <img src="${item.img}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <p class="text-[13px] font-bold text-midnight mb-1 leading-tight uppercase">${item.name}</p>
                            <p class="text-xs text-[#5B88B2] font-black italic">RP ${item.price.toLocaleString('id-ID')}</p>
                        </div>
                        <div class="flex flex-col items-center gap-2 bg-slate-50 rounded-2xl p-2">
                            <button onclick="changeQty(${index}, 1)" class="text-[#5B88B2] hover:scale-125 transition"><i class="fa-solid fa-plus text-[10px]"></i></button>
                            <span class="text-xs font-black text-midnight">${item.qty}</span>
                            <button onclick="changeQty(${index}, -1)" class="text-slate-300 hover:text-red-500 transition"><i class="fa-solid fa-minus text-[10px]"></i></button>
                        </div>
                    </div>`;
            });

            const discountPercent = getDiscountPercent();
            const discountValue = Math.round(subtotal * discountPercent / 100);
            const total = subtotal - discountValue;

            cartCount.innerText = `${cart.length} Items`;
            subtotalEl.innerText = `RP ${subtotal.toLocaleString('id-ID')}`;
            if (discountPercent > 0) {
                totalEl.innerHTML = `RP ${total.toLocaleString('id-ID')} <span class="block text-xs text-green-600 font-bold">(Disc ${discountPercent}% - RP ${discountValue.toLocaleString('id-ID')})</span>`;
            } else {
                totalEl.innerText = `RP ${total.toLocaleString('id-ID')}`;
            }

            // Sync ke localStorage agar topbar badge update
            try {
                localStorage.setItem('foodtyu_cart', JSON.stringify(cart));
                // Update topbar badge langsung
                const badge = document.getElementById('topbarCartBadge');
                if (badge) {
                    const totalQty = cart.reduce((s, i) => s + (i.qty || 1), 0);
                    badge.textContent = totalQty;
                    badge.style.display = totalQty > 0 ? '' : 'none';
                }
            } catch(e){}
        }

        function changeQty(index, delta) {
            cart[index].qty += delta;
            if (cart[index].qty <= 0) cart.splice(index, 1);
            updateCartUI();
        }

        function filterMenu(category) {
            const items = document.querySelectorAll('#foodContainer .menu-item');
            const buttons = document.querySelectorAll('.filter-btn');

            buttons.forEach(btn => {
                btn.classList.remove('bg-midnight', 'text-white', 'shadow-md');
                btn.classList.add('bg-white', 'text-slate-400');
            });

            const activeBtn = document.getElementById(`btn-${category}`);
            if (activeBtn) {
                activeBtn.classList.add('bg-midnight', 'text-white', 'shadow-md');
                activeBtn.classList.remove('bg-white', 'text-slate-400');
            }

            items.forEach(item => {
                const itemCategory = item.dataset.canton || item.dataset.category;
                item.style.display = (category === 'all' || itemCategory === category) ? 'block' : 'none';
            });
        }

        function toggleCart() {
            const cart = document.getElementById('cartSidebar');
            cart.classList.toggle('translate-x-full');
        }

        function isVoucherActive() {
            return sessionStorage.getItem('voucher_active') === 'true';
        }

        function getDiscountPercent() {
            return isVoucherActive() ? parseInt(sessionStorage.getItem('discount_amount') || '0', 10) : 0;
        }

        function updateProductPrices() {
            document.querySelectorAll('#foodContainer .menu-item').forEach(item => {
                const priceEl = item.querySelector('.product-price');
                const basePrice = Number(item.dataset.basePrice);
                if (!priceEl || !basePrice) return;

                if (isVoucherActive()) {
                    const discountValue = Math.round(basePrice * getDiscountPercent() / 100);
                    const discountedPrice = basePrice - discountValue;
                    priceEl.innerHTML = `<span class="line-through text-slate-400">RP ${basePrice.toLocaleString('id-ID')}</span> <span class="text-[#122C4F] font-black">RP ${discountedPrice.toLocaleString('id-ID')}</span>`;
                } else {
                    priceEl.innerHTML = `RP ${basePrice.toLocaleString('id-ID')}`;
                }
            });
        }

        // Daftar voucher yang tersedia
        const allVouchers = [
            { id: 1, name: 'Diskon 10% Minuman', discount: 10, category: 'beverage' },
            { id: 2, name: 'Diskon 15% Makanan Berat', discount: 15, category: 'heavy' },
            { id: 3, name: 'Diskon 20% Cemilan', discount: 20, category: 'snack' },
            { id: 4, name: 'Diskon 25% Semua Menu', discount: 25, category: 'all' },
            { id: 5, name: 'Diskon 12% Pembeli Setia', discount: 12, category: 'all' },
            { id: 6, name: 'Diskon 18% Akhir Bulan', discount: 18, category: 'all' },
            { id: 7, name: 'Diskon 15% Minuman Segar', discount: 15, category: 'beverage' }
        ];

        // Fungsi untuk mendapatkan voucher acak berdasarkan hari
        function getDailyVouchers() {
            const today = new Date().toDateString();
            const lastDate = localStorage.getItem('voucher_last_date');
            const lastVouchers = localStorage.getItem('daily_vouchers');
            
            if (lastDate === today && lastVouchers) {
                return JSON.parse(lastVouchers);
            }
            
            // Shuffle vouchers dan ambil 3 secara acak
            const shuffled = [...allVouchers].sort(() => Math.random() - 0.5);
            const dailyVouchers = shuffled.slice(0, 3);
            
            localStorage.setItem('voucher_last_date', today);
            localStorage.setItem('daily_vouchers', JSON.stringify(dailyVouchers));
            
            return dailyVouchers;
        }

        // Tampilkan daftar voucher di hero section
        function displayVoucherList() {
            const container = document.getElementById('voucherListSection');
            if (!container) return;
            
            const dailyVouchers = getDailyVouchers();
            const activeVoucher = sessionStorage.getItem('active_voucher_id');
            
            container.innerHTML = dailyVouchers.map(voucher => `
                <button onclick="selectVoucher(${voucher.id}, ${voucher.discount}, '${voucher.name}')" 
                        class="w-full px-4 py-3 rounded-lg font-bold transition-all text-left ${
                            activeVoucher == voucher.id 
                            ? 'bg-green-400 text-white shadow-lg' 
                            : 'bg-white/20 text-white hover:bg-white/30'
                        }">
                    ${voucher.name} (${voucher.discount}%)
                </button>
            `).join('');
        }

        // Fungsi untuk membuka modal voucher
        function openVoucherModal() {
            const modal = document.getElementById('voucherModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                displayDailyVouchersInModal();
            }
        }

        function closeVoucherModal() {
            const modal = document.getElementById('voucherModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function displayDailyVouchersInModal() {
            const container = document.getElementById('modalVoucherList');
            if (!container) return;
            
            const dailyVouchers = getDailyVouchers();
            const activeVoucher = sessionStorage.getItem('active_voucher_id');
            
            container.innerHTML = dailyVouchers.map(voucher => `
                <div class="p-4 border-2 rounded-2xl cursor-pointer transition-all ${
                    activeVoucher == voucher.id 
                    ? 'border-green-400 bg-green-50' 
                    : 'border-slate-100 hover:border-blue-400'
                }" onclick="selectVoucher(${voucher.id}, ${voucher.discount}, '${voucher.name}')">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-bold text-[#122C4F]">${voucher.name}</h4>
                            <p class="text-sm text-slate-500">Diskon ${voucher.discount}%</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black text-blue-600">${voucher.discount}%</span>
                            ${activeVoucher == voucher.id ? '<span class="text-green-600 font-bold">✓ Aktif</span>' : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function selectVoucher(voucherId, discount, voucherName) {
            sessionStorage.setItem('voucher_active', 'true');
            sessionStorage.setItem('active_voucher_id', voucherId);
            sessionStorage.setItem('discount_amount', discount);
            sessionStorage.setItem('voucher_name', voucherName);
            
            displayVoucherList();
            displayDailyVouchersInModal();
            updateProductPrices();
            updateCartUI();
            
            alert(`Voucher "${voucherName}" berhasil diaktifkan! Diskon ${discount}% untuk semua menu.`);
        }

        function refreshVoucherUI() {
            displayVoucherList();
            if (isVoucherActive()) {
                updateProductPrices();
            }
        }

        function openPaymentModal() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }
            // Update total di modal
            const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
            const disc = Math.round(subtotal * getDiscountPercent() / 100);
            const total = subtotal - disc;
            const el = document.getElementById('modalTotalDisplay');
            if (el) el.textContent = 'RP ' + total.toLocaleString('id-ID');

            toggleCart(); // tutup sidebar cart dulu
            const modal = document.getElementById('paymentModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('paymentLoadingState')?.classList.add('hidden');
        }

        // Klik metode → langsung proses
        function selectAndPay(type) {
            selectedPaymentType = type;
            processFinalPayment();
        }

        let selectedPaymentType = 'qris';

        function showPaymentDetail(type) {
            document.getElementById('method-options').classList.add('hidden');
            const container = document.getElementById('payment-detail-container');
            const content = document.getElementById('detail-content');
            container.classList.remove('hidden');
            selectedPaymentType = type;

            if (type === 'qris') {
                // QRIS Payment
                content.innerHTML = `
                    <p class="font-bold text-[#122C4F] mb-4 uppercase text-[10px] tracking-widest">Scan QRIS Berikut</p>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=FoodTYUPay" class="mx-auto rounded-xl shadow-md mb-3 border-4 border-white">
                    <p class="text-[10px] text-slate-400 font-medium">Menerima Dana, OVO, GoPay, dan ShopeePay</p>
                `;
            } else if (type === 'ewallet') {
                // E-Wallet Payment - Sub options
                content.innerHTML = `
                    <p class="font-bold text-[#122C4F] mb-4 uppercase text-[10px] tracking-widest">Pilih E-Wallet Kamu</p>
                    <div class="space-y-3">
                        <div onclick="selectEWallet('shopee')" class="p-4 bg-white border-2 border-slate-100 rounded-xl flex items-center gap-3 hover:border-[#FF7520] cursor-pointer transition group">
                            <i class="fa-solid fa-cart-shopping text-[#FF7520] text-2xl"></i>
                            <div class="text-left flex-1">
                                <p class="font-bold text-slate-700">Shopee Pay</p>
                                <p class="text-xs text-slate-400">Pembayaran instan</p>
                            </div>
                            <i class="fa-solid fa-check text-slate-300 group-hover:text-[#FF7520]"></i>
                        </div>
                        <div onclick="selectEWallet('gopay')" class="p-4 bg-white border-2 border-slate-100 rounded-xl flex items-center gap-3 hover:border-[#00B4D8] cursor-pointer transition group">
                            <i class="fa-solid fa-mobile text-[#00B4D8] text-2xl"></i>
                            <div class="text-left flex-1">
                                <p class="font-bold text-slate-700">GoPay</p>
                                <p class="text-xs text-slate-400">Dari aplikasi GO-JEK</p>
                            </div>
                            <i class="fa-solid fa-check text-slate-300 group-hover:text-[#00B4D8]"></i>
                        </div>
                        <div onclick="selectEWallet('dana')" class="p-4 bg-white border-2 border-slate-100 rounded-xl flex items-center gap-3 hover:border-[#0055FF] cursor-pointer transition group">
                            <i class="fa-solid fa-wallet text-[#0055FF] text-2xl"></i>
                            <div class="text-left flex-1">
                                <p class="font-bold text-slate-700">DANA</p>
                                <p class="text-xs text-slate-400">Dompet digital Indonesia</p>
                            </div>
                            <i class="fa-solid fa-check text-slate-300 group-hover:text-[#0055FF]"></i>
                        </div>
                    </div>
                `;
            } else if (type === 'saldo') {
                // Saldo TyU-Pay Payment - Show balance
                const userBalance = {{ Auth::user()->balance ?? 0 }};
                const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
                const discountPercent = getDiscountPercent();
                const discountValue = Math.round(subtotal * discountPercent / 100);
                const totalPaid = subtotal - discountValue;
                const isEnoughBalance = userBalance >= totalPaid;
                
                content.innerHTML = `
                    <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-2xl p-6 text-white mb-4">
                        <p class="text-xs font-medium opacity-80 mb-1">Saldo TyU-Pay Kamu</p>
                        <h4 class="text-3xl font-black">RP ${userBalance.toLocaleString('id-ID')}</h4>
                    </div>
                    <div class="space-y-3 text-left">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600">Total Pesanan:</span>
                            <span class="font-bold text-slate-800">RP ${totalPaid.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                            <span class="text-sm font-bold ${isEnoughBalance ? 'text-green-600' : 'text-red-600'}">
                                Sisa Saldo:
                            </span>
                            <span class="text-xl font-black ${isEnoughBalance ? 'text-green-600' : 'text-red-600'}">
                                RP ${(userBalance - totalPaid).toLocaleString('id-ID')}
                            </span>
                        </div>
                        ${!isEnoughBalance ? `
                            <div class="bg-red-50 border border-red-300 rounded-xl p-3 mt-3">
                                <p class="text-xs text-red-700 font-bold">
                                    ⚠️ Saldo tidak cukup! Kurang RP ${(totalPaid - userBalance).toLocaleString('id-ID')}
                                </p>
                            </div>
                        ` : ''}
                    </div>
                `;
            }
        }

        function selectEWallet(wallet) {
            selectedPaymentType = 'ewallet_' + wallet;
            const containers = document.querySelectorAll('#detail-content > div:last-child > div');
            containers.forEach(el => {
                el.classList.remove('border-[#FF7520]', 'border-[#00B4D8]', 'border-[#0055FF]');
                el.classList.add('border-slate-100');
            });
            
            event.target.closest('.p-4')?.classList.add(
                wallet === 'shopee' ? 'border-[#FF7520]' : 
                wallet === 'gopay' ? 'border-[#00B4D8]' : 
                'border-[#0055FF]'
            );
        }

        function backToMethods() {
            document.getElementById('method-options').classList.remove('hidden');
            document.getElementById('payment-detail-container').classList.add('hidden');
        }

        function processFinalPayment() {
            // Validasi lokasi jika pilih antar
            if (deliveryType === 'deliver') {
                const area = document.getElementById('pickupArea')?.value;
                if (!area) {
                    alert('⚠️ Pilih dulu area/gedung tujuan pengiriman!');
                    closeModal();
                    toggleCart();
                    document.getElementById('pickupArea').focus();
                    return;
                }
            }

            const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
            const discountPercent = getDiscountPercent();
            const discountValue = Math.round(subtotal * discountPercent / 100);
            const totalPaid = subtotal - discountValue;

            // GROUP CART BY CANTEEN
            const cartByCanteen = {};
            cart.forEach(item => {
                const cid = item.canteen_id;
                if (!cartByCanteen[cid]) cartByCanteen[cid] = { items: [], total: 0 };
                const itemTotal = item.price * item.qty;
                cartByCanteen[cid].items.push({ name: item.name, price: item.price, qty: item.qty, subtotal: itemTotal });
                cartByCanteen[cid].total += itemTotal;
            });

            const voucherName = sessionStorage.getItem('voucher_name') || 'Tanpa Voucher';
            let paymentMethodNote = selectedPaymentType;
            if (selectedPaymentType.includes('ewallet_shopee')) paymentMethodNote = 'E-Wallet (Shopee Pay)';
            else if (selectedPaymentType.includes('ewallet_gopay'))  paymentMethodNote = 'E-Wallet (GoPay)';
            else if (selectedPaymentType.includes('ewallet_dana'))   paymentMethodNote = 'E-Wallet (DANA)';
            else if (selectedPaymentType === 'qris')   paymentMethodNote = 'QRIS';
            else if (selectedPaymentType === 'saldo')  paymentMethodNote = 'Saldo TyU-Pay';

            const notesStr = `${getLokasiNote()} | Diskon: ${discountPercent}% (${voucherName})`;

            // ===== SALDO TYU-PAY: pakai flow lama via /api/orders =====
            if (selectedPaymentType === 'saldo') {
                const userBalance = {{ Auth::user()->balance ?? 0 }};
                if (userBalance < totalPaid) {
                    alert('⚠️ Saldo tidak cukup!\n\nSaldo kamu: Rp ' + userBalance.toLocaleString('id-ID') + '\nTotal: Rp ' + totalPaid.toLocaleString('id-ID'));
                    return;
                }
                fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        cart_by_canteen: cartByCanteen,
                        grand_total: totalPaid,
                        payment_method: 'Saldo TyU-Pay',
                        notes: notesStr,
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        closeModal();
                        showReceiptModal(data.orders, totalPaid, 'Saldo TyU-Pay');
                        cart = [];
                        localStorage.removeItem('cart');
                        sessionStorage.removeItem('voucher_code');
                        sessionStorage.removeItem('voucher_name');
                    } else {
                        alert('❌ ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(() => alert('❌ Koneksi bermasalah, coba lagi.'));
                return;
            }

            // ===== QRIS / E-WALLET: pakai Midtrans Snap =====
            if (cart.length === 0) { alert('Keranjang kosong!'); return; }

            // Tampilkan loading di modal
            const loadingEl = document.getElementById('paymentLoadingState');
            if (loadingEl) loadingEl.classList.remove('hidden');
            const confirmBtn = document.getElementById('confirm-payment-btn');
            const originalText = confirmBtn?.innerHTML || '';
            if (confirmBtn) { confirmBtn.innerHTML = '...'; confirmBtn.disabled = true; }

            fetch('/payment/snap-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    cart_by_canteen: cartByCanteen,
                    grand_total: totalPaid,
                    payment_method: paymentMethodNote,
                    notes: notesStr,
                })
            })
            .then(r => r.json())
            .then(data => {
                if (loadingEl) loadingEl.classList.add('hidden');
                if (confirmBtn) { confirmBtn.innerHTML = originalText; confirmBtn.disabled = false; }

                if (!data.success) {
                    alert('❌ Gagal: ' + data.message);
                    return;
                }

                closeModal();

                // Buka Midtrans Snap popup
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        cart = [];
                        sessionStorage.removeItem('voucher_name');
                        updateCartUI();
                        window.location.href = '/payment/finish?order_id=' + result.order_id + '&transaction_status=' + result.transaction_status;
                    },
                    onPending: function(result) {
                        window.location.href = '/payment/finish?order_id=' + result.order_id + '&transaction_status=pending';
                    },
                    onError: function(result) {
                        alert('❌ Pembayaran gagal: ' + (result.status_message || 'Terjadi kesalahan'));
                    },
                    onClose: function() {
                        // User tutup popup tanpa bayar
                        console.log('Midtrans popup ditutup');
                    }
                });
            })
            .catch(err => {
                if (loadingEl) loadingEl.classList.add('hidden');
                if (confirmBtn) { confirmBtn.innerHTML = originalText; confirmBtn.disabled = false; }
                console.error(err);
                alert('❌ Koneksi bermasalah, coba lagi.');
            });
        }

        function processPayment() {
            processFinalPayment();
        }

        function openKantinModal() {
            document.getElementById('kantinModal').classList.remove('hidden');
            document.getElementById('kantinModal').classList.add('flex');
        }

        function closeKantinModal() {
            document.getElementById('kantinModal').classList.add('hidden');
            document.getElementById('kantinModal').classList.remove('flex');
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            const searchPopup = document.getElementById('searchPopup');
            searchPopup.innerHTML = '';
            
            if (query.length > 0) {
                searchPopup.classList.remove('hidden');
                let found = false;
                
                document.querySelectorAll('.menu-item').forEach(item => {
                    const name = item.getAttribute('data-name').toLowerCase();
                    if (name.includes(query)) {
                        found = true;
                        const row = document.createElement('div');
                        row.className = "p-4 hover:bg-blue-50 cursor-pointer border-b border-gray-50 last:border-none flex items-center";
                        row.innerHTML = `<span class="font-medium text-gray-700">${item.getAttribute('data-name')}</span>`;
                        
                        row.onclick = () => {
                            item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            item.classList.add('ring-4', 'ring-orange-400');
                            searchPopup.classList.add('hidden');
                            setTimeout(() => item.classList.remove('ring-4', 'ring-orange-400'), 2000);
                        };
                        searchPopup.appendChild(row);
                    }
                });
                
                if (!found) {
                    searchPopup.innerHTML = '<div class="p-4 text-gray-400">Makanan tidak ditemukan...</div>';
                }
            } else {
                searchPopup.classList.add('hidden');
            }
        });
        
        document.addEventListener('click', (e) => {
            const searchInput = document.getElementById('searchInput');
            const searchPopup = document.getElementById('searchPopup');
            if (!searchInput.contains(e.target) && !searchPopup.contains(e.target)) {
                searchPopup.classList.add('hidden');
            }
        });

        refreshVoucherUI();

        // ==================== RECEIPT MODAL ====================
        function showReceiptModal(orders, total, paymentMethod) {
            const html = `
                <div id="receiptModal" class="fixed inset-0 bg-black/70 flex items-center justify-center p-4 z-50">
                    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-auto">
                        <!-- Header -->
                        <div class="sticky top-0 bg-gradient-to-r from-green-500 to-green-600 p-6 text-white text-center">
                            <i class="fas fa-check-circle text-4xl mb-2"></i>
                            <h2 class="text-3xl font-black">Pesanan Berhasil!</h2>
                            <p class="text-green-100 mt-1">Pesanan kamu telah dikirim ke kantin</p>
                        </div>

                        <!-- Content -->
                        <div class="p-8">
                            <!-- Orders Detail -->
                            <div class="space-y-4 mb-6">
                                ${orders.map(order => `
                                    <div class="bg-slate-50 p-4 rounded-xl border-l-4 border-orange-500">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-black text-lg text-slate-900">${order.order_number}</p>
                                                <p class="text-sm text-slate-600"><i class="fas fa-store"></i> ${order.canteen?.name || 'Kantin'}</p>
                                            </div>
                                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-xs font-bold">Rp ${new Intl.NumberFormat('id-ID').format(order.total_amount)}</span>
                                        </div>
                                        <div class="text-xs text-slate-600 space-y-1">
                                            ${order.notes ? `<p><i class="fas fa-sticky-note"></i> ${order.notes}</p>` : ''}
                                            <p><i class="fas fa-clock"></i> ${new Date(order.created_at).toLocaleString('id-ID')}</p>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>

                            <!-- Total Summary -->
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-xl mb-6 border border-blue-100">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-slate-600">Total Pesanan:</span>
                                    <span class="font-bold text-lg text-slate-900">Rp ${new Intl.NumberFormat('id-ID').format(total)}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-600">Metode Pembayaran:</span>
                                    <span class="font-semibold text-slate-900">${paymentMethod}</span>
                                </div>
                            </div>

                            <!-- Status Info -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                                <p class="text-sm text-blue-900">
                                    <i class="fas fa-info-circle"></i>
                                    Pesanan akan diproses dalam waktu <strong>5-15 menit</strong>. Pantau status di halaman <strong>Pesanan Aktif</strong>.
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <button onclick="location.href='/orders/active'" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                    <i class="fas fa-clock"></i> Lihat Pesanan Aktif
                                </button>
                                <button onclick="document.getElementById('receiptModal').remove(); location.reload();" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-bold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                    <i class="fas fa-home"></i> Kembali ke Home
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            const modalDiv = document.createElement('div');
            modalDiv.innerHTML = html;
            document.body.appendChild(modalDiv.firstElementChild);
        }

        // ==================== MENU DETAIL MODAL (HOME) ====================
        function showMenuDetailFromHome(menu) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50';
            modal.id = 'homeMenuDetailModal';
            
            let categoryLabel = '';
            if (menu.category === 'heavy') categoryLabel = 'Makanan Berat';
            else if (menu.category === 'beverage') categoryLabel = 'Minuman';
            else if (menu.category === 'snack') categoryLabel = 'Snack';

            modal.innerHTML = `
                <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-auto">
                    <div class="flex items-center justify-between p-4 border-b border-slate-200 sticky top-0 bg-white z-10">
                        <h3 class="font-bold text-lg text-slate-900">Detail Menu</h3>
                        <button onclick="document.getElementById('homeMenuDetailModal').remove()" class="text-slate-500 hover:text-slate-700 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4 h-48 bg-slate-200 rounded-xl overflow-hidden">
                            <img src="${menu.image_url}" alt="${menu.name}" class="w-full h-full object-cover">
                        </div>

                        <span class="inline-block bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold mb-2">
                            🏪 ${menu.canteen?.name || 'Kantin'}
                        </span>
                        
                        <h2 class="text-2xl font-black text-slate-900 mb-2">${menu.name}</h2>
                        <p class="text-3xl font-black text-orange-500 mb-4">Rp ${new Intl.NumberFormat('id-ID').format(menu.price)}</p>

                        ${menu.description ? `
                            <div class="bg-slate-50 p-4 rounded-xl mb-4">
                                <h4 class="font-bold text-slate-900 mb-2">Deskripsi</h4>
                                <p class="text-slate-700 text-sm leading-relaxed">${menu.description}</p>
                            </div>
                        ` : ''}

                        ${menu.rating ? `
                            <div class="bg-yellow-50 p-4 rounded-xl mb-4 border border-yellow-200">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-star text-yellow-400"></i>
                                    <span class="font-bold text-slate-900">${menu.rating}/5</span>
                                </div>
                            </div>
                        ` : ''}

                        <div class="flex gap-2">
                            <button onclick="document.getElementById('homeMenuDetailModal').remove()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-bold py-3 px-4 rounded-xl transition">
                                Tutup
                            </button>
                            <button onclick="addToCart('${menu.name}', ${menu.price}, '${menu.image_url}', ${menu.canteen_id}); document.getElementById('homeMenuDetailModal').remove(); toggleCart();" 
                                    class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                <i class="fas fa-cart-plus"></i> Pesan
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Close on outside click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }
    </script>

    <button onclick="toggleCart()" class="fixed bottom-8 right-8 z-50 bg-[#122C4F] text-white w-16 h-16 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-all">
        <i class="fa-solid fa-cart-shopping text-xl"></i>
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-6 h-6 rounded-full flex items-center justify-center border-4 border-[#FBF9E4]">1</span>
    </button>

    <script>
    const searchInput = document.getElementById('searchInput');
    const foodCards = document.querySelectorAll('.food-card');
    const foodContainer = document.getElementById('foodContainer');
    const recommendationSection = document.getElementById('recommendationSection');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let hasResults = false;

        foodCards.forEach(card => {
            const foodName = card.getAttribute('data-name').toLowerCase();
            
            if (foodName.includes(searchTerm)) {
                card.style.display = 'block'; // Tampilkan jika cocok
                hasResults = true;
            } else {
                card.style.display = 'none'; // Sembunyikan jika tidak cocok
            }
        });

        // Logika untuk menampilkan rekomendasi jika user sedang mengetik
        if (searchTerm.length > 0) {
            recommendationSection.classList.remove('hidden');
            // Menambahkan animasi halus agar konten terasa "naik ke atas"
            foodContainer.style.transition = "all 0.3s ease";
        } else {
            recommendationSection.classList.add('hidden');
        }
    });
</script>
@endsection
