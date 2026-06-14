<?php $__env->startSection('title', 'Monitoring Pesanan'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6">

        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#0B2D5C]">Monitoring Pesanan</h2>
                <p class="text-slate-500 text-sm mt-0.5">Pantau dan kelola semua transaksi Food-TYU secara real-time.</p>
            </div>
            <?php
                $pendingNow = \App\Models\Order::where('status','pending')->count();
                $processingNow = \App\Models\Order::where('status','processing')->count();
                $completedNow = \App\Models\Order::where('status','completed')->count();
            ?>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1.5 bg-orange-50 border border-orange-100 rounded-full px-3 py-1.5">
                    <span class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></span>
                    <span class="text-orange-700 text-xs font-black"><?php echo e($pendingNow); ?> Pending</span>
                </div>
                <div class="flex items-center gap-1.5 bg-blue-50 border border-blue-100 rounded-full px-3 py-1.5">
                    <i class="fas fa-spinner fa-spin text-blue-500 text-[10px]"></i>
                    <span class="text-blue-700 text-xs font-black"><?php echo e($processingNow); ?> Diproses</span>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border border-[#0B2D5C]/05 shadow-sm p-5">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Status Pesanan</label>
                    <select name="status" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#0B2D5C]/20 focus:border-[#0B2D5C] outline-none transition">
                        <option value="all" <?php echo e($currentStatus === 'all' ? 'selected' : ''); ?>>Semua Status</option>
                        <option value="pending" <?php echo e($currentStatus === 'pending' ? 'selected' : ''); ?>>⏳ Pending</option>
                        <option value="processing" <?php echo e($currentStatus === 'processing' ? 'selected' : ''); ?>>🔄 Diproses</option>
                        <option value="completed" <?php echo e($currentStatus === 'completed' ? 'selected' : ''); ?>>✅ Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kantin</label>
                    <select name="canteen_id" class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-[#0B2D5C]/20 focus:border-[#0B2D5C] outline-none transition">
                        <option value="all" <?php echo e($currentCanteen === 'all' ? 'selected' : ''); ?>>Semua Kantin</option>
                        <?php $__currentLoopData = $canteens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $canteen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($canteen->id); ?>" <?php echo e($currentCanteen == $canteen->id ? 'selected' : ''); ?>><?php echo e($canteen->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex items-center gap-2 bg-[#0B2D5C] text-white text-sm font-black px-5 py-2.5 rounded-xl hover:bg-[#1a4a82] transition shadow-sm">
                        <i class="fas fa-search text-xs"></i> Filter
                    </button>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="flex items-center gap-2 bg-slate-100 text-slate-600 text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-slate-200 transition">
                        <i class="fas fa-rotate-left text-xs"></i> Reset
                    </a>
                </div>
            </form>
        </div>


        
        <div class="bg-white rounded-2xl border border-[#0B2D5C]/05 shadow-sm overflow-hidden">
            <?php if($orders->count() > 0): ?>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-50">
                            <th class="px-5 py-3.5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">No. Pesanan</th>
                            <th class="px-4 py-3.5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Pelanggan</th>
                            <th class="px-4 py-3.5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Kantin</th>
                            <th class="px-4 py-3.5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</th>
                            <th class="px-4 py-3.5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-4 py-3.5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu</th>
                            <th class="px-4 py-3.5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50" id="ordersTableBody">
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                            ?>
                            <tr class="hover:bg-slate-50/70 transition" data-order-id="<?php echo e($order->id); ?>">
                                <td class="px-5 py-3.5"><span class="font-black text-[#0B2D5C] text-xs bg-[#0B2D5C]/6 px-2.5 py-1 rounded-lg">#<?php echo e($order->order_number); ?></span></td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-gradient-to-br from-[#7AB8FF] to-[#F5A8D0] rounded-lg flex items-center justify-center text-white font-black text-[10px] flex-shrink-0"><?php echo e(strtoupper(substr($order->user->name ?? '?',0,1))); ?></div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-800"><?php echo e($order->user->name); ?></div>
                                            <div class="text-[10px] text-slate-400"><?php echo e($order->user->email); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="text-xs font-bold text-[#2D6A8F] bg-blue-50 px-2.5 py-1 rounded-lg"><?php echo e($order->canteen->name ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-4 py-3.5 font-black text-slate-800 text-xs">Rp <?php echo e(number_format($order->total_amount, 0, ',', '.')); ?></td>
                                <td class="px-4 py-3.5">
                                    <?php if($order->status === 'pending'): ?>
                                        <span class="inline-flex items-center gap-1 bg-orange-50 text-orange-600 border border-orange-100 px-2.5 py-1 rounded-full text-[10px] font-black"><span class="w-1.5 h-1.5 bg-orange-400 rounded-full animate-pulse"></span>Pending</span>
                                    <?php elseif($order->status === 'processing'): ?>
                                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-1 rounded-full text-[10px] font-black"><i class="fas fa-spinner fa-spin text-[8px]"></i>Diproses</span>
                                    <?php elseif($order->status === 'completed'): ?>
                                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-600 border border-green-100 px-2.5 py-1 rounded-full text-[10px] font-black"><i class="fas fa-check text-[8px]"></i>Selesai</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 border border-red-100 px-2.5 py-1 rounded-full text-[10px] font-black"><i class="fas fa-times text-[8px]"></i>Batal</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-slate-500 font-medium"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></td>
                                <td class="px-6 py-4 text-center">
                                    <?php
                                        $orderData = [
                                            'id'            => $order->id,
                                            'order_number'  => $order->order_number,
                                            'customer'      => $order->user->name,
                                            'email'         => $order->user->email,
                                            'canteen'       => $order->canteen->name ?? 'N/A',
                                            'ibu_kantin'    => optional(optional($order->canteen)->ibuKantin)->name ?? '-',
                                            'items'         => $items,
                                            'total'         => $order->total_amount,
                                            'status'        => $order->status,
                                            'notes'         => $order->notes,
                                            'created_at'    => $order->created_at->format('d M Y, H:i'),
                                            'processing_at' => $order->processing_at?->format('d M Y, H:i') ?? null,
                                            'completed_at'  => $order->completed_at?->format('d M Y, H:i') ?? null,
                                        ];
                                    ?>
                                    <button
                                        onclick="showOrderDetail(this)"
                                        data-order="<?php echo e(json_encode($orderData, JSON_HEX_QUOT | JSON_HEX_APOS)); ?>"
                                        class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2 rounded-lg transition">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t">
                    <?php echo e($orders->links()); ?>

                </div>
            <?php else: ?>
                <div class="px-6 py-16 text-center">
                    <i class="fas fa-inbox text-4xl text-slate-200 mb-4 block"></i>
                    <p class="text-slate-400 font-medium text-sm">Tidak ada pesanan yang sesuai filter</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl p-5 text-white">
                <div class="w-9 h-9 bg-white/15 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-clock"></i></div>
                <p class="text-3xl font-black"><?php echo e(\App\Models\Order::where('status', 'pending')->count()); ?></p>
                <p class="text-white/70 text-xs font-bold mt-1">Menunggu</p>
            </div>
            <div class="bg-gradient-to-br from-[#2D6A8F] to-[#0B2D5C] rounded-2xl p-5 text-white">
                <div class="w-9 h-9 bg-white/15 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-spinner"></i></div>
                <p class="text-3xl font-black"><?php echo e(\App\Models\Order::where('status', 'processing')->count()); ?></p>
                <p class="text-white/70 text-xs font-bold mt-1">Diproses</p>
            </div>
            <div class="bg-gradient-to-br from-emerald-600 to-teal-500 rounded-2xl p-5 text-white">
                <div class="w-9 h-9 bg-white/15 rounded-xl flex items-center justify-center mb-3"><i class="fas fa-check-circle"></i></div>
                <p class="text-3xl font-black"><?php echo e(\App\Models\Order::where('status', 'completed')->count()); ?></p>
                <p class="text-white/70 text-xs font-bold mt-1">Selesai</p>
            </div>
        </div>

    </div>
</div>

<!-- ===================== MODAL DETAIL PESANAN ===================== -->
<div id="orderDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-gradient-to-r from-[#122C4F] to-[#1e4a78] text-white p-6 rounded-t-[30px] flex justify-between items-center">
            <div>
                <h2 class="text-xl font-black" id="modalOrderNumber">—</h2>
                <p class="text-blue-200 text-sm mt-0.5">Detail Lengkap Pesanan</p>
            </div>
            <button onclick="closeOrderModal()" class="w-9 h-9 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-5">
            <!-- Status Badge -->
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-500">Status:</span>
                <span id="modalStatusBadge" class="px-4 py-1 rounded-full text-sm font-bold">—</span>
            </div>

            <!-- Info Pelanggan -->
            <div class="bg-blue-50 rounded-2xl p-4 space-y-2">
                <p class="text-xs font-black text-blue-400 uppercase tracking-widest mb-2">👤 Pelanggan</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Nama</span>
                    <span class="font-bold text-gray-800" id="modalCustomer">—</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium text-gray-700 text-xs" id="modalEmail">—</span>
                </div>
            </div>

            <!-- Info Kantin -->
            <div class="bg-orange-50 rounded-2xl p-4 space-y-2">
                <p class="text-xs font-black text-orange-400 uppercase tracking-widest mb-2">🏪 Kantin</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Nama Kantin</span>
                    <span class="font-bold text-gray-800" id="modalCanteen">—</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Pemilik Kantin</span>
                    <span class="font-medium text-gray-700" id="modalIbuKantin">—</span>
                </div>
            </div>

            <!-- Items -->
            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">🛒 Item Pesanan</p>
                <div id="modalItems" class="space-y-2"></div>
                <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between items-center">
                    <span class="font-bold text-gray-700">Total Pembayaran</span>
                    <span class="text-xl font-black text-green-600" id="modalTotal">—</span>
                </div>
            </div>

            <!-- Catatan -->
            <div id="modalNotesSection" class="hidden bg-yellow-50 rounded-2xl p-4">
                <p class="text-xs font-black text-yellow-500 uppercase tracking-widest mb-2">📝 Catatan</p>
                <p class="text-sm text-gray-700" id="modalNotes">—</p>
            </div>

            <!-- Waktu -->
            <div class="bg-purple-50 rounded-2xl p-4 space-y-2">
                <p class="text-xs font-black text-purple-400 uppercase tracking-widest mb-2">🕐 Timeline</p>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Dipesan</span>
                    <span class="font-medium text-gray-700" id="modalCreatedAt">—</span>
                </div>
                <div class="flex justify-between text-sm" id="modalProcessingRow">
                    <span class="text-gray-500">Mulai Diproses</span>
                    <span class="font-medium text-gray-700" id="modalProcessingAt">—</span>
                </div>
                <div class="flex justify-between text-sm" id="modalCompletedRow">
                    <span class="text-gray-500">Selesai</span>
                    <span class="font-medium text-gray-700" id="modalCompletedAt">—</span>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 pb-6">
            <button onclick="closeOrderModal()" class="w-full bg-[#122C4F] hover:bg-[#1e4a78] text-white font-bold py-3 rounded-2xl transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function showOrderDetail(btn) {
    const order = JSON.parse(btn.getAttribute('data-order'));
    // Isi semua field modal
    document.getElementById('modalOrderNumber').textContent = order.order_number;
    document.getElementById('modalCustomer').textContent   = order.customer;
    document.getElementById('modalEmail').textContent      = order.email;
    document.getElementById('modalCanteen').textContent    = order.canteen;
    document.getElementById('modalIbuKantin').textContent  = order.ibu_kantin;
    document.getElementById('modalCreatedAt').textContent  = order.created_at;
    document.getElementById('modalProcessingAt').textContent = order.processing_at ?? '—';
    document.getElementById('modalCompletedAt').textContent  = order.completed_at  ?? '—';

    // Total
    document.getElementById('modalTotal').textContent =
        'Rp ' + parseInt(order.total).toLocaleString('id-ID');

    // Status badge
    const badge = document.getElementById('modalStatusBadge');
    const statusMap = {
        pending:    { label: '⏳ Menunggu',  cls: 'bg-yellow-100 text-yellow-800' },
        processing: { label: '👨‍🍳 Diproses', cls: 'bg-blue-100 text-blue-800' },
        completed:  { label: '✅ Selesai',   cls: 'bg-green-100 text-green-800' },
        cancelled:  { label: '❌ Dibatalkan',cls: 'bg-red-100 text-red-800' },
    };
    const s = statusMap[order.status] ?? { label: order.status, cls: 'bg-gray-100 text-gray-700' };
    badge.textContent = s.label;
    badge.className   = 'px-4 py-1 rounded-full text-sm font-bold ' + s.cls;

    // Items
    const itemsContainer = document.getElementById('modalItems');
    itemsContainer.innerHTML = '';
    if (order.items && order.items.length > 0) {
        order.items.forEach(item => {
            const qty  = item.qty ?? item.quantity ?? 1;
            const price = item.price ?? 0;
            const subtotal = qty * price;
            itemsContainer.innerHTML += `
                <div class="flex justify-between items-center text-sm py-1 border-b border-gray-100 last:border-0">
                    <span class="text-gray-700 font-medium">${item.name} <span class="text-gray-400">×${qty}</span></span>
                    <span class="font-bold text-gray-800">Rp ${subtotal.toLocaleString('id-ID')}</span>
                </div>`;
        });
    } else {
        itemsContainer.innerHTML = '<p class="text-gray-400 text-sm">Data item tidak tersedia</p>';
    }

    // Catatan
    if (order.notes) {
        document.getElementById('modalNotes').textContent = order.notes;
        document.getElementById('modalNotesSection').classList.remove('hidden');
    } else {
        document.getElementById('modalNotesSection').classList.add('hidden');
    }

    // Tampilkan modal
    const modal = document.getElementById('orderDetailModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeOrderModal() {
    const modal = document.getElementById('orderDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Tutup kalau klik backdrop
document.getElementById('orderDetailModal').addEventListener('click', function(e) {
    if (e.target === this) closeOrderModal();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\PROJECT WEB\WEB KANTIN\hulahup-localhost\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>