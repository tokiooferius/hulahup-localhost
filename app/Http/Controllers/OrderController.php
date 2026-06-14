<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * API endpoint untuk save order ke database
     * UPDATED: Create separate orders per canteen (split payment)
     */
    public function store(Request $request)
    {
        $request->validate([
            'cart_by_canteen' => 'required|array', // Array of [canteen_id => [items, total]]
            'grand_total' => 'required|numeric',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $user = Auth::user();
            $grandTotal = (float) $request->grand_total;
            $paymentMethod = $request->payment_method;
            $cartByCanteen = $request->cart_by_canteen;
            
            // DEFENSIVE PROGRAMMING: Jika bayar pakai Saldo TyU-Pay, validasi saldo mencukupi
            if ($paymentMethod === 'Saldo TyU-Pay') {
                if ($user->balance < $grandTotal) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memesan! Saldo TyU-Pay kamu tidak mencukupi. Saldo: Rp ' . number_format($user->balance, 0, ',', '.') . ', Total: Rp ' . number_format($grandTotal, 0, ',', '.') . '. Silakan lakukan top up terlebih dahulu.',
                        'balance' => $user->balance,
                        'required' => $grandTotal,
                    ], 400);
                }
            }
            
            // PRE-GENERATE ORDER NUMBERS FOR ALL CANTEENS (atomic, outside transaction)
            // Get the base order number, then increment it for each canteen
            $baseOrderNumber = Order::generateOrderNumber();
            $baseNum = intval(substr($baseOrderNumber, 3)); // Extract number from 'ORDxxxxx'
            
            $orderNumbers = [];
            $canteenIndex = 0;
            foreach ($cartByCanteen as $canteenId => $cartData) {
                $orderNumbers[$canteenId] = 'ORD' . str_pad($baseNum + $canteenIndex, 5, '0', STR_PAD_LEFT);
                $canteenIndex++;
            }
            
            // CREATE SEPARATE ORDERS FOR EACH CANTEEN (with transaction protection)
            $createdOrders = [];
            $totalDeduct = 0;
            $successMsg = '';
            $notes = $request->notes ?? null;
            
            DB::transaction(function () use (
                &$createdOrders,
                &$totalDeduct,
                &$successMsg,
                $orderNumbers,
                $cartByCanteen,
                $paymentMethod,
                $user,
                $notes
            ) {
                foreach ($cartByCanteen as $canteenId => $cartData) {
                    // Get pre-generated order number
                    $orderNumber = $orderNumbers[$canteenId];
                    
                    // Build notes
                    $notesParts = [];
                    $notesParts[] = 'Pembayaran: ' . $paymentMethod;
                    if ($notes) {
                        $notesParts[] = $notes;
                    }
                    
                    // Create order with canteen_id - THIS IS THE FIX!
                    $order = Order::create([
                        'user_id' => $user->id,
                        'canteen_id' => $canteenId, // ✅ NOW STORING CANTEEN_ID!
                        'order_number' => $orderNumber,
                        'items' => $cartData['items'] ?? [],
                        'total_amount' => $cartData['total'] ?? 0,
                        'status' => 'pending',
                        'notes' => implode(' | ', $notesParts),
                    ]);
                    
                    $createdOrders[] = $order;
                    $totalDeduct += $order->total_amount;
                }
                
                // Jika bayar pakai Saldo TyU-Pay, potong saldo otomatis
                if ($paymentMethod === 'Saldo TyU-Pay') {
                    $user->decrement('balance', $totalDeduct);
                    $user->refresh();
                    $successMsg = 'Pesanan berhasil dibuat dari ' . count($createdOrders) . ' kantin! Saldo TyU-Pay kamu otomatis terpotong.';
                } else {
                    // Untuk metode lain (QRIS, E-Wallet): order dibuat tanpa potong saldo
                    $successMsg = 'Pesanan berhasil dibuat dari ' . count($createdOrders) . ' kantin! Tunggu konfirmasi dari sistem pembayaran.';
                }
            });

            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'orders' => $createdOrders,
                'order_count' => count($createdOrders),
                'balance_remaining' => $user->balance,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage(),
                'error' => $e->getLine(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Get all ACTIVE orders for authenticated user (pending or processing)
     * Used for "Pesanan Aktif" dashboard
     */
    public function activeOrders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->with(['canteen' => function ($query) {
                $query->select('id', 'name', 'ibu_kantin_id');
                $query->with(['ibuKantin:id,name']);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.active', [
            'orders' => $orders,
            'user' => $user,
        ]);
    }

    /**
     * Get full order details with canteen info (for receipt/tracking)
     * API endpoint: GET /api/orders/{order}
     */
    public function getOrderDetails(Order $order)
    {
        // Verify user owns this order
        if (Auth::id() !== $order->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $order->load(['canteen' => function ($query) {
            $query->with(['ibuKantin:id,name,email']);
        }]);

        return response()->json([
            'success' => true,
            'order' => $order,
            'items' => json_decode($order->items, true) ?? [],
        ]);
    }

    /**
     * Get all COMPLETED orders for authenticated user (riwayat/history)
     * Used for "Riwayat Pesanan" page
     */
    public function orderHistory()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->where('status', '=', 'completed')
            ->with(['canteen' => function ($query) {
                $query->select('id', 'name', 'ibu_kantin_id');
                $query->with(['ibuKantin:id,name']);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Pre-process untuk JS receipt (hindari PHP logic di dalam JS section)
        $ordersForJs = $orders->map(function($o) {
            $items = is_array($o->items) ? $o->items : (json_decode($o->items, true) ?? []);
            $notes = $o->notes ?? '';
            $payMethod = 'CASH';
            if (str_contains($notes, 'QRIS')) $payMethod = 'QRIS';
            elseif (str_contains($notes, 'TyU-Pay') || str_contains($notes, 'Saldo')) $payMethod = 'Saldo TyU-Pay';
            elseif (str_contains($notes, 'E-Wallet')) $payMethod = 'E-Wallet';
            elseif (str_contains($notes, 'Midtrans')) $payMethod = 'Midtrans';
            $txnId = '';
            if (preg_match('/TXN:\s*(HUL-[A-Z0-9]+)/i', $notes, $m)) $txnId = $m[1];
            elseif (preg_match('/(HUL-[A-Z0-9]+)/i', $notes, $m)) $txnId = $m[1];
            $lokasi = '';
            if (preg_match('/(📍[^|]+)/', $notes, $m)) $lokasi = trim($m[1]);
            return [
                'id'           => $o->id,
                'order_number' => $o->order_number,
                'canteen'      => $o->canteen->name ?? '-',
                'ibu_kantin'   => optional(optional($o->canteen)->ibuKantin)->name ?? '-',
                'items'        => $items,
                'total'        => $o->total_amount,
                'pay_method'   => $payMethod,
                'txn_id'       => $txnId,
                'lokasi'       => $lokasi,
                'date'         => $o->created_at->format('d/m/Y'),
                'time'         => $o->created_at->format('H:i:s'),
            ];
        });

        return view('history', [
            'orders'      => $orders,
            'ordersForJs' => $ordersForJs,
            'user'        => $user,
        ]);
    }

    /**
     * Reorder items from a past order
     */
    public function reorder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return back()->with('error', 'Akses ditolak.');
        }

        $items = is_array($order->items) ? $order->items : (json_decode($order->items, true) ?? []);
        $cart = session()->get('cart', []);
        
        foreach ($items as $item) {
            $menuId = $item['menu_id'] ?? null;
            $qty = $item['quantity'] ?? $item['qty'] ?? 1;
            
            if ($menuId) {
                $menu = \App\Models\Menu::find($menuId);
                if ($menu) {
                    $existingKey = null;
                    foreach ($cart as $key => $cartItem) {
                        if ($cartItem['menu_id'] == $menu->id) {
                            $existingKey = $key;
                            break;
                        }
                    }
                    
                    if ($existingKey !== null) {
                        $cart[$existingKey]['quantity'] += $qty;
                    } else {
                        $cart[] = [
                            'menu_id' => $menu->id,
                            'canteen_id' => $menu->canteen_id,
                            'name' => $menu->name,
                            'price' => $menu->price,
                            'quantity' => $qty,
                        ];
                    }
                }
            }
        }
        
        session()->put('cart', $cart);
        
        return redirect()->route('cart.index')->with('success', 'Item dari pesanan sebelumnya telah ditambahkan ke keranjang!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
