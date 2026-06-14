@extends('layouts.pembeli')

@section('title', 'Riwayat Pesanan')
@section('page-title', 'Riwayat Pesanan')

@section('extra-css')
<style>
    .page-content { background: #F8FAFC !important; }
    .hist-card { 
        background: white; 
        border-radius: 24px; 
        padding: 24px; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); 
        border: 1px solid #F1F5F9; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        cursor: pointer; 
        position: relative;
    }
    .hist-card:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04); 
        border-color: #E2E8F0;
    }
    .item-chip { 
        display: inline-flex; 
        align-items: center; 
        gap: 6px; 
        background: #F1F5F9; 
        border-radius: 12px; 
        padding: 6px 14px; 
        font-size: 13px; 
        font-weight: 600; 
        color: #334155; 
        margin: 4px; 
        transition: all 0.2s; 
    }
    .item-chip:hover {
        background: #E2E8F0;
    }
    .status-badge-completed {
        background: #DCFCE7;
        color: #15803D;
        font-size: 11px;
        font-weight: 800;
        padding: 6px 14px;
        border-radius: 99px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.02em;
    }
    .btn-reorder {
        background: #F97316;
        color: white;
        font-size: 12px;
        font-weight: 800;
        padding: 10px 20px;
        border-radius: 14px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.25);
        transition: all 0.2s ease;
    }
    .btn-reorder:hover {
        background: #EA580C;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(249, 115, 22, 0.35);
    }
    .btn-reorder:active {
        transform: translateY(1px);
    }
    .btn-receipt-sec {
        background: #FFFFFF;
        color: #475569;
        border: 1px solid #E2E8F0;
        font-size: 12px;
        font-weight: 700;
        padding: 10px 18px;
        border-radius: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn-receipt-sec:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
        color: #1E293B;
    }

    /* Receipt print styles */
    @media print {
        body * { visibility: hidden !important; }
        #receiptPrintArea, #receiptPrintArea * { visibility: visible !important; }
        #receiptPrintArea { position: fixed !important; inset: 0 !important; z-index: 9999 !important; background: white !important; display: flex !important; align-items: center !important; justify-content: center !important; }
    }
</style>
@endsection

@section('content')
<div style="max-width:900px; padding-bottom: 40px;">
    @if(session('success'))
    <div style="background:#DCFCE7; border:1px solid #BBF7D0; color:#15803D; padding:16px 20px; border-radius:18px; font-weight:700; font-size:14px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
        <i class="fa-solid fa-circle-check" style="font-size:16px;"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div style="background:#FEE2E2; border:1px solid #FCA5A5; color:#B91C1C; padding:16px 20px; border-radius:18px; font-weight:700; font-size:14px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
        <i class="fa-solid fa-circle-exclamation" style="font-size:16px;"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($orders->isEmpty())
    <div style="background:white;border-radius:28px;padding:72px 24px;text-align:center;box-shadow:0 4px 6px -1px rgba(0, 0, 0, 0.05); border:1px solid #F1F5F9;">
        <div style="width:80px;height:80px;background:#F1F5F9;border-radius:24px;display:flex;align-items:center;justify-content:center;font-size:36px;margin:0 auto 20px;">📭</div>
        <h3 style="font-size:20px;font-weight:900;color:#1e293b;margin-bottom:8px;">Belum Ada Riwayat</h3>
        <p style="color:#64748B;font-size:14px;margin-bottom:28px;">Pesanan yang sudah selesai akan muncul di sini.</p>
        <a href="{{ route('canteens.shop') }}" style="background:#F97316;color:white;padding:14px 32px;border-radius:16px;font-weight:800;text-decoration:none;font-size:14px;box-shadow:0 4px 12px rgba(249, 115, 22, 0.2);">Lihat Daftar Kantin</a>
    </div>
    @else
    <p style="color:#64748B;font-size:13px;font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:6px;padding-left:4px;">
        <span style="background:#E2E8F0;color:#475569;padding:2px 8px;border-radius:8px;font-size:11px;">{{ $orders->count() }}</span>
        <span>pesanan selesai</span>
    </p>
    <div style="display:flex;flex-direction:column;gap:18px;">
        @foreach($orders as $order)
        @php
            $items = is_array($order->items) ? $order->items : (json_decode($order->items, true) ?? []);
            $notes = $order->notes ?? '';
            $payMethod = 'CASH';
            $txnId = '';
            if (str_contains($notes, 'QRIS')) $payMethod = 'QRIS';
            elseif (str_contains($notes, 'TyU-Pay') || str_contains($notes, 'Saldo')) $payMethod = 'Saldo TyU-Pay';
            elseif (str_contains($notes, 'E-Wallet')) $payMethod = 'E-Wallet';
            elseif (str_contains($notes, 'Midtrans')) $payMethod = 'Midtrans';
            if (preg_match('/TXN:\s*(HUL-[A-Z0-9]+)/i', $notes, $m)) $txnId = $m[1];
            elseif (preg_match('/(HUL-[A-Z0-9]+)/i', $notes, $m)) $txnId = $m[1];
            $lokasiNote = '';
            if (preg_match('/(📍[^|]+)/', $notes, $m)) $lokasiNote = trim($m[1]);
        @endphp
        <div class="hist-card" onclick="openReceipt({{ $order->id }})">
            <!-- Header Row -->
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;background:#F8FAFC;border-radius:12px;display:flex;align-items:center;justify-content:center;border:1px solid #E2E8F0;color:#1A3A5C;font-size:16px;">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <div>
                        <h4 style="font-size:15px;font-weight:800;color:#1E293B;margin:0;">{{ $order->canteen->name ?? '-' }}</h4>
                        <p style="font-size:12px;color:#64748B;margin:2px 0 0 0;">{{ $order->created_at->format('d M Y, H:i') }} · <span style="font-weight:500;">{{ $order->created_at->diffForHumans() }}</span></p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="status-badge-completed">
                        <i class="fa-solid fa-circle-check" style="font-size:10px;"></i> Selesai
                    </span>
                </div>
            </div>

            <!-- Items List -->
            <div style="display:flex;flex-wrap:wrap;gap:2px;margin-bottom:14px;padding-left:2px;">
                @foreach($items as $item)
                <span class="item-chip">
                    <span style="font-size:14px;margin-right:2px;">🍽️</span> 
                    <span style="font-weight:700;">{{ $item['name'] }}</span> 
                    <span style="color:#64748B;font-weight:500;font-size:11px;margin-left:4px;background:#E2E8F0;padding:2px 6px;border-radius:6px;">x{{ $item['qty'] ?? $item['quantity'] ?? 1 }}</span>
                </span>
                @endforeach
            </div>

            <!-- Delivery / Note Badges -->
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:18px;">
                @if($lokasiNote)
                <span style="display:inline-flex;align-items:center;gap:6px;background:#EFF6FF;color:#1E40AF;font-size:12px;font-weight:700;padding:6px 12px;border-radius:10px;">
                    <i class="fa-solid fa-location-dot" style="font-size:10px;color:#3B82F6;"></i> {{ $lokasiNote }}
                </span>
                @endif
                <span style="display:inline-flex;align-items:center;gap:6px;background:#F8FAFC;border:1px solid #E2E8F0;color:#475569;font-size:11px;font-weight:700;padding:5px 12px;border-radius:10px;font-family:monospace;letter-spacing:0.02em;">
                    <i class="fa-solid fa-hashtag" style="font-size:9px;color:#94A3B8;"></i> {{ $order->order_number }}
                </span>
            </div>

            <!-- Card Footer Row -->
            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;border-top:1px solid #F1F5F9;">
                <!-- Payment details -->
                <div style="display:flex;gap:20px;">
                    <div>
                        <p style="font-size:10px;color:#94A3B8;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 3px 0;">Metode Bayar</p>
                        <p style="font-size:13px;font-weight:800;color:#475569;margin:0;display:flex;align-items:center;gap:4px;">
                            <i class="fa-solid fa-wallet" style="font-size:11px;color:#64748B;"></i> {{ $payMethod === 'Midtrans' ? 'Online Payment' : $payMethod }}
                        </p>
                    </div>
                    <div>
                        <p style="font-size:10px;color:#94A3B8;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 3px 0;">Total Bayar</p>
                        <p style="font-size:18px;font-weight:900;color:#1E293B;margin:0;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div style="display:flex;gap:10px;" onclick="event.stopPropagation();">
                    <button onclick="openReceipt({{ $order->id }})" class="btn-receipt-sec">
                        <i class="fa-solid fa-receipt"></i> Struk
                    </button>
                    <form action="{{ route('orders.reorder', $order->id) }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-reorder">
                            <i class="fa-solid fa-redo-alt" style="font-size:11px;"></i> Pesan Lagi
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- ===== RECEIPT MODAL ===== -->
<div id="receiptModal" style="display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:16px;">
    <div style="background:white;border-radius:28px;width:100%;max-width:400px;max-height:90vh;overflow-y:auto;box-shadow:0 24px 64px rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.2);">

        <!-- Header -->
        <div style="background:linear-gradient(135deg,#122C4F,#2d6a8f);border-radius:28px 28px 0 0;padding:20px 24px;display:flex;justify-content:space-between;align-items:center;">
            <h3 style="color:white;font-weight:900;font-size:16px;margin:0;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-receipt"></i> Detail Struk
            </h3>
            <button onclick="closeReceipt()" style="background:rgba(255,255,255,0.2);border:none;color:white;width:32px;height:32px;border-radius:10px;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">✕</button>
        </div>

        <div id="receiptPrintArea" style="padding:24px;">
            <!-- Store Header -->
            <div style="text-align:center;margin-bottom:16px;">
                <p style="font-size:20px;font-weight:900;color:#122C4F;letter-spacing:-0.5px;margin:0 0 2px 0;">FOOD-TYU</p>
                <p style="font-size:9px;color:#94A3B8;font-weight:800;letter-spacing:0.15em;text-transform:uppercase;margin:0 0 8px 0;">KANTIN TEL-U</p>
                <p id="rcptCanteen" style="font-size:14px;font-weight:800;color:#1E293B;margin:4px 0 2px 0;"></p>
                <p id="rcptIbuKantin" style="font-size:11px;color:#64748B;margin:0;"></p>
                <p style="font-size:10px;font-weight:800;color:#F97316;letter-spacing:0.1em;text-transform:uppercase;margin:10px 0 0 0;">CASH RECEIPT</p>
            </div>

            <div style="border-top:1px dashed #E2E8F0;border-bottom:1px dashed #E2E8F0;padding:12px 0;margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:5px;">
                    <span style="color:#64748B;">Date</span>
                    <span id="rcptDate" style="font-weight:700;"></span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:5px;">
                    <span style="color:#64748B;">Time</span>
                    <span id="rcptTime" style="font-weight:700;"></span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;">
                    <span style="color:#64748B;">Order #</span>
                    <span id="rcptOrderNum" style="font-weight:900;font-family:monospace;color:#122C4F;"></span>
                </div>
            </div>

            <!-- Items -->
            <div id="rcptItems" style="margin-bottom:12px;border-bottom:1px dashed #E2E8F0;padding-bottom:12px;"></div>

            <!-- Totals -->
            <div style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:900;color:#122C4F;margin-bottom:8px;">
                    <span>TOTAL</span>
                    <span id="rcptTotal"></span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                    <span style="color:#64748B;">Payment</span>
                    <span id="rcptPayMethod" style="font-weight:800;color:#2d6a8f;"></span>
                </div>
                <div id="rcptTxnRow" style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px;">
                    <span style="color:#64748B;">Transaction ID</span>
                    <span id="rcptTxnId" style="font-weight:700;font-family:monospace;color:#2d6a8f;font-size:10px;"></span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                    <span style="color:#64748B;">Status</span>
                    <span style="font-weight:800;color:#22C55E;">✓ PAID</span>
                </div>
                <div id="rcptLokasiRow" style="display:flex;justify-content:space-between;font-size:11px;">
                    <span style="color:#64748B;">Lokasi</span>
                    <span id="rcptLokasi" style="font-weight:700;color:#122C4F;text-align:right;max-width:200px;font-size:10px;"></span>
                </div>
                <div id="rcptMidtransRow" style="display:flex;justify-content:space-between;font-size:11px;margin-top:4px;">
                    <span style="color:#64748B;">Secured by</span>
                    <span style="font-weight:800;color:#2d6a8f;">Midtrans 🔒</span>
                </div>
            </div>

            <!-- Footer -->
            <div style="text-align:center;padding-top:12px;border-top:1px dashed #E2E8F0;">
                <p style="font-size:14px;font-weight:900;color:#122C4F;letter-spacing:0.05em;margin:0 0 2px 0;">THANK YOU</p>
                <p style="font-size:11px;color:#94A3B8;margin:0;">Untuk Pesananmu</p>
                <div style="display:flex;justify-content:center;gap:2px;margin:12px 0;">
                    @for($i=0;$i<18;$i++)<div style="width:3px;height:16px;background:#122C4F;border-radius:1px;opacity:{{ $i%3==0?'1':($i%3==1?'0.4':'0.7') }};"></div>@endfor
                </div>
                <p id="rcptOrderNumBarcode" style="font-size:10px;font-family:monospace;color:#64748B;margin:0;"></p>
                <p style="font-size:10px;color:#94A3B8;margin:6px 0 0 0;font-style:italic;">Kualitas adalah prioritas kami<br>Semoga Anda puas 😊</p>
            </div>
        </div>

        <!-- Action buttons -->
        <div style="padding:0 24px 24px;display:flex;gap:10px;">
            <button onclick="printReceipt()" style="flex:1;background:#122C4F;color:white;font-weight:800;font-size:13px;padding:12px;border-radius:14px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all 0.2s;" onmouseover="this.style.background='#1e3e6b'" onmouseout="this.style.background='#122C4F'">
                <i class="fas fa-print"></i> Print Struk
            </button>
            <button onclick="closeReceipt()" style="flex:1;background:#F1F5F9;color:#475569;font-weight:800;font-size:13px;padding:12px;border-radius:14px;border:none;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
const ordersData = @json($ordersForJs);

function openReceipt(orderId) {
    const order = ordersData.find(o => o.id === orderId);
    if (!order) return;

    document.getElementById('rcptCanteen').textContent = order.canteen;
    document.getElementById('rcptIbuKantin').textContent = order.ibu_kantin;
    document.getElementById('rcptDate').textContent = order.date;
    document.getElementById('rcptTime').textContent = order.time;
    document.getElementById('rcptOrderNum').textContent = order.order_number;
    document.getElementById('rcptOrderNumBarcode').textContent = order.order_number;
    document.getElementById('rcptTotal').textContent = 'Rp ' + parseInt(order.total).toLocaleString('id-ID');
    document.getElementById('rcptPayMethod').textContent = order.pay_method;

    const txnRow = document.getElementById('rcptTxnRow');
    if (order.txn_id) {
        document.getElementById('rcptTxnId').textContent = order.txn_id;
        txnRow.style.display = 'flex';
    } else { txnRow.style.display = 'none'; }

    const lokasiRow = document.getElementById('rcptLokasiRow');
    if (order.lokasi) {
        document.getElementById('rcptLokasi').textContent = order.lokasi;
        lokasiRow.style.display = 'flex';
    } else { lokasiRow.style.display = 'none'; }

    document.getElementById('rcptMidtransRow').style.display =
        order.pay_method === 'Saldo TyU-Pay' ? 'none' : 'flex';

    const itemsEl = document.getElementById('rcptItems');
    itemsEl.innerHTML = '';
    (order.items || []).forEach(item => {
        const qty   = item.qty ?? item.quantity ?? 1;
        const price = item.price ?? 0;
        itemsEl.innerHTML += `
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12px;padding:4px 0;">
                <div style="flex:1;">
                    <span style="font-weight:700;color:#1e293b;">${item.name}</span>
                    <span style="color:#94A3B8;"> ×${qty}</span>
                </div>
                <span style="font-weight:800;color:#122C4F;">${(qty * price).toLocaleString('id-ID')}</span>
            </div>`;
    });

    const modal = document.getElementById('receiptModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeReceipt() {
    document.getElementById('receiptModal').style.display = 'none';
    document.body.style.overflow = '';
}

function printReceipt() { window.print(); }

document.getElementById('receiptModal').addEventListener('click', function(e) {
    if (e.target === this) closeReceipt();
});
</script>
@endsection
