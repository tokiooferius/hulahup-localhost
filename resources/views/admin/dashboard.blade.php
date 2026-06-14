@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('extra-css')
<style>
    .stat-gradient-1 { background: linear-gradient(135deg, #0B2D5C, #1a4a82); }
    .stat-gradient-2 { background: linear-gradient(135deg, #2D6A8F, #3a85b0); }
    .stat-gradient-3 { background: linear-gradient(135deg, #c2410c, #ea580c); }
    .stat-gradient-4 { background: linear-gradient(135deg, #065f46, #059669); }
    .stat-gradient-5 { background: linear-gradient(135deg, #7c3aed, #a855f7); }
    .stat-gradient-6 { background: linear-gradient(135deg, #be185d, #F472B6); }

    @keyframes shimmer {
        0%   { background-position: -200px 0; }
        100% { background-position: calc(200px + 100%) 0; }
    }
    .revenue-num {
        background: linear-gradient(135deg, #0B2D5C, #7AB8FF, #F5A8D0);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .order-row { transition: background .15s; }
    .order-row:hover { background: #f8faff; }
</style>
@endsection

@section('content')
<div class="p-6 md:p-8">
    <div class="max-w-7xl mx-auto space-y-7">

        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-[#0B2D5C]">Selamat datang, {{ Auth::user()->name }}! 👑</h2>
                <p class="text-slate-500 text-sm mt-0.5">Pantau semua aktivitas Food-TYU secara real-time.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="/admin/orders" class="flex items-center gap-2 bg-[#0B2D5C] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a4a82] transition shadow-sm">
                    <i class="fas fa-receipt text-xs"></i> Monitoring Pesanan
                </a>
                <a href="/admin/export" class="flex items-center gap-2 bg-white border border-[#0B2D5C]/15 text-[#0B2D5C] text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-slate-50 transition shadow-sm">
                    <i class="fas fa-file-excel text-green-600 text-xs"></i> Export CSV
                </a>
            </div>
        </div>

        {{-- ===== STAT CARDS ===== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $statCards = [
                    ['grad'=>'stat-gradient-1','icon'=>'fa-receipt','label'=>'Total Pesanan','value'=> $totalOrders, 'sub'=>'Semua waktu','suffix'=>''],
                    ['grad'=>'stat-gradient-3','icon'=>'fa-clock','label'=>'Pending','value'=> $pendingOrders, 'sub'=>'Menunggu konfirmasi','suffix'=>''],
                    ['grad'=>'stat-gradient-2','icon'=>'fa-spinner','label'=>'Diproses','value'=> $processingOrders, 'sub'=>'Sedang dikerjakan','suffix'=>''],
                    ['grad'=>'stat-gradient-4','icon'=>'fa-check-circle','label'=>'Selesai','value'=> $completedOrders, 'sub'=>'Berhasil diselesaikan','suffix'=>''],
                ];
            @endphp
            @foreach($statCards as $i => $sc)
            <div class="{{ $sc['grad'] }} rounded-2xl p-5 text-white shadow-lg" style="animation: fadeInUp .4s ease {{ $i*0.08 }}s both">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center">
                        <i class="fas {{ $sc['icon'] }} text-sm"></i>
                    </div>
                    <span class="text-white/40 text-[10px] font-bold uppercase tracking-widest">{{ $sc['sub'] }}</span>
                </div>
                <p class="text-3xl font-black leading-none mb-1">{{ number_format($sc['value']) }}{{ $sc['suffix'] }}</p>
                <p class="text-white/60 text-xs font-bold">{{ $sc['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- ===== REVENUE + EXTRA STATS ===== --}}
        @php
            $totalRevenue = \App\Models\Order::where('status','completed')->sum('total_amount');
            $totalUsers   = \App\Models\User::where('role','!=','admin')->count();
            $totalMenus   = \App\Models\Menu::where('is_available',true)->count();
            $totalKantin  = \App\Models\Canteen::count();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Revenue Card -->
            <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-[#0B2D5C]/05 shadow-sm">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Total Pendapatan Platform</p>
                <p class="text-4xl font-black revenue-num mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-slate-400 text-xs font-medium">Dari semua transaksi pesanan selesai</p>
                <div class="flex gap-6 mt-5 pt-5 border-t border-slate-50">
                    <div>
                        <p class="text-2xl font-black text-[#0B2D5C]">{{ $totalUsers }}</p>
                        <p class="text-xs text-slate-400 font-bold">Pengguna Aktif</p>
                    </div>
                    <div class="w-px bg-slate-100"></div>
                    <div>
                        <p class="text-2xl font-black text-[#0B2D5C]">{{ $totalMenus }}</p>
                        <p class="text-xs text-slate-400 font-bold">Menu Tersedia</p>
                    </div>
                    <div class="w-px bg-slate-100"></div>
                    <div>
                        <p class="text-2xl font-black text-[#0B2D5C]">{{ $totalKantin }}</p>
                        <p class="text-xs text-slate-400 font-bold">Kantin Aktif</p>
                    </div>
                </div>
            </div>

            <!-- Kantin Stats -->
            <div class="bg-white rounded-2xl p-6 border border-[#0B2D5C]/05 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <p class="font-black text-[#0B2D5C] text-sm">Performa Kantin</p>
                    <a href="/admin/canteens" class="text-[10px] text-blue-600 font-bold hover:underline">Kelola →</a>
                </div>
                <div class="space-y-3">
                    @foreach($canteenStats->take(4) as $cs)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#0B2D5C] to-[#2D6A8F] rounded-lg flex items-center justify-center text-white font-black text-xs flex-shrink-0">
                            {{ strtoupper(substr($cs->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-700 truncate">{{ Str::limit($cs->name, 20) }}</p>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-1">
                                <div class="bg-gradient-to-r from-[#0B2D5C] to-[#7AB8FF] h-1.5 rounded-full"
                                     style="width: {{ $totalOrders > 0 ? min(100, ($cs->orders_count / max($totalOrders,1)) * 300) : 10 }}%"></div>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-[#0B2D5C] flex-shrink-0">{{ $cs->orders_count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ===== RECENT ORDERS ===== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#0B2D5C]/05 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-[#0B2D5C]/8 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-[#0B2D5C] text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-black text-[#0B2D5C] text-sm">Pesanan Terbaru</h2>
                        <p class="text-[10px] text-slate-400 font-medium">Update real-time</p>
                    </div>
                </div>
                <a href="/admin/orders" class="text-xs font-black text-[#2D6A8F] hover:text-[#0B2D5C] transition flex items-center gap-1">
                    Lihat Semua <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-50">
                            <th class="text-left py-3 px-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Order</th>
                            <th class="text-left py-3 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pelanggan</th>
                            <th class="text-left py-3 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kantin</th>
                            <th class="text-right py-3 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</th>
                            <th class="text-center py-3 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr class="order-row border-b border-slate-50/70">
                            <td class="py-3.5 px-6">
                                <span class="font-black text-[#0B2D5C] text-xs bg-[#0B2D5C]/6 px-2.5 py-1 rounded-lg">#{{ $order->order_number }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 bg-gradient-to-br from-[#7AB8FF] to-[#F5A8D0] rounded-lg flex items-center justify-center text-white font-black text-[10px] flex-shrink-0">
                                        {{ strtoupper(substr($order->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-700 text-xs">{{ $order->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-xs font-bold text-[#2D6A8F] bg-blue-50 px-2.5 py-1 rounded-lg">
                                    {{ $order->canteen?->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-black text-slate-800 text-xs">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($order->status === 'pending')
                                    <span class="inline-flex items-center gap-1 bg-orange-50 text-orange-600 border border-orange-100 px-2.5 py-1 rounded-full text-[10px] font-black">
                                        <span class="w-1.5 h-1.5 bg-orange-400 rounded-full animate-pulse"></span> Pending
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-1 rounded-full text-[10px] font-black">
                                        <i class="fas fa-spinner fa-spin text-[9px]"></i> Diproses
                                    </span>
                                @elseif($order->status === 'completed')
                                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-600 border border-green-100 px-2.5 py-1 rounded-full text-[10px] font-black">
                                        <i class="fas fa-check text-[9px]"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 border border-red-100 px-2.5 py-1 rounded-full text-[10px] font-black">
                                        <i class="fas fa-times text-[9px]"></i> Batal
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-14 text-center">
                                <i class="fas fa-inbox text-3xl text-slate-200 mb-3 block"></i>
                                <p class="text-slate-400 font-medium text-sm">Belum ada pesanan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== QUICK ACTIONS ===== --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $quickActions = [
                    ['href'=>'/admin/canteens','icon'=>'fa-store','label'=>'Kelola Kantin','sub'=>'Tambah & edit kantin','grad'=>'from-[#0B2D5C] to-[#2D6A8F]'],
                    ['href'=>'/admin/users','icon'=>'fa-users','label'=>'Users','sub'=>'Manajemen pengguna','grad'=>'from-[#7c3aed] to-[#a855f7]'],
                    ['href'=>'/admin/vouchers','icon'=>'fa-ticket','label'=>'Vouchers','sub'=>'Kelola kode diskon','grad'=>'from-[#be185d] to-[#F472B6]'],
                    ['href'=>'/admin/export','icon'=>'fa-file-excel','label'=>'Export CSV','sub'=>'Download laporan','grad'=>'from-[#065f46] to-[#059669]'],
                ];
            @endphp
            @foreach($quickActions as $qa)
            <a href="{{ $qa['href'] }}"
               class="bg-gradient-to-br {{ $qa['grad'] }} rounded-2xl p-5 text-white hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
                <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center mb-3 group-hover:bg-white/25 transition">
                    <i class="fas {{ $qa['icon'] }}"></i>
                </div>
                <p class="font-black text-sm">{{ $qa['label'] }}</p>
                <p class="text-white/55 text-[10px] mt-0.5">{{ $qa['sub'] }}</p>
            </a>
            @endforeach
        </div>

        <!-- Footer -->
        <p class="text-center text-slate-400 text-xs pb-2">
            Food-TYU Admin Dashboard &bull; Update terakhir: <span id="lastUpdate"></span>
        </p>

    </div>
</div>
@endsection

@section('extra-js')
<script>
    function updateTime() {
        document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString('id-ID');
    }
    updateTime();
    setInterval(updateTime, 1000);
</script>
@endsection
