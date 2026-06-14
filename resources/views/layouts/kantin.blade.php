<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Food-TYU Kantin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-foodtyu.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #F0F4F8; }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0f1f3d 0%, #122C4F 60%, #1a3a5c 100%);
            position: fixed; left: 0; top: 0; bottom: 0;
            display: flex; flex-direction: column;
            z-index: 50;
            box-shadow: 4px 0 24px rgba(0,0,0,0.18);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        .sidebar-logo { padding: 24px 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.07); }

        .nav-section-label {
            font-size: 10px; font-weight: 800;
            letter-spacing: 0.14em; color: rgba(255,255,255,0.28);
            padding: 18px 24px 6px; text-transform: uppercase;
        }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; margin: 2px 10px;
            border-radius: 14px;
            color: rgba(255,255,255,0.55);
            font-size: 13.5px; font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            position: relative;
        }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: white; transform: translateX(2px); }
        .nav-item.active {
            background: linear-gradient(135deg, #2d6a8f, #1a3a5c);
            color: white; font-weight: 800;
            box-shadow: 0 4px 16px rgba(45,106,143,0.45);
        }
        .nav-icon {
            width: 34px; height: 34px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; background: rgba(255,255,255,0.07);
            flex-shrink: 0; transition: all .2s;
        }
        .nav-item.active .nav-icon   { background: rgba(255,255,255,0.18); }
        .nav-item:hover  .nav-icon   { background: rgba(255,255,255,0.12); }
        .nav-badge {
            margin-left: auto;
            background: #EF4444; color: white;
            font-size: 10px; font-weight: 800;
            padding: 2px 7px; border-radius: 99px;
            animation: pulse-badge 2s ease-in-out infinite;
        }
        @keyframes pulse-badge { 0%,100%{transform:scale(1)} 50%{transform:scale(1.12)} }

        .sidebar-bottom { margin-top: auto; padding: 14px 14px 22px; border-top: 1px solid rgba(255,255,255,0.07); }
        .saldo-card {
            background: linear-gradient(135deg, rgba(45,106,143,0.35), rgba(26,58,92,0.5));
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px; padding: 14px 16px; margin-bottom: 10px;
        }
        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 14px;
            background: rgba(255,255,255,0.05);
            cursor: pointer; transition: background .2s;
        }
        .user-card:hover { background: rgba(255,255,255,0.09); }
        .avatar {
            width: 34px; height: 34px; border-radius: 10px;
            background: linear-gradient(135deg, #2d6a8f, #5D89B3);
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; color: white; font-size: 13px; flex-shrink: 0;
        }

        /* MAIN */
        .main-content { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: white; border-bottom: 1px solid #E8EDF2;
            padding: 0 28px; height: 62px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }
        .page-content { padding: 28px; flex: 1; }

        /* Mobile */
        .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:40; }
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%);}
            .sidebar.open{transform:translateX(0);}
            .sidebar-overlay.show{display:block;}
            .main-content{margin-left:0;}
        }

        /* Topbar search */
        .search-input {
            padding: 8px 14px 8px 38px;
            border-radius: 12px;
            border: 1.5px solid #E5E7EB;
            background: #F9FAFB;
            font-size: 13px; outline: none; width: 240px;
            transition: all .2s;
        }
        .search-input:focus { border-color: #2d6a8f; background: white; box-shadow: 0 0 0 3px rgba(45,106,143,.1); }

        /* Content card style helper */
        .hh-card { background: white; border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }

        @yield('extra-css')
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div style="display:flex;align-items:center;gap:12px;">
            <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Logo" style="width:38px;height:38px;object-fit:contain;border-radius:10px;">
            <div>
                <p style="color:white;font-weight:900;font-size:19px;font-style:italic;line-height:1;">Food-TYU.</p>
                <p style="font-size:9px;font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.18em;text-transform:uppercase;margin-top:2px;">DAPUR KANTIN</p>
            </div>
        </div>
    </div>

    <nav style="flex:1;overflow-y:auto;padding:6px 0;">
        <div class="nav-section-label">Menu Utama</div>

        <a href="{{ route('canteen.dashboard') }}" class="nav-item {{ request()->routeIs('canteen.dashboard') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-tachometer-alt"></i></div>
            Dashboard
        </a>

        <a href="{{ route('canteen.orders.index') }}" class="nav-item {{ request()->routeIs('canteen.orders*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div>
            Kelola Pesanan
            @php
                $sidebarPending = 0;
                try {
                    $sidebarCanteen = auth()->user()->canteen;
                    if($sidebarCanteen) $sidebarPending = $sidebarCanteen->orders()->where('status','pending')->count();
                } catch(\Exception $e) {}
            @endphp
            @if($sidebarPending > 0)
                <span class="nav-badge">{{ $sidebarPending }}</span>
            @endif
        </a>

        <a href="{{ route('canteen.menus.index') }}" class="nav-item {{ request()->routeIs('canteen.menus*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-utensils"></i></div>
            Menu Kantin
        </a>

        <div class="nav-section-label">Keuangan</div>

        <a href="{{ route('canteen.vouchers.index') }}" class="nav-item {{ request()->routeIs('canteen.vouchers*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-ticket-alt"></i></div>
            Voucher
        </a>

        <a href="{{ route('canteen.sales') }}" class="nav-item {{ request()->routeIs('canteen.sales') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-chart-bar"></i></div>
            Penjualan
        </a>

        <a href="{{ route('canteen.payments') }}" class="nav-item {{ request()->routeIs('canteen.payments') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-credit-card"></i></div>
            Pembayaran
        </a>
        <a href="{{ route('canteen.export') }}" class="nav-item {{ request()->routeIs('canteen.export*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-file-excel"></i></div>
            Export Laporan
        </a>

        <div class="nav-section-label">Lainnya</div>

        {{-- Gunakan form POST ke switch.view agar session viewing_as_buyer ter-set --}}
        <form action="{{ route('switch.view') }}" method="POST" id="switchToBuyerForm" style="margin:0;">
            @csrf
            <input type="hidden" name="target" value="buyer">
            <button type="submit" class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;color:rgba(234,216,177,0.85);text-align:left;">
                <div class="nav-icon" style="color:rgba(234,216,177,0.9);"><i class="fas fa-eye"></i></div>
                Lihat sebagai Pembeli
            </button>
        </form>
    </nav>

    <!-- Bottom -->
    <div class="sidebar-bottom">
        @php $sidebarCanteenBalance = 0; try { $sidebarCanteenBalance = auth()->user()->canteen->balance ?? 0; } catch(\Exception $e){} @endphp
        <div class="saldo-card">
            <p style="font-size:10px;font-weight:700;color:rgba(255,255,255,0.38);letter-spacing:0.1em;text-transform:uppercase;">Saldo Kantin</p>
            <p style="color:white;font-weight:900;font-size:20px;margin-top:4px;">Rp {{ number_format($sidebarCanteenBalance, 0, ',', '.') }}</p>
        </div>
        <div class="user-card" onclick="toggleProfilePanel()">
            <div class="avatar" id="kantinAvatarArea" style="overflow:hidden;padding:0;">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/avatars/'.auth()->user()->avatar) }}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <p style="color:white;font-weight:700;font-size:13px;line-height:1.2;" class="truncate">{{ auth()->user()->name ?? 'Pemilik Kantin' }}</p>
                <p style="font-size:11px;color:rgba(255,255,255,0.4);">🍳 Mitra Kantin</p>
            </div>
            <i class="fas fa-chevron-up text-xs" style="color:rgba(255,255,255,0.3);transition:transform .2s;" id="sidebarChevron"></i>
        </div>
        <!-- Profile panel -->
        <div id="sidebarProfilePanel" style="display:none;background:rgba(255,255,255,0.05);border-radius:12px;padding:12px 14px;margin-top:8px;">

            {{-- Avatar upload di sidebar --}}
            <div style="text-align:center;margin-bottom:12px;">
                <div style="position:relative;display:inline-block;" onclick="document.getElementById('kantinAvatarInput').click()" class="cursor-pointer group">
                    <div style="width:60px;height:60px;border-radius:50%;overflow:hidden;border:3px solid rgba(255,255,255,0.2);margin:0 auto;" id="kantinAvatarBig">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/avatars/'.auth()->user()->avatar) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,#2d6a8f,#5B88B2);display:flex;align-items:center;justify-content:center;color:white;font-size:22px;font-weight:900;">
                                {{ strtoupper(substr(auth()->user()->name ?? 'K', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div style="position:absolute;bottom:0;right:0;background:#F97316;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid #122C4F;">
                        <i class="fas fa-camera" style="font-size:8px;color:white;"></i>
                    </div>
                </div>
                <p style="font-size:10px;color:rgba(255,255,255,0.4);margin-top:4px;">Ketuk untuk ganti foto</p>
                <div id="kantinAvatarMsg" style="display:none;font-size:10px;font-weight:700;margin-top:4px;padding:3px 8px;border-radius:6px;"></div>
                <input type="file" id="kantinAvatarInput" accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none;" onchange="uploadKantinAvatar(this)">
            </div>

            <p style="font-size:10px;color:rgba(255,255,255,0.35);text-transform:uppercase;font-weight:700;">Email</p>
            <p style="font-size:12px;color:rgba(255,255,255,0.75);font-weight:600;margin-bottom:8px;" class="truncate">{{ auth()->user()->email ?? '-' }}</p>
            <p style="font-size:10px;color:rgba(255,255,255,0.35);text-transform:uppercase;font-weight:700;">No. HP</p>
            <p style="font-size:12px;color:rgba(255,255,255,0.75);font-weight:600;margin-bottom:10px;">{{ auth()->user()->phone ?? '-' }}</p>

            {{-- Lihat sebagai Pembeli --}}
            <form action="{{ route('switch.view') }}" method="POST" style="margin-bottom:8px;">
                @csrf
                <input type="hidden" name="target" value="buyer">
                <button type="submit" style="width:100%;background:rgba(251,191,36,0.15);color:rgba(251,191,36,0.9);border:1.5px solid rgba(251,191,36,0.3);border-radius:10px;padding:8px;font-weight:700;font-size:12px;cursor:pointer;transition:background .2s;display:flex;align-items:center;justify-content:center;gap:6px;"
                    onmouseover="this.style.background='rgba(251,191,36,0.25)'" onmouseout="this.style.background='rgba(251,191,36,0.15)'">
                    <i class="fas fa-eye"></i> Lihat sebagai Pembeli
                </button>
            </form>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="width:100%;background:rgba(239,68,68,0.15);color:rgba(239,68,68,0.9);border:none;border-radius:10px;padding:8px;font-weight:700;font-size:12px;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='rgba(239,68,68,0.25)'" onmouseout="this.style.background='rgba(239,68,68,0.15)'">
                    <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- MAIN -->
<div class="main-content">
    <!-- Topbar -->
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:16px;">
            <button onclick="toggleSidebar()" style="display:none;padding:8px;border-radius:10px;border:none;background:transparent;cursor:pointer;" id="mobileMenuBtn">
                <i class="fas fa-bars text-slate-600"></i>
            </button>
            <!-- Page title + breadcrumb -->
            <div>
                <p style="font-size:11px;color:#9CA3AF;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">
                    Kantin / @yield('page-title', 'Dashboard')
                </p>
                <h1 style="font-size:17px;font-weight:800;color:#0f1f3d;line-height:1.2;">@yield('page-title', 'Dashboard')</h1>
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:12px;">
            <!-- Notif - buka panel pesanan pending (Dropdown) -->
            <div style="position:relative;">
                <button onclick="toggleNotifDropdown(event)" id="canteenNotifBtn"
                    style="position:relative;padding:8px;border-radius:12px;border:1.5px solid #E5E7EB;background:white;cursor:pointer;transition:all .2s;"
                    onmouseover="this.style.borderColor='#2d6a8f';this.style.background='#EFF6FF'"
                    onmouseout="this.style.borderColor='#E5E7EB';this.style.background='white'"
                    title="Lihat pesanan masuk">
                    <i class="fas fa-bell text-slate-500 text-sm"></i>
                    @if(isset($sidebarPending) && $sidebarPending > 0)
                    <span style="position:absolute;top:5px;right:5px;min-width:16px;height:16px;border-radius:99px;background:#EF4444;border:2px solid white;font-size:9px;font-weight:900;color:white;display:flex;align-items:center;justify-content:center;padding:0 3px;">{{ $sidebarPending }}</span>
                    @endif
                </button>

                <!-- Notification Dropdown Panel -->
                <div id="canteenNotifDropdown" style="display: none; position: absolute; right: 0; mt: 8px; width: 320px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid #E2E8F0; padding: 12px 0; z-index: 1000; margin-top: 10px;">
                    <div style="padding: 0 16px 8px 16px; border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 900; color: #1e293b; font-size: 13px;">Pesanan Baru</span>
                        <span id="canteenNotifCountText" style="font-size: 10px; background: #FEE2E2; color: #EF4444; font-weight: 800; padding: 2px 8px; border-radius: 99px;">{{ $sidebarPending ?? 0 }} Pending</span>
                    </div>
                    <div id="canteenNotifList" style="max-height: 260px; overflow-y: auto;">
                        <div style="padding: 24px 16px; text-align: center; color: #94A3B8; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-shopping-basket" style="font-size: 20px; margin-bottom: 6px; display: block; opacity: 0.3;"></i>
                            Tidak ada pesanan pending
                        </div>
                    </div>
                    <div style="padding: 8px 12px 0 12px; border-top: 1px solid #F1F5F9; text-align: center;">
                        <a href="{{ route('canteen.orders.index') }}" style="display: block; width: 100%; background: #F8FAFC; border-radius: 10px; font-size: 11px; font-weight: 850; color: #2d6a8f; text-decoration: none; padding: 8px 0; transition: all 0.2s;" onmouseover="this.style.background='#EFF6FF'" onmouseout="this.style.background='#F8FAFC'">
                            Lihat Semua Pesanan
                        </a>
                    </div>
                </div>
            </div>
            <!-- CTA -->
            <a href="{{ route('canteen.menus.create') }}"
               style="background:linear-gradient(135deg,#1a3a5c,#2d6a8f);color:white;font-weight:800;font-size:12px;padding:9px 18px;border-radius:12px;text-decoration:none;display:flex;align-items:center;gap:6px;box-shadow:0 4px 14px rgba(45,106,143,0.35);transition:all .2s;"
               onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 18px rgba(45,106,143,0.45)'"
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(45,106,143,0.35)'">
                <i class="fas fa-plus text-xs"></i> Tambah Menu
            </a>
        </div>
    </header>

    <div class="page-content">
        @yield('content')
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
function toggleProfilePanel() {
    const panel = document.getElementById('sidebarProfilePanel');
    const chev = document.getElementById('sidebarChevron');
    const shown = panel.style.display !== 'none';
    panel.style.display = shown ? 'none' : 'block';
    chev.style.transform = shown ? 'rotate(0deg)' : 'rotate(180deg)';
}
// Show hamburger on mobile
if(window.innerWidth <= 768) document.getElementById('mobileMenuBtn').style.display = 'block';
window.addEventListener('resize', () => {
    document.getElementById('mobileMenuBtn').style.display = window.innerWidth <= 768 ? 'block' : 'none';
});

// ===== AVATAR UPLOAD IBU KANTIN =====
function uploadKantinAvatar(input) {
    if (!input.files?.[0]) return;
    const file = input.files[0];
    if (file.size > 3 * 1024 * 1024) {
        showKantinAvatarMsg('❌ Maks 3MB!', 'error'); return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        const big = document.getElementById('kantinAvatarBig');
        if (big) big.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        const small = document.getElementById('kantinAvatarArea');
        if (small) small.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">`;
    };
    reader.readAsDataURL(file);
    showKantinAvatarMsg('⏳ Upload...', 'info');
    const fd = new FormData();
    fd.append('avatar', file);
    fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
    fetch('/profile/upload-avatar-ajax', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => showKantinAvatarMsg(data.success ? '✅ Berhasil!' : '❌ Gagal', data.success ? 'success' : 'error'))
        .catch(() => showKantinAvatarMsg('❌ Error koneksi', 'error'));
}

function showKantinAvatarMsg(text, type) {
    const el = document.getElementById('kantinAvatarMsg');
    if (!el) return;
    el.textContent = text;
    el.style.background = type === 'success' ? 'rgba(34,197,94,0.2)' : type === 'error' ? 'rgba(239,68,68,0.2)' : 'rgba(59,130,246,0.2)';
    el.style.color = type === 'success' ? '#4ade80' : type === 'error' ? '#f87171' : '#93c5fd';
    el.style.display = 'block';
    if (type === 'success') setTimeout(() => el.style.display = 'none', 2500);
}

// ===== REAL-TIME ORDER NOTIFICATION SYSTEM (Ibu Kantin) =====
let lastLatestPendingId = null;
let isFirstCheck = true;

function toggleNotifDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('canteenNotifDropdown');
    if (!dropdown) return;
    
    const isHidden = dropdown.style.display === 'none';
    dropdown.style.display = isHidden ? 'block' : 'none';
}

// Close dropdown when clicking outside
window.addEventListener('click', function(event) {
    const dropdown = document.getElementById('canteenNotifDropdown');
    const btn = document.getElementById('canteenNotifBtn');
    
    if (dropdown && dropdown.style.display !== 'none') {
        if (!dropdown.contains(event.target) && !btn.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    }
});

function playNewOrderSound(buyerName) {
    try {
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        
        // Chime 1
        const osc1 = audioCtx.createOscillator();
        const gain1 = audioCtx.createGain();
        osc1.connect(gain1);
        gain1.connect(audioCtx.destination);
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 note
        gain1.gain.setValueAtTime(0.1, audioCtx.currentTime);
        gain1.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
        osc1.start(audioCtx.currentTime);
        osc1.stop(audioCtx.currentTime + 0.3);

        // Chime 2 (delayed, higher pitch)
        setTimeout(() => {
            const osc2 = audioCtx.createOscillator();
            const gain2 = audioCtx.createGain();
            osc2.connect(gain2);
            gain2.connect(audioCtx.destination);
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(1320, audioCtx.currentTime); // E6 note
            gain2.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gain2.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.4);
            osc2.start(audioCtx.currentTime);
            osc2.stop(audioCtx.currentTime + 0.4);
        }, 120);

        // Notifikasi Suara Vokal (Text-To-Speech / TTS)
        // Diberikan delay agar tidak bertabrakan dengan bunyi bel (chime)
        setTimeout(() => {
            if ('speechSynthesis' in window) {
                // Batalkan suara yang sedang berjalan (jika ada) untuk menghindari antrean panjang
                window.speechSynthesis.cancel();
                
                const name = buyerName || "Pembeli";
                const phrase = `Pesanan dari ${name} masuk. `;
                const text = phrase.repeat(3); // Ulangi kalimat sebanyak 3 kali
                
                const utterance = new SpeechSynthesisUtterance(text);
                
                utterance.lang = 'id-ID'; // Bahasa Indonesia
                utterance.pitch = 1.15;   // Pitch sedikit tinggi agar terdengar ramah/semangat
                utterance.rate = 1.05;    // Sedikit lebih cepat agar pengulangan 3x tidak terlalu lambat
                
                // Cari suara bahasa Indonesia
                const voices = window.speechSynthesis.getVoices();
                const idVoice = voices.find(voice => voice.lang.includes('id'));
                if (idVoice) {
                    utterance.voice = idVoice;
                }
                
                window.speechSynthesis.speak(utterance);
            }
        }, 700);

    } catch (e) {
        console.warn('Web Audio atau SpeechSynthesis tidak didukung:', e);
    }
}

function showOrderNotificationToast(orderNumber, buyerName, timeStr) {
    let container = document.getElementById('order-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'order-toast-container';
        container.style.cssText = 'position:fixed; bottom:24px; right:24px; z-index:9999; display:flex; flex-direction:column; gap:12px; max-width:360px; width:calc(100vw - 48px);';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.style.cssText = 'background:linear-gradient(135deg, #FF4B2B 0%, #FF416C 100%); color:white; border-radius:20px; padding:18px; box-shadow:0 10px 25px rgba(255, 65, 108, 0.3); display:flex; gap:14px; align-items:start; transform:translateY(50px); opacity:0; transition:all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position:relative; overflow:hidden;';
    
    const glow = document.createElement('div');
    glow.style.cssText = 'position:absolute; top:-20px; right:-20px; width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,0.15); filter:blur(10px);';
    toast.appendChild(glow);

    // Dynamic animation styling
    if (!document.getElementById('bell-shake-style')) {
        const styleSheet = document.createElement('style');
        styleSheet.id = 'bell-shake-style';
        styleSheet.textContent = `
            @keyframes bell-ring {
                0%, 100% { transform: rotate(0); }
                15% { transform: rotate(15deg); }
                30% { transform: rotate(-10deg); }
                45% { transform: rotate(5deg); }
                60% { transform: rotate(-5deg); }
                75% { transform: rotate(2deg); }
                85% { transform: rotate(-2deg); }
            }
            .bell-anim {
                animation: bell-ring 1s ease-in-out infinite;
            }
        `;
        document.head.appendChild(styleSheet);
    }

    toast.innerHTML += `
        <div style="background:rgba(255,255,255,0.2); border-radius:12px; width:40px; height:40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <i class="fas fa-bell bell-anim" style="font-size:20px;"></i>
        </div>
        <div style="flex:1; z-index: 2;">
            <h5 style="font-weight:900; font-size:14px; margin:0 0 3px 0; text-transform:uppercase; letter-spacing:0.03em;">Pesanan Baru Masuk! 🛍️</h5>
            <p style="margin:0; font-size:12px; font-weight:600; opacity:0.95;">${orderNumber} &middot; ${buyerName}</p>
            <p style="margin:4px 0 0 0; font-size:10px; opacity:0.8; font-weight:500;">Baru saja</p>
            <div style="margin-top:12px; display:flex; gap:8px;">
                <a href="/canteen/orders" style="background:white; color:#FF416C; border-radius:8px; padding:6px 12px; font-size:11px; font-weight:800; text-decoration:none; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.1); transition:all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">Kelola Pesanan</a>
                <button onclick="this.closest('.canteen-toast-item').style.opacity='0'; setTimeout(()=>this.closest('.canteen-toast-item').remove(), 400);" style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); color:white; border-radius:8px; padding:6px 12px; font-size:11px; font-weight:700; cursor:pointer;">Nanti</button>
            </div>
        </div>
    `;
    
    toast.className = 'canteen-toast-item';
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
    }, 50);
    
    // Auto dismiss after 12 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.transform = 'translateY(-20px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }
    }, 12000);
}

function checkNewOrders() {
    fetch('/canteen/api/notifications')
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Update badge di topbar jika elemennya ada
                const bellBtn = document.getElementById('canteenNotifBtn');
                const sidebarLink = document.querySelector('a[href="/canteen/orders"]');
                
                if (data.pending_count > 0) {
                    // Update or create badge in topbar button
                    let bellBadge = bellBtn ? bellBtn.querySelector('span') : null;
                    if (bellBadge) {
                        bellBadge.textContent = data.pending_count;
                        bellBadge.style.display = 'flex';
                    } else if (bellBtn) {
                        bellBtn.insertAdjacentHTML('beforeend', `<span style="position:absolute;top:5px;right:5px;min-width:16px;height:16px;border-radius:99px;background:#EF4444;border:2px solid white;font-size:9px;font-weight:900;color:white;display:flex;align-items:center;justify-content:center;padding:0 3px;">${data.pending_count}</span>`);
                    }
                    
                    // Update sidebar badge
                    let sBadge = sidebarLink ? sidebarLink.querySelector('.nav-badge') : null;
                    if (sBadge) {
                        sBadge.textContent = data.pending_count;
                        sBadge.style.display = 'inline-block';
                    } else if (sidebarLink) {
                        sidebarLink.insertAdjacentHTML('beforeend', `<span class="nav-badge">${data.pending_count}</span>`);
                    }
                } else {
                    // Hide badges
                    if (bellBtn && bellBtn.querySelector('span')) bellBtn.querySelector('span').style.display = 'none';
                    if (sidebarLink && sidebarLink.querySelector('.nav-badge')) sidebarLink.querySelector('.nav-badge').style.display = 'none';
                }

                // Update dropdown list content
                const listEl = document.getElementById('canteenNotifList');
                const countTextEl = document.getElementById('canteenNotifCountText');
                
                if (countTextEl) countTextEl.textContent = `${data.pending_count} Pending`;
                
                if (listEl) {
                    if (data.orders_list && data.orders_list.length > 0) {
                        listEl.innerHTML = data.orders_list.map(order => `
                            <a href="/canteen/orders" style="display: block; padding: 12px 16px; text-decoration: none; border-bottom: 1px solid #F8FAFC; transition: background 0.2s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; text-align: left;">
                                    <div style="display: flex; gap: 8px; align-items: flex-start;">
                                        <div style="background: #FFF3CD; color: #D97706; width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0; margin-top: 2px;">
                                            <i class="fas fa-shopping-basket"></i>
                                        </div>
                                        <div>
                                            <p style="font-weight: 800; color: #1e293b; font-size: 12px; margin: 0;">${order.order_number}</p>
                                            <p style="font-size: 10px; color: #64748B; margin: 2px 0 0 0; font-weight: 600;">Pelanggan: ${order.buyer_name}</p>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <p style="font-weight: 900; color: #2d6a8f; font-size: 11px; margin: 0;">${order.amount}</p>
                                        <p style="font-size: 9px; color: #94A3B8; margin: 2px 0 0 0;">${order.time}</p>
                                    </div>
                                </div>
                            </a>
                        `).join('');
                    } else {
                        listEl.innerHTML = `
                            <div style="padding: 24px 16px; text-align: center; color: #94A3B8; font-size: 12px; font-weight: 600;">
                                <i class="fas fa-shopping-basket" style="font-size: 20px; margin-bottom: 6px; display: block; opacity: 0.3;"></i>
                                Tidak ada pesanan pending
                            </div>
                        `;
                    }
                }

                // Check for new order
                if (data.latest_pending_id) {
                    if (lastLatestPendingId !== null && data.latest_pending_id > lastLatestPendingId) {
                        // A new order has arrived!
                        playNewOrderSound(data.latest_pending_buyer);
                        showOrderNotificationToast(data.latest_pending_number, data.latest_pending_buyer, data.latest_pending_time);
                        
                        // If current page is orders management, reload list after a small delay
                        if (window.location.pathname.includes('/canteen/orders')) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        }
                    }
                    lastLatestPendingId = data.latest_pending_id;
                } else {
                    lastLatestPendingId = 0;
                }
                
                isFirstCheck = false;
            }
        })
        .catch(err => console.error('Error checking new orders:', err));
}

// Start polling every 5 seconds
if ({{ auth()->check() ? 'true' : 'false' }}) {
    // Immediate check on load
    checkNewOrders();
    // Repeat check
    setInterval(checkNewOrders, 5000);
}
</script>
@yield('extra-js')
</body>
</html>
