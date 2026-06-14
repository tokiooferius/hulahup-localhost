<?php

namespace App\Http\Controllers;

use App\Models\Canteen;
use App\Models\Menu;
use App\Models\Voucher;
use App\Models\Order;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

class CanteenController extends Controller
{
    /**
     * Show canteen dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        if (!$user->isIbuKantin()) {
            abort(403, 'Hanya Pemilik Kantin yang bisa akses dashboard ini');
        }

        // Get canteen yang dimiliki user
        $canteen = $user->canteen;
        
        if (!$canteen) {
            return redirect('/canteen/create')->with('error', 'Anda belum memiliki kantin');
        }

        // Stats
        $totalMenus = $canteen->menus()->count();
        $totalVouchers = $canteen->vouchers()->count();
        $totalOrders = $canteen->orders()->count();
        $totalRevenue = $canteen->orders()
            ->where('status', 'completed')
            ->sum('total_amount');

        // Order status breakdown
        $pendingOrders = $canteen->orders()->where('status', 'pending')->count();
        $processingOrders = $canteen->orders()->where('status', 'processing')->count();
        $completedOrders = $canteen->orders()->where('status', 'completed')->count();

        // Recent orders
        $recentOrders = $canteen->orders()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // Pending payments
        $pendingPayments = PaymentDetail::where('canteen_id', $canteen->id)
            ->where('status', 'pending')
            ->with('payment')
            ->latest()
            ->take(5)
            ->get();

        return view('canteen.dashboard', compact(
            'canteen',
            'totalMenus',
            'totalVouchers',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'recentOrders',
            'pendingPayments'
        ));
    }

    /**
     * Show create canteen form
     */
    public function create()
    {
        return view('canteen.create');
    }

    /**
     * Store new canteen
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->isIbuKantin()) {
            abort(403, 'Hanya Pemilik Kantin yang bisa membuat kantin');
        }

        // Check if user already has canteen
        if ($user->canteen) {
            return redirect('/canteen/dashboard')->with('error', 'Anda sudah memiliki kantin');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'logo_url' => 'nullable|url',
        ]);

        $canteen = Canteen::create([
            'ibu_kantin_id' => $user->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'logo_url' => $validated['logo_url'] ?? null,
            'status' => 'active',
        ]);

        // Update user's canteen_id
        $user->update(['canteen_id' => $canteen->id]);

        return redirect('/canteen/dashboard')->with('success', 'Kantin berhasil dibuat');
    }

    /**
     * Show menu management page
     */
    public function menuIndex()
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        $menus = $canteen->menus()->latest()->paginate(10);

        return view('canteen.menus.index', compact('canteen', 'menus'));
    }

    /**
     * Show create menu form
     */
    public function menuCreate()
    {
        $canteen = auth()->user()->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        return view('canteen.menus.create', compact('canteen'));
    }

    /**
     * Store new menu item
     */
    public function menuStore(Request $request)
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:heavy,beverage,snack',
            'price' => 'required|numeric|min:1000',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        // Handle file upload
        $imagePath = 'https://via.placeholder.com/200';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('menus', $filename, 'public');
            $imagePath = 'storage/menus/' . $filename;
        }

        Menu::create([
            'canteen_id' => $canteen->id,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image_url' => $imagePath,
            'is_available' => $request->has('is_available') ? $request->boolean('is_available') : true,
        ]);

        return redirect('/canteen/menus')->with('success', 'Menu berhasil ditambahkan');
    }

    /**
     * Show edit menu form
     */
    public function menuEdit($id)
    {
        $menu = Menu::findOrFail($id);
        $canteen = auth()->user()->canteen;

        if ($menu->canteen_id !== $canteen->id) {
            abort(403, 'Anda tidak memiliki akses ke menu ini');
        }

        return view('canteen.menus.edit', compact('menu', 'canteen'));
    }

    /**
     * Update menu item
     */
    public function menuUpdate(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $canteen = auth()->user()->canteen;

        if ($menu->canteen_id !== $canteen->id) {
            abort(403, 'Anda tidak memiliki akses ke menu ini');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:heavy,beverage,snack',
            'price' => 'required|numeric|min:1000',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('menus', $filename, 'public');
            $validated['image_url'] = 'storage/menus/' . $filename;
        }

        $menu->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image_url' => $validated['image_url'] ?? $menu->image_url,
            'is_available' => $request->has('is_available') ? $request->boolean('is_available') : false,
        ]);

        return redirect('/canteen/menus')->with('success', 'Menu berhasil diupdate');
    }

    /**
     * Delete menu item
     */
    public function menuDestroy($id)
    {
        $menu = Menu::findOrFail($id);
        $canteen = auth()->user()->canteen;

        if ($menu->canteen_id !== $canteen->id) {
            abort(403, 'Anda tidak memiliki akses ke menu ini');
        }

        $menu->delete();

        return redirect('/canteen/menus')->with('success', 'Menu berhasil dihapus');
    }

    /**
     * Show voucher management page
     */
    public function voucherIndex()
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        $vouchers = $canteen->vouchers()->latest()->paginate(10);

        return view('canteen.vouchers.index', compact('canteen', 'vouchers'));
    }

    /**
     * Show create voucher form
     */
    public function voucherCreate()
    {
        $canteen = auth()->user()->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        return view('canteen.vouchers.create', compact('canteen'));
    }

    /**
     * Store new voucher
     */
    public function voucherStore(Request $request)
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        $validated = $request->validate([
            'code' => 'required|string|unique:vouchers,code',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        // Konversi format tanggal dari datetime-local (ISO) ke format database
        $validated['valid_from'] = date('Y-m-d H:i:s', strtotime($validated['valid_from']));
        $validated['valid_to']   = date('Y-m-d H:i:s', strtotime($validated['valid_to']));

        // Default ke 999999 jika kosong (tanpa limit)
        $validated['max_uses'] = $validated['max_uses'] ?? 999999;

        Voucher::create([
            'canteen_id' => $canteen->id,
            ...$validated,
        ]);

        return redirect('/canteen/vouchers')->with('success', 'Voucher berhasil dibuat! 🎉');
    }

    /**
     * Show edit voucher form
     */
    public function voucherEdit($id)
    {
        $voucher = Voucher::findOrFail($id);
        $canteen = auth()->user()->canteen;

        if ($voucher->canteen_id !== $canteen->id) {
            abort(403, 'Anda tidak memiliki akses ke voucher ini');
        }

        return view('canteen.vouchers.edit', compact('voucher', 'canteen'));
    }

    /**
     * Update voucher
     */
    public function voucherUpdate(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        $canteen = auth()->user()->canteen;

        if ($voucher->canteen_id !== $canteen->id) {
            abort(403, 'Anda tidak memiliki akses ke voucher ini');
        }

        $validated = $request->validate([
            'code' => 'required|string|unique:vouchers,code,' . $id,
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        // Konversi format tanggal dari datetime-local (ISO) ke format database
        $validated['valid_from'] = date('Y-m-d H:i:s', strtotime($validated['valid_from']));
        $validated['valid_to']   = date('Y-m-d H:i:s', strtotime($validated['valid_to']));

        // Default ke 999999 jika kosong (tanpa limit)
        $validated['max_uses'] = $validated['max_uses'] ?? 999999;

        $voucher->update($validated);

        return redirect('/canteen/vouchers')->with('success', 'Voucher berhasil diupdate! ✅');
    }

    /**
     * Delete voucher
     */
    public function voucherDestroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $canteen = auth()->user()->canteen;

        if ($voucher->canteen_id !== $canteen->id) {
            abort(403, 'Anda tidak memiliki akses ke voucher ini');
        }

        $voucher->delete();

        return redirect('/canteen/vouchers')->with('success', 'Voucher berhasil dihapus');
    }

    /**
     * Show sales/orders page
     */
    public function sales()
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        $orders = $canteen->orders()
            ->with('user')
            ->latest()
            ->paginate(15);

        $totalSales = $canteen->orders()
            ->where('status', 'completed')
            ->sum('total_amount');

        return view('canteen.sales', compact('canteen', 'orders', 'totalSales'));
    }

    /**
     * Show payment tracking page
     */
    public function payments()
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        $paymentDetails = $canteen->paymentDetails()
            ->with('payment', 'settlement')
            ->latest()
            ->paginate(15);

        $totalReceived = $canteen->settlements()
            ->where('status', 'completed')
            ->sum('amount_received');

        $totalPending = $canteen->paymentDetails()
            ->where('status', 'pending')
            ->sum('amount_for_canteen');

        return view('canteen.payments', compact(
            'canteen',
            'paymentDetails',
            'totalReceived',
            'totalPending'
        ));
    }

    /**
     * Public shop listing - show all canteens (for customers)
     * GET /canteens
     */
    public function shop()
    {
        $canteens = Canteen::with(['ibuKantin:id,name', 'menus'])
            ->where('status', 'active')
            ->get()
            ->map(function ($canteen) {
                // Calculate rating from completed orders
                $completedOrders = $canteen->orders()
                    ->where('status', 'completed')
                    ->count();
                
                // For now, rating = number of completed orders as a proxy
                // You can replace this with actual review ratings later
                $canteen->rating = $completedOrders > 0 ? min(5, 3 + ($completedOrders / 20)) : 3;
                $canteen->completed_orders = $completedOrders;
                
                return $canteen;
            });

        return view('canteens.shop', compact('canteens'));
    }

    /**
     * Public shop detail - show specific canteen and their menus
     * GET /canteens/{canteen}
     */
    public function shopDetail(Canteen $canteen)
    {
        $canteen->load(['menus', 'ibuKantin:id,name,email']);

        // Calculate rating
        $completedOrders = $canteen->orders()
            ->where('status', 'completed')
            ->count();
        $canteen->rating = $completedOrders > 0 ? min(5, 3 + ($completedOrders / 20)) : 3;
        $canteen->completed_orders = $completedOrders;

        // Group menus by category
        $menusByCategory = $canteen->menus
            ->groupBy('category')
            ->map(function ($items) {
                return $items->values();
            });

        return view('canteens.detail', compact('canteen', 'menusByCategory'));
    }

    /**
     * Upload foto kantin (ibu kantin hanya bisa ubah kantin miliknya)
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $user   = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan'], 404);
        }

        // Hapus foto lama kalau ada
        if ($canteen->image && file_exists(public_path($canteen->image))) {
            @unlink(public_path($canteen->image));
        }

        $file     = $request->file('photo');
        $filename = 'canteen_' . $canteen->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('canteens', $filename, 'public');

        $canteen->update([
            'image'    => 'storage/canteens/' . $filename,
            'logo_url' => 'storage/canteens/' . $filename,
        ]);

        return response()->json([
            'success' => true,
            'image'   => asset('storage/canteens/' . $filename),
            'message' => 'Foto kantin berhasil diupdate!',
        ]);
    }

    /**
     * Show export panel page for canteen
     */
    public function exportIndex()
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create')->with('error', 'Anda belum memiliki kantin');
        }

        return view('canteen.export', compact('canteen'));
    }

    /**
     * Export canteen orders to CSV
     */
    public function exportOrders(Request $request)
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        $query = $canteen->orders()->with('user')->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=riwayat_penjualan_kantin_' . date('Ymd_His') . '.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'ID Pesanan', 
                'Nama Pembeli', 
                'Total Pembayaran', 
                'Potongan Voucher', 
                'Metode Pembayaran', 
                'Status', 
                'Tanggal Pesanan'
            ], ';');

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? 'User Terhapus',
                    'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                    'Rp ' . number_format($order->discount_amount ?? 0, 0, ',', '.'),
                    strtoupper($order->payment_method ?? 'CASH'),
                    ucfirst($order->status),
                    $order->created_at->format('Y-m-d H:i:s')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export menus performance to CSV
     */
    public function exportMenus(Request $request)
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        // Fetch menus along with total quantity sold and revenue from completed orders
        $menus = $canteen->menus()->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=performa_menu_kantin_' . date('Ymd_His') . '.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $callback = function() use ($menus) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Nama Menu', 
                'Kategori', 
                'Harga', 
                'Status Ketersediaan', 
                'Total Terjual', 
                'Total Omset Menu'
            ], ';');

            foreach ($menus as $menu) {
                // Get sales count and sum
                $salesData = \Illuminate\Support\Facades\DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('order_items.menu_id', $menu->id)
                    ->where('orders.status', 'completed')
                    ->select(
                        \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_qty'),
                        \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
                    )
                    ->first();

                $totalQty = $salesData->total_qty ?? 0;
                $totalRevenue = $salesData->total_revenue ?? 0;

                fputcsv($file, [
                    $menu->name,
                    ucfirst($menu->category),
                    'Rp ' . number_format($menu->price, 0, ',', '.'),
                    $menu->is_available ? 'Tersedia' : 'Habis',
                    $totalQty . ' porsi',
                    'Rp ' . number_format($totalRevenue, 0, ',', '.')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * View sales report print layout (for saving to PDF)
     */
    public function printOrders(Request $request)
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        $query = $canteen->orders()->with('user')->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->get();
        
        // Hitung ringkasan statistik
        $totalOrders = $orders->count();
        $totalRevenue = $orders->where('status', 'completed')->sum('total_amount');
        $totalDiscount = $orders->sum('discount_amount');
        
        // Hitung berdasarkan status
        $statusCounts = $orders->groupBy('status')->map(fn($group) => $group->count());

        return view('canteen.print_orders', compact(
            'canteen', 
            'orders', 
            'totalOrders', 
            'totalRevenue', 
            'totalDiscount', 
            'statusCounts',
            'request'
        ));
    }

    /**
     * View menus performance report print layout (for saving to PDF)
     */
    public function printMenus(Request $request)
    {
        $user = auth()->user();
        $canteen = $user->canteen;

        if (!$canteen) {
            return redirect('/canteen/create');
        }

        $menus = $canteen->menus()->get();
        
        $menuReport = [];
        $totalPorsiSold = 0;
        $totalCanteenOmset = 0;

        foreach ($menus as $menu) {
            $salesData = \Illuminate\Support\Facades\DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('order_items.menu_id', $menu->id)
                ->where('orders.status', 'completed')
                ->select(
                    \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_qty'),
                    \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
                )
                ->first();

            $qty = $salesData->total_qty ?? 0;
            $revenue = $salesData->total_revenue ?? 0;
            
            $totalPorsiSold += $qty;
            $totalCanteenOmset += $revenue;

            $menuReport[] = [
                'name' => $menu->name,
                'category' => $menu->category,
                'price' => $menu->price,
                'is_available' => $menu->is_available,
                'total_qty' => $qty,
                'total_revenue' => $revenue
            ];
        }

        // Urutkan berdasarkan yang terlaris (total porsi terbanyak)
        usort($menuReport, function($a, $b) {
            return $b['total_qty'] <=> $a['total_qty'];
        });

        return view('canteen.print_menus', compact(
            'canteen', 
            'menuReport', 
            'totalPorsiSold', 
            'totalCanteenOmset'
        ));
    }

    /**
     * API for real-time notifications on new orders (canteen area)
     */
    public function apiNotifications()
    {
        $user = auth()->user();
        $canteen = $user->canteen;
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'No canteen found']);
        }
        
        $pendingOrders = \App\Models\Order::where('canteen_id', $canteen->id)
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();

        $latestPending = $pendingOrders->first();

        // Ambil 5 pesanan pending terbaru untuk dirender di dropdown topbar
        $ordersList = $pendingOrders->take(5)->map(function($o) {
            return [
                'id' => $o->id,
                'order_number' => $o->order_number,
                'buyer_name' => $o->user->name ?? 'Pembeli',
                'amount' => 'Rp ' . number_format($o->total_amount, 0, ',', '.'),
                'time' => $o->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success' => true,
            'pending_count' => $pendingOrders->count(),
            'latest_pending_id' => $latestPending ? $latestPending->id : null,
            'latest_pending_number' => $latestPending ? $latestPending->order_number : null,
            'latest_pending_buyer' => $latestPending ? ($latestPending->user->name ?? 'Pembeli') : null,
            'latest_pending_time' => $latestPending ? $latestPending->created_at->diffForHumans() : null,
            'orders_list' => $ordersList,
        ]);
    }
}
