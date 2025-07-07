<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelangganController; 
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\GajiKaryawanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\RekeningController;
use Illuminate\Support\Facades\Session; 

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', function () {
    if (!Session::get('logged_in')) {
        return redirect('/login'); // Jika belum, arahkan ke halaman login
    }
    return view('dashboard');
})->name('dashboard');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', function () {
    return redirect('/login');
});
Route::resource('pelanggan',PelangganController::class);
Route::resource('kategoribarang',KategoriBarangController::class);
Route::resource('bahan',BahanController::class);
Route::resource('satuan',SatuanController::class);
Route::resource('produk',ProdukController::class);
Route::resource('pengeluaran',PengeluaranController::class);
Route::resource('karyawan',KaryawanController::class);
Route::resource('gaji',GajiKaryawanController::class);
Route::singleton('perusahaan', PerusahaanController::class);
Route::resource('rekening', RekeningController::class);
Route::get('gaji/{id}/print', [GajiKaryawanController::class, 'print'])->name('gaji.print');
Route::get('transaksi/get-product-details', [TransaksiController::class, 'getProductDetails'])->name('transaksi.get-product-details');
Route::get('transaksi/get-produk-item-row', [TransaksiController::class, 'getProdukItemRow'])->name('transaksi.get-produk-item-row');
Route::resource('transaksi', TransaksiController::class);
Route::get('piutang', [TransaksiController::class, 'piutangIndex'])->name('piutang.index');