<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Food-TYU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-md p-10 text-center">

        @if($transactionStatus === 'settlement' || $transactionStatus === 'capture')
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check-circle text-5xl text-green-500"></i>
            </div>
            <h1 class="text-3xl font-black text-gray-800 mb-2">Pembayaran Berhasil! 🎉</h1>
            <p class="text-gray-500 mb-6">Pesanan kamu sudah diterima dan sedang disiapkan oleh pemilik kantin.</p>
            <div class="bg-green-50 rounded-2xl p-4 mb-6 text-left">
                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Kode Transaksi</p>
                <p class="font-black text-green-700">{{ $transactionCode }}</p>
                @if($payment)
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mt-3 mb-1">Total Dibayar</p>
                    <p class="font-black text-gray-800">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
                @endif
            </div>
        @elseif($transactionStatus === 'pending')
            <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-hourglass-half text-5xl text-yellow-500"></i>
            </div>
            <h1 class="text-3xl font-black text-gray-800 mb-2">Menunggu Pembayaran ⏳</h1>
            <p class="text-gray-500 mb-6">Pembayaran kamu sedang diproses. Pesanan akan aktif setelah konfirmasi dari sistem pembayaran.</p>
            <div class="bg-yellow-50 rounded-2xl p-4 mb-6">
                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Kode Transaksi</p>
                <p class="font-black text-yellow-700">{{ $transactionCode }}</p>
            </div>
        @else
            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-times-circle text-5xl text-red-500"></i>
            </div>
            <h1 class="text-3xl font-black text-gray-800 mb-2">Pembayaran Gagal ❌</h1>
            <p class="text-gray-500 mb-6">Pembayaran dibatalkan atau terjadi kesalahan. Silakan coba kembali.</p>
        @endif

        <div class="flex gap-3">
            <a href="/orders/active" class="flex-1 bg-[#122C4F] text-white py-4 rounded-2xl font-bold hover:bg-blue-800 transition">
                <i class="fas fa-clock mr-2"></i>Pesanan Aktif
            </a>
            <a href="/home" class="flex-1 bg-gray-100 text-gray-700 py-4 rounded-2xl font-bold hover:bg-gray-200 transition">
                <i class="fas fa-home mr-2"></i>Beranda
            </a>
        </div>
    </div>
</body>
</html>
