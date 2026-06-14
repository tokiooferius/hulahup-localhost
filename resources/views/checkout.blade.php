@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <!-- Tombol Kembali -->
        <div class="mb-6">
            <a href="/cart" class="inline-flex items-center gap-2 text-gray-600 hover:text-[#122C4F] font-bold transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
            </a>
        </div>

        <h1 class="text-4xl font-bold text-gray-900 mb-8">💳 Konfirmasi Pesanan</h1>

        <form action="{{ route('payment.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf

            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                @foreach($checkout['cart_by_canteen'] as $canteenId => $canteenData)
                    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-600">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">
                            Kantin #{{ $canteenId }}
                        </h2>
                        <div class="space-y-2 border-b pb-4 mb-4">
                            @foreach($canteenData['items'] as $item)
                                <div class="flex justify-between">
                                    <span class="text-gray-700">{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                    <span class="font-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Subtotal:</span>
                            <span class="text-green-600">Rp {{ number_format($canteenData['total'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach

                <!-- ===== LOKASI PENGAMBILAN ===== -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">📍 Lokasi Pengambilan</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Area / Gedung Kampus</label>
                            <select name="pickup_area" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none">
                                <option value="">-- Pilih Area --</option>
                                <optgroup label="🏫 Area Kelas">
                                    <option value="Gedung A – Lantai 1">Gedung A – Lantai 1</option>
                                    <option value="Gedung A – Lantai 2">Gedung A – Lantai 2</option>
                                    <option value="Gedung A – Lantai 3">Gedung A – Lantai 3</option>
                                    <option value="Gedung B – Lantai 1">Gedung B – Lantai 1</option>
                                    <option value="Gedung B – Lantai 2">Gedung B – Lantai 2</option>
                                    <option value="Gedung C – Lantai Dasar">Gedung C – Lantai Dasar</option>
                                    <option value="Gedung D – Basement">Gedung D – Basement</option>
                                </optgroup>
                                <optgroup label="🔬 Area Lab">
                                    <option value="Lab Informatika – Lantai 3">Lab Informatika – Lantai 3</option>
                                    <option value="Lab Komputer – Gedung E">Lab Komputer – Gedung E</option>
                                    <option value="Lab Jaringan">Lab Jaringan</option>
                                </optgroup>
                                <optgroup label="🌿 Area Umum">
                                    <option value="Halaman Utama Kampus">Halaman Utama Kampus</option>
                                    <option value="Ruang Makan Mahasiswa">Ruang Makan Mahasiswa</option>
                                    <option value="Perpustakaan – Lantai 1">Perpustakaan – Lantai 1</option>
                                    <option value="Area Parkir Selatan">Area Parkir Selatan</option>
                                    <option value="Taman Belakang Kampus">Taman Belakang Kampus</option>
                                </optgroup>
                                <optgroup label="🏃 Lainnya">
                                    <option value="Ambil Langsung di Kantin">Ambil Langsung di Kantin</option>
                                </optgroup>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Detail Lokasi <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <input type="text" name="pickup_detail"
                                placeholder="Contoh: Ruang 301, meja pojok kiri"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm">
                            <p class="text-xs text-gray-400 mt-1">Nomor ruangan, meja, atau keterangan tambahan</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Payment Summary -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Pembayaran</h3>

                    <!-- Payment Method -->
                    <div class="mb-6 pb-6 border-b">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Metode Pembayaran</label>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="midtrans" checked class="w-4 h-4">
                                <span class="ml-2 text-gray-700">💳 Midtrans (Coming Soon)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="saldo" class="w-4 h-4">
                                <span class="ml-2 text-gray-700">💰 Saldo TyU-Pay</span>
                            </label>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="space-y-3 mb-6 pb-6 border-b">
                        <div class="flex justify-between text-gray-700">
                            <span>Subtotal:</span>
                            <span class="font-semibold">Rp {{ number_format($checkout['grand_total'] + $checkout['discount'], 0, ',', '.') }}</span>
                        </div>
                        @if($checkout['discount'] > 0)
                            <div class="flex justify-between text-gray-700">
                                <span>Diskon:</span>
                                <span class="font-semibold text-red-600">-Rp {{ number_format($checkout['discount'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Total -->
                    <div class="flex justify-between mb-6 text-xl border-b pb-6">
                        <span class="font-bold text-gray-900">Total Pembayaran:</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($checkout['grand_total'], 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition">
                        ✓ Proses Pembayaran
                    </button>
                    <a href="/cart" class="block text-center mt-3 text-blue-600 hover:text-blue-700 font-semibold">
                        ← Kembali ke Keranjang
                    </a>

                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded p-3">
                        <p class="text-xs text-yellow-800">
                            <strong>⚠️ Note:</strong> Midtrans belum diintegrasikan. Implementasi sedang dalam tahap development.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
