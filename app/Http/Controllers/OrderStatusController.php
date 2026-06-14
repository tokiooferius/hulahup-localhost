<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    /**
     * Show orders for current canteen with status tracking
     */
    public function index()
    {
        $canteenId = auth()->user()->canteen_id;
        
        // Get all orders for this canteen, grouped by status
        $orders = Order::where('canteen_id', $canteenId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Group by status
        $pendingOrders = $orders->where('status', 'pending')->values();
        $processingOrders = $orders->where('status', 'processing')->values();
        $completedOrders = $orders->where('status', 'completed')->values();
        
        return view('canteen.orders.index', [
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'completedOrders' => $completedOrders,
            'totalOrders' => $orders->count(),
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Verify user owns this canteen
        if ($order->canteen_id != auth()->user()->canteen_id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        // Update status with timestamp
        $order->status = $validated['status'];
        
        if ($validated['status'] === 'processing') {
            $order->processing_at = now();
        } elseif ($validated['status'] === 'completed') {
            $order->completed_at = now();
        }
        
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan diperbarui',
            'order' => $order,
        ]);
    }

    /**
     * Get order details
     */
    public function show(Order $order)
    {
        // Verify user owns this canteen
        if ($order->canteen_id != auth()->user()->canteen_id) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'order' => $order->load('user'),
        ]);
    }
}
