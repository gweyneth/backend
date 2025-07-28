<?php

namespace App\Http\Controllers; // Pastikan namespace ini benar sesuai lokasi file Anda

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Perusahaan; // Pastikan model Perusahaan di-import jika digunakan
use Illuminate\Http\JsonResponse; // Tambahkan ini untuk JsonResponse di logout
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard'; // Redirect ke dashboard setelah login

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Menerapkan middleware 'guest' ke semua metode di controller ini,
        // kecuali untuk metode 'logout'. Ini memastikan pengguna yang sudah login
        // tidak bisa mengakses halaman login lagi.
        $this->middleware('guest')->except('logout');
    }

    /**
     * Menampilkan form login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $perusahaan = null; // Inisialisasi variabel perusahaan
        try {
            // Mengambil data perusahaan pertama atau membuat instance baru jika tidak ada.
            // Ini untuk menampilkan logo/nama perusahaan di halaman login.
            // Pastikan tabel 'perusahaan' sudah ada di database Anda dan memiliki data.
            $perusahaan = Perusahaan::firstOrNew([]);
        } catch (\Exception $e) {
            // Log error jika terjadi masalah saat mengambil data perusahaan
            Log::error('Error loading Perusahaan data in LoginController: ' . $e->getMessage());
            // Anda bisa mengarahkan ke halaman error atau memberikan pesan default di view
            // Untuk saat ini, $perusahaan akan tetap null atau instance kosong
        }

        return view('auth.login', compact('perusahaan')); // Pastikan view Anda adalah 'auth.login'
    }

    /**
     * Get the login username to be used by the controller.
     * Menggunakan 'username' sebagai field untuk login, sesuai dengan kolom di database Anda.
     *
     * @return string
     */
    public function username()
    {
        return 'username'; // Dikembalikan ke 'username' karena Anda sudah mengubahnya di database
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // Validasi input login
        $this->validateLogin($request);

        // Coba otentikasi pengguna
        $credentials = $request->only($this->username(), 'password');

        // Menggunakan Auth::attempt() secara langsung
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session untuk mencegah session fixation
            $request->session()->regenerate();

            // Redirect ke tujuan yang dimaksud dengan pesan sukses
            return redirect()->intended($this->redirectTo)->with('success', 'Anda berhasil login!');
        }

        // Jika otentikasi gagal, kembalikan ke form login dengan pesan error
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], [
            // Pesan kustom untuk validasi
            $this->username() . '.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Mengarahkan kembali ke halaman login dengan input lama (kecuali password)
        // dan mem-flash pesan error ke sesi.
        // SweetAlert di view akan menangkap pesan 'error' ini.
        return redirect()->route('login')
            ->withInput($request->except('password'))
            ->withErrors([$this->username() => trans('auth.failed')]) // Mem-flash error validasi
            ->with('error', 'Username atau password salah.'); // Mem-flash pesan error umum untuk SweetAlert
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Logout pengguna dari guard 'web'
        Auth::guard('web')->logout();

        // Invalidasi sesi saat ini
        $request->session()->invalidate();

        // Regenerasi token CSRF untuk sesi berikutnya
        $request->session()->regenerateToken();

        // Redirect ke halaman login dengan pesan sukses
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}
