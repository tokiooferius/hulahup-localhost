@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">🛒 Keranjang Belanja</h1>

        @if(session('cart') == null || empty(session('cart')))
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-lg text-gray-600 mb-4">Keranjang Anda kosong</p>
                <a href="/home" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Lanjut Belanja
                </a>
            </div>
        @else
            <form action="{{ route('cart.checkout') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf

                <!-- Cart Items by Canteen -->
                <div class="lg:col-span-2 space-y-6">
                    @foreach($cartByCanteen as $canteenId => $canteenData)
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-bold text-gray-900">
                                    {{ $canteenData['canteen']->name ?? 'Kantin #' . $canteenId }}
                                </h2>
                                <label class="flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="selected_canteens[]" 
                                        value="{{ $canteenId }}"
                                        checked
                                        class="w-5 h-5 text-blue-600 rounded"
                                    >
                                    <span class="ml-2 text-sm text-gray-600">Pilih Kantin</span>
                                </label>
                            </div>

                            <!-- Items in this canteen -->
                            <div class="space-y-3 border-t pt-4">
                                @foreach($canteenData['items'] as $item)
                                    <div class="flex items-center justify-between py-2 border-b">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">{{ $item['name'] }}</p>
                                            <p class="text-sm text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('cart.update', $item['menu_id']) }}" method="POST" class="flex items-center gap-1">
                                                @csrf
                                                @method('PUT')
                                                <input 
                                                    type="number" 
                                                    name="quantity" 
                                                    value="{{ $item['quantity'] }}"
                                                    min="1"
                                                    class="w-12 px-2 py-1 border rounded text-center"
                                                    onchange="this.form.submit()"
                                                >
                                            </form>
                                            <p class="font-semibold w-24 text-right">
                                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            </p>
                                            <form action="{{ route('cart.remove', $item['menu_id']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Subtotal per canteen -->
                            <div class="mt-4 pt-4 border-t-2 flex justify-between">
                                <span class="font-bold text-gray-900">Subtotal Kantin:</span>
                                <span class="font-bold text-blue-600 text-lg">
                                    Rp {{ number_format($canteenData['total'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Checkout Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Ringkasan Pesanan</h3>

                        <!-- Voucher -->
                        <div class="mb-6 pb-6 border-b">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Voucher</label>
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="voucher_code" 
                                    placeholder="Masukkan kode"
                                    class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                >
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="space-y-3 mb-6 pb-6 border-b">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal:</span>
                                <span class="font-semibold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Diskon:</span>
                                <span class="font-semibold text-red-600">Rp 0</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between mb-6 text-xl">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span class="font-bold text-blue-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>

                        <!-- Checkout Button -->
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">
                            ✓ Lanjut ke Pembayaran
                        </button>

                        <!-- Clear Cart -->
                        <form action="{{ route('cart.clear') }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-gray-300 text-gray-700 font-semibold py-2 rounded-lg hover:bg-gray-400 transition">
                                Kosongkan Keranjang
                            </button>
                        </form>

                        <!-- Continue Shopping -->
                        <a href="/home" class="block text-center mt-3 text-blue-600 hover:text-blue-700 font-semibold">
                            ← Lanjut Belanja
                        </a>
                    </div>
                </div>
            </form>

            <!-- Info -->
            <div class="mt-8 bg-blue-50 border-l-4 border-blue-600 p-4 rounded">
                <p class="text-sm text-gray-700">
                    <strong>💡 Tip:</strong> Anda dapat memilih kantin mana saja yang ingin dibayar. Pilih checkbox di samping nama kantin untuk memilih, atau kosongkan untuk menghapus dari pesanan.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection