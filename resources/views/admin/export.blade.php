@extends('layouts.admin')

@section('title', 'Export Laporan')

@section('content')
<div class="p-8">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-3xl font-black text-[#122C4F]">Export Laporan 📊</h1>
            <p class="text-slate-500 font-medium text-sm mt-1">Unduh seluruh riwayat dan data transaksi kantin Food-TYU dalam format Spreadsheet (CSV)</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Order Export -->
            <div class="bg-white rounded-[22px] p-6 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow duration-300">
                <div>
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-2xl mb-4 text-emerald-600">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <h3 class="text-lg font-black text-[#122C4F] mb-2">Riwayat Transaksi</h3>
                    <p class="text-slate-500 text-sm font-medium mb-6">Unduh semua detail pesanan dari seluruh pembeli dan kantin. Lengkap dengan detail nominal, diskon, dan status.</p>
                </div>
                
                <form action="{{ route('admin.export.orders') }}" method="GET" class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-[#122C4F] focus:border-[#122C4F] outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-[#122C4F] focus:border-[#122C4F] outline-none transition-all">
                    </div>
                    <button type="submit" class="w-full mt-4 flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl transition duration-300 shadow-sm shadow-emerald-600/20 text-sm">
                        <i class="fa-solid fa-file-excel"></i>
                        <span>Export Riwayat (.csv)</span>
                    </button>
                </form>
            </div>

            <!-- Canteen Export -->
            <div class="bg-white rounded-[22px] p-6 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow duration-300">
                <div>
                    <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center text-2xl mb-4 text-indigo-600">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h3 class="text-lg font-black text-[#122C4F] mb-2">Rekap Data Kantin</h3>
                    <p class="text-slate-500 text-sm font-medium mb-6">Unduh rangkuman performa seluruh kantin terdaftar. Termasuk saldo kantin saat ini, total menu, jumlah promo, serta total omset.</p>
                </div>
                
                <div>
                    <div class="p-4 bg-indigo-50/50 rounded-xl mb-6 border border-indigo-100/30">
                        <p class="text-xs text-indigo-700 font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-info-circle"></i>
                            Format data: UTF-8 BOM, separator semi-kolon (;) untuk kompatibilitas penuh dengan Excel.
                        </p>
                    </div>
                    <a href="{{ route('admin.export.canteens') }}" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition duration-300 shadow-sm shadow-indigo-600/20 text-sm">
                        <i class="fa-solid fa-file-excel"></i>
                        <span>Export Rekap Kantin</span>
                    </a>
                </div>
            </div>

            <!-- User Export -->
            <div class="bg-white rounded-[22px] p-6 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow duration-300">
                <div>
                    <div class="w-14 h-14 bg-violet-100 rounded-2xl flex items-center justify-center text-2xl mb-4 text-violet-600">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-lg font-black text-[#122C4F] mb-2">Rekap Data Pengguna</h3>
                    <p class="text-slate-500 text-sm font-medium mb-6">Unduh seluruh daftar akun pengguna Food-TYU. Berisi informasi email, peran (role), sisa saldo TyU-Pay, serta tanggal registrasi.</p>
                </div>
                
                <div>
                    <div class="p-4 bg-violet-50/50 rounded-xl mb-6 border border-violet-100/30">
                        <p class="text-xs text-violet-700 font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-info-circle"></i>
                            Membantu pemantauan perputaran total saldo (deposit) pengguna dalam sistem.
                        </p>
                    </div>
                    <a href="{{ route('admin.export.users') }}" class="w-full flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-bold py-3 px-4 rounded-xl transition duration-300 shadow-sm shadow-violet-600/20 text-sm">
                        <i class="fa-solid fa-file-excel"></i>
                        <span>Export Rekap Pengguna</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Documentation Card -->
        <div class="bg-slate-50 border border-slate-100 rounded-[22px] p-6">
            <h4 class="text-sm font-bold text-[#122C4F] mb-2 flex items-center gap-2">
                <i class="fa-solid fa-circle-question text-slate-500"></i>
                Petunjuk Penggunaan Laporan CSV
            </h4>
            <ul class="text-xs text-slate-600 space-y-2 list-disc list-inside">
                <li>Gunakan aplikasi spreadsheet seperti <strong>Microsoft Excel</strong>, <strong>Google Sheets</strong>, atau <strong>LibreOffice Calc</strong> untuk membuka file.</li>
                <li>Jika data terlihat menyatu di satu kolom saat dibuka di Excel, Anda bisa menggunakan fitur <strong>Text to Columns</strong> dengan delimiter semi-kolon (<strong>;</strong>).</li>
                <li>File sudah menyertakan BOM (Byte Order Mark) UTF-8, sehingga karakter khusus seperti lambang mata uang dan emotikon akan terbaca dengan benar.</li>
            </ul>
        </div>

    </div>
</div>
@endsection
