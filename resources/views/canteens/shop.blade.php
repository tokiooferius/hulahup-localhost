@extends('layouts.pembeli')

@section('title', 'Daftar Kantin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-slate-900 flex items-center justify-center gap-2 mb-2">
                <i class="fas fa-store text-orange-500 text-5xl"></i>
                Daftar Kantin
            </h1>
            <p class="text-slate-600 text-lg">Jelajahi semua kantin yang tersedia dan lihat menu mereka</p>
            <div class="flex justify-center mt-4">
                <a href="/home" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow">
                    <i class="fas fa-home"></i> Kembali ke Home
                </a>
            </div>
        </div>

        @if($canteens->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-900 mb-2">Belum Ada Kantin</h3>
                <p class="text-slate-600">Kantin sedang siap dibuka. Nantikan segera! 🏪</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($canteens as $canteen)
                @php
                    $canUpload = Auth::check() && (
                        Auth::user()->role === 'admin' ||
                        (Auth::user()->role === 'ibu_kantin' && Auth::user()->canteen?->id === $canteen->id)
                    );
                    $imgSrc = $canteen->image ?? $canteen->logo_url ?? null;
                    // image sudah disimpan sebagai 'storage/canteens/xxx' jadi langsung pakai asset()
                    $imgUrl = $imgSrc ? asset($imgSrc) : null;
                    $fallback = 'https://ui-avatars.com/api/?name='.urlencode($canteen->name).'&background=F97316&color=fff&size=400&bold=true&font-size=0.35';
                @endphp

                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="relative h-48 overflow-hidden bg-slate-200">
                        <img id="canteenImg_{{ $canteen->id }}" src="{{ $imgUrl ?? $fallback }}" alt="{{ $canteen->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        {{-- Overlay klik ke detail (seluruh gambar) --}}
                        <a href="/canteens/{{ $canteen->id }}" class="absolute inset-0 z-10"></a>

                        {{-- Upload button (admin & ibu kantin pemilik) --}}
                        @if($canUpload)
                        <div class="absolute inset-0 z-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/40">
                            <button type="button"
                                onclick="openUploadModal({{ $canteen->id }}, '{{ addslashes($canteen->name) }}', '{{ Auth::user()->role }}')"
                                class="bg-white text-slate-800 font-bold text-xs px-4 py-2 rounded-xl flex items-center gap-2 shadow-lg hover:bg-orange-50 transition active:scale-95 relative z-30">
                                <i class="fas fa-camera text-orange-500"></i> Ganti Foto
                            </button>
                        </div>
                        @endif
                    </div>

                    {{-- ===== KONTEN KARTU ===== --}}
                    <a href="/canteens/{{ $canteen->id }}" class="block p-5 hover:bg-slate-50 transition">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $canteen->name }}</h3>
                        <p class="text-sm text-slate-500 mb-2">
                            <i class="fas fa-user-tie text-orange-400 mr-1"></i>
                            {{ $canteen->ibuKantin->name ?? 'Admin' }}
                        </p>

                        @if($canteen->description)
                            <p class="text-sm text-slate-600 mb-3 line-clamp-2">{{ $canteen->description }}</p>
                        @endif

                        {{-- Stats --}}
                        <div class="grid grid-cols-3 gap-2 mb-3 py-3 border-y border-slate-100">
                            <div class="text-center">
                                <p class="text-lg font-bold text-orange-500">{{ $canteen->menus->count() }}</p>
                                <p class="text-xs text-slate-500">Menu</p>
                            </div>
                            <div class="text-center border-x border-slate-100">
                                <p class="text-lg font-bold text-blue-500">{{ $canteen->completed_orders }}</p>
                                <p class="text-xs text-slate-500">Selesai</p>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-yellow-500">{{ number_format($canteen->rating, 1) }}</p>
                                <p class="text-xs text-slate-500">Rating</p>
                            </div>
                        </div>

                        {{-- Stars --}}
                        <div class="flex items-center gap-1 mb-4">
                            @php $rating = $canteen->rating; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($rating))
                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                @elseif($i == ceil($rating) && ($rating - floor($rating)) >= 0.5)
                                    <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                                @else
                                    <i class="far fa-star text-slate-300 text-sm"></i>
                                @endif
                            @endfor
                            <span class="text-xs text-slate-500 ml-1">({{ $canteen->completed_orders }} orders)</span>
                        </div>

                        <div class="w-full bg-orange-500 hover:bg-orange-600 text-white text-center font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2 text-sm">
                            <i class="fas fa-arrow-right"></i> Lihat Menu
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- ===== MODAL UPLOAD FOTO (admin & ibu kantin) ===== --}}
@if(Auth::check() && in_array(Auth::user()->role, ['admin', 'ibu_kantin']))
<div id="uploadPhotoModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[28px] shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 flex justify-between items-center">
            <div>
                <h3 class="text-white font-black text-lg">📷 Ganti Foto Kantin</h3>
                <p class="text-white/70 text-sm mt-0.5" id="uploadModalCanteenName">—</p>
            </div>
            <button onclick="closeUploadModal()" class="w-9 h-9 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center text-white transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            {{-- Preview --}}
            <div class="relative w-full h-44 rounded-2xl overflow-hidden bg-slate-100 mb-5 border-2 border-dashed border-slate-200 cursor-pointer"
                 onclick="document.getElementById('photoInput').click()">
                <img id="photoPreview" src="" class="w-full h-full object-cover hidden">
                <div id="uploadPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-cloud-upload-alt text-2xl text-orange-500"></i>
                    </div>
                    <p class="font-bold text-slate-600 text-sm">Klik untuk pilih foto</p>
                    <p class="text-slate-400 text-xs">JPG, PNG, WEBP — maks 3MB</p>
                </div>
                <div id="changeOverlay" class="hidden absolute inset-0 bg-black/40 flex items-center justify-center">
                    <p class="text-white font-bold text-sm"><i class="fas fa-camera mr-1"></i> Ganti Foto</p>
                </div>
            </div>
            <input type="file" id="photoInput" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewPhoto(this)">
            <div id="uploadMsg" class="hidden rounded-xl px-4 py-3 text-sm font-bold mb-4"></div>
            <div class="flex gap-3">
                <button onclick="closeUploadModal()" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition">Batal</button>
                <button onclick="submitUpload()" id="uploadSubmitBtn"
                    class="flex-1 py-3 bg-orange-500 text-white font-bold rounded-2xl hover:bg-orange-600 transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Foto
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('extra-js')
<script>
let currentCanteenId   = null;
let currentCanteenRole = null;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

function openUploadModal(canteenId, canteenName, role) {
    currentCanteenId   = canteenId;
    currentCanteenRole = role;
    document.getElementById('uploadModalCanteenName').textContent = canteenName;
    document.getElementById('photoPreview').classList.add('hidden');
    document.getElementById('uploadPlaceholder').style.display = 'flex';
    document.getElementById('changeOverlay').classList.add('hidden');
    document.getElementById('photoInput').value = '';
    document.getElementById('uploadMsg').classList.add('hidden');
    const modal = document.getElementById('uploadPhotoModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUploadModal() {
    const m = document.getElementById('uploadPhotoModal');
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
}

function previewPhoto(input) {
    if (!input.files?.[0]) return;
    const file = input.files[0];
    if (file.size > 3 * 1024 * 1024) {
        showUploadMsg('Ukuran file terlalu besar! Maks 3MB.', 'error');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        const prev = document.getElementById('photoPreview');
        prev.src = e.target.result;
        prev.classList.remove('hidden');
        document.getElementById('uploadPlaceholder').style.display = 'none';
        document.getElementById('changeOverlay').classList.remove('hidden');
        document.getElementById('changeOverlay').classList.add('flex');
        document.getElementById('uploadMsg').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

function submitUpload() {
    const file = document.getElementById('photoInput').files?.[0];
    if (!file) { showUploadMsg('Pilih foto dulu!', 'error'); return; }

    const btn = document.getElementById('uploadSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';

    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', csrfToken);

    // Admin bisa upload foto kantin manapun, ibu kantin hanya miliknya
    const url = currentCanteenRole === 'admin'
        ? `/admin/canteens/${currentCanteenId}/upload-photo`
        : `/canteen/upload-photo`;

    fetch(url, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Foto';
            if (data.success) {
                showUploadMsg(data.message, 'success');
                // Update gambar di kartu langsung tanpa reload
                const img = document.getElementById('canteenImg_' + currentCanteenId);
                if (img) img.src = data.image + '?t=' + Date.now();
                setTimeout(() => closeUploadModal(), 1200);
            } else {
                showUploadMsg(data.message || 'Gagal upload foto.', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan Foto';
            showUploadMsg('Koneksi bermasalah, coba lagi.', 'error');
        });
}

function showUploadMsg(text, type) {
    const el = document.getElementById('uploadMsg');
    el.textContent = (type === 'success' ? '✅ ' : '❌ ') + text;
    el.className = type === 'success'
        ? 'rounded-xl px-4 py-3 text-sm font-bold mb-4 bg-green-50 text-green-700 border border-green-200'
        : 'rounded-xl px-4 py-3 text-sm font-bold mb-4 bg-red-50 text-red-700 border border-red-200';
    el.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('uploadPhotoModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeUploadModal();
    });
});
</script>
@endsection
