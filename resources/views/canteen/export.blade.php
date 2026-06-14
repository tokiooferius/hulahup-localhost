@extends('layouts.kantin')

@section('page-title', 'Export Laporan')

@section('content')
<style>
    .export-card {
        background: white;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #E2E8F0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s;
    }
    .export-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }
    .btn-download {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-download-primary {
        background: #10B981;
        color: white;
    }
    .btn-download-primary:hover {
        background: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    .btn-download-secondary {
        background: #3B82F6;
        color: white;
    }
    .btn-download-secondary:hover {
        background: #2563EB;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    .date-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #CBD5E1;
        font-size: 14px;
        font-weight: 500;
        outline: none;
        transition: all 0.2s;
    }
    .date-input:focus {
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
</style>

<div style="max-width:1000px; padding: 0 16px;">

    {{-- Header Banner --}}
    <div style="background:linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius:24px; padding:28px 32px; margin-bottom:28px; position:relative; overflow:hidden; box-shadow:0 4px 20px rgba(16,185,129,0.15);">
        <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
        <div style="position:absolute;bottom:-60px;right:80px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,0.04);"></div>
        <div style="position:relative;z-index:1;">
            <p style="color:rgba(255,255,255,0.75);font-size:13px;font-weight:600;margin-bottom:4px;">Laporan & Rekapitulasi</p>
            <h2 style="color:white;font-size:26px;font-weight:900;line-height:1.2;">Export Data Kantin 📊</h2>
            <p style="color:rgba(255,255,255,0.85);font-size:13px;font-weight:600;margin-top:6px;">🏪 {{ $canteen->name }}</p>
        </div>
    </div>

    {{-- Cards Grid --}}
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
        
        {{-- Card 1: Sales History --}}
        <div class="export-card">
            <div>
                <div class="icon-wrapper" style="background: #ECFDF5; color: #10B981;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3 style="color: #0f1f3d; font-size: 18px; font-weight: 800; margin-bottom: 8px;">Riwayat Penjualan</h3>
                <p style="color: #64748B; font-size: 13px; line-height: 1.5; margin-bottom: 24px;">
                    Unduh semua riwayat pesanan yang masuk ke kantin Anda. Data mencakup nomor pesanan, nama pembeli, total belanja, potongan diskon voucher, metode pembayaran, status transaksi, dan waktu pemesanan.
                </p>
            </div>

            <form action="{{ route('canteen.export.orders') }}" method="GET" style="display: flex; flex-direction: column; gap: 14px;">
                <div style="display: flex; gap: 12px;">
                    <div style="flex: 1; display: flex; flex-direction: column; gap: 6px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Mulai Tanggal</label>
                        <input type="date" name="start_date" class="date-input">
                    </div>
                    <div style="flex: 1; display: flex; flex-direction: column; gap: 6px;">
                        <label style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em;">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="date-input">
                    </div>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 8px;">
                    <button type="submit" class="btn-download btn-download-primary" style="flex: 1;">
                        <i class="fas fa-file-excel"></i>
                        <span>Export CSV</span>
                    </button>
                    <button type="button" onclick="printPdfReport('orders')" class="btn-download" style="flex: 1; background: #EF4444; color: white;" onmouseover="this.style.background='#DC2626'" onmouseout="this.style.background='#EF4444'">
                        <i class="fas fa-file-pdf"></i>
                        <span>Cetak PDF</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Card 2: Menus Performance --}}
        <div class="export-card">
            <div>
                <div class="icon-wrapper" style="background: #EFF6FF; color: #3B82F6;">
                    <i class="fas fa-hamburger"></i>
                </div>
                <h3 style="color: #0f1f3d; font-size: 18px; font-weight: 800; margin-bottom: 8px;">Performa Menu Terlaris</h3>
                <p style="color: #64748B; font-size: 13px; line-height: 1.5; margin-bottom: 24px;">
                    Unduh statistik performa penjualan untuk seluruh menu makanan dan minuman Anda. Data mencakup nama menu, kategori menu, harga, status ketersediaan, jumlah porsi terjual, serta total omset yang dihasilkan dari menu tersebut.
                </p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div style="padding: 12px; background: #F8FAFC; border-radius: 12px; border: 1px dashed #E2E8F0; font-size: 12px; color: #475569;">
                    <i class="fas fa-info-circle" style="color: #3B82F6; margin-right: 4px;"></i>
                    Data omset menu hanya menghitung pesanan yang berstatus <strong>Selesai (Completed)</strong>.
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('canteen.export.menus') }}" class="btn-download btn-download-secondary" style="flex: 1;">
                        <i class="fas fa-file-excel"></i>
                        <span>Export CSV</span>
                    </a>
                    <a href="{{ route('canteen.export.menus.pdf') }}" target="_blank" class="btn-download" style="flex: 1; background: #EF4444; color: white; text-align: center;" onmouseover="this.style.background='#DC2626'" onmouseout="this.style.background='#EF4444'">
                        <i class="fas fa-file-pdf"></i>
                        <span>Cetak PDF</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Guide Card --}}
    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 20px; padding: 24px; margin-bottom: 24px;">
        <h4 style="color: #0f1f3d; font-size: 14px; font-weight: 800; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-question-circle" style="color: #64748B;"></i>
            Panduan & Tips Ekspor Data
        </h4>
        <ul style="color: #475569; font-size: 12px; line-height: 1.6; margin: 0; padding-left: 20px; display: flex; flex-direction: column; gap: 8px;">
            <li>Ekspor data menggunakan format standar <strong>CSV (Comma-Separated Values)</strong> dengan pemisah titik koma (<strong>;</strong>).</li>
            <li>Anda bisa mengimpor data ini secara langsung ke program pengolah angka favorit Anda, seperti <strong>Microsoft Excel</strong>, <strong>Google Sheets</strong>, atau <strong>Apple Numbers</strong>.</li>
            <li>Jika file dibuka di Excel dan karakter bahasa/nomor berantakan, Excel Anda mungkin memerlukan encoding UTF-8. File ini sudah menyertakan Byte Order Mark (BOM) sehingga di Microsoft Excel versi modern harusnya otomatis rapi.</li>
        </ul>
    </div>

</div>

<script>
    function printPdfReport(type) {
        if (type === 'orders') {
            const start = document.querySelector('input[name="start_date"]').value;
            const end = document.querySelector('input[name="end_date"]').value;
            let url = "{{ route('canteen.export.orders.pdf') }}";
            const params = [];
            if (start) params.push('start_date=' + start);
            if (end) params.push('end_date=' + end);
            if (params.length) url += '?' + params.join('&');
            window.open(url, '_blank');
        }
    }
</script>
@endsection
