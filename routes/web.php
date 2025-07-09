<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController; // Menggunakan DashboardController untuk dashboard
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\GajiKaryawanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KasbonKaryawanController;
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PelangganController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Grup rute yang memerlukan autentikasi (Auth Middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Grup rute untuk Admin (hanya bisa diakses oleh role 'admin')
    Route::middleware(['role:admin'])->group(function () {
        // Modul Manajemen Data (Admin Only)
        Route::resource('kategoribarang', KategoriBarangController::class);
        Route::resource('bahan', BahanController::class);
        Route::resource('satuan', SatuanController::class);
        Route::resource('produk', ProdukController::class);
        Route::resource('karyawan', KaryawanController::class);
        Route::resource('pengeluaran', PengeluaranController::class);
        Route::resource('gaji', GajiKaryawanController::class);
        Route::singleton('perusahaan', PerusahaanController::class);
        Route::resource('rekening', RekeningController::class);

        // Rute Khusus Gaji Karyawan (Admin Only)
        Route::get('gaji/{id}/print', [GajiKaryawanController::class, 'print'])->name('gaji.print'); // Menggunakan 'print' sesuai rute Anda

        // Modul Laporan Keuangan (Admin Only)
        Route::get('piutang', [TransaksiController::class, 'piutangIndex'])->name('piutang.index');
        Route::get('pendapatan', [TransaksiController::class, 'pendapatanIndex'])->name('pendapatan.index');
        Route::get('pendapatan/print-pdf', [TransaksiController::class, 'printPendapatanPdf'])->name('pendapatan.print-pdf');
        // Rute Kasbon Karyawan (jika hanya index yang perlu diakses admin)
        // Route::get('kasbon-karyawan', [KasbonKaryawanController::class, 'index'])->name('kasbon-karyawan.index');
    });

    // Grup rute untuk Admin dan Kasir (bisa diakses oleh role 'admin' dan 'kasir')
    Route::middleware(['role:admin,kasir'])->group(function () {
        // Modul Pelanggan dan Transaksi (Admin & Kasir)
        Route::resource('pelanggan', PelangganController::class);
        Route::resource('transaksi', TransaksiController::class);

        // Rute Khusus Transaksi (Admin & Kasir)
        Route::get('transaksi/get-product-details', [TransaksiController::class, 'getProductDetails'])->name('transaksi.get-product-details');
        Route::get('transaksi/get-produk-item-row', [TransaksiController::class, 'getProdukItemRow'])->name('transaksi.get-produk-item-row');
        Route::put('transaksi/{id}/pelunasan', [TransaksiController::class, 'pelunasan'])->name('transaksi.pelunasan');
        Route::get('transaksi/{id}/print-receipt', [TransaksiController::class, 'printReceipt'])->name('transaksi.print-receipt');
        Route::get('transaksi/{id}/print-invoice', [TransaksiController::class, 'printInvoice'])->name('transaksi.print-invoice');
    });
});
