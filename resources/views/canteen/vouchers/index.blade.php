@extends('layouts.kantin')

@section('page-title', 'Voucher')
@section('content')

<style>
    .voucher-card { background:white; border-radius:20px; padding:0; box-shadow:0 2px 12px rgba(0,0,0,0.06); overflow:hidden; transition:all .25s; position:relative; }
    .voucher-card:hover { transform:translateY(-3px); box-shadow:0 8px 28px rgba(0,0,0,0.1); }
    .voucher-notch { position:absolute; left:-12px; top:50%; transform:translateY(-50%); width:24px; height:24px; border-radius:50%; background:#F0F4F8; }
    .voucher-notch-r { left:auto; right:-12px; }
</style>

<div style="max-width:1100px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <p style="color:#94A3B8;font-size:13px;font-weight:600;">{{ $vouchers->count() }} voucher terdaftar</p>
        <a href="{{ route('canteen.vouchers.create') }}" style="background:linear-gradient(135deg,#6D28D9,#8B5CF6);color:white;font-weight:800;font-size:13px;padding:11px 22px;border-radius:14px;text-decoration:none;display:flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(109,40,217,0.3);transition:all .2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-ticket-alt"></i> Buat Voucher Baru
        </a>
    </div>

    @if($vouchers->isEmpty())
    <div style="background:white;border-radius:22px;padding:60px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,0.05);">
        <div style="width:80px;height:80px;background:#F5F3FF;border-radius:24px;display:flex;align-items:center;justify-content:center;font-size:36px;margin:0 auto 16px;">🎟️</div>
        <h3 style="font-size:18px;font-weight:800;color:#1e293b;margin-bottom:6px;">Belum Ada Voucher</h3>
        <p style="color:#94A3B8;font-size:13px;margin-bottom:24px;">Buat voucher untuk menarik lebih banyak pembeli!</p>
        <a href="{{ route('canteen.vouchers.create') }}" style="background:linear-gradient(135deg,#6D28D9,#8B5CF6);color:white;padding:12px 28px;border-radius:14px;font-weight:800;text-decoration:none;font-size:13px;">+ Buat Voucher Pertama</a>
    </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:20px;">
        @foreach($vouchers as $voucher)
        @php $isActive = now() >= $voucher->valid_from && now() <= $voucher->valid_to; @endphp
        <div class="voucher-card">
            {{-- Left colored strip + ticket body --}}
            <div style="display:flex;height:100%;">
                {{-- Color strip --}}
                <div style="width:10px;background:{{ $isActive ? 'linear-gradient(180deg,#6D28D9,#8B5CF6)' : '#CBD5E1' }};flex-shrink:0;"></div>

                {{-- Main content --}}
                <div style="flex:1;padding:20px 20px 20px 18px;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
                        <div>
                            <span style="font-size:11px;font-weight:700;color:{{ $isActive ? '#6D28D9' : '#94A3B8' }};text-transform:uppercase;letter-spacing:0.1em;background:{{ $isActive ? '#F5F3FF' : '#F1F5F9' }};padding:3px 8px;border-radius:6px;">
                                {{ $isActive ? '● Aktif' : '✕ Tidak Aktif' }}
                            </span>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-size:26px;font-weight:900;color:{{ $isActive ? '#6D28D9' : '#94A3B8' }};">
                                @if($voucher->discount_percentage){{ $voucher->discount_percentage }}%@else Rp {{ number_format($voucher->discount_amount,0,',','.') }}@endif
                            </p>
                        </div>
                    </div>

                    <p style="font-size:18px;font-weight:900;color:#1e293b;font-family:monospace;letter-spacing:2px;margin-bottom:6px;">{{ $voucher->code }}</p>
                    <p style="font-size:12px;color:#64748B;margin-bottom:14px;">{{ $voucher->description }}</p>

                    <div style="border-top:2px dashed #F1F5F9;padding-top:12px;display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <p style="font-size:10px;color:#94A3B8;font-weight:700;text-transform:uppercase;">Terpakai</p>
                            <p style="font-size:15px;font-weight:800;color:#1e293b;">{{ $voucher->times_used }}<span style="color:#94A3B8;font-weight:500;">/{{ $voucher->max_uses >= 999999 ? '∞' : $voucher->max_uses }}</span></p>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-size:10px;color:#94A3B8;font-weight:700;text-transform:uppercase;">Berlaku s/d</p>
                            <p style="font-size:12px;font-weight:700;color:#1e293b;">{{ $voucher->valid_to->format('d M Y') }}</p>
                        </div>
                        <div style="display:flex;gap:8px;">
                            <a href="{{ route('canteen.vouchers.edit', $voucher->id) }}" style="background:#FEF3C7;color:#92400E;border-radius:10px;padding:7px 12px;font-size:12px;font-weight:700;text-decoration:none;"><i class="fas fa-pen"></i></a>
                            <form action="{{ route('canteen.vouchers.destroy', $voucher->id) }}" method="POST" onsubmit="return confirm('Hapus voucher {{ $voucher->code }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#FEE2E2;color:#DC2626;border:none;border-radius:10px;padding:7px 12px;font-size:12px;font-weight:700;cursor:pointer;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
