<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Algoritma Sign Up (Simpan Data)
    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'username' => 'required|string|min:5|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'nim' => 'nullable|numeric|digits:12|unique:users,nim',
            'phone' => 'required|numeric|digits_between:10,13',
            'address' => 'required|string|min:10',
            'password' => 'required|min:8|confirmed',
        ], [
            'nim.digits' => 'NIM harus tepat 12 digit!',
            'nim.unique' => 'NIM ini sudah terdaftar!',
            'address.min' => 'Alamatnya kurang lengkap, nih!',
        ]);

        // RBAC: Penentuan role otomatis berdasarkan domain email & NIM
        $role = 'user'; // Default role
        
        // Jika email menggunakan domain kampus Telkom University
        if (str_ends_with($request->email, '@student.telkomuniversity.ac.id')) {
            $role = 'mahasiswa';
        }
        
        // Jika ada NIM, prioritaskan sebagai mahasiswa (verified student)
        if ($request->nim) {
            $role = 'mahasiswa';
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'nim' => $request->nim,
            'role' => $role,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }

    // Algoritma Login (Cek Data)
    public function login(Request $request) 
    {
        // Kita ambil username dan password dari form
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // RBAC: Redirect berdasarkan role user
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Selamat datang Admin!');
            }
            
            if (Auth::user()->role === 'ibu_kantin') {
                return redirect('/canteen/dashboard')->with('success', 'Selamat datang, Pemilik Kantin!');
            }
            
            // User biasa ke halaman home
            return redirect('/home')->with('success', 'Login berhasil! Selamat datang ' . Auth::user()->name . '!');
        }

        return back()->with('error', 'Username atau Password salah!');
    }

    // Tambahkan fungsi Logout untuk Desktop
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Top Up Saldo - DEFENSIVE PROGRAMMING: Validasi minimal dan update balance
    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5000',
        ], [
            'amount.required' => 'Nominal harus diisi!',
            'amount.numeric' => 'Nominal hanya boleh berisi angka!',
            'amount.min' => 'Minimal top up Rp 5.000!',
        ]);

        try {
            $user = Auth::user();
            $amount = (int) $request->amount;

            // Increment balance di database
            $user->increment('balance', $amount);
            
            // Refresh user untuk mendapatkan balance terbaru
            $user->refresh();

            return back()->with('success', 'Top up berhasil! Saldo kamu bertambah Rp ' . number_format($amount, 0, ',', '.') . '. Total saldo sekarang: Rp ' . number_format($user->balance, 0, ',', '.'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat top up. Silakan coba lagi.');
        }
    }
    /**
     * Switch view mode: admin/ibu_kantin bisa "lihat sebagai pembeli" dan kembali
     */
    public function switchView(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        $target = $request->input('target'); // 'buyer' atau 'original'

        if ($target === 'buyer') {
            // Simpan role asli ke session, set view_as_buyer
            session(['original_role' => $user->role, 'viewing_as_buyer' => true]);
            return redirect('/home')->with('info', 'Kamu sedang melihat sebagai pembeli.');
        }

        // Kembali ke role asli
        session()->forget('viewing_as_buyer');
        $originalRole = session('original_role', $user->role);
        session()->forget('original_role');

        if ($originalRole === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($originalRole === 'ibu_kantin') {
            return redirect('/canteen/dashboard');
        }
        return redirect('/home');
    }

}