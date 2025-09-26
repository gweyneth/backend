<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class RekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        // Variabel default
        $periodeJudul = '';
        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));

        // Query Builder untuk Pemasukan dan Pengeluaran
        $pemasukanQuery = TransaksiDetail::with('produk', 'transaksi');
        $pengeluaranQuery = Pengeluaran::query();

        // Cek jenis filter
        if ($request->has('periode') && $request->input('periode') === 'all') {
            $periodeJudul = 'Semua Waktu';
            $selectedMonth = null;
            // Tidak ada filter tanggal yang diterapkan
        } else {
            $carbonMonth = Carbon::parse($selectedMonth);
            $startDate = $carbonMonth->startOfMonth()->copy();
            $endDate = $carbonMonth->endOfMonth()->copy();
            $periodeJudul = $carbonMonth->isoFormat('MMMM YYYY');

            // Terapkan filter tanggal ke query
            $pemasukanQuery->whereHas('transaksi', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_order', [$startDate, $endDate]);
            });
            $pengeluaranQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // --- AMBIL DATA DETAIL & HITUNG TOTAL ---

        // 1. Pemasukan
        $detailPemasukan = $pemasukanQuery->get();
        $totalPemasukan = $detailPemasukan->sum('total');

        // 2. Pengeluaran
        $detailPengeluaran = $pengeluaranQuery->get();
        $totalPengeluaran = $detailPengeluaran->sum('total');

        // 3. Piutang (selalu total keseluruhan)
        $totalPiutang = Transaksi::where('sisa', '>', 0)->sum('sisa');

        // --- KALKULASI REKAPITULASI ---
        $labaKotor = $totalPemasukan - $totalPengeluaran;
        $saldoBersih = $labaKotor - $totalPiutang;
        $totalAset = $labaKotor; // Seperti penjelasan di atas

        return view('pages.laporan.rekapitulasi.index', compact(
            'selectedMonth',
            'periodeJudul',
            'totalPemasukan',
            'totalPengeluaran',
            'labaKotor',
            'totalPiutang',
            'saldoBersih',
            'totalAset',
            'detailPemasukan',  
            'detailPengeluaran'  
        ));
    }
}