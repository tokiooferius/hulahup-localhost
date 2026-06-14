<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - {{ $canteen->name }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white;
                color: black;
            }
            .print-card {
                box-shadow: none !important;
                border: 1px solid #E2E8F0 !important;
            }
        }
        @page {
            size: A4;
            margin: 1.5cm;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen">

    <!-- Control bar (Hidden during print) -->
    <div class="no-print bg-[#122C4F] text-white py-4 px-6 sticky top-0 shadow-md z-50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('canteen.export') }}" class="hover:text-slate-300 transition text-sm flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <span class="text-slate-500">|</span>
            <h1 class="font-extrabold text-sm tracking-wide">PREVIEW CETAK PDF</h1>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="bg-[#10B981] hover:bg-[#059669] text-white font-extrabold px-5 py-2.5 rounded-xl text-sm transition shadow-lg flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="bg-slate-700 hover:bg-slate-600 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Document container -->
    <div class="max-w-4xl mx-auto my-8 p-10 bg-white shadow-xl rounded-[30px] border border-slate-100 print:shadow-none print:border-none print:my-0 print:p-0">
        
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-slate-100 pb-6 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-black text-sm">F</span>
                    <span class="text-slate-900 font-black text-xl tracking-tight">Food-TYU.</span>
                </div>
                <h2 class="text-2xl font-black text-[#122C4F] uppercase tracking-wide">Laporan Riwayat Penjualan</h2>
                <p class="text-sm text-slate-500 mt-1">🏪 Kantin: <strong class="text-slate-700">{{ $canteen->name }}</strong></p>
                <p class="text-xs text-slate-400 mt-0.5">Pemilik: {{ auth()->user()->name }}</p>
            </div>
            <div class="text-right">
                <div class="bg-slate-100 text-[#122C4F] font-bold text-xs px-3.5 py-1.5 rounded-full inline-block">
                    Dibuat: {{ now()->format('d M Y, H:i') }}
                </div>
                <div class="text-xs text-slate-500 mt-3 font-medium">
                    Periode Laporan:<br>
                    <span class="text-slate-700 font-bold">
                        @if($request->filled('start_date'))
                            {{ date('d M Y', strtotime($request->start_date)) }}
                        @else
                            Awal
                        @endif
                        s/d
                        @if($request->filled('end_date'))
                            {{ date('d M Y', strtotime($request->end_date)) }}
                        @else
                            Hari Ini
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Summary Cards Grid -->
        <div class="grid grid-cols-3 gap-5 mb-8">
            <div class="print-card bg-emerald-50/40 border border-emerald-100 rounded-2xl p-4 text-center">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total Omset Selesai</p>
                <h4 class="text-xl font-black text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">Dari pesanan berstatus 'Selesai'</p>
            </div>
            
            <div class="print-card bg-blue-50/40 border border-blue-100 rounded-2xl p-4 text-center">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total Pesanan</p>
                <h4 class="text-xl font-black text-blue-600">{{ $totalOrders }} Pesanan</h4>
                <p class="text-[10px] text-slate-400 mt-1">Seluruh status transaksi</p>
            </div>

            <div class="print-card bg-purple-50/40 border border-purple-100 rounded-2xl p-4 text-center">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Potongan Voucher</p>
                <h4 class="text-xl font-black text-purple-600">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">Total subsidi diskon</p>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="mb-6 flex flex-wrap gap-2 items-center text-xs">
            <span class="font-bold text-slate-500 mr-2 uppercase tracking-wide">Rincian Transaksi:</span>
            <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-800 font-semibold">
                Selesai: {{ $statusCounts->get('completed', 0) }}
            </span>
            <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold">
                Proses: {{ $statusCounts->get('processing', 0) }}
            </span>
            <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-semibold">
                Pending: {{ $statusCounts->get('pending', 0) }}
            </span>
            <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-800 font-semibold">
                Batal: {{ $statusCounts->get('cancelled', 0) }}
            </span>
        </div>

        <!-- Table -->
        <div class="overflow-hidden border border-slate-200 rounded-2xl">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-[#122C4F] border-b border-slate-200 font-black uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">No. Order</th>
                        <th class="py-3 px-4">Pembeli</th>
                        <th class="py-3 px-4">Tanggal/Waktu</th>
                        <th class="py-3 px-4">Metode</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Potongan</th>
                        <th class="py-3 px-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">{{ $order->order_number }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700">{{ $order->user->name ?? 'User Terhapus' }}</td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-4 text-slate-600 font-medium uppercase text-[10px]">{{ $order->payment_method ?? 'CASH' }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                                    @if($order->status === 'completed') bg-green-50 text-green-700 border border-green-200
                                    @elseif($order->status === 'processing') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($order->status === 'pending') bg-amber-50 text-amber-700 border border-amber-200
                                    @else bg-red-50 text-red-700 border border-red-200
                                    @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right text-purple-600 font-semibold">
                                @if($order->discount_amount > 0)
                                    -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                                @else
                                    Rp 0
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-black text-slate-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400 font-medium">
                                Tidak ada transaksi yang ditemukan untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Signature -->
        <div class="mt-12 pt-8 border-t border-slate-100 flex justify-between items-center text-[10px] text-slate-400">
            <div>
                <p>Sistem Rekapitulasi Otomatis <strong>Food-TYU.</strong></p>
                <p class="mt-0.5">Laporan ini sah dan dicetak secara elektronik.</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-slate-700 text-xs mt-4 border-t border-slate-200 pt-2 w-48 ml-auto">
                    {{ auth()->user()->name }}
                </p>
                <p>Ibu Kantin / Pengelola</p>
            </div>
        </div>

    </div>

    <!-- Auto-trigger print script -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
