@extends('layouts.kantin')

@section('page-title', 'Pembayaran')
@section('content')

<style>
    .data-table { width:100%; border-collapse:separate; border-spacing:0; }
    .data-table th { background:#F8FAFC; font-size:11px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:0.1em; padding:14px 18px; text-align:left; }
    .data-table th:first-child { border-radius:14px 0 0 14px; }
    .data-table th:last-child  { border-radius:0 14px 14px 0; }
    .data-table td { padding:14px 18px; border-bottom:1px solid #F1F5F9; font-size:13px; }
    .data-table tr:last-child td { border-bottom:none; }
    .data-table tbody tr:hover td { background:#F8FAFC; }
</style>

<div style="max-width:1100px;">

    {{-- Summary cards --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
        <div style="background:linear-gradient(135deg,#065F46,#10B981);border-radius:20px;padding:24px;color:white;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <p style="font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;letter-spacing:0.1em;">Total Diterima</p>
                <p style="font-size:30px;font-weight:900;margin-top:6px;">Rp {{ number_format($totalReceived, 0, ',', '.') }}</p>
            </div>
            <div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:26px;">✅</div>
        </div>
        <div style="background:linear-gradient(135deg,#92400E,#F97316);border-radius:20px;padding:24px;color:white;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <p style="font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;letter-spacing:0.1em;">Menunggu Verifikasi</p>
                <p style="font-size:30px;font-weight:900;margin-top:6px;">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
            </div>
            <div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:26px;">⏳</div>
        </div>
    </div>

    {{-- Table --}}
    <div style="background:white;border-radius:22px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <h3 style="font-size:15px;font-weight:800;color:#0f1f3d;margin-bottom:18px;display:flex;align-items:center;gap:8px;"><i class="fas fa-credit-card" style="color:#8B5CF6;"></i> Riwayat Pembayaran</h3>

        @if($paymentDetails->isEmpty())
        <div style="text-align:center;padding:48px;color:#CBD5E1;">
            <i class="fas fa-wallet" style="font-size:40px;margin-bottom:12px;display:block;"></i>
            <p style="font-weight:700;font-size:14px;">Belum ada data pembayaran</p>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentDetails as $payment)
                    <tr>
                        <td><span style="font-family:monospace;font-weight:700;color:#1a3a5c;font-size:12px;">{{ $payment->payment->transaction_code }}</span></td>
                        <td><span style="font-weight:800;color:#059669;">Rp {{ number_format($payment->amount_for_canteen, 0, ',', '.') }}</span></td>
                        <td>
                            @if($payment->status === 'pending')
                                <span style="background:#FEF3C7;color:#92400E;font-size:11px;font-weight:800;padding:4px 10px;border-radius:99px;">⏳ Menunggu</span>
                            @else
                                <span style="background:#D1FAE5;color:#065F46;font-size:11px;font-weight:800;padding:4px 10px;border-radius:99px;">✓ Selesai</span>
                            @endif
                        </td>
                        <td style="color:#64748B;">{{ $payment->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $paymentDetails->links() }}</div>
        @endif
    </div>
</div>
@endsection
