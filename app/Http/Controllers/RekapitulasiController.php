<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class RekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        // Variabel default
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        $periodeJudul = ''; // Judul untuk ditampilkan di view
        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));

        // 1. Cek jenis filter yang dipilih pengguna
        if ($request->has('periode') && $request->input('periode') === 'all') {
            // JIKA PENGGUNA MEMILIH "TAMPILKAN SEMUA"
            $periodeJudul = 'Semua Waktu';
            $totalPemasukan = Transaksi::sum('total');
            $totalPengeluaran = Pengeluaran::sum('total');
            // Kosongkan selectedMonth agar tidak menyorot bulan tertentu
            $selectedMonth = null;
        } else {
            // JIKA PENGGUNA MEMFILTER PER BULAN (LOGIKA SEBELUMNYA)
            $carbonMonth = Carbon::parse($selectedMonth);
            $startDate = $carbonMonth->startOfMonth()->copy();
            $endDate = $carbonMonth->endOfMonth()->copy();

            // Format judul periode ke dalam Bahasa Indonesia (contoh: September 2025)
            $periodeJudul = $carbonMonth->isoFormat('MMMM YYYY');

            $totalPemasukan = Transaksi::whereBetween('tanggal_order', [$startDate, $endDate])->sum('total');
            $totalPengeluaran = Pengeluaran::whereBetween('created_at', [$startDate, $endDate])->sum('total');
        }

        // 2. Hitung Total Piutang (ini selalu total keseluruhan, tidak terpengaruh filter)
        $totalPiutang = Transaksi::where('sisa', '>', 0)->sum('sisa');

        // 3. Lakukan Kalkulasi Rekapitulasi
        $labaKotor = $totalPemasukan - $totalPengeluaran;
        $saldoBersih = $labaKotor - $totalPiutang;

        // 4. Kirim semua data ke View
        return view('pages.laporan.rekapitulasi.index', compact(
            'selectedMonth',
            'periodeJudul', // Kirim judul ke view
            'totalPemasukan',
            'totalPengeluaran',
            'labaKotor',
            'totalPiutang',
            'saldoBersih'
        ));
    }
}