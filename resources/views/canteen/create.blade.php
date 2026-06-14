@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">🍴 Buat Kantin Baru</h1>

        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <p class="text-gray-700 mb-4">
                Anda akan membuat kantin baru sebagai pemilik. Setelah membuat kantin, Anda dapat:
            </p>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Menambah dan mengelola menu makanan</li>
                <li>Membuat voucher dan promosi</li>
                <li>Melihat pesanan dan penjualan</li>
                <li>Melacak pembayaran yang diterima</li>
            </ul>
        </div>

        <form action="{{ route('canteen.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-8">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kantin</label>
                <input type="text" name="name" required placeholder="Kantin ABC" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Kantin</label>
                <textarea name="description" rows="4" placeholder="Deskripsi kantin Anda..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">URL Logo Kantin</label>
                <input type="url" name="logo_url" placeholder="https://example.com/logo.png" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                @error('logo_url') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    ✓ Buat Kantin
                </button>
                <a href="/home" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-bold py-3 rounded-lg text-center transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
