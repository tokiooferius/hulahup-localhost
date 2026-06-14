<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Upload profile photo (Legacy - for backward compatibility)
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'photo.required' => 'Pilih foto terlebih dahulu!',
            'photo.image' => 'File harus berupa gambar!',
            'photo.max' => 'Ukuran foto maksimal 2MB!',
        ]);

        $user = Auth::user();
        
        // Delete old photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new photo
        $file = $request->file('photo');
        $filename = 'profile-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile-photos', $filename, 'public');

        // Update user profile
        $user->update(['profile_photo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diupload!',
            'photo_url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Upload avatar with simple form redirect
     * Defensive Programming: Validasi ketat untuk memastikan file yang masuk benar-benar gambar
     */
    public function uploadAvatar(Request $request)
    {
        try {
            // Validasi ketat agar file yang masuk benar-benar gambar
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
            ], [
                'avatar.required' => 'Pilih foto terlebih dahulu!',
                'avatar.image' => 'File harus berupa gambar!',
                'avatar.mimes' => 'Format harus jpg, png, atau gif!',
                'avatar.max' => 'Ukuran foto maksimal 2MB!',
            ]);

            $user = Auth::user();

            if (!$request->hasFile('avatar')) {
                return back()->with('error', 'File tidak ditemukan dalam request!');
            }

            $file = $request->file('avatar');

            // Defensive: Validasi file object tidak null
            if (!$file->isValid()) {
                return back()->with('error', 'File upload gagal! Silakan coba lagi.');
            }

            // Buat nama file unik: username_timestamp.extension
            $fileName = $user->username . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Defensive: Jika user sudah punya foto lama, hapus dulu agar tidak memenuhi server
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                try {
                    Storage::disk('public')->delete('avatars/' . $user->avatar);
                } catch (\Exception $e) {
                    \Log::warning("Failed to delete old avatar: " . $e->getMessage());
                    // Continue dengan upload baru meski delete lama gagal
                }
            }

            // Simpan file baru ke folder: storage/app/public/avatars
            $path = $file->storeAs('avatars', $fileName, 'public');
            
            if (!$path) {
                return back()->with('error', 'Gagal menyimpan file! Cek permission folder storage.');
            }

            // Update database user
            $user->update([
                'avatar' => $fileName
            ]);

            return back()->with('success', 'Foto profil berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error("Avatar upload error: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat upload: ' . $e->getMessage());
        }
    }
    /**
     * Upload avatar via AJAX - returns JSON (for modal without page reload)
     */
    public function uploadAvatarAjax(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            ]);

            $user = Auth::user();
            $file = $request->file('avatar');

            // Hapus avatar lama
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $fileName = $user->username . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('avatars', $fileName, 'public');
            $user->update(['avatar' => $fileName]);

            return response()->json([
                'success'    => true,
                'message'    => 'Foto profil berhasil diperbarui!',
                'avatar_url' => asset('storage/avatars/' . $fileName),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Update user profile information via AJAX
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|alpha_dash|max:255|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'nim' => 'nullable|string|max:20|unique:users,nim,' . $user->id,
                'address' => 'nullable|string|max:1000',
            ], [
                'name.required' => 'Nama lengkap wajib diisi!',
                'username.required' => 'Username wajib diisi!',
                'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah!',
                'username.unique' => 'Username sudah digunakan oleh orang lain!',
                'email.required' => 'Email wajib diisi!',
                'email.email' => 'Format email tidak valid!',
                'email.unique' => 'Email sudah digunakan oleh orang lain!',
                'nim.unique' => 'NIM sudah digunakan oleh orang lain!',
            ]);

            $user->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'nim' => $request->nim,
                'address' => $request->address,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'user' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

}
