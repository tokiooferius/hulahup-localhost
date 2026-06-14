<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Show admin dashboard - monitoring/supervision only
     */
    public function dashboard()
    {
        // Get all statistics for dashboard (MONITORING ONLY - READ-ONLY)
        $totalOrders = Order::count();
        $activeUsers = User::where('role', '!=', 'admin')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        
        // Get recent orders across all canteens
        $recentOrders = Order::with(['user', 'canteen'])
            ->latest()
            ->take(10)
            ->get();
        
        // Get canteen statistics
        $canteenStats = \App\Models\Canteen::with('ibuKantin')
            ->withCount('orders', 'menus', 'vouchers')
            ->get();

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'activeUsers' => $activeUsers,
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'completedOrders' => $completedOrders,
            'recentOrders' => $recentOrders,
            'canteenStats' => $canteenStats,
        ]);
    }

    /**
     * Show all orders across all canteens - for admin monitoring
     */
    public function ordersIndex(Request $request)
    {
        $query = Order::with(['user', 'canteen'])->latest();
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by canteen if provided
        if ($request->has('canteen_id') && $request->canteen_id !== 'all') {
            $query->where('canteen_id', $request->canteen_id);
        }
        
        $orders = $query->paginate(20);
        $canteens = \App\Models\Canteen::all();
        
        return view('admin.orders.index', [
            'orders' => $orders,
            'canteens' => $canteens,
            'currentStatus' => $request->status ?? 'all',
            'currentCanteen' => $request->canteen_id ?? 'all',
        ]);
    }

    /**
     * Show all canteens with management interface
     */
    public function canteensIndex()
    {
        $canteens = \App\Models\Canteen::with('ibuKantin')
            ->withCount(['orders', 'menus', 'vouchers'])
            ->get();
        
        return view('admin.canteens.index', [
            'canteens' => $canteens,
        ]);
    }

    /**
     * Upload foto kantin (admin bisa ubah kantin manapun)
     */
    public function uploadCanteenPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $canteen = \App\Models\Canteen::findOrFail($id);

        // Hapus foto lama kalau ada
        if ($canteen->image && file_exists(public_path($canteen->image))) {
            @unlink(public_path($canteen->image));
        }

        $file     = $request->file('photo');
        $filename = 'canteen_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
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
     * Store new Canteen and its associated Ibu Kantin user
     */
    public function storeCanteen(Request $request)
    {
        $request->validate([
            'canteen_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'canteen_name.required' => 'Nama kantin wajib diisi!',
            'location.required' => 'Lokasi/Gedung wajib diisi!',
            'owner_name.required' => 'Nama pemilik wajib diisi!',
            'username.required' => 'Username wajib diisi!',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah!',
            'username.unique' => 'Username sudah digunakan!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah digunakan!',
            'phone.required' => 'No. Telepon wajib diisi!',
            'password.required' => 'Password wajib diisi!',
            'password.min' => 'Password minimal 6 karakter!',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat Canteen
            $canteen = \App\Models\Canteen::create([
                'name' => $request->canteen_name,
                'location' => $request->location,
                'description' => 'Kantin baru terdaftar di Food-TYU',
                'status' => 'active',
            ]);

            // Handle upload photo kantin if exists
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = 'canteen_' . $canteen->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('canteens', $filename, 'public');
                $canteen->update([
                    'image' => 'storage/canteens/' . $filename,
                    'logo_url' => 'storage/canteens/' . $filename,
                ]);
            }

            // 2. Buat User (Ibu Kantin)
            $user = User::create([
                'name' => $request->owner_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'role' => 'ibu_kantin',
                'phone' => $request->phone,
                'address' => $request->location,
                'canteen_id' => $canteen->id,
            ]);

            // 3. Tautkan User ke Canteen
            $canteen->update([
                'ibu_kantin_id' => $user->id
            ]);

            DB::commit();

            return redirect()->route('admin.canteens.index')->with('success', 'Kantin dan Akun Pemilik Kantin berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Update Canteen and its associated Ibu Kantin user
     */
    public function updateCanteen(Request $request, $id)
    {
        $canteen = \App\Models\Canteen::findOrFail($id);
        $user = $canteen->ibuKantin;

        $request->validate([
            'canteen_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:255|unique:users,username,' . ($user ? $user->id : 'NULL'),
            'email' => 'required|email|max:255|unique:users,email,' . ($user ? $user->id : 'NULL'),
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'owner_avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'canteen_name.required' => 'Nama kantin wajib diisi!',
            'location.required' => 'Lokasi/Gedung wajib diisi!',
            'owner_name.required' => 'Nama pemilik wajib diisi!',
            'username.required' => 'Username wajib diisi!',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah!',
            'username.unique' => 'Username sudah digunakan!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah digunakan!',
            'phone.required' => 'No. Telepon wajib diisi!',
            'password.min' => 'Password minimal 6 karakter!',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update Canteen
            $canteenData = [
                'name' => $request->canteen_name,
                'location' => $request->location,
            ];
            
            // Handle upload photo kantin if exists
            if ($request->hasFile('photo')) {
                if ($canteen->image && file_exists(public_path($canteen->image))) {
                    @unlink(public_path($canteen->image));
                }
                
                $file = $request->file('photo');
                $filename = 'canteen_' . $canteen->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('canteens', $filename, 'public');
                $canteenData['image'] = 'storage/canteens/' . $filename;
                $canteenData['logo_url'] = 'storage/canteens/' . $filename;
            }

            $canteen->update($canteenData);

            // 2. Update/Create User (Ibu Kantin)
            if ($user) {
                $userData = [
                    'name' => $request->owner_name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->location,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
                }

                // Handle upload photo profil ibu kantin (avatar) if exists
                if ($request->hasFile('owner_avatar')) {
                    if ($user->avatar && file_exists(public_path('storage/avatars/' . $user->avatar))) {
                        @unlink(public_path('storage/avatars/' . $user->avatar));
                    }
                    
                    $file = $request->file('owner_avatar');
                    $filename = $user->username . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('avatars', $filename, 'public');
                    $userData['avatar'] = $filename;
                }

                $user->update($userData);
            } else {
                $userData = [
                    'name' => $request->owner_name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password ?? 'kantin123'),
                    'role' => 'ibu_kantin',
                    'phone' => $request->phone,
                    'address' => $request->location,
                    'canteen_id' => $canteen->id,
                ];

                if ($request->hasFile('owner_avatar')) {
                    $file = $request->file('owner_avatar');
                    $filename = $request->username . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('avatars', $filename, 'public');
                    $userData['avatar'] = $filename;
                }

                $newUser = User::create($userData);
                $canteen->update(['ibu_kantin_id' => $newUser->id]);
            }

            DB::commit();

            return redirect()->route('admin.canteens.index')->with('success', 'Data Kantin berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Show export panel page
     */
    public function exportIndex()
    {
        return view('admin.export');
    }

    /**
     * Export all orders to CSV
     */
    public function exportOrders(Request $request)
    {
        $query = Order::with(['user', 'canteen'])->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=riwayat_pesanan_keseluruhan_' . date('Ymd_His') . '.csv',
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
                'Kantin', 
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
                    $order->canteen->name ?? 'Kantin Terhapus',
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
     * Export canteens recap to CSV
     */
    public function exportCanteens(Request $request)
    {
        $canteens = \App\Models\Canteen::with('ibuKantin')
            ->withCount(['orders', 'menus', 'vouchers'])
            ->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=rekap_kantin_' . date('Ymd_His') . '.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $callback = function() use ($canteens) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Nama Kantin', 
                'Pemilik / Pengelola', 
                'Email Pemilik', 
                'Saldo Kantin', 
                'Jumlah Menu', 
                'Jumlah Voucher', 
                'Total Pesanan', 
                'Total Omset Selesai'
            ], ';');

            foreach ($canteens as $canteen) {
                $totalRevenue = $canteen->orders()
                    ->where('status', 'completed')
                    ->sum('total_amount');

                fputcsv($file, [
                    $canteen->name,
                    $canteen->ibuKantin->name ?? '-',
                    $canteen->ibuKantin->email ?? '-',
                    'Rp ' . number_format($canteen->balance ?? 0, 0, ',', '.'),
                    $canteen->menus_count,
                    $canteen->vouchers_count,
                    $canteen->orders_count,
                    'Rp ' . number_format($totalRevenue, 0, ',', '.')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all users to CSV
     */
    public function exportUsers(Request $request)
    {
        $users = User::orderBy('name')->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=rekap_pengguna_' . date('Ymd_His') . '.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Nama Pengguna', 
                'Email', 
                'Peran (Role)', 
                'Saldo TyU-Pay', 
                'Tanggal Terdaftar'
            ], ';');

            foreach ($users as $user) {
                $roleLabel = '';
                if ($user->role === 'admin') $roleLabel = 'Admin';
                elseif ($user->role === 'ibu_kantin') $roleLabel = 'Pemilik Kantin';
                else $roleLabel = 'Pembeli';

                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $roleLabel,
                    'Rp ' . number_format($user->balance ?? 0, 0, ',', '.'),
                    $user->created_at->format('Y-m-d H:i:s')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
