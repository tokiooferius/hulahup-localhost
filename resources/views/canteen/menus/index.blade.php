@extends('layouts.kantin')

@section('page-title', 'Menu Kantin')
@section('content')

<style>
    .menu-card { background:white; border-radius:20px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.06); transition:all .25s; }
    .menu-card:hover { transform:translateY(-4px); box-shadow:0 10px 30px rgba(0,0,0,0.12); }
    .menu-card img { width:100%; height:160px; object-fit:cover; transition:transform .35s; }
    .menu-card:hover img { transform:scale(1.06); }
    .cat-badge { font-size:11px; font-weight:800; padding:4px 10px; border-radius:99px; text-transform:capitalize; }
    .cat-heavy    { background:#DBEAFE; color:#1E40AF; }
    .cat-beverage { background:#D1FAE5; color:#065F46; }
    .cat-snack    { background:#FEF3C7; color:#92400E; }
    .btn-edit { background:#FEF3C7; color:#92400E; border:none; border-radius:10px; padding:8px 14px; font-size:12px; font-weight:700; cursor:pointer; transition:all .2s; text-decoration:none; display:inline-flex; align-items:center; gap:5px; }
    .btn-edit:hover { background:#FDE68A; }
    .btn-del { background:#FEE2E2; color:#DC2626; border:none; border-radius:10px; padding:8px 14px; font-size:12px; font-weight:700; cursor:pointer; transition:all .2s; }
    .btn-del:hover { background:#FECACA; }
</style>

<div style="max-width:1200px;">
    {{-- Header bar --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <p style="color:#94A3B8;font-size:13px;font-weight:600;">{{ $menus->total() ?? $menus->count() }} item terdaftar</p>
        </div>
        <a href="{{ route('canteen.menus.create') }}" style="background:linear-gradient(135deg,#1a3a5c,#2d6a8f);color:white;font-weight:800;font-size:13px;padding:11px 22px;border-radius:14px;text-decoration:none;display:flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(45,106,143,0.35);transition:all .2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-plus"></i> Tambah Menu Baru
        </a>
    </div>

    @if(session('success'))
    <div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:14px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-check-circle" style="color:#059669;"></i>
        <p style="font-size:13px;font-weight:700;color:#065F46;">{{ session('success') }}</p>
    </div>
    @endif

    @if($menus->isEmpty())
    <div style="background:white;border-radius:22px;padding:60px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
        <div style="width:80px;height:80px;background:#F0F4F8;border-radius:24px;display:flex;align-items:center;justify-content:center;font-size:36px;margin:0 auto 16px;">🍴</div>
        <h3 style="font-size:18px;font-weight:800;color:#1e293b;margin-bottom:6px;">Belum Ada Menu</h3>
        <p style="color:#94A3B8;font-size:13px;margin-bottom:24px;">Mulai tambahkan menu pertama untuk kantinmu!</p>
        <a href="{{ route('canteen.menus.create') }}" style="background:linear-gradient(135deg,#1a3a5c,#2d6a8f);color:white;padding:12px 28px;border-radius:14px;font-weight:800;text-decoration:none;font-size:13px;">+ Tambah Menu Pertama</a>
    </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;">
        @foreach($menus as $menu)
        <div class="menu-card">
            <div style="position:relative;overflow:hidden;">
                @if($menu->image_url)
                    @php
                        $menuImgUrl = (str_starts_with($menu->image_url, 'http') || str_starts_with($menu->image_url, '//')) 
                            ? $menu->image_url 
                            : asset($menu->image_url);
                    @endphp
                    <img src="{{ $menuImgUrl }}" alt="{{ $menu->name }}">
                @else
                    <div style="width:100%;height:160px;background:linear-gradient(135deg,#F0F4F8,#E2E8F0);display:flex;align-items:center;justify-content:center;font-size:52px;">🍴</div>
                @endif
                <span class="cat-badge cat-{{ $menu->category }}" style="position:absolute;top:12px;left:12px;">{{ ucfirst($menu->category) }}</span>
                <div style="position:absolute;top:12px;right:12px;background:rgba(0,0,0,0.55);backdrop-filter:blur(6px);border-radius:99px;padding:4px 10px;">
                    <span style="color:#FCD34D;font-size:11px;font-weight:800;">⭐ {{ $menu->rating ?? '4.7' }}</span>
                </div>
            </div>
            <div style="padding:16px;">
                <h3 style="font-size:15px;font-weight:800;color:#1e293b;margin-bottom:4px;">{{ $menu->name }}</h3>
                <p style="font-size:12px;color:#94A3B8;line-height:1.5;margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $menu->description ?: 'Tidak ada deskripsi.' }}</p>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                    <p style="font-size:18px;font-weight:900;color:#1a3a5c;">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                    <span style="font-size:11px;font-weight:700;padding:4px 10px;border-radius:99px;background:{{ $menu->is_available ?? true ? '#D1FAE5' : '#FEE2E2' }};color:{{ $menu->is_available ?? true ? '#065F46' : '#DC2626' }};">
                        {{ ($menu->is_available ?? true) ? '● Tersedia' : '● Habis' }}
                    </span>
                </div>
                <div style="display:flex;gap:8px;">
                    <a href="{{ route('canteen.menus.edit', $menu->id) }}" class="btn-edit" style="flex:1;justify-content:center;"><i class="fas fa-pen"></i> Edit</a>
                    <form action="{{ route('canteen.menus.destroy', $menu->id) }}" method="POST" style="flex:1;" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del" style="width:100%;display:flex;align-items:center;justify-content:center;gap:5px;"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:28px;">{{ $menus->links() }}</div>
    @endif
</div>
@endsection
