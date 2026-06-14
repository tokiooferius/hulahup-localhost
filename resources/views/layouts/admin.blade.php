<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Food-TYU</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-foodtyu.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { background: #F0F4F8; }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #060e1e 0%, #0B2D5C 55%, #122C4F 100%);
            position: fixed; left: 0; top: 0; bottom: 0;
            display: flex; flex-direction: column;
            z-index: 50;
            box-shadow: 6px 0 32px rgba(0,0,0,0.25);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }

        .sidebar-brand {
            padding: 20px 18px 14px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .nav-section-label {
            font-size: 9.5px; font-weight: 800;
            letter-spacing: 0.16em; color: rgba(255,255,255,0.25);
            padding: 16px 22px 5px; text-transform: uppercase;
        }

        .admin-nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; margin: 2px 10px;
            border-radius: 14px;
            color: rgba(255,255,255,0.52);
            font-size: 13px; font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
            position: relative; overflow: hidden;
        }
        .admin-nav-item::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0;
            width: 3px; border-radius: 0 3px 3px 0;
            background: #F5A8D0;
            opacity: 0; transition: opacity .2s;
        }
        .admin-nav-item:hover {
            background: rgba(255,255,255,0.07);
            color: white;
            transform: translateX(2px);
        }
        .admin-nav-item.active {
            background: linear-gradient(135deg, rgba(122,184,255,0.18), rgba(245,168,208,0.12));
            color: white; font-weight: 800;
            box-shadow: 0 4px 16px rgba(11,45,92,0.3);
            border: 1px solid rgba(122,184,255,0.15);
        }
        .admin-nav-item.active::before { opacity: 1; }

        .nav-icon {
            width: 32px; height: 32px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            background: rgba(255,255,255,0.06);
            flex-shrink: 0; transition: all .2s;
        }
        .admin-nav-item.active .nav-icon { background: rgba(122,184,255,0.2); }
        .admin-nav-item:hover  .nav-icon { background: rgba(255,255,255,0.1); }

        .sidebar-bottom {
            margin-top: auto;
            padding: 12px 12px 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .admin-info-card {
            background: linear-gradient(135deg, rgba(122,184,255,0.12), rgba(245,168,208,0.08));
            border: 1px solid rgba(122,184,255,0.15);
            border-radius: 16px; padding: 12px 14px;
            margin-bottom: 10px;
        }

        /* ===== TOPBAR ===== */
        .admin-topbar {
            position: sticky; top: 0; z-index: 40;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(11,45,92,0.07);
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 20px rgba(11,45,92,0.06);
        }

        /* ===== CONTENT ===== */
        .admin-main {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* ===== CARD STYLES ===== */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 22px;
            border: 1px solid rgba(11,45,92,0.05);
            box-shadow: 0 2px 12px rgba(11,45,92,0.05);
            transition: transform .3s, box-shadow .3s;
            position: relative; overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(11,45,92,0.12);
        }

        /* Logout danger */
        .admin-nav-item.danger { color: rgba(252,165,165,0.75); }
        .admin-nav-item.danger:hover { background: rgba(239,68,68,0.12); color: #fca5a5; }
        .admin-nav-item.danger .nav-icon { background: rgba(239,68,68,0.1); }

        /* Mobile sidebar overlay */
        #sidebarOverlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 40;
        }
        @media (max-width: 1024px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
        }

        /* Scrollbar */
        .admin-sidebar::-webkit-scrollbar { width: 4px; }
        .admin-sidebar::-webkit-scrollbar-track { background: transparent; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        /* Fade in page */
        @keyframes fadeInUp {
            from { opacity:0; transform: translateY(16px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .page-content { animation: fadeInUp .45s ease; }
    </style>
    @yield('extra-css')
</head>
<body>

<!-- Mobile overlay -->
<div id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ===== SIDEBAR ===== -->
<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <a href="/admin/dashboard" class="flex items-center gap-3">
            <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('images/logo-foodtyu.png') }}" alt="Logo" class="w-6 h-6 object-contain">
            </div>
            <div>
                <h1 class="text-[17px] font-black italic text-white leading-none">Food-TYU<span class="text-[#F5A8D0]">.</span></h1>
                <p class="text-[9px] text-white/35 font-bold uppercase tracking-widest mt-0.5">Admin Panel</p>
            </div>
        </a>
    </div>

    <!-- Admin info -->
    <div class="px-4 py-3">
        <div class="admin-info-card">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#7AB8FF] to-[#F5A8D0] flex items-center justify-center text-white font-black text-xs flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-black truncate">{{ Auth::user()->name }}</p>
                    <p class="text-white/40 text-[9px] font-bold uppercase tracking-wider">Super Admin</p>
                </div>
                <span class="ml-auto flex-shrink-0 w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto pb-2">
        <p class="nav-section-label">Main</p>

        <a href="/admin/dashboard"
           class="admin-nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
            Dashboard
        </a>

        <a href="/admin/orders"
           class="admin-nav-item {{ request()->is('admin/orders*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-receipt"></i></span>
            Monitoring Pesanan
            @php $pendingCount = \App\Models\Order::where('status','pending')->count(); @endphp
            @if($pendingCount > 0)
            <span class="ml-auto bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full min-w-[18px] text-center animate-pulse">{{ $pendingCount }}</span>
            @endif
        </a>

        <p class="nav-section-label">Kelola</p>

        <a href="/admin/canteens"
           class="admin-nav-item {{ request()->is('admin/canteens*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-store"></i></span>
            Kelola Kantin
        </a>

        <a href="/admin/users"
           class="admin-nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-users"></i></span>
            Users Management
        </a>

        <a href="/admin/vouchers"
           class="admin-nav-item {{ request()->is('admin/vouchers*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-ticket"></i></span>
            Vouchers Management
        </a>

        <p class="nav-section-label">Laporan</p>

        <a href="/admin/export"
           class="admin-nav-item {{ request()->is('admin/export*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-file-excel"></i></span>
            Export Laporan
        </a>
    </div>

    <!-- Sidebar Bottom -->
    <div class="sidebar-bottom">
        <a href="/home" class="admin-nav-item">
            <span class="nav-icon"><i class="fas fa-home"></i></span>
            Kembali ke Home
        </a>

        <form action="{{ route('switch.view') }}" method="POST">
            @csrf
            <input type="hidden" name="target" value="buyer">
            <button type="submit" class="admin-nav-item w-full text-left" style="color:rgba(250,204,21,0.75)">
                <span class="nav-icon" style="background:rgba(250,204,21,0.1)"><i class="fas fa-eye"></i></span>
                Lihat sebagai Pembeli
            </button>
        </form>

        <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        <a href="#" onclick="document.getElementById('admin-logout-form').submit()"
           class="admin-nav-item danger">
            <span class="nav-icon"><i class="fas fa-right-from-bracket"></i></span>
            Logout
        </a>
    </div>
</aside>

<!-- ===== MAIN CONTENT ===== -->
<div class="admin-main">

    <!-- Topbar -->
    <div class="admin-topbar">
        <div class="flex items-center gap-4">
            <!-- Mobile hamburger -->
            <button class="lg:hidden w-9 h-9 bg-[#0B2D5C]/8 rounded-xl flex items-center justify-center text-[#0B2D5C] hover:bg-[#0B2D5C]/15 transition"
                    onclick="openSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Breadcrumb -->
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-400 font-bold mb-0.5">
                    <i class="fas fa-crown text-[#F5A8D0] text-[10px]"></i>
                    <span>Admin Panel</span>
                    <i class="fas fa-chevron-right text-[9px]"></i>
                    <span class="text-[#0B2D5C]">@yield('title', 'Dashboard')</span>
                </div>
                <h1 class="text-lg font-black text-[#0B2D5C] leading-none">@yield('title', 'Dashboard')</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Live status -->
            <div class="hidden md:flex items-center gap-2 bg-green-50 border border-green-100 rounded-full px-3 py-1.5">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-green-700 text-[10px] font-black uppercase tracking-wider">Sistem Online</span>
            </div>

            <!-- Time -->
            <div class="hidden md:block text-right">
                <p class="text-xs font-black text-slate-700" id="topbarTime">--:--</p>
                <p class="text-[10px] text-slate-400 font-medium">WIB</p>
            </div>

            <!-- Avatar -->
            <div class="w-9 h-9 bg-gradient-to-br from-[#0B2D5C] to-[#2D6A8F] text-white rounded-xl flex items-center justify-center font-black text-sm shadow-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @yield('content')
    </div>
</div>

@yield('extra-js')

<script>
    // Clock
    function updateClock() {
        const now = new Date();
        const t = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
        const el = document.getElementById('topbarTime');
        if (el) el.textContent = t;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Mobile sidebar
    function openSidebar() {
        document.getElementById('adminSidebar').classList.add('open');
        document.getElementById('sidebarOverlay').style.display = 'block';
    }
    function closeSidebar() {
        document.getElementById('adminSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').style.display = 'none';
    }
</script>
</body>
</html>
