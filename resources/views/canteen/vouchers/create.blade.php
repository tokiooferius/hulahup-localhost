@extends('layouts.kantin')

@section('title', 'Buat Voucher Baru')
@section('page-title', 'Buat Voucher Baru')

@section('content')
<div class="max-w-2xl mx-auto pb-10">

    <div class="mb-6">
        <a href="{{ route('canteen.vouchers.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-[#122C4F] font-bold transition text-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Voucher
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-ticket-alt text-purple-600 text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-900">Buat Voucher Baru</h1>
                <p class="text-sm text-slate-500">Buat promo menarik untuk pelanggan kantin kamu</p>
            </div>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <p class="text-red-700 font-bold text-sm mb-2">⚠️ Ada kesalahan:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-red-600 text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('canteen.vouchers.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Kode Voucher --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Kode Voucher <span class="text-red-500">*</span></label>
                <input type="text" name="code" required value="{{ old('code') }}"
                    placeholder="contoh: PROMO2026"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 font-mono text-sm uppercase @error('code') border-red-400 @enderror">
                @error('code') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="2" placeholder="Promo spesial untuk pelanggan setia..."
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 text-sm resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Diskon --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Diskon (%)</label>
                    <div class="relative">
                        <input type="number" name="discount_percentage" min="0" max="100" step="1"
                            value="{{ old('discount_percentage') }}" placeholder="10"
                            class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 text-sm pr-10 @error('discount_percentage') border-red-400 @enderror">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">%</span>
                    </div>
                    @error('discount_percentage') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Atau Diskon (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                        <input type="number" name="discount_amount" min="0" step="500"
                            value="{{ old('discount_amount') }}" placeholder="5000"
                            class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 text-sm @error('discount_amount') border-red-400 @enderror">
                    </div>
                    @error('discount_amount') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            <p class="text-xs text-slate-400 -mt-2">💡 Isi salah satu saja — diskon persen ATAU diskon rupiah</p>

            {{-- Tanggal Berlaku --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Berlaku Dari <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="valid_from" required
                        value="{{ old('valid_from') }}"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 text-sm @error('valid_from') border-red-400 @enderror">
                    @error('valid_from') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Berlaku Sampai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="valid_to" required
                        value="{{ old('valid_to') }}"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 text-sm @error('valid_to') border-red-400 @enderror">
                    @error('valid_to') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Maks Penggunaan --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Maksimal Penggunaan <span class="text-slate-400 font-normal">(Kosongkan jika tanpa limit/unlimited)</span></label>
                <input type="number" name="max_uses" min="1"
                    value="{{ old('max_uses') }}" placeholder="contoh: 100 (kosongkan untuk unlimited)"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 text-sm @error('max_uses') border-red-400 @enderror">
                @error('max_uses') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                <p class="text-xs text-slate-400 mt-1">Berapa kali voucher ini bisa digunakan oleh seluruh pembeli secara kumulatif.</p>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i> Buat Voucher
                </button>
                <a href="{{ route('canteen.vouchers.index') }}"
                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl text-center transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
