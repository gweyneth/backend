<?php

namespace App\Http\Controllers;

use App\Models\Transaksi; // Import model Transaksi
use App\Models\Pelanggan; // Import model Pelanggan
use App\Models\Pengeluaran; // Import model Pengeluaran
use Illuminate\Http\Request;
use Carbon\Carbon; // Untuk bekerja dengan tanggal dan waktu

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalOrderan = Transaksi::count();

        $orderanHariIni = Transaksi::whereDate('tanggal_order', $today)->count();

        $orderanBulanIni = Transaksi::whereBetween('tanggal_order', [$startOfMonth, $endOfMonth])->count();

        $totalKonsumen = Pelanggan::count();

        $totalOmsetHariIni = Transaksi::whereDate('updated_at', $today)
            ->sum('uang_muka');

        $totalPengeluaranHariIni = Pengeluaran::whereDate('created_at', $today)->sum('total');


        $recentTransactions = Transaksi::with(['pelanggan'])
            ->latest()
            ->take(7) 
            ->get();
        $largestReceivables = Transaksi::where('sisa', '>', 0)
            ->with('pelanggan')
            ->orderByDesc('sisa')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalOrderan',
            'orderanHariIni',
            'orderanBulanIni',
            'totalKonsumen',
            'totalOmsetHariIni',
            'totalPengeluaranHariIni',
            'recentTransactions', 
            'largestReceivables' 
        ));
    }
}
