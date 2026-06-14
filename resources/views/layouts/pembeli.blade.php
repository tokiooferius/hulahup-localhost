<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Food-TYU</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-foodtyu.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('head-scripts')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }

        .sidebar {
            width: 270px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0f1f3d 0%, #122C4F 55%, #1a3a5c 100%);
            position: fixed; left: 0; top: 0; bottom: 0;
            display: flex; flex-direction: column;
            z-index: 50;
            box-shadow: 4px 0 28px rgba(0,0,0,0.2);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        .sidebar-logo {
            padding: 22px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .user-greeting {
            background: rgba(255,255,255,0.06);
            border-radius: 16px; padding: 12px 14px;
            display: flex; align-items: center; gap: 12px;
            margin-top: 12px;
        }
        .avatar {
            width: 38px; height: 38px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; color: white; font-size: 14px;
            flex-shrink: 0;
        }
        .nav-section-label {
            font-size: 9.5px; font-weight: 800;
            letter-spacing: 0.15em; color: rgba(255,255,255,0.27);
            padding: 16px 24px 5px; text-transform: uppercase;
        }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; margin: 2px 10px;
            border-radius: 14px; color: rgba(255,255,255,0.55);
            font-size: 13.5px; font-weight: 600;
            text-decoration: none; transition: all .2s;
        }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: white; transform: translateX(3px); }
        .nav-item.active {
            background: linear-gradient(135deg, #2d6a8f 0%, #1a3a5c 100%);
            color: white; font-weight: 800;
            box-shadow: 0 4px 18px rgba(45,106,143,0.45);
        }
        .nav-icon {
            width: 34px; height: 34px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; background: rgba(255,255,255,0.07);
            flex-shrink: 0; transition: all .2s;
        }
        .nav-item.active .nav-icon   { background: rgba(255,255,255,0.18); }
        .nav-item:hover  .nav-icon   { background: rgba(255,255,255,0.12); }
        .nav-badge {
            margin-left: auto;
            background: #F97316; color: white;
            font-size: 10px; font-weight: 800;
            padding: 2px 7px; border-radius: 99px;
            animation: pulse-b 2s ease-in-out infinite;
        }
        @keyframes pulse-b { 0%,100%{transform:scale(1)} 50%{transform:scale(1.1)} }

        /* Saldo card */
        .saldo-card {
            margin: 0 12px 8px;
            background: linear-gradient(135deg, #1a3a5c, #2d6a8f);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 18px; padding: 16px 18px;
            position: relative; overflow: hidden;
        }
        .saldo-card::after {
            content:''; position:absolute; top:-20px; right:-20px;
            width:80px; height:80px; border-radius:50%;
            background: rgba(255,255,255,0.05);
        }
        /* Voucher btn */
        .voucher-btn {
            margin: 0 12px 8px;
            background: linear-gradient(135deg, #EAD8B1, #d4be82);
            color: #0f1f3d; border-radius: 14px;
            padding: 11px 16px; font-size: 13px; font-weight: 800;
            text-decoration: none; display: flex; align-items: center; gap: 8px;
            transition: all .2s;
        }
        .voucher-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(234,216,177,0.35); }

        .sidebar-bottom {
            padding: 10px 12px 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .logout-btn {
            width: 100%; display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 12px;
            background: rgba(239,68,68,0.1); border: none; cursor: pointer;
            color: rgba(239,68,68,0.85); font-size: 13px; font-weight: 700;
            transition: background .2s;
        }
        .logout-btn:hover { background: rgba(239,68,68,0.2); }

        /* MAIN */
        .main-content { margin-left: 270px; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: white; border-bottom: 1px solid #E8EDF2;
            padding: 0 28px; height: 62px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
            box-shadow: 0 1px 8px rgba(0,0,0,0.05);
        }
        .page-content { padding: 28px; flex: 1; background: #F5F2E8; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        /* Sidebar overlay mobile */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 40; }
        @media(max-width:768px){
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-content { margin-left: 0; }
        }

        @yield('extra-css')
    </style>
</head>
<body style="background:#F5F2E8;">
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div style="display:flex;align-items:center;gap:12px;">
            <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Logo" style="width:40px;height:40px;object-fit:contain;border-radius:12px;">
            <div>
                <p style="color:white;font-weight:900;font-size:20px;font-style:italic;line-height:1;">Food-TYU.</p>
                <p style="font-size:9px;font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.2em;text-transform:uppercase;margin-top:2px;">KANTIN TEL-U</p>
            </div>
        </div>

        <!-- User greeting -->
        <div class="user-greeting">
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/avatars/'.Auth::user()->avatar) }}" style="width:38px;height:38px;border-radius:12px;object-fit:cover;">
            @else
                <div class="avatar" style="background:linear-gradient(135deg,#2d6a8f,#5B88B2);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div style="flex:1;min-width:0;">
                <p style="color:white;font-weight:800;font-size:13px;line-height:1.2;" class="truncate">{{ Auth::user()->name }}</p>
                <p style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">
                    @if(Auth::user()->role === 'mahasiswa')✨ Mahasiswa Tel-U
                    @elseif(Auth::user()->role === 'admin')👑 Admin
                    @else👤 User Biasa @endif
                </p>
            </div>
        </div>
    </div>

    <nav style="flex:1;overflow-y:auto;padding:6px 0;">
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-house"></i></div>
            Dashboard
        </a>
        <a href="{{ route('canteens.shop') }}" class="nav-item {{ request()->routeIs('canteens*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-store"></i></div>
            Daftar Kantin
        </a>
        <a href="{{ route('orders.active') }}" class="nav-item {{ request()->routeIs('orders.active') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-clock"></i></div>
            Pesanan Aktif
            @php
                $sidebarActiveOrders = 0;
                try { $sidebarActiveOrders = \App\Models\Order::where('user_id', Auth::id())->whereIn('status',['pending','processing'])->count(); } catch(\Exception $e){}
            @endphp
            @if($sidebarActiveOrders > 0)
                <span class="nav-badge">{{ $sidebarActiveOrders }}</span>
            @endif
        </a>
        <a href="{{ route('orders.history') }}" class="nav-item {{ request()->routeIs('orders.history') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-history"></i></div>
            Riwayat Pesan
        </a>
        <a href="/topup" class="nav-item {{ request()->is('topup') ? 'active' : '' }}">
            <div class="nav-icon"><i class="fas fa-wallet"></i></div>
            Saldo TyU-Pay
        </a>
        @if(Auth::user()->role === 'admin')
        <div class="nav-section-label">Admin</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item" style="color:rgba(252,211,77,0.85);">
            <div class="nav-icon" style="color:rgba(252,211,77,0.9);"><i class="fas fa-crown"></i></div>
            Admin Dashboard
        </a>
        @elseif(Auth::user()->role === 'ibu_kantin')
        <div class="nav-section-label">Mitra Kantin</div>
        <a href="{{ route('canteen.dashboard') }}" class="nav-item" style="color:rgba(251,146,60,0.9);">
            <div class="nav-icon" style="color:rgba(251,146,60,1.0);"><i class="fas fa-store-alt"></i></div>
            Kantin Dashboard
        </a>
        @endif
    </nav>

    <!-- Saldo TyU-Pay -->
    <div class="saldo-card">
        <p style="font-size:10px;font-weight:700;color:rgba(255,255,255,0.4);letter-spacing:0.1em;text-transform:uppercase;">Saldo TyU-Pay</p>
        <p style="color:white;font-weight:900;font-size:22px;margin-top:4px;letter-spacing:-0.5px;">
            Rp {{ number_format(Auth::user()->balance ?? 0, 0, ',', '.') }}
        </p>
        <a href="/topup" style="font-size:11px;color:rgba(234,216,177,0.85);font-weight:700;text-decoration:none;margin-top:6px;display:inline-flex;align-items:center;gap:4px;">
            <i class="fas fa-plus-circle text-xs"></i> Top Up Sekarang
        </a>
    </div>

    <!-- Voucher -->
    <a href="javascript:void(0)" onclick="typeof openVoucherModal !== 'undefined' ? openVoucherModal() : null" class="voucher-btn">
        <i class="fas fa-ticket-alt"></i>
        Pakai Voucher
    </a>

    <!-- Bottom logout -->
    <div class="sidebar-bottom">
        <form action="{{ route('logout') }}" method="POST" id="sidebar-logout-form">@csrf</form>
        <button class="logout-btn" onclick="document.getElementById('sidebar-logout-form').submit()">
            <i class="fas fa-right-from-bracket"></i> Keluar
        </button>
    </div>
</aside>

<!-- MAIN -->
<div class="main-content">
    <!-- Topbar -->
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:14px;">
            <button onclick="toggleSidebar()" id="mobileMenuBtn" style="display:none;padding:8px;border-radius:10px;border:1.5px solid #E5E7EB;background:white;cursor:pointer;">
                <i class="fas fa-bars text-slate-600 text-sm"></i>
            </button>
            <div>
                <p style="font-size:10px;color:#9CA3AF;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">
                    Food-TYU / @yield('page-title', 'Dashboard')
                </p>
                <h1 style="font-size:16px;font-weight:900;color:#0f1f3d;line-height:1.2;">@yield('page-title', 'Dashboard')</h1>
            </div>
        </div>

        <!-- Right side: notif + cart + user -->
        <div style="display:flex;align-items:center;gap:10px;">

            <!-- Notif Bell + Panel -->
            <div style="position:relative;" id="notifWrapper">
                <button id="topbarNotifBtn"
                    onclick="toggleNotifPanel()"
                    style="position:relative;padding:9px 10px;border-radius:12px;border:1.5px solid #E5E7EB;background:white;cursor:pointer;transition:all .2s;"
                    onmouseover="this.style.borderColor='#2d6a8f';this.style.background='#EFF6FF'"
                    onmouseout="this.style.borderColor='#E5E7EB';this.style.background='white'">
                    <i class="fas fa-bell text-slate-500 text-sm" id="notifBellIcon"></i>
                    <span id="notifBadge" style="display:none;position:absolute;top:-4px;right:-4px;min-width:18px;height:18px;border-radius:99px;background:#EF4444;color:white;font-size:10px;font-weight:800;line-height:18px;text-align:center;padding:0 4px;border:2px solid white;"></span>
                </button>

                <!-- Notification Panel (dropdown) -->
                <div id="notifPanel" style="display:none;position:absolute;top:calc(100% + 10px);right:0;width:360px;background:white;border-radius:20px;box-shadow:0 20px 60px rgba(0,0,0,0.15);border:1.5px solid #E5E7EB;z-index:9999;overflow:hidden;animation:notifSlideIn .2s ease;">
                    <!-- Header -->
                    <div style="padding:16px 18px 12px;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <h3 style="font-size:15px;font-weight:800;color:#0F172A;margin:0;">🔔 Notifikasi</h3>
                            <p id="notifSubtitle" style="font-size:11px;color:#94A3B8;margin:2px 0 0;font-weight:600;"></p>
                        </div>
                        <button onclick="markAllRead()" id="markReadBtn" style="display:none;font-size:11px;font-weight:700;color:#2d6a8f;background:#EFF6FF;border:none;border-radius:8px;padding:5px 10px;cursor:pointer;">
                            Tandai semua dibaca
                        </button>
                    </div>
                    <!-- Content -->
                    <div id="notifList" style="max-height:400px;overflow-y:auto;padding:8px 0;">
                        <div id="notifLoading" style="padding:32px;text-align:center;">
                            <i class="fas fa-spinner fa-spin" style="font-size:24px;color:#CBD5E1;"></i>
                            <p style="font-size:12px;color:#94A3B8;margin-top:8px;font-weight:600;">Memuat notifikasi...</p>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div style="padding:10px 18px;border-top:1px solid #F1F5F9;text-align:center;">
                        <a href="/orders/active" style="font-size:12px;font-weight:700;color:#2d6a8f;text-decoration:none;">
                            Lihat semua pesanan aktif →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cart button -->
            <button onclick="typeof toggleCart !== 'undefined' ? toggleCart() : window.location.href='{{ route('cart.index') }}'"
               id="topbarCartBtn"
               style="position:relative;padding:9px 10px;border-radius:12px;border:1.5px solid #E5E7EB;background:white;cursor:pointer;transition:all .2s;display:inline-flex;align-items:center;gap:6px;"
               onmouseover="this.style.borderColor='#2d6a8f';this.style.background='#EFF6FF'" onmouseout="this.style.borderColor='#E5E7EB';this.style.background='white'">
                <i class="fas fa-shopping-cart text-slate-600 text-sm"></i>
                @php
                    $topbarCartCount = 0;
                    try {
                        $sessionCart = session('cart', []);
                        if (is_array($sessionCart)) {
                            foreach ($sessionCart as $item) {
                                $topbarCartCount += (int)($item['quantity'] ?? $item['qty'] ?? 1);
                            }
                        }
                    } catch(\Exception $e){ $topbarCartCount = 0; }
                @endphp
                <span id="topbarCartBadge" style="background:#F97316;color:white;font-size:10px;font-weight:800;padding:1px 6px;border-radius:99px;{{ $topbarCartCount > 0 ? '' : 'display:none;' }}">{{ $topbarCartCount }}</span>
            </button>

            <!-- User chip -->
            <div onclick="typeof openProfileModal !== 'undefined' ? openProfileModal() : null"
                style="display:flex;align-items:center;gap:8px;padding:6px 12px 6px 8px;background:#F1F5F9;border-radius:12px;border:1.5px solid #E5E7EB;cursor:pointer;transition:all .2s;"
                onmouseover="this.style.borderColor='#2d6a8f';this.style.background='#EFF6FF'" onmouseout="this.style.borderColor='#E5E7EB';this.style.background='#F1F5F9'">
                <div id="topbarAvatarArea">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/avatars/'.Auth::user()->avatar) }}" style="width:26px;height:26px;border-radius:8px;object-fit:cover;">
                    @else
                        <div class="avatar" style="width:26px;height:26px;font-size:11px;border-radius:8px;background:linear-gradient(135deg,#2d6a8f,#5B88B2);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <span style="font-size:13px;font-weight:700;color:#1e293b;">{{ explode(' ', Auth::user()->name)[0] }}</span>
                <i class="fas fa-chevron-down text-slate-400" style="font-size:9px;"></i>
            </div>

        </div>{{-- end right flex --}}
    </header>

    {{-- Floating "Kembali ke Dashboard" button untuk admin & ibu kantin yang lagi lihat sebagai pembeli --}}
    @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'ibu_kantin']) && session('viewing_as_buyer'))
    <div style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:9999;">
        <form action="{{ route('switch.view') }}" method="POST">
            @csrf
            <input type="hidden" name="target" value="original">
            <button type="submit"
                style="background:linear-gradient(135deg,#1a3a5c,#2d6a8f);color:white;font-weight:900;font-size:13px;padding:12px 24px;border-radius:99px;border:none;cursor:pointer;box-shadow:0 8px 32px rgba(45,106,143,0.45);display:flex;align-items:center;gap:10px;white-space:nowrap;"
                onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard {{ Auth::user()->role === 'admin' ? 'Admin' : 'Mitra Kantin' }}
                <span style="background:rgba(255,255,255,0.2);padding:2px 10px;border-radius:99px;font-size:11px;">{{ Auth::user()->role === 'admin' ? '🛡️' : '🍳' }}</span>
            </button>
        </form>
    </div>
    @endif
    </header>

    <main class="page-content">
        @yield('content')
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
function checkMobile() {
    document.getElementById('mobileMenuBtn').style.display = window.innerWidth <= 768 ? 'block' : 'none';
}
checkMobile();
window.addEventListener('resize', checkMobile);

// Sync cart badge dari localStorage (karena cart disimpan di JS/localStorage)
function syncCartBadge() {
    try {
        const cart = JSON.parse(localStorage.getItem('foodtyu_cart') || '[]');
        const total = cart.reduce((s, i) => s + (parseInt(i.qty) || 1), 0);
        const badge = document.getElementById('topbarCartBadge');
        if (badge) {
            badge.textContent = total;
            badge.style.display = total > 0 ? '' : 'none';
        }
    } catch(e){}
}
// Sync setiap kali halaman fokus atau storage berubah
window.addEventListener('storage', syncCartBadge);
window.addEventListener('focus', syncCartBadge);
document.addEventListener('DOMContentLoaded', syncCartBadge);
// Sync berkala
setInterval(syncCartBadge, 1500);

// ======= NOTIFICATION PANEL =======
let notifPanelOpen = false;
let notifData = [];
let notifRead = new Set(JSON.parse(localStorage.getItem('foodtyu_notif_read') || '[]'));

// CSS animation keyframe
const notifStyle = document.createElement('style');
notifStyle.textContent = `
@keyframes notifSlideIn {
    from { opacity:0; transform:translateY(-8px) scale(0.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
#notifList::-webkit-scrollbar { width:5px; }
#notifList::-webkit-scrollbar-track { background:#F8FAFC; }
#notifList::-webkit-scrollbar-thumb { background:#CBD5E1; border-radius:99px; }
.notif-item { display:flex;align-items:flex-start;gap:12px;padding:12px 18px;cursor:pointer;transition:background .15s;border-bottom:1px solid #F8FAFC; }
.notif-item:hover { background:#F8FAFC; }
.notif-item:last-child { border-bottom:none; }
.notif-item.unread { background:#FFFBEB; }
.notif-item.unread:hover { background:#FEF9C3; }
`;
document.head.appendChild(notifStyle);

function toggleNotifPanel() {
    const panel = document.getElementById('notifPanel');
    if (!notifPanelOpen) {
        panel.style.display = 'block';
        notifPanelOpen = true;
        fetchNotifications();
        // bell animation
        const bell = document.getElementById('notifBellIcon');
        bell.style.transform = 'rotate(-20deg)';
        setTimeout(() => bell.style.transform = '', 300);
    } else {
        closeNotifPanel();
    }
}

function closeNotifPanel() {
    const panel = document.getElementById('notifPanel');
    if (panel) panel.style.display = 'none';
    notifPanelOpen = false;
}

function fetchNotifications() {
    document.getElementById('notifLoading').style.display = 'block';
    fetch('/api/notifications', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        notifData = data.notifications || [];
        document.getElementById('notifLoading').style.display = 'none';
        renderNotifications();
        updateNotifBadge();
    })
    .catch(() => {
        document.getElementById('notifLoading').innerHTML = '<p style="font-size:12px;color:#94A3B8;text-align:center;padding:20px;">Gagal memuat notifikasi</p>';
    });
}

function renderNotifications() {
    const list = document.getElementById('notifList');
    const subtitle = document.getElementById('notifSubtitle');
    const markBtn = document.getElementById('markReadBtn');

    if (!notifData || notifData.length === 0) {
        list.innerHTML = `
            <div style="padding:40px 20px;text-align:center;">
                <div style="font-size:40px;margin-bottom:12px;">🎉</div>
                <p style="font-weight:800;color:#1E293B;font-size:14px;">Semua sudah terbaca!</p>
                <p style="font-size:12px;color:#94A3B8;margin-top:4px;">Tidak ada notifikasi baru saat ini</p>
            </div>`;
        subtitle.textContent = 'Tidak ada notifikasi';
        markBtn.style.display = 'none';
        return;
    }

    const unreadItems = notifData.filter(n => !n.read && !notifRead.has(n.id));
    subtitle.textContent = unreadItems.length > 0 ? `${unreadItems.length} notifikasi belum dibaca` : 'Semua sudah dibaca';
    markBtn.style.display = unreadItems.length > 0 ? '' : 'none';

    list.innerHTML = notifData.map(n => {
        const isUnread = !n.read && !notifRead.has(n.id);
        return `
        <div class="notif-item ${isUnread ? 'unread' : ''}" onclick="handleNotifClick('${n.id}', '${n.link}')">
            <div style="width:40px;height:40px;border-radius:12px;background:${n.color}18;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;border:1.5px solid ${n.color}30;">
                ${n.icon}
            </div>
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                    <p style="font-size:12px;font-weight:800;color:${n.color};margin:0;text-transform:uppercase;letter-spacing:0.04em;">${n.title}</p>
                    ${isUnread ? '<span style="width:7px;height:7px;border-radius:50%;background:#EF4444;flex-shrink:0;margin-top:2px;"></span>' : ''}
                </div>
                <p style="font-size:13px;font-weight:700;color:#1E293B;margin:2px 0;">${n.body}</p>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:2px;">
                    <span style="font-size:11px;font-weight:700;color:#64748B;">${n.meta}</span>
                    <span style="font-size:10px;color:#94A3B8;">${n.time}</span>
                </div>
            </div>
        </div>`;
    }).join('');
}

function handleNotifClick(id, link) {
    notifRead.add(id);
    localStorage.setItem('foodtyu_notif_read', JSON.stringify([...notifRead]));
    renderNotifications();
    updateNotifBadge();
    if (link) { closeNotifPanel(); window.location.href = link; }
}

function markAllRead() {
    notifData.forEach(n => notifRead.add(n.id));
    localStorage.setItem('foodtyu_notif_read', JSON.stringify([...notifRead]));
    renderNotifications();
    updateNotifBadge();
}

function updateNotifBadge() {
    const badge = document.getElementById('notifBadge');
    const bell  = document.getElementById('notifBellIcon');
    if (!badge) return;
    const count = notifData.filter(n => !n.read && !notifRead.has(n.id)).length;
    if (count > 0) {
        badge.textContent = count > 9 ? '9+' : count;
        badge.style.display = '';
        bell.style.color = '#EF4444';
    } else {
        badge.style.display = 'none';
        bell.style.color = '';
    }
}

// Close panel saat klik di luar
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notifWrapper');
    if (wrapper && !wrapper.contains(e.target) && notifPanelOpen) {
        closeNotifPanel();
    }
});

// Auto-fetch badge count saat load
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/notifications', { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            notifData = data.notifications || [];
            updateNotifBadge();
        }).catch(() => {});
    // Refresh notif count setiap 60 detik
    setInterval(() => {
        fetch('/api/notifications', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => { notifData = data.notifications || []; updateNotifBadge(); })
            .catch(() => {});
    }, 60000);
});
</script>
@yield('extra-js')
</body>
</html>
