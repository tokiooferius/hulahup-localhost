<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Performa Menu - {{ $canteen->name }}</title>
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
            <button onclick="window.print()" class="bg-[#3B82F6] hover:bg-[#2563EB] text-white font-extrabold px-5 py-2.5 rounded-xl text-sm transition shadow-lg flex items-center gap-2">
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
                    <span class="w-8 h-8 rounded-lg bg-blue-500 text-white flex items-center justify-center font-black text-sm">F</span>
                    <span class="text-slate-900 font-black text-xl tracking-tight">Food-TYU.</span>
                </div>
                <h2 class="text-2xl font-black text-[#122C4F] uppercase tracking-wide">Laporan Performa Menu Terlaris</h2>
                <p class="text-sm text-slate-500 mt-1">🏪 Kantin: <strong class="text-slate-700">{{ $canteen->name }}</strong></p>
                <p class="text-xs text-slate-400 mt-0.5">Pemilik: {{ auth()->user()->name }}</p>
            </div>
            <div class="text-right">
                <div class="bg-slate-100 text-[#122C4F] font-bold text-xs px-3.5 py-1.5 rounded-full inline-block">
                    Dibuat: {{ now()->format('d M Y, H:i') }}
                </div>
                <div class="text-xs text-slate-500 mt-3 font-semibold">
                    Kategori Analisis:<br>
                    <span class="text-blue-600">Performa Menu Terlaris & Omset</span>
                </div>
            </div>
        </div>

        <!-- Summary Cards Grid -->
        <div class="grid grid-cols-3 gap-5 mb-8">
            <div class="print-card bg-emerald-50/40 border border-emerald-100 rounded-2xl p-4 text-center">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total Omset Menu</p>
                <h4 class="text-xl font-black text-emerald-600">Rp {{ number_format($totalCanteenOmset, 0, ',', '.') }}</h4>
                <p class="text-[10px] text-slate-400 mt-1">Akumulasi dari pesanan selesai</p>
            </div>
            
            <div class="print-card bg-blue-50/40 border border-blue-100 rounded-2xl p-4 text-center">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total Porsi Terjual</p>
                <h4 class="text-xl font-black text-blue-600">{{ $totalPorsiSold }} Porsi</h4>
                <p class="text-[10px] text-slate-400 mt-1">Seluruh menu terjual</p>
            </div>

            <div class="print-card bg-amber-50/40 border border-amber-100 rounded-2xl p-4 text-center">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Menu Terlaris (Top)</p>
                <h4 class="text-sm font-black text-amber-600 truncate mt-1">
                    @if(count($menuReport) > 0 && $menuReport[0]['total_qty'] > 0)
                        {{ $menuReport[0]['name'] }} ({{ $menuReport[0]['total_qty'] }}x)
                    @else
                        Belum ada penjualan
                    @endif
                </h4>
                <p class="text-[10px] text-slate-400 mt-1">Menu dengan kuantitas porsi tertinggi</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden border border-slate-200 rounded-2xl">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-[#122C4F] border-b border-slate-200 font-black uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4 w-12 text-center font-black">Rank</th>
                        <th class="py-3 px-4">Nama Menu</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4 text-right">Harga Satuan</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Porsi Terjual</th>
                        <th class="py-3 px-4 text-right">Total Omset</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $rank = 1; @endphp
                    @forelse($menuReport as $item)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-500">#{{ $rank++ }}</td>
                            <td class="py-3.5 px-4 font-black text-slate-900">{{ $item['name'] }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-500 text-[10px] uppercase tracking-wide">{{ $item['category'] }}</td>
                            <td class="py-3.5 px-4 text-right font-semibold text-slate-700">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                                    @if($item['is_available']) bg-green-50 text-green-700 border border-green-200
                                    @else bg-red-50 text-red-700 border border-red-200
                                    @endif">
                                    {{ $item['is_available'] ? 'Tersedia' : 'Habis' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-black text-blue-600">
                                {{ $item['total_qty'] }} porsi
                            </td>
                            <td class="py-3.5 px-4 text-right font-black text-slate-900">
                                Rp {{ number_format($item['total_revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400 font-medium">
                                Belum ada menu terdaftar di kantin Anda.
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
