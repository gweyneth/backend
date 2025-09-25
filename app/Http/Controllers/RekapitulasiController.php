<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class RekapitulasiController extends Controller
{
    /**
     * Menampilkan halaman laporan rekapitulasi keuangan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 1. Tentukan Periode Filter (Bulanan)
        // Jika tidak ada filter, gunakan bulan saat ini
        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));
        $carbonMonth = Carbon::parse($selectedMonth);

        // Tentukan tanggal awal dan akhir dari bulan yang dipilih
        $startDate = $carbonMonth->startOfMonth()->copy();
        $endDate = $carbonMonth->endOfMonth()->copy();

        // 2. Hitung Total Pemasukan
        // Pemasukan dihitung dari total transaksi yang terjadi dalam periode yang dipilih.
        $totalPemasukan = Transaksi::whereBetween('tanggal_order', [$startDate, $endDate])
                                    ->sum('total');

        // 3. Hitung Total Pengeluaran
        // Pengeluaran dihitung dari data pengeluaran pada periode yang dipilih.
        $totalPengeluaran = Pengeluaran::whereBetween('created_at', [$startDate, $endDate])
                                        ->sum('total');

        // 4. Hitung Total Piutang
        // Piutang adalah total sisa tagihan dari SEMUA transaksi yang belum lunas,
        // karena ini mempengaruhi kas yang sebenarnya.
        $totalPiutang = Transaksi::where('sisa', '>', 0)->sum('sisa');

        // 5. Lakukan Kalkulasi Rekapitulasi
        $labaKotor = $totalPemasukan - $totalPengeluaran;
        $saldoBersih = $labaKotor - $totalPiutang;

        // 6. Kirim semua data ke View
        return view('pages.laporan.rekapitulasi.index', compact(
            'selectedMonth',
            'totalPemasukan',
            'totalPengeluaran',
            'labaKotor',
            'totalPiutang',
            'saldoBersih'
        ));
    }
}