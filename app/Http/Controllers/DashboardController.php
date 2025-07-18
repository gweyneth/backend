<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB Facade
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data untuk Info Box ---
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        
        $totalOrderan = Transaksi::count();
        $orderanHariIni = Transaksi::whereDate('tanggal_order', $today)->count();
        $orderanBulanIni = Transaksi::whereBetween('tanggal_order', [$startOfMonth, Carbon::now()])->count();
        $totalKonsumen = Pelanggan::count();
        
        // PERBAIKAN: Mengganti 'total_harga' menjadi 'total' untuk mengatasi error SQL
        $totalOmsetHariIni = Transaksi::whereDate('tanggal_order', $today)->sum('total'); 
        
        $totalPengeluaranHariIni = Pengeluaran::whereDate('created_at', $today)->sum('total');
        
        $recentTransactions = Transaksi::with(['pelanggan'])
            ->latest()
            ->take(7) 
            ->get();
        
        // --- Logika untuk Mengambil Data Grafik ---
        $monthlyStats = Transaksi::select(
                DB::raw('YEAR(tanggal_order) as year'),
                DB::raw('MONTH(tanggal_order) as month'),
                // PERBAIKAN: Mengganti 'total_harga' menjadi 'total'
                DB::raw('SUM(total) as total_income'), 
                DB::raw('COUNT(*) as transaction_count')
            )
            ->where('tanggal_order', '>=', Carbon::now()->subMonths(5)->startOfMonth()) // Ambil data 6 bulan terakhir
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        $monthlyIncomeLabels = [];
        $monthlyIncomeValues = [];
        $monthlyTransactionCounts = [];

        // Inisialisasi data 6 bulan terakhir dengan nilai 0
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M'); // Format nama bulan (e.g., 'Jul')
            $yearMonth = $date->format('Y-n');
            
            $monthlyIncomeLabels[] = $monthName;
            $monthlyData[$yearMonth] = [
                'income' => 0,
                'transactions' => 0
            ];
        }

        // Isi data dari database
        foreach ($monthlyStats as $stat) {
            $yearMonth = $stat->year . '-' . $stat->month;
            if (isset($monthlyData[$yearMonth])) {
                $monthlyData[$yearMonth]['income'] = $stat->total_income;
                $monthlyData[$yearMonth]['transactions'] = $stat->transaction_count;
            }
        }

        // Pisahkan data ke array final
        foreach ($monthlyData as $data) {
            $monthlyIncomeValues[] = $data['income'];
            $monthlyTransactionCounts[] = $data['transactions'];
        }

        return view('dashboard', compact(
            'totalOrderan',
            'orderanHariIni',
            'orderanBulanIni',
            'totalKonsumen',
            'totalOmsetHariIni',
            'totalPengeluaranHariIni',
            'recentTransactions',
            // Variabel baru untuk grafik
            'monthlyIncomeLabels',
            'monthlyIncomeValues',
            'monthlyTransactionCounts'
        ));
    }
}
