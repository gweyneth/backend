<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OmsetPenjualanExport;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OmsetPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $produks = Produk::all();
        $selectedProdukId = $request->input('produk_id');
        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));
        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->startOfMonth()->toDateString();
        $endOfMonth = $carbonMonth->endOfMonth()->toDateString();
        $query = TransaksiDetail::with(['transaksi', 'produk'])
            ->whereHas('transaksi', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('tanggal_order', [$startOfMonth, $endOfMonth]);
            });
        if ($selectedProdukId && $selectedProdukId !== 'all') {
            $query->where('produk_id', $selectedProdukId);
        }

        $filteredTransaksiDetails = $query->get();

        $omsetProduk = $filteredTransaksiDetails->groupBy('produk_id')->map(function ($details) {
            $productName = $details->first()->nama_produk;
            $totalQty = $details->sum('qty');
            $totalOmset = $details->sum('total');

            return [
                'nama_produk' => $productName,
                'jumlah' => $totalQty,
                'total' => $totalOmset,
            ];
        })->sortByDesc('total');
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
        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));

        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->startOfMonth()->toDateString();
        $endOfMonth = $carbonMonth->endOfMonth()->toDateString();

        $query = TransaksiDetail::with(['transaksi', 'produk'])
            ->whereHas('transaksi', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('tanggal_order', [$startOfMonth, $endOfMonth]);
            });

        if ($selectedProdukId && $selectedProdukId !== 'all') {
            $query->where('produk_id', $selectedProdukId);
        }

        $filteredTransaksiDetails = $query->get();

        $omsetProduk = $filteredTransaksiDetails->groupBy('produk_id')->map(function ($details) {
            $productName = $details->first()->nama_produk;
            $totalQty = $details->sum('qty');
            $totalOmset = $details->sum('total');

            return [
                'nama_produk' => $productName,
                'jumlah' => $totalQty,
                'total' => $totalOmset,
            ];
        });

        $subtotalOmset = $omsetProduk->sum('total');

        $fileName = 'omset_penjualan_' . date('Ym', strtotime($selectedMonth)) . '.xlsx';


        return Excel::download(new OmsetPenjualanExport($omsetProduk, $subtotalOmset, $selectedMonth), $fileName);
    }
}
