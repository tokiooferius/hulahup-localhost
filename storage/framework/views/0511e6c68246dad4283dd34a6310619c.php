<?php $__env->startSection('page-title', 'Kelola Pesanan'); ?>
<?php $__env->startSection('content'); ?>
<div>
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-4xl font-black text-[#122C4F] mb-2">📋 Kelola Status Pesanan</h1>
        <p class="text-slate-600 font-medium">Update status pesanan secara real-time dari pending hingga selesai</p>
    </div>

    <!-- Status Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
        <div class="bg-white rounded-xl p-6 shadow-md border-l-4 border-orange-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Pesanan Pending</p>
                    <h3 class="text-3xl font-bold text-orange-600"><?php echo e($pendingOrders->count()); ?></h3>
                </div>
                <span class="text-3xl">⏳</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Sedang Diproses</p>
                    <h3 class="text-3xl font-bold text-blue-600"><?php echo e($processingOrders->count()); ?></h3>
                </div>
                <span class="text-3xl">🔄</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Selesai</p>
                    <h3 class="text-3xl font-bold text-green-600"><?php echo e($completedOrders->count()); ?></h3>
                </div>
                <span class="text-3xl">✅</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md border-l-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Pesanan</p>
                    <h3 class="text-3xl font-bold text-purple-600"><?php echo e($totalOrders); ?></h3>
                </div>
                <span class="text-3xl">📦</span>
            </div>
        </div>
    </div>

    <!-- Tabs for different statuses -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="flex border-b">
            <button class="tab-btn active flex-1 py-4 px-6 font-bold text-center border-b-4 border-orange-500 bg-orange-50 text-orange-700 transition" data-tab="pending">
                ⏳ PENDING (<?php echo e($pendingOrders->count()); ?>)
            </button>
            <button class="tab-btn flex-1 py-4 px-6 font-bold text-center border-b-4 border-transparent text-slate-600 hover:bg-slate-50 transition" data-tab="processing">
                🔄 DIPROSES (<?php echo e($processingOrders->count()); ?>)
            </button>
            <button class="tab-btn flex-1 py-4 px-6 font-bold text-center border-b-4 border-transparent text-slate-600 hover:bg-slate-50 transition" data-tab="completed">
                ✅ SELESAI (<?php echo e($completedOrders->count()); ?>)
            </button>
        </div>

        <!-- Pending Orders Tab -->
        <div id="pending" class="tab-content p-8 active">
            <?php $__empty_1 = true; $__currentLoopData = $pendingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                    $notes = $order->notes ?? '';
                    // Parse lokasi dari notes
                    $lokasiMatch = null;
                    if (preg_match('/(📍[^|]+)/', $notes, $m)) {
                        $lokasiMatch = trim($m[1]);
                    }
                    $isAntar = $lokasiMatch && str_contains($lokasiMatch, 'Antar ke:');
                ?>
                <div class="order-card bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg p-6 mb-4 border-l-4 border-orange-500 shadow-sm hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-[#122C4F]"><?php echo e($order->order_number); ?></h3>
                            <p class="text-sm text-slate-600">👤 <?php echo e($order->user->name); ?></p>
                        </div>
                        <span class="bg-orange-200 text-orange-700 px-4 py-2 rounded-full font-bold text-sm">⏳ PENDING</span>
                    </div>

                    
                    <?php if($lokasiMatch): ?>
                        <div class="mb-4 rounded-xl px-4 py-3 flex items-center gap-3 font-bold
                            <?php echo e($isAntar ? 'bg-blue-100 border border-blue-300 text-blue-800' : 'bg-green-100 border border-green-300 text-green-800'); ?>">
                            <span class="text-2xl"><?php echo e($isAntar ? '🛵' : '🏃'); ?></span>
                            <div>
                                <p class="text-xs uppercase tracking-widest opacity-70 mb-0.5"><?php echo e($isAntar ? 'ANTAR KE LOKASI' : 'AMBIL SENDIRI DI KANTIN'); ?></p>
                                <p class="text-sm"><?php echo e($lokasiMatch); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="mb-4 rounded-xl px-4 py-2 bg-gray-100 border border-gray-200 text-gray-600 text-sm flex items-center gap-2">
                            <span>📍</span> <span>Lokasi tidak dicantumkan</span>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Items</p>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="text-sm font-medium text-slate-700"><?php echo e($item['name']); ?> x<?php echo e($item['qty']); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Total</p>
                            <p class="text-lg font-bold text-orange-600">Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Waktu Pesan</p>
                            <p class="text-sm font-medium text-slate-700"><?php echo e($order->created_at->format('d M Y H:i')); ?></p>
                        </div>
                    </div>

                    <?php if($notes): ?>
                        <div class="mb-4 text-xs text-slate-500 bg-white/60 rounded-lg px-3 py-2">
                            📝 <?php echo e($notes); ?>

                        </div>
                    <?php endif; ?>

                    <div class="flex gap-3">
                        <button onclick="updateOrderStatus(<?php echo e($order->id); ?>, 'processing')" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                            ➜ Mulai Proses
                        </button>
                        <button onclick="updateOrderStatus(<?php echo e($order->id); ?>, 'cancelled')" class="px-6 py-3 bg-red-100 text-red-700 rounded-lg font-bold hover:bg-red-200 transition">
                            ❌ Batal
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12">
                    <p class="text-3xl mb-3">🎉</p>
                    <p class="text-slate-600 font-semibold">Tidak ada pesanan pending!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Processing Orders Tab -->
        <div id="processing" class="tab-content p-8 hidden">
            <?php $__empty_1 = true; $__currentLoopData = $processingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                    $notes = $order->notes ?? '';
                    $lokasiMatch = null;
                    if (preg_match('/(📍[^|]+)/', $notes, $m)) $lokasiMatch = trim($m[1]);
                    $isAntar = $lokasiMatch && str_contains($lokasiMatch, 'Antar ke:');
                ?>
                <div class="order-card bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-6 mb-4 border-l-4 border-blue-500 shadow-sm hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-[#122C4F]"><?php echo e($order->order_number); ?></h3>
                            <p class="text-sm text-slate-600">👤 <?php echo e($order->user->name); ?></p>
                        </div>
                        <span class="bg-blue-200 text-blue-700 px-4 py-2 rounded-full font-bold text-sm">🔄 DIPROSES</span>
                    </div>

                    
                    <?php if($lokasiMatch): ?>
                        <div class="mb-4 rounded-xl px-4 py-3 flex items-center gap-3 font-bold
                            <?php echo e($isAntar ? 'bg-blue-100 border border-blue-300 text-blue-800' : 'bg-green-100 border border-green-300 text-green-800'); ?>">
                            <span class="text-2xl"><?php echo e($isAntar ? '🛵' : '🏃'); ?></span>
                            <div>
                                <p class="text-xs uppercase tracking-widest opacity-70 mb-0.5"><?php echo e($isAntar ? 'ANTAR KE LOKASI' : 'AMBIL SENDIRI DI KANTIN'); ?></p>
                                <p class="text-sm"><?php echo e($lokasiMatch); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Items</p>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="text-sm font-medium text-slate-700"><?php echo e($item['name']); ?> x<?php echo e($item['qty']); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Total</p>
                            <p class="text-lg font-bold text-blue-600">Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Mulai Proses</p>
                            <p class="text-sm font-medium text-slate-700"><?php echo e($order->processing_at?->format('d M Y H:i') ?? '-'); ?></p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="updateOrderStatus(<?php echo e($order->id); ?>, 'completed')" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition">
                            ✅ Selesaikan
                        </button>
                        <button onclick="updateOrderStatus(<?php echo e($order->id); ?>, 'pending')" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-lg font-bold hover:bg-slate-300 transition">
                            ↩️ Kembali Pending
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12">
                    <p class="text-3xl mb-3">🎯</p>
                    <p class="text-slate-600 font-semibold">Tidak ada pesanan yang sedang diproses.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Completed Orders Tab -->
        <div id="completed" class="tab-content p-8 hidden">
            <?php $__empty_1 = true; $__currentLoopData = $completedOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="order-card bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6 mb-4 border-l-4 border-green-500 shadow-sm hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-[#122C4F]"><?php echo e($order->order_number); ?></h3>
                            <p class="text-sm text-slate-600">👤 <?php echo e($order->user->name); ?></p>
                        </div>
                        <span class="bg-green-200 text-green-700 px-4 py-2 rounded-full font-bold text-sm">✅ SELESAI</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Items</p>
                            <?php $items = is_string($order->items) ? json_decode($order->items, true) : $order->items; ?>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="text-sm font-medium text-slate-700"><?php echo e($item['name']); ?> x<?php echo e($item['qty']); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Total</p>
                            <p class="text-lg font-bold text-green-600">Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase">Waktu Selesai</p>
                            <p class="text-sm font-medium text-slate-700"><?php echo e($order->completed_at?->format('d M Y H:i') ?? '-'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12">
                    <p class="text-3xl mb-3">📭</p>
                    <p class="text-slate-600 font-semibold">Tidak ada pesanan yang selesai.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Back Button -->
</div>

<!-- JavaScript for Tab Switching and Status Updates -->
<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active state from all buttons
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active', 'border-orange-500', 'border-blue-500', 'border-green-500', 'bg-orange-50', 'bg-blue-50', 'bg-green-50');
                b.classList.add('border-transparent', 'text-slate-600');
            });

            // Remove active state from all content
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));

            // Add active state to clicked button
            this.classList.add('active');
            this.classList.remove('border-transparent', 'text-slate-600');
            this.classList.add('border-b-4');

            const tabName = this.getAttribute('data-tab');
            if (tabName === 'pending') {
                this.classList.add('border-orange-500', 'bg-orange-50', 'text-orange-700');
            } else if (tabName === 'processing') {
                this.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700');
            } else if (tabName === 'completed') {
                this.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
            }

            // Show active content
            document.getElementById(tabName).classList.remove('hidden');
        });
    });

    // Update order status
    async function updateOrderStatus(orderId, newStatus) {
        if (!confirm(`Ubah status pesanan ke ${newStatus.toUpperCase()}?`)) {
            return;
        }

        try {
            const response = await fetch(`/canteen/orders/${orderId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ status: newStatus }),
            });

            const data = await response.json();

            if (data.success) {
                alert('Status pesanan berhasil diperbarui!');
                location.reload();
            } else {
                alert('Gagal memperbarui status: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui status');
        }
    }
</script>

<style>
    .tab-btn.active {
        border-bottom-color: currentColor !important;
    }

    .tab-content.hidden {
        display: none;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .order-card {
        animation: slideIn 0.3s ease-out;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.kantin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\PROJECT WEB\WEB KANTIN\hulahup-localhost\resources\views/canteen/orders/index.blade.php ENDPATH**/ ?>