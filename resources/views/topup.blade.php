@extends('layouts.pembeli')

@section('title', 'Saldo TyU-Pay')
@section('page-title', 'Saldo TyU-Pay')

@section('content')


        <h2 class="text-3xl font-bold text-[#122C4F] mb-6">Saldo TyU-Pay</h2>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl">
                <p class="text-sm font-bold">✓ {{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl">
                <p class="text-sm font-bold">⚠ Terjadi kesalahan!</p>
                @foreach ($errors->all() as $error)
                    <p class="text-xs mt-1">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-[#122C4F] p-8 rounded-[40px] text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <p class="opacity-70 text-lg">Total Saldo Kamu</p>
                    <h3 class="text-5xl font-bold mt-2">RP {{ number_format(Auth::user()->balance, 0, ',', '.') }}</h3>
                    <div class="mt-10 flex gap-4">
                        <a href="#topup-section" class="bg-[#5B88B2] px-6 py-3 rounded-2xl font-bold hover:bg-blue-400 transition inline-block">+ Top Up Saldo</a>
                        <button class="border border-white px-6 py-3 rounded-2xl font-bold hover:bg-white hover:text-[#122C4F] transition" disabled>Transfer</button>
                    </div>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#5B88B2] opacity-20 rounded-full"></div>
            </div>

            <div class="bg-white p-8 rounded-[40px] shadow-sm" id="topup-section">
                <h4 class="font-bold text-xl mb-4 text-gray-800">Isi Saldo Cepat</h4>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <form action="{{ route('topup') }}" method="POST">
                        @csrf
                        <button type="submit" name="amount" value="10000" class="w-full p-4 border-2 border-gray-100 rounded-2xl font-bold text-[#122C4F] hover:border-[#5B88B2] hover:bg-blue-50 transition">+ RP 10.000</button>
                    </form>
                    <form action="{{ route('topup') }}" method="POST">
                        @csrf
                        <button type="submit" name="amount" value="20000" class="w-full p-4 border-2 border-gray-100 rounded-2xl font-bold text-[#122C4F] hover:border-[#5B88B2] hover:bg-blue-50 transition">+ RP 20.000</button>
                    </form>
                    <form action="{{ route('topup') }}" method="POST">
                        @csrf
                        <button type="submit" name="amount" value="50000" class="w-full p-4 border-2 border-gray-100 rounded-2xl font-bold text-[#122C4F] hover:border-[#5B88B2] hover:bg-blue-50 transition">+ RP 50.000</button>
                    </form>
                    <form action="{{ route('topup') }}" method="POST">
                        @csrf
                        <button type="submit" name="amount" value="100000" class="w-full p-4 border-2 border-gray-100 rounded-2xl font-bold text-[#122C4F] hover:border-[#5B88B2] hover:bg-blue-50 transition">+ RP 100.000</button>
                    </form>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-3">Atau masukkan nominal lain:</p>
                    <form action="{{ route('topup') }}" method="POST">
                        @csrf
                        <input type="number" name="amount" placeholder="Minimal Rp 5.000" 
                            class="w-full p-4 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#5B88B2] @error('amount') border-red-500 @enderror"
                            min="5000" step="1000">
                        
                        @error('amount')
                            <span class="text-red-500 text-xs mt-2 inline-block">{{ $message }}</span>
                        @enderror

                        <button type="submit" class="w-full mt-4 bg-[#5B88B2] text-white py-3 rounded-2xl font-bold hover:bg-blue-600 transition">
                            Isi Saldo Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
@endsection
