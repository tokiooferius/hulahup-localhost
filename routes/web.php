<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CanteenController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;

// Healthcheck endpoint for Railway
Route::get('/up', function () {
    return response()->json(['status' => 'ok']);
});

// 1. Halaman LANDING PAGE (Welcome) - Ini pintu masuk utama
Route::get('/', function () {
    // Stats realtime dari DB
    $activeCanteenCount = \App\Models\Canteen::whereIn('status', ['buka', 'active', 'open'])->count();
    $totalCanteenCount  = \App\Models\Canteen::count();
    $totalMenuCount     = \App\Models\Menu::where('is_available', true)->count();

    // Menu paling laris: ambil semua completed orders, parse items JSON, hitung frekuensi per menu_id
    $orders = \App\Models\Order::whereIn('status', ['completed', 'selesai', 'siap_diambil'])
        ->pluck('items');

    $menuSales = [];
    foreach ($orders as $items) {
        $itemsArr = is_array($items) ? $items : (json_decode($items, true) ?: []);
        foreach ($itemsArr as $item) {
            $mid = $item['menu_id'] ?? $item['id'] ?? null;
            if ($mid) {
                $menuSales[$mid] = ($menuSales[$mid] ?? 0) + ($item['quantity'] ?? $item['qty'] ?? 1);
            }
        }
    }
    arsort($menuSales);
    $topMenuIds = array_keys(array_slice($menuSales, 0, 6, true));

    // Ambil menu terlaris dari DB (dengan gambar & kantin)
    if (!empty($topMenuIds)) {
        $trendingMenus = \App\Models\Menu::whereIn('id', $topMenuIds)
            ->where('is_available', true)
            ->with('canteen')
            ->get()
            ->sortBy(fn($m) => array_search($m->id, $topMenuIds))
            ->values();
        // Tambahkan sold_count ke setiap menu
        $trendingMenus = $trendingMenus->map(function($m) use ($menuSales) {
            $m->sold_count = $menuSales[$m->id] ?? 0;
            return $m;
        });
    } else {
        // Fallback: ambil menu random jika belum ada order
        $trendingMenus = \App\Models\Menu::where('is_available', true)
            ->with('canteen')
            ->inRandomOrder()
            ->take(6)
            ->get()
            ->map(function($m) { $m->sold_count = 0; return $m; });
    }

    // Canteens untuk section kantin
    $canteens = \App\Models\Canteen::withCount(['menus' => fn($q) => $q->where('is_available', true)])
        ->take(3)
        ->get();

    // Latest order untuk live ticker
    $latestOrder = \App\Models\Order::with(['user', 'canteen'])
        ->latest()
        ->first();

    return view('welcome', compact(
        'activeCanteenCount',
        'totalCanteenCount',
        'totalMenuCount',
        'trendingMenus',
        'canteens',
        'latestOrder',
        'menuSales'
    ));
})->name('welcome');

// 2. Halaman Login
Route::get('/login', function () {
    return view('auth.login'); // Pastikan file ada di resources/views/auth/login.blade.php
})->name('login');

// 3. Halaman Sign Up
Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

// 4. Halaman Utama Dashboard (Setelah Login)
Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

// 4B. Admin Dashboard (Khusus Admin)
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware('auth', 'admin')
    ->name('admin.dashboard');

// 4C. Admin Orders Monitoring
Route::get('/admin/orders', [AdminController::class, 'ordersIndex'])
    ->middleware('auth', 'admin')
    ->name('admin.orders.index');

// 4D. Admin Canteens Management
Route::get('/admin/canteens', [AdminController::class, 'canteensIndex'])
    ->middleware('auth', 'admin')
    ->name('admin.canteens.index');
Route::post('/admin/canteens', [AdminController::class, 'storeCanteen'])
    ->middleware('auth', 'admin')
    ->name('admin.canteens.store');
Route::post('/admin/canteens/{id}/update', [AdminController::class, 'updateCanteen'])
    ->middleware('auth', 'admin')
    ->name('admin.canteens.update');

// Admin: upload foto kantin
Route::post('/admin/canteens/{id}/upload-photo', [AdminController::class, 'uploadCanteenPhoto'])
    ->middleware('auth', 'admin')
    ->name('admin.canteens.upload-photo');

// Ibu Kantin: upload foto kantin sendiri
Route::post('/canteen/upload-photo', [CanteenController::class, 'uploadPhoto'])
    ->middleware('auth')
    ->name('canteen.upload-photo');

// 5. Proses Logic Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'store']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/topup', [AuthController::class, 'topup'])->middleware('auth')->name('topup');

// 6. Profile Routes
Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->middleware('auth')->name('profile.upload-photo');
Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->middleware('auth')->name('profile.upload.avatar');
Route::post('/profile/upload-avatar-ajax', [ProfileController::class, 'uploadAvatarAjax'])->middleware('auth')->name('profile.upload.avatar.ajax');
Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->middleware('auth')->name('profile.update');

// Role view switching (ibu kantin & admin bisa lihat sebagai pembeli dan kembali)
Route::post('/switch-view', [AuthController::class, 'switchView'])->middleware('auth')->name('switch.view');

// 6B. Order Routes (API endpoints)
Route::post('/api/orders', [OrderController::class, 'store'])->middleware('auth')->name('orders.store');
Route::post('/orders/{order}/reorder', [OrderController::class, 'reorder'])->middleware('auth')->name('orders.reorder');

// 6B2. Voucher Validation API (untuk pembeli cek kode promo)
Route::post('/api/voucher/check', function (\Illuminate\Http\Request $request) {
    $code = strtoupper(trim($request->input('code', '')));

    if (!$code) {
        return response()->json(['valid' => false, 'message' => 'Kode voucher tidak boleh kosong.']);
    }

    $voucher = \App\Models\Voucher::where('code', $code)
        ->where(function($q) {
            $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
        })
        ->where(function($q) {
            $q->whereNull('valid_to')->orWhere('valid_to', '>=', now());
        })
        ->first();

    if (!$voucher) {
        return response()->json(['valid' => false, 'message' => 'Kode voucher tidak ditemukan atau sudah tidak berlaku.']);
    }

    if ($voucher->max_uses !== null && $voucher->times_used >= $voucher->max_uses) {
        return response()->json(['valid' => false, 'message' => 'Voucher ini sudah mencapai batas penggunaan.']);
    }

    return response()->json([
        'valid'               => true,
        'code'                => $voucher->code,
        'description'         => $voucher->description,
        'discount_percentage' => $voucher->discount_percentage,
        'discount_amount'     => $voucher->discount_amount,
        'valid_to'            => $voucher->valid_to ? $voucher->valid_to->format('d M Y H:i') : null,
        'canteen_name'        => $voucher->canteen ? $voucher->canteen->name : null,
    ]);
})->middleware('auth')->name('api.voucher.check');

// 6B3. Chatbot Gemini API Route
Route::post('/api/chatbot/chat', [\App\Http\Controllers\ChatbotController::class, 'chat'])->middleware('auth')->name('api.chatbot.chat');

// 6C-notif. Notification API
Route::get('/api/notifications', function () {
    $user = auth()->user();
    $notifications = [];

    // 1. Pesanan aktif (pending/processing)
    $activeOrders = \App\Models\Order::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'processing'])
        ->with('canteen:id,name')
        ->latest()
        ->take(5)
        ->get();

    foreach ($activeOrders as $order) {
        $icon  = $order->status === 'pending' ? '⏳' : '🔄';
        $label = $order->status === 'pending' ? 'Menunggu Konfirmasi' : 'Sedang Diproses';
        $color = $order->status === 'pending' ? '#F97316' : '#3B82F6';
        $notifications[] = [
            'id'      => 'order_' . $order->id,
            'type'    => 'order',
            'icon'    => $icon,
            'color'   => $color,
            'title'   => $label,
            'body'    => $order->order_number . ' · ' . ($order->canteen->name ?? 'Kantin'),
            'meta'    => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
            'link'    => '/orders/active',
            'time'    => $order->created_at->diffForHumans(),
            'read'    => false,
        ];
    }

    // 2. Pesanan selesai dalam 24 jam terakhir
    $recentDone = \App\Models\Order::where('user_id', $user->id)
        ->where('status', 'completed')
        ->where('completed_at', '>=', now()->subHours(24))
        ->with('canteen:id,name')
        ->latest('completed_at')
        ->take(3)
        ->get();

    foreach ($recentDone as $order) {
        $notifications[] = [
            'id'    => 'done_' . $order->id,
            'type'  => 'done',
            'icon'  => '✅',
            'color' => '#22C55E',
            'title' => 'Pesanan Selesai!',
            'body'  => $order->order_number . ' · ' . ($order->canteen->name ?? 'Kantin'),
            'meta'  => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
            'link'  => '/history',
            'time'  => $order->completed_at->diffForHumans(),
            'read'  => true,
        ];
    }

    // 3. Saldo TyU-Pay jika rendah
    if (($user->balance ?? 0) < 10000) {
        $notifications[] = [
            'id'    => 'balance_low',
            'type'  => 'info',
            'icon'  => '💳',
            'color' => '#8B5CF6',
            'title' => 'Saldo Hampir Habis',
            'body'  => 'Saldo TyU-Pay kamu hanya Rp ' . number_format($user->balance ?? 0, 0, ',', '.'),
            'meta'  => 'Top up sekarang',
            'link'  => '/topup',
            'time'  => 'Sekarang',
            'read'  => false,
        ];
    }

    $unreadCount = collect($notifications)->where('read', false)->count();

    return response()->json([
        'notifications' => $notifications,
        'unread_count'  => $unreadCount,
    ]);
})->middleware('auth')->name('api.notifications');

// 6C. Canteen Routes (Ibu Kantin)
Route::middleware(['auth', 'checkRole:ibu_kantin'])->prefix('canteen')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CanteenController::class, 'dashboard'])->name('canteen.dashboard');
    Route::get('/create', [CanteenController::class, 'create'])->name('canteen.create');
    Route::post('/', [CanteenController::class, 'store'])->name('canteen.store');

    // Menu Management
    Route::get('/menus', [CanteenController::class, 'menuIndex'])->name('canteen.menus.index');
    Route::get('/menus/create', [CanteenController::class, 'menuCreate'])->name('canteen.menus.create');
    Route::post('/menus', [CanteenController::class, 'menuStore'])->name('canteen.menus.store');
    Route::get('/menus/{id}/edit', [CanteenController::class, 'menuEdit'])->name('canteen.menus.edit');
    Route::put('/menus/{id}', [CanteenController::class, 'menuUpdate'])->name('canteen.menus.update');
    Route::delete('/menus/{id}', [CanteenController::class, 'menuDestroy'])->name('canteen.menus.destroy');

    // Voucher Management
    Route::get('/vouchers', [CanteenController::class, 'voucherIndex'])->name('canteen.vouchers.index');
    Route::get('/vouchers/create', [CanteenController::class, 'voucherCreate'])->name('canteen.vouchers.create');
    Route::post('/vouchers', [CanteenController::class, 'voucherStore'])->name('canteen.vouchers.store');
    Route::get('/vouchers/{id}/edit', [CanteenController::class, 'voucherEdit'])->name('canteen.vouchers.edit');
    Route::put('/vouchers/{id}', [CanteenController::class, 'voucherUpdate'])->name('canteen.vouchers.update');
    Route::delete('/vouchers/{id}', [CanteenController::class, 'voucherDestroy'])->name('canteen.vouchers.destroy');

    // Sales & Payments
    Route::get('/sales', [CanteenController::class, 'sales'])->name('canteen.sales');
    Route::get('/payments', [CanteenController::class, 'payments'])->name('canteen.payments');
    
    // Order Status Management
    Route::get('/orders', [\App\Http\Controllers\OrderStatusController::class, 'index'])->name('canteen.orders.index');
    Route::post('/orders/{order}/status', [\App\Http\Controllers\OrderStatusController::class, 'updateStatus'])->name('canteen.orders.updateStatus');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderStatusController::class, 'show'])->name('canteen.orders.show');

    // Export Reports
    Route::get('/export', [CanteenController::class, 'exportIndex'])->name('canteen.export');
    Route::get('/export/orders', [CanteenController::class, 'exportOrders'])->name('canteen.export.orders');
    Route::get('/export/menus', [CanteenController::class, 'exportMenus'])->name('canteen.export.menus');
    Route::get('/export/orders-pdf', [CanteenController::class, 'printOrders'])->name('canteen.export.orders.pdf');
    Route::get('/export/menus-pdf', [CanteenController::class, 'printMenus'])->name('canteen.export.menus.pdf');
    Route::get('/api/notifications', [CanteenController::class, 'apiNotifications'])->name('canteen.api.notifications');
});

// 7. Halaman Lainnya
Route::get('/history', [OrderController::class, 'orderHistory'])->middleware('auth')->name('orders.history');
Route::get('/topup', function () { return view('topup'); })->middleware('auth');

// 7E. Active Orders Dashboard (Customer)
Route::get('/orders/active', [OrderController::class, 'activeOrders'])->middleware('auth')->name('orders.active');

// 7F. Order Details (for receipt/tracking)
Route::get('/api/orders/{order}', [OrderController::class, 'getOrderDetails'])->middleware('auth')->name('orders.details');

// 7G. Canteen Shop Listing & Details (Public)
Route::get('/canteens', [CanteenController::class, 'shop'])->middleware('auth')->name('canteens.shop');
Route::get('/canteens/{canteen}', [CanteenController::class, 'shopDetail'])->middleware('auth')->name('canteens.shop.detail');

// 7B. Cart Routes (for customers/users)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{menu}/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{menu}/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

// 7C. Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('/payment/snap-token', [PaymentController::class, 'createSnapToken'])->name('payment.snap-token');
    Route::get('/payment/{payment}/status', [PaymentController::class, 'status'])->name('payment.status');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
    Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');
});

// 7D. Payment Webhook (No auth required for webhook)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// 8. Admin Routes - System Monitoring & User Management (NO canteen management)
Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Users Management (Monitor online users, etc)
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::post('/users/{user}/change-role', [UserController::class, 'changeRole'])->name('admin.users.changeRole');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Vouchers Management
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('admin.vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('admin.vouchers.store');
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('admin.vouchers.update');
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');

    // Export Reports
    Route::get('/export', [AdminController::class, 'exportIndex'])->name('admin.export');
    Route::get('/export/orders', [AdminController::class, 'exportOrders'])->name('admin.export.orders');
    Route::get('/export/canteens', [AdminController::class, 'exportCanteens'])->name('admin.export.canteens');
    Route::get('/export/users', [AdminController::class, 'exportUsers'])->name('admin.export.users');
});

