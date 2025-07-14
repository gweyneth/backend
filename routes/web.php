<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\OmsetPenjualanController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('kategoribarang', KategoriBarangController::class);
        Route::resource('bahan', BahanController::class);
        Route::resource('satuan', SatuanController::class);
        Route::resource('produk', ProdukController::class);
        Route::resource('karyawan', KaryawanController::class);
        Route::get('karyawan/export/excel', [KaryawanController::class, 'exportExcel'])->name('karyawan.export_excel');
        Route::resource('pengeluaran', PengeluaranController::class);
        Route::resource('gaji', GajiKaryawanController::class);
        Route::get('gaji-karyawan/export-excel', [GajiKaryawanController::class, 'exportExcel'])->name('gaji.export-excel');
        Route::singleton('perusahaan', PerusahaanController::class);
        Route::resource('rekening', RekeningController::class);
        Route::get('gaji/{id}/print', [GajiKaryawanController::class, 'print'])->name('gaji.print');
        Route::get('piutang', [TransaksiController::class, 'piutangIndex'])->name('piutang.index');
        Route::get('pendapatan', [TransaksiController::class, 'pendapatanIndex'])->name('pendapatan.index');
        Route::get('pendapatan/print-pdf', [TransaksiController::class, 'printPendapatanPdf'])->name('pendapatan.print-pdf');
        // Route::get('kasbon-karyawan', [KasbonKaryawanController::class, 'index'])->name('kasbon-karyawan.index');
        Route::get('omset-penjualan', [OmsetPenjualanController::class, 'index'])->name('omset.index');
        Route::get('omset-penjualan/export-excel', [OmsetPenjualanController::class, 'exportExcel'])->name('omset.export-excel');
    });

    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::resource('pelanggan', PelangganController::class);
        Route::get('transaksi/get-product-details', [TransaksiController::class, 'getProductDetails'])->name('transaksi.get-product-details');
        Route::get('transaksi/get-produk-item-row', [TransaksiController::class, 'getProdukItemRow'])->name('transaksi.get-produk-item-row');
        Route::put('transaksi/{id}/pelunasan', [TransaksiController::class, 'pelunasan'])->name('transaksi.pelunasan');
        Route::get('transaksi/{id}/print-receipt', [TransaksiController::class, 'printReceipt'])->name('transaksi.print-receipt');
        Route::get('transaksi/{id}/print-invoice', [TransaksiController::class, 'printInvoice'])->name('transaksi.print-invoice');
        Route::get('transaksi/export-excel', [TransaksiController::class, 'exportExcel'])->name('transaksi.export-excel');
        Route::resource('transaksi', TransaksiController::class);

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::put('/update-profile', [ProfileController::class, 'updateProfile'])->name('update-profile');
            Route::post('/update-photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
            Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        });
    });
});
