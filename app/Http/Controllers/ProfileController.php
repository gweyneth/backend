<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Models\User; // Pastikan model User sudah di-import

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('pages.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ], [
            'username.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
        ]);

        try {
            $user->update([
                'username' => $request->username,
                'email' => $request->email,
            ]);

            return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Maks 2MB
        ], [
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, gif, svg.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada dan bukan foto default
                if ($user->photo && $user->photo !== 'user_photos/default.png' && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                // Simpan foto baru
                $path = $request->file('photo')->store('user_photos', 'public');
                $user->update(['photo' => $path]);
            }
            // Opsional: Anda bisa menambahkan logika di sini untuk menghapus foto
            // jika pengguna tidak mengunggah file baru dan ada opsi "hapus foto".
            // Misalnya: if (!$request->hasFile('photo') && $request->input('remove_photo')) { ... }

            return redirect()->route('profile.show')->with('success', 'Foto profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui foto profil: ' . $e->getMessage())->withInput();
        }
    }

  
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Verifikasi password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            // Menggunakan ValidationException untuk menampilkan error di input yang relevan
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini salah.'],
            ]);
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->route('profile.show')->with('success', 'Password berhasil diubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah password: ' . $e->getMessage())->withInput();
        }
    }
}
