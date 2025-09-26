<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\TransaksiDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OmsetPenjualanExport;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OmsetPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $produks = Produk::orderBy('nama', 'asc')->get();
        $selectedProdukId = $request->input('produk_id');
        // PERBAIKAN: Hapus nilai default, agar bisa kosong (untuk menampilkan semua)
        $selectedMonth = $request->input('bulan');

        $query = TransaksiDetail::with(['transaksi', 'produk']);

        // PERBAIKAN: Filter bulan hanya dijalankan jika $selectedMonth memiliki nilai
        if ($selectedMonth) {
            $carbonMonth = Carbon::parse($selectedMonth);
            $startOfMonth = $carbonMonth->startOfMonth()->toDateString();
            $endOfMonth = $carbonMonth->endOfMonth()->toDateString();

            $query->whereHas('transaksi', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('tanggal_order', [$startOfMonth, $endOfMonth]);
            });
        }

        if ($selectedProdukId && $selectedProdukId !== 'all') {
            $query->where('produk_id', $selectedProdukId);
        }

        $filteredTransaksiDetails = $query->get();

        $omsetProduk = $filteredTransaksiDetails->groupBy('produk_id')->map(function ($details) {
            $productName = optional($details->first()->produk)->nama ?? $details->first()->nama_produk;
            $totalQty = $details->sum('qty');
            $totalOmset = $details->sum('total');

            return [
                'nama_produk' => $productName,
                'jumlah' => $totalQty,
                'total' => $totalOmset,
            ];
        })->sortKeys();

        $subtotalOmset = $omsetProduk->sum('total');

        return view('pages.omset.index', compact(
            'produks',
            'omsetProduk',
            'subtotalOmset',
            'selectedProdukId',
            'selectedMonth'
        ));
    }

    public function exportExcel(Request $request)
    {
        $selectedProdukId = $request->input('produk_id');
        $selectedMonth = $request->input('bulan'); // Hapus default

        $query = TransaksiDetail::with(['transaksi', 'produk']);

        // Logika yang sama seperti di method index
        if ($selectedMonth) {
            $carbonMonth = Carbon::parse($selectedMonth);
            $startOfMonth = $carbonMonth->startOfMonth()->toDateString();
            $endOfMonth = $carbonMonth->endOfMonth()->toDateString();

            $query->whereHas('transaksi', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('tanggal_order', [$startOfMonth, $endOfMonth]);
            });
        }

        if ($selectedProdukId && $selectedProdukId !== 'all') {
            $query->where('produk_id', $selectedProdukId);
        }

        $filteredTransaksiDetails = $query->get();

        $omsetProduk = $filteredTransaksiDetails->groupBy('produk_id')->map(function ($details) {
            $productName = optional($details->first()->produk)->nama ?? $details->first()->nama_produk;
            $totalQty = $details->sum('qty');
            $totalOmset = $details->sum('total');

            return [
                'nama_produk' => $productName,
                'jumlah' => $totalQty,
                'total' => $totalOmset,
            ];
        })->sortKeys();

        $subtotalOmset = $omsetProduk->sum('total');

        $fileName = 'omset_penjualan_' . ($selectedMonth ? date('Ym', strtotime($selectedMonth)) : 'semua_waktu') . '.xlsx';

        return Excel::download(new OmsetPenjualanExport($omsetProduk, $subtotalOmset, $selectedMonth), $fileName);
    }
}