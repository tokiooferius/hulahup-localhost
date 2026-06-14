<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Setup Midtrans config - pakai env() langsung agar tidak bergantung cache
        \Midtrans\Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$clientKey    = env('MIDTRANS_CLIENT_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    /**
     * Buat Midtrans Snap Token — dipanggil via AJAX dari keranjang
     * POST /payment/snap-token
     */
    public function createSnapToken(Request $request)
    {
        $request->validate([
            'cart_by_canteen' => 'required|array',
            'grand_total'     => 'required|numeric|min:1',
            'payment_method'  => 'required|string',
            'notes'           => 'nullable|string',
        ]);

        $user       = auth()->user();
        $grandTotal = (int) $request->grand_total;

        // Buat kode transaksi unik
        $transactionCode = 'HUL-' . strtoupper(uniqid());

        // Simpan payment record dulu
        $payment = Payment::create([
            'user_id'          => $user->id,
            'transaction_code' => $transactionCode,
            'total_amount'     => $grandTotal,
            'status'           => 'pending',
            'payment_method'   => 'midtrans',
        ]);

        // Simpan detail per kantin & buat order
        $itemDetails = [];
        foreach ($request->cart_by_canteen as $canteenId => $canteenData) {
            PaymentDetail::create([
                'payment_id'          => $payment->id,
                'canteen_id'          => $canteenId,
                'amount_for_canteen'  => $canteenData['total'] ?? 0,
                'status'              => 'pending',
            ]);

            $orderNumber = Order::generateOrderNumber();
            Order::create([
                'user_id'      => $user->id,
                'canteen_id'   => $canteenId,
                'order_number' => $orderNumber,
                'items'        => $canteenData['items'] ?? [],
                'total_amount' => $canteenData['total'] ?? 0,
                'status'       => 'pending',
                'notes'        => ($request->notes ?? '') . ' | Pembayaran: Midtrans | TXN: ' . $transactionCode,
            ]);

            // Tambah item details untuk Midtrans
            foreach ($canteenData['items'] as $item) {
                $itemDetails[] = [
                    'id'       => 'ITEM-' . preg_replace('/\s+/', '-', strtoupper($item['name'])),
                    'price'    => (int) $item['price'],
                    'quantity' => (int) ($item['qty'] ?? 1),
                    'name'     => substr($item['name'], 0, 50), // Midtrans max 50 char
                ];
            }
        }

        // Simpan payment_id di session untuk callback
        session(['pending_payment_id' => $payment->id]);

        // Build Midtrans transaction params
        $params = [
            'transaction_details' => [
                'order_id'      => $transactionCode,
                'gross_amount'  => $grandTotal,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone ?? '08000000000',
            ],
            'item_details' => $itemDetails,
            'callbacks'    => [
                'finish'  => url('/payment/finish'),
                'error'   => url('/payment/error'),
                'pending' => url('/payment/pending'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan snap token ke payment record
            $payment->update(['midtrans_response' => ['snap_token' => $snapToken]]);

            return response()->json([
                'success'          => true,
                'snap_token'       => $snapToken,
                'transaction_code' => $transactionCode,
                'payment_id'       => $payment->id,
                'client_key'       => env('MIDTRANS_CLIENT_KEY'),
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: ' . $e->getMessage());
            $payment->update(['status' => 'failed']);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat sesi pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook dari Midtrans — update status otomatis
     * POST /payment/webhook
     */
    public function webhook(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;
            $orderId           = $notification->order_id; // = transaction_code kita
            $grossAmount       = $notification->gross_amount;

            Log::info("Midtrans Webhook: order_id={$orderId} status={$transactionStatus} fraud={$fraudStatus}");

            $payment = Payment::where('transaction_code', $orderId)->first();
            if (!$payment) {
                Log::warning("Payment not found for order_id: {$orderId}");
                return response()->json(['status' => 'not_found'], 404);
            }

            // Tentukan status berdasarkan Midtrans response
            if ($transactionStatus === 'capture') {
                $newStatus = ($fraudStatus === 'accept') ? 'paid' : 'fraud';
            } elseif ($transactionStatus === 'settlement') {
                $newStatus = 'paid';
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $newStatus = 'failed';
            } elseif ($transactionStatus === 'pending') {
                $newStatus = 'pending';
            } else {
                $newStatus = 'pending';
            }

            // Update payment
            $payment->update([
                'status'             => $newStatus,
                'midtrans_response'  => array_merge(
                    $payment->midtrans_response ?? [],
                    ['notification' => $request->all()]
                ),
            ]);

            // Kalau sudah bayar, update semua orders terkait ke "pending" (siap diproses kantin)
            if ($newStatus === 'paid') {
                $payment->paymentDetails->each(function ($detail) {
                    $detail->update(['status' => 'paid']);
                });

                // Update orders dengan transaction code ini ke status pending
                Order::where('notes', 'like', '%TXN: ' . $orderId . '%')
                    ->update(['status' => 'pending']);

                Log::info("Payment {$orderId} marked as PAID. Orders updated.");
            } elseif ($newStatus === 'failed') {
                // Batalkan orders
                Order::where('notes', 'like', '%TXN: ' . $orderId . '%')
                    ->update(['status' => 'cancelled']);
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Halaman finish setelah pembayaran Midtrans
     */
    public function finish(Request $request)
    {
        $transactionCode = $request->get('order_id');
        $payment = Payment::where('transaction_code', $transactionCode)->first();

        return view('payment.finish', [
            'payment'          => $payment,
            'transactionCode'  => $transactionCode,
            'transactionStatus'=> $request->get('transaction_status', 'pending'),
        ]);
    }

    /**
     * Halaman error pembayaran
     */
    public function error(Request $request)
    {
        return redirect('/home')->with('error', '❌ Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
    }

    /**
     * Halaman pending pembayaran
     */
    public function pending(Request $request)
    {
        return redirect('/orders/active')->with('info', '⏳ Pembayaran kamu sedang diproses. Kami akan update statusnya segera.');
    }

    /**
     * Cek status pembayaran (polling dari frontend)
     */
    public function status($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        if ($payment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return response()->json([
            'payment' => $payment,
            'details' => $payment->paymentDetails()->with('canteen')->get(),
        ]);
    }

    /**
     * (Legacy) checkout via form — tidak dipakai lagi, redirect ke home
     */
    public function checkout()
    {
        return redirect('/home');
    }

    /**
     * (Legacy) process via form
     */
    public function process(Request $request)
    {
        return redirect('/home');
    }
}
