<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Import Session
// Jika Anda ingin menggunakan Auth facade di masa depan, tambahkan:
// use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses permintaan login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Cek username dan password hardcode
        if ($username === 'kasir' && $password === 'kasir123') {
            // Jika berhasil, set sesi login
            Session::put('logged_in', true);
            Session::put('username', $username); // Simpan username di sesi

            // Redirect ke dashboard dengan pesan sukses untuk SweetAlert Toast
            return redirect('/dashboard')->with('status', 'Login berhasil! Selamat datang.');
        } else {
            // Jika gagal, kembali ke form login dengan pesan error untuk SweetAlert Toast
            return redirect()->back()->withInput()->with('error', 'Username atau password salah. Silakan coba lagi.');
        }
    }

    // Logout
    public function logout()
    {
        Session::forget('logged_in'); // Hapus sesi login
        Session::forget('username');   // Hapus username dari sesi
        
        // Redirect kembali ke halaman login dengan pesan sukses logout (opsional)
        return redirect('/login')->with('status', 'Anda telah berhasil logout.');
    }
}