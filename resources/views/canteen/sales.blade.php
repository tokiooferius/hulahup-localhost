@extends('layouts.kantin')

@section('page-title', 'Penjualan')
@section('content')

<style>
    .data-table { width:100%; border-collapse:separate; border-spacing:0; }
    .data-table th { background:#F8FAFC; font-size:11px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:0.1em; padding:14px 18px; text-align:left; }
    .data-table th:first-child { border-radius:14px 0 0 14px; }
    .data-table th:last-child  { border-radius:0 14px 14px 0; }
    .data-table td { padding:14px 18px; border-bottom:1px solid #F1F5F9; font-size:13px; }
    .data-table tr:last-child td { border-bottom:none; }
    .data-table tbody tr { transition:background .15s; }
    .data-table tbody tr:hover td { background:#F8FAFC; }
    .status-pill { font-size:11px; font-weight:800; padding:4px 10px; border-radius:99px; }
</style>

<div style="max-width:1100px;">

    {{-- Summary cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
        <div style="background:linear-gradient(135deg,#065F46,#10B981);border-radius:20px;padding:22px;color:white;">
            <p style="font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;letter-spacing:0.1em;">Total Pendapatan</p>
            <p style="font-size:28px;font-weight:900;margin-top:4px;letter-spacing:-0.5px;">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
            <p style="font-size:12px;opacity:.65;margin-top:6px;">dari semua transaksi</p>
        </div>
        <div style="background:white;border-radius:20px;padding:22px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <p style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:0.1em;">Total Pesanan</p>
            <p style="font-size:36px;font-weight:900;color:#1e293b;margin-top:4px;">{{ $orders->total() ?? $orders->count() }}</p>
            <p style="font-size:12px;color:#94A3B8;margin-top:6px;">semua status</p>
        </div>
        <div style="background:white;border-radius:20px;padding:22px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
            <p style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:0.1em;">Avg. Per Pesanan</p>
            <p style="font-size:28px;font-weight:900;color:#1e293b;margin-top:4px;">Rp {{ $orders->count() > 0 ? number_format($totalSales / $orders->count(), 0, ',', '.') : '0' }}</p>
            <p style="font-size:12px;color:#94A3B8;margin-top:6px;">rata-rata nilai</p>
        </div>
    </div>

    {{-- Table --}}
    <div style="background:white;border-radius:22px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
        <h3 style="font-size:15px;font-weight:800;color:#0f1f3d;margin-bottom:18px;display:flex;align-items:center;gap:8px;"><i class="fas fa-list" style="color:#2d6a8f;"></i> Semua Transaksi</h3>

        @if($orders->isEmpty())
        <div style="text-align:center;padding:48px;color:#CBD5E1;">
            <i class="fas fa-chart-bar" style="font-size:40px;margin-bottom:12px;display:block;"></i>
            <p style="font-weight:700;font-size:14px;">Belum ada data penjualan</p>
        </div>
        @else
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pembeli</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td><span style="font-family:monospace;font-weight:700;color:#1a3a5c;font-size:12px;">{{ $order->order_number }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:10px;background:linear-gradient(135deg,#2d6a8f,#5B88B2);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:12px;flex-shrink:0;">{{ strtoupper(substr($order->user->name,0,1)) }}</div>
                                <span style="font-weight:600;color:#1e293b;">{{ $order->user->name }}</span>
                            </div>
                        </td>
                        <td><span style="font-weight:800;color:#059669;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></td>
                        <td>
                            <span class="status-pill
                                @if($order->status==='pending')    " style="background:#FEF3C7;color:#92400E;"
                                @elseif($order->status==='processing') " style="background:#DBEAFE;color:#1E40AF;"
                                @elseif($order->status==='completed')  " style="background:#D1FAE5;color:#065F46;"
                                @else " style="background:#FEE2E2;color:#DC2626;"
                                @endif>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td style="color:#94A3B8;">{{ $order->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
