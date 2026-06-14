@extends('layouts.pembeli')

@section('title', 'Pesanan Aktif')

@section('page-title', 'Pesanan Aktif')
@section('content')
<div>
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-clock text-orange-500"></i>
                    Pesanan Aktif
                </h1>
                <p class="text-slate-600 mt-2">Pantau status pesanan kamu yang sedang diproses</p>
            </div>
            <div class="flex gap-3">
                <button onclick="location.reload()" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-sync"></i> Refresh
                </button>
                
            </div>
        </div>

        <!-- Auto Refresh Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 flex items-center gap-3">
            <i class="fas fa-info-circle text-blue-600"></i>
            <span class="text-blue-800 text-sm">Halaman akan auto-refresh setiap 10 detik untuk status terbaru</span>
        </div>

        @if($orders->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-16 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-bag text-4xl text-slate-400"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Tidak ada pesanan aktif</h3>
                <p class="text-slate-500 mb-6">Semua pesanan kamu sudah selesai, atau kamu belum melakukan pemesanan.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-[#122C4F] text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-800 transition">
                    <i class="fas fa-utensils"></i> Pesan Sekarang
                </a>
            </div>
        @else
            <!-- Orders Grid -->
            <div class="space-y-6">
                @foreach($orders as $order)
                    @php
                        $statusColors = [
                            'pending'    => ['bg' => 'bg-yellow-50',  'border' => 'border-yellow-400', 'badge' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-hourglass-start text-yellow-600'],
                            'processing' => ['bg' => 'bg-blue-50',    'border' => 'border-blue-400',   'badge' => 'bg-blue-100 text-blue-800',     'icon' => 'fas fa-fire text-blue-600'],
                            'completed'  => ['bg' => 'bg-green-50',   'border' => 'border-green-400',  'badge' => 'bg-green-100 text-green-800',   'icon' => 'fas fa-check-circle text-green-600'],
                        ];
                        $colors   = $statusColors[$order->status] ?? $statusColors['pending'];
                        $progress = ['pending' => 33, 'processing' => 66, 'completed' => 100][$order->status] ?? 33;
                        $items    = is_array($order->items) ? $order->items : (json_decode($order->items, true) ?? []);
                    @endphp

                    <div class="{{ $colors['bg'] }} border-l-4 {{ $colors['border'] }} rounded-2xl shadow-md p-6 hover:shadow-lg transition">
                        <!-- Header Row -->
                        <div class="flex items-start justify-between mb-5 pb-4 border-b border-slate-200">
                            <div>
                                <h3 class="text-lg font-black text-slate-900">{{ $order->order_number }}</h3>
                                <p class="text-sm text-slate-500 mt-0.5">
                                    🏪 {{ $order->canteen->name ?? 'N/A' }}
                                    @if($order->canteen?->ibuKantin)
                                        · {{ $order->canteen->ibuKantin->name }}
                                    @endif
                                </p>
                                <p class="text-xs text-slate-400 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1.5 {{ $colors['badge'] }} px-3 py-1.5 rounded-full text-sm font-bold">
                                    <i class="{{ $colors['icon'] }}"></i>
                                    @if($order->status === 'pending') ⏳ Menunggu
                                    @elseif($order->status === 'processing') 👨‍🍳 Diproses
                                    @else ✅ Selesai @endif
                                </span>
                                <p class="text-lg font-black text-slate-800 mt-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-5">
                            <h4 class="text-sm font-bold text-slate-600 uppercase tracking-widest mb-2">
                                <i class="fas fa-box text-orange-400 mr-1"></i> Item Pesanan
                            </h4>
                            <div class="bg-white/60 rounded-xl p-3 space-y-1.5">
                                @forelse($items as $item)
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-slate-700 font-medium">
                                            {{ $item['name'] ?? 'Item' }}
                                            <span class="text-slate-400 font-normal">×{{ $item['qty'] ?? $item['quantity'] ?? 1 }}</span>
                                        </span>
                                        <span class="font-bold text-slate-800">
                                            Rp {{ number_format(($item['subtotal'] ?? (($item['price'] ?? 0) * ($item['qty'] ?? $item['quantity'] ?? 1))), 0, ',', '.') }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-slate-400 text-sm text-center py-2">Data item tidak tersedia</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between text-xs font-bold text-slate-500 mb-1.5">
                                <span class="{{ $order->status !== 'pending' ? 'text-green-600' : 'text-orange-600' }}">✓ Dipesan</span>
                                <span class="{{ $order->status === 'processing' ? 'text-blue-600' : ($order->status === 'completed' ? 'text-green-600' : 'text-slate-400') }}">⟳ Diproses</span>
                                <span class="{{ $order->status === 'completed' ? 'text-green-600' : 'text-slate-400' }}">✓ Selesai</span>
                            </div>
                            <div class="bg-white/60 rounded-full h-2.5 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <!-- Footer Status -->
                        <div class="flex justify-end">
                            @if($order->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1.5">
                                    <i class="fas fa-hourglass-end"></i> Menunggu Konfirmasi Kantin
                                </span>
                            @elseif($order->status === 'processing')
                                <span class="bg-blue-100 text-blue-800 px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1.5">
                                    <i class="fas fa-spinner fa-spin"></i> Sedang Disiapkan
                                </span>
                            @else
                                <span class="bg-green-100 text-green-800 px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1.5">
                                    <i class="fas fa-check-circle"></i> Siap Diambil! 🎉
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Riwayat Link -->
            <div class="mt-8 text-center">
                <a href="/history" class="text-slate-500 hover:text-[#122C4F] font-medium transition text-sm">
                    <i class="fas fa-history mr-1"></i> Lihat Riwayat Pesanan Selesai →
                </a>
            </div>
        @endif

    </div>
</div>

<!-- Auto Refresh -->
<script>
    setInterval(() => { location.reload(); }, 10000);
</script>
@endsection
