<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>

<style>
    .stat-card { background: white; border-radius: 20px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); transition: all .25s; position: relative; overflow: hidden; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,0.1); }
    .stat-card::before { content:''; position:absolute; top:-30px; right:-20px; width:90px; height:90px; border-radius:50%; opacity:.07; }

    .quick-card { border-radius: 20px; padding: 22px; text-decoration: none; display: flex; flex-direction: column; align-items: flex-start; gap: 10px; transition: all .25s; position: relative; overflow: hidden; }
    .quick-card:hover { transform: translateY(-4px); filter: brightness(1.06); box-shadow: 0 10px 28px rgba(0,0,0,0.18); }
    .quick-card .qicon { width:46px; height:46px; border-radius:14px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:20px; }

    .order-row { padding: 14px 18px; border-radius: 14px; border: 1px solid #F1F5F9; transition: all .2s; display:flex; align-items:center; gap:12px; }
    .order-row:hover { background: #F8FAFC; border-color: #E2E8F0; transform: translateX(3px); }

    .status-pill { font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 99px; }
    .status-pending    { background:#FEF3C7; color:#92400E; }
    .status-processing { background:#DBEAFE; color:#1E40AF; }
    .status-completed  { background:#D1FAE5; color:#065F46; }
    .status-cancelled  { background:#FEE2E2; color:#991B1B; }

    @keyframes countUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
    .stat-num { animation: countUp .5s ease forwards; }
</style>

<div style="max-width:1200px;">

    
    <div style="background:linear-gradient(135deg, #1a3a5c 0%, #2d6a8f 60%, #3a7fa8 100%); border-radius:24px; padding:28px 32px; margin-bottom:28px; position:relative; overflow:hidden;">
        <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.05);"></div>
        <div style="position:absolute;bottom:-60px;right:80px;width:160px;height:160px;border-radius:50%;background:rgba(234,216,177,0.06);"></div>
        <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div>
                <p style="color:rgba(255,255,255,0.6);font-size:13px;font-weight:600;margin-bottom:4px;">Selamat datang kembali 👋</p>
                <h2 style="color:white;font-size:26px;font-weight:900;line-height:1.2;"><?php echo e(Auth::user()->name); ?></h2>
                <p style="color:rgba(234,216,177,0.8);font-size:13px;font-weight:600;margin-top:6px;">🏪 <?php echo e($canteen->name ?? 'Kantin Kamu'); ?></p>
            </div>
            <div style="text-align:right;">
                <p style="color:rgba(255,255,255,0.5);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Saldo Kantin</p>
                <p style="color:white;font-size:28px;font-weight:900;letter-spacing:-1px;">Rp <?php echo e(number_format($canteen->balance ?? 0, 0, ',', '.')); ?></p>
                <p style="color:rgba(255,255,255,0.45);font-size:11px;margin-top:2px;"><?php echo e(now()->format('d M Y')); ?></p>
            </div>
        </div>
    </div>

    
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">

        <div class="stat-card" style="border-top: 3px solid #3B82F6;">
            <div style="::before{background:#3B82F6;}display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:12px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:0.08em;">Total Menu</p>
                    <p class="stat-num" style="font-size:36px;font-weight:900;color:#0f1f3d;line-height:1.1;margin-top:6px;"><?php echo e($totalMenus); ?></p>
                </div>
                <div style="width:48px;height:48px;border-radius:14px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;font-size:22px;">🍽️</div>
            </div>
            <p style="font-size:12px;color:#94A3B8;margin-top:12px;">item tersedia</p>
        </div>

        <div class="stat-card" style="border-top: 3px solid #8B5CF6;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:12px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:0.08em;">Voucher Aktif</p>
                    <p class="stat-num" style="font-size:36px;font-weight:900;color:#0f1f3d;line-height:1.1;margin-top:6px;"><?php echo e($totalVouchers); ?></p>
                </div>
                <div style="width:48px;height:48px;border-radius:14px;background:#F5F3FF;display:flex;align-items:center;justify-content:center;font-size:22px;">🎟️</div>
            </div>
            <p style="font-size:12px;color:#94A3B8;margin-top:12px;">promo berjalan</p>
        </div>

        <div class="stat-card" style="border-top: 3px solid #F97316;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:12px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:0.08em;">Total Pesanan</p>
                    <p class="stat-num" style="font-size:36px;font-weight:900;color:#0f1f3d;line-height:1.1;margin-top:6px;"><?php echo e($totalOrders); ?></p>
                </div>
                <div style="width:48px;height:48px;border-radius:14px;background:#FFF7ED;display:flex;align-items:center;justify-content:center;font-size:22px;">📦</div>
            </div>
            <p style="font-size:12px;color:#94A3B8;margin-top:12px;">semua waktu</p>
        </div>

        <div class="stat-card" style="border-top: 3px solid #10B981;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:12px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:0.08em;">Pendapatan</p>
                    <p class="stat-num" style="font-size:26px;font-weight:900;color:#059669;line-height:1.1;margin-top:6px;">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></p>
                </div>
                <div style="width:48px;height:48px;border-radius:14px;background:#ECFDF5;display:flex;align-items:center;justify-content:center;font-size:22px;">💰</div>
            </div>
            <p style="font-size:12px;color:#94A3B8;margin-top:12px;">total terkumpul</p>
        </div>
    </div>

    
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
        <div style="background:linear-gradient(135deg,#FFF7ED,#FED7AA);border-radius:20px;padding:22px;border-left:4px solid #F97316;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <p style="font-size:12px;font-weight:800;color:#C2410C;text-transform:uppercase;letter-spacing:0.08em;">Pending</p>
                <p style="font-size:40px;font-weight:900;color:#EA580C;line-height:1;"><?php echo e($pendingOrders); ?></p>
                <p style="font-size:12px;color:#C2410C;margin-top:4px;">menunggu diproses</p>
            </div>
            <div style="font-size:40px;opacity:.5;">⏳</div>
        </div>
        <div style="background:linear-gradient(135deg,#EFF6FF,#BFDBFE);border-radius:20px;padding:22px;border-left:4px solid #3B82F6;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <p style="font-size:12px;font-weight:800;color:#1D4ED8;text-transform:uppercase;letter-spacing:0.08em;">Diproses</p>
                <p style="font-size:40px;font-weight:900;color:#2563EB;line-height:1;"><?php echo e($processingOrders); ?></p>
                <p style="font-size:12px;color:#1D4ED8;margin-top:4px;">sedang dimasak</p>
            </div>
            <div style="font-size:40px;opacity:.5;">🔄</div>
        </div>
        <div style="background:linear-gradient(135deg,#F0FDF4,#BBF7D0);border-radius:20px;padding:22px;border-left:4px solid #22C55E;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <p style="font-size:12px;font-weight:800;color:#15803D;text-transform:uppercase;letter-spacing:0.08em;">Selesai</p>
                <p style="font-size:40px;font-weight:900;color:#16A34A;line-height:1;"><?php echo e($completedOrders); ?></p>
                <p style="font-size:12px;color:#15803D;margin-top:4px;">sudah diambil</p>
            </div>
            <div style="font-size:40px;opacity:.5;">✅</div>
        </div>
    </div>

    
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;">
        <a href="<?php echo e(route('canteen.menus.create')); ?>" class="quick-card" style="background:linear-gradient(135deg,#1a3a5c,#2d6a8f);color:white;">
            <div class="qicon"><i class="fas fa-plus"></i></div>
            <div>
                <p style="font-weight:800;font-size:14px;">Tambah Menu</p>
                <p style="font-size:12px;opacity:.7;margin-top:2px;">Tambah item baru</p>
            </div>
        </a>
        <a href="<?php echo e(route('canteen.vouchers.create')); ?>" class="quick-card" style="background:linear-gradient(135deg,#6D28D9,#8B5CF6);color:white;">
            <div class="qicon"><i class="fas fa-ticket-alt"></i></div>
            <div>
                <p style="font-weight:800;font-size:14px;">Buat Voucher</p>
                <p style="font-size:12px;opacity:.7;margin-top:2px;">Promo & diskon baru</p>
            </div>
        </a>
        <a href="<?php echo e(route('canteen.sales')); ?>" class="quick-card" style="background:linear-gradient(135deg,#065F46,#10B981);color:white;">
            <div class="qicon"><i class="fas fa-chart-bar"></i></div>
            <div>
                <p style="font-weight:800;font-size:14px;">Lihat Penjualan</p>
                <p style="font-size:12px;opacity:.7;margin-top:2px;">Laporan pendapatan</p>
            </div>
        </a>
        <a href="<?php echo e(route('canteen.orders.index')); ?>" class="quick-card" style="background:linear-gradient(135deg,#92400E,#F97316);color:white;">
            <div class="qicon"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <p style="font-weight:800;font-size:14px;">Kelola Pesanan</p>
                <p style="font-size:12px;opacity:.7;margin-top:2px;">Update status realtime</p>
            </div>
        </a>
    </div>

    
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        
        <div style="background:white;border-radius:22px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                <h3 style="font-size:15px;font-weight:800;color:#0f1f3d;display:flex;align-items:center;gap:8px;"><i class="fas fa-receipt" style="color:#2d6a8f;"></i> Pesanan Terbaru</h3>
                <a href="<?php echo e(route('canteen.sales')); ?>" style="font-size:12px;color:#2d6a8f;font-weight:700;text-decoration:none;">Lihat semua →</a>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="order-row">
                <div style="width:38px;height:38px;border-radius:12px;background:#F0F4F8;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                    <?php if($order->status==='completed'): ?>✅<?php elseif($order->status==='processing'): ?>🔄<?php elseif($order->status==='pending'): ?>⏳<?php else: ?>❌<?php endif; ?>
                </div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:13px;font-weight:700;color:#1e293b;"><?php echo e($order->order_number); ?></p>
                    <p style="font-size:12px;color:#94A3B8;"><?php echo e($order->user->name); ?></p>
                </div>
                <div style="text-align:right;">
                    <p style="font-size:13px;font-weight:800;color:#0f1f3d;">Rp <?php echo e(number_format($order->total_amount,0,',','.')); ?></p>
                    <span class="status-pill status-<?php echo e($order->status); ?>"><?php echo e(ucfirst($order->status)); ?></span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:32px;color:#CBD5E1;">
                <i class="fas fa-inbox" style="font-size:36px;margin-bottom:8px;display:block;"></i>
                <p style="font-size:13px;font-weight:600;">Belum ada pesanan</p>
            </div>
            <?php endif; ?>
        </div>

        
        <div style="background:white;border-radius:22px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                <h3 style="font-size:15px;font-weight:800;color:#0f1f3d;display:flex;align-items:center;gap:8px;"><i class="fas fa-credit-card" style="color:#8B5CF6;"></i> Pembayaran Pending</h3>
                <a href="<?php echo e(route('canteen.payments')); ?>" style="font-size:12px;color:#2d6a8f;font-weight:700;text-decoration:none;">Lihat semua →</a>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $pendingPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="order-row">
                <div style="width:38px;height:38px;border-radius:12px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">💳</div>
                <div style="flex:1;min-width:0;">
                    <p style="font-size:12px;font-weight:700;color:#1e293b;font-family:monospace;"><?php echo e(substr($payment->payment->transaction_code,0,16)); ?>…</p>
                    <p style="font-size:12px;color:#94A3B8;"><?php echo e($payment->payment->user->name); ?></p>
                </div>
                <div style="text-align:right;">
                    <p style="font-size:13px;font-weight:800;color:#0f1f3d;">Rp <?php echo e(number_format($payment->amount_for_canteen,0,',','.')); ?></p>
                    <span class="status-pill" style="background:#FEF3C7;color:#92400E;">⏳ Menunggu</span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="text-align:center;padding:32px;color:#CBD5E1;">
                <i class="fas fa-check-circle" style="font-size:36px;margin-bottom:8px;display:block;color:#86EFAC;"></i>
                <p style="font-size:13px;font-weight:600;">Semua pembayaran beres!</p>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.kantin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\PROJECT WEB\WEB KANTIN\hulahup-localhost\resources\views/canteen/dashboard.blade.php ENDPATH**/ ?>