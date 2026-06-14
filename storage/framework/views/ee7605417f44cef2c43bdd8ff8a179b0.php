<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — Food-TYU Kantin</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo-foodtyu.png')); ?>">
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

        <?php echo $__env->yieldContent('extra-css'); ?>
    </style>
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div style="display:flex;align-items:center;gap:12px;">
            <img src="<?php echo e(asset('images/logo-foodtyu.png')); ?>" alt="Logo" style="width:38px;height:38px;object-fit:contain;border-radius:10px;">
            <div>
                <p style="color:white;font-weight:900;font-size:19px;font-style:italic;line-height:1;">Food-TYU.</p>
                <p style="font-size:9px;font-weight:700;color:rgba(255,255,255,0.35);letter-spacing:0.18em;text-transform:uppercase;margin-top:2px;">DAPUR KANTIN</p>
            </div>
        </div>
    </div>

    <nav style="flex:1;overflow-y:auto;padding:6px 0;">
        <div class="nav-section-label">Menu Utama</div>

        <a href="<?php echo e(route('canteen.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('canteen.dashboard') ? 'active' : ''); ?>">
            <div class="nav-icon"><i class="fas fa-tachometer-alt"></i></div>
            Dashboard
        </a>

        <a href="<?php echo e(route('canteen.orders.index')); ?>" class="nav-item <?php echo e(request()->routeIs('canteen.orders*') ? 'active' : ''); ?>">
            <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div>
            Kelola Pesanan
            <?php
                $sidebarPending = 0;
                try {
                    $sidebarCanteen = auth()->user()->canteen;
                    if($sidebarCanteen) $sidebarPending = $sidebarCanteen->orders()->where('status','pending')->count();
                } catch(\Exception $e) {}
            ?>
            <?php if($sidebarPending > 0): ?>
                <span class="nav-badge"><?php echo e($sidebarPending); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('canteen.menus.index')); ?>" class="nav-item <?php echo e(request()->routeIs('canteen.menus*') ? 'active' : ''); ?>">
            <div class="nav-icon"><i class="fas fa-utensils"></i></div>
            Menu Kantin
        </a>

        <div class="nav-section-label">Keuangan</div>

        <a href="<?php echo e(route('canteen.vouchers.index')); ?>" class="nav-item <?php echo e(request()->routeIs('canteen.vouchers*') ? 'active' : ''); ?>">
            <div class="nav-icon"><i class="fas fa-ticket-alt"></i></div>
            Voucher
        </a>

        <a href="<?php echo e(route('canteen.sales')); ?>" class="nav-item <?php echo e(request()->routeIs('canteen.sales') ? 'active' : ''); ?>">
            <div class="nav-icon"><i class="fas fa-chart-bar"></i></div>
            Penjualan
        </a>

        <a href="<?php echo e(route('canteen.payments')); ?>" class="nav-item <?php echo e(request()->routeIs('canteen.payments') ? 'active' : ''); ?>">
            <div class="nav-icon"><i class="fas fa-credit-card"></i></div>
            Pembayaran
        </a>
        <a href="<?php echo e(route('canteen.export')); ?>" class="nav-item <?php echo e(request()->routeIs('canteen.export*') ? 'active' : ''); ?>">
            <div class="nav-icon"><i class="fas fa-file-excel"></i></div>
            Export Laporan
        </a>

        <div class="nav-section-label">Lainnya</div>

        
        <form action="<?php echo e(route('switch.view')); ?>" method="POST" id="switchToBuyerForm" style="margin:0;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="target" value="buyer">
            <button type="submit" class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;color:rgba(234,216,177,0.85);text-align:left;">
                <div class="nav-icon" style="color:rgba(234,216,177,0.9);"><i class="fas fa-eye"></i></div>
                Lihat sebagai Pembeli
            </button>
        </form>
    </nav>

    <!-- Bottom -->
    <div class="sidebar-bottom">
        <?php $sidebarCanteenBalance = 0; try { $sidebarCanteenBalance = auth()->user()->canteen->balance ?? 0; } catch(\Exception $e){} ?>
        <div class="saldo-card">
            <p style="font-size:10px;font-weight:700;color:rgba(255,255,255,0.38);letter-spacing:0.1em;text-transform:uppercase;">Saldo Kantin</p>
            <p style="color:white;font-weight:900;font-size:20px;margin-top:4px;">Rp <?php echo e(number_format($sidebarCanteenBalance, 0, ',', '.')); ?></p>
        </div>
        <div class="user-card" onclick="toggleProfilePanel()">
            <div class="avatar" id="kantinAvatarArea" style="overflow:hidden;padding:0;">
                <?php if(auth()->user()->avatar): ?>
                    <img src="<?php echo e(asset('storage/avatars/'.auth()->user()->avatar)); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
                <?php else: ?>
                    <?php echo e(strtoupper(substr(auth()->user()->name ?? 'K', 0, 1))); ?>

                <?php endif; ?>
            </div>
            <div style="flex:1;min-width:0;">
                <p style="color:white;font-weight:700;font-size:13px;line-height:1.2;" class="truncate"><?php echo e(auth()->user()->name ?? 'Pemilik Kantin'); ?></p>
                <p style="font-size:11px;color:rgba(255,255,255,0.4);">🍳 Mitra Kantin</p>
            </div>
            <i class="fas fa-chevron-up text-xs" style="color:rgba(255,255,255,0.3);transition:transform .2s;" id="sidebarChevron"></i>
        </div>
        <!-- Profile panel -->
        <div id="sidebarProfilePanel" style="display:none;background:rgba(255,255,255,0.05);border-radius:12px;padding:12px 14px;margin-top:8px;">

            
            <div style="text-align:center;margin-bottom:12px;">
                <div style="position:relative;display:inline-block;" onclick="document.getElementById('kantinAvatarInput').click()" class="cursor-pointer group">
                    <div style="width:60px;height:60px;border-radius:50%;overflow:hidden;border:3px solid rgba(255,255,255,0.2);margin:0 auto;" id="kantinAvatarBig">
                        <?php if(auth()->user()->avatar): ?>
                            <img src="<?php echo e(asset('storage/avatars/'.auth()->user()->avatar)); ?>" style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,#2d6a8f,#5B88B2);display:flex;align-items:center;justify-content:center;color:white;font-size:22px;font-weight:900;">
                                <?php echo e(strtoupper(substr(auth()->user()->name ?? 'K', 0, 1))); ?>

                            </div>
                        <?php endif; ?>
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
            <p style="font-size:12px;color:rgba(255,255,255,0.75);font-weight:600;margin-bottom:8px;" class="truncate"><?php echo e(auth()->user()->email ?? '-'); ?></p>
            <p style="font-size:10px;color:rgba(255,255,255,0.35);text-transform:uppercase;font-weight:700;">No. HP</p>
            <p style="font-size:12px;color:rgba(255,255,255,0.75);font-weight:600;margin-bottom:10px;"><?php echo e(auth()->user()->phone ?? '-'); ?></p>

            
            <form action="<?php echo e(route('switch.view')); ?>" method="POST" style="margin-bottom:8px;">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="target" value="buyer">
                <button type="submit" style="width:100%;background:rgba(251,191,36,0.15);color:rgba(251,191,36,0.9);border:1.5px solid rgba(251,191,36,0.3);border-radius:10px;padding:8px;font-weight:700;font-size:12px;cursor:pointer;transition:background .2s;display:flex;align-items:center;justify-content:center;gap:6px;"
                    onmouseover="this.style.background='rgba(251,191,36,0.25)'" onmouseout="this.style.background='rgba(251,191,36,0.15)'">
                    <i class="fas fa-eye"></i> Lihat sebagai Pembeli
                </button>
            </form>

            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
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
                    Kantin / <?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
                </p>
                <h1 style="font-size:17px;font-weight:800;color:#0f1f3d;line-height:1.2;"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:12px;">
            <!-- Notif - buka panel pesanan pending -->
            <button onclick="typeof openNotifPanel !== 'undefined' ? openNotifPanel() : window.location.href='<?php echo e(route('canteen.orders.index')); ?>'"
                style="position:relative;padding:8px;border-radius:12px;border:1.5px solid #E5E7EB;background:white;cursor:pointer;transition:all .2s;"
                onmouseover="this.style.borderColor='#2d6a8f';this.style.background='#EFF6FF'"
                onmouseout="this.style.borderColor='#E5E7EB';this.style.background='white'"
                title="Lihat pesanan masuk">
                <i class="fas fa-bell text-slate-500 text-sm"></i>
                <?php if(isset($sidebarPending) && $sidebarPending > 0): ?>
                <span style="position:absolute;top:5px;right:5px;min-width:16px;height:16px;border-radius:99px;background:#EF4444;border:2px solid white;font-size:9px;font-weight:900;color:white;display:flex;align-items:center;justify-content:center;padding:0 3px;"><?php echo e($sidebarPending); ?></span>
                <?php endif; ?>
            </button>
            <!-- CTA -->
            <a href="<?php echo e(route('canteen.menus.create')); ?>"
               style="background:linear-gradient(135deg,#1a3a5c,#2d6a8f);color:white;font-weight:800;font-size:12px;padding:9px 18px;border-radius:12px;text-decoration:none;display:flex;align-items:center;gap:6px;box-shadow:0 4px 14px rgba(45,106,143,0.35);transition:all .2s;"
               onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 18px rgba(45,106,143,0.45)'"
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(45,106,143,0.35)'">
                <i class="fas fa-plus text-xs"></i> Tambah Menu
            </a>
        </div>
    </header>

    <div class="page-content">
        <?php echo $__env->yieldContent('content'); ?>
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
</script>
<?php echo $__env->yieldContent('extra-js'); ?>
</body>
</html>
<?php /**PATH D:\PROJECT WEB\WEB KANTIN\hulahup-localhost\resources\views/layouts/kantin.blade.php ENDPATH**/ ?>