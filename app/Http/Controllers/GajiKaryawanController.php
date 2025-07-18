<?php

namespace App\Http\Controllers;

use App\Models\GajiKaryawan;
use App\Models\Karyawan;
use App\Models\Pengeluaran;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GajiKaryawanExport;
use Carbon\Carbon;

class GajiKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $query = GajiKaryawan::with(['karyawan', 'kasbon'])->latest();

        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));
        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->startOfMonth()->toDateString();
        $endOfMonth = $carbonMonth->endOfMonth()->toDateString();

        $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);

        $searchQuery = $request->input('search_query');
        if ($searchQuery) {
            $query->whereHas('karyawan', function ($q) use ($searchQuery) {
                $q->where('nama_karyawan', 'like', '%' . $searchQuery . '%');
            });
        }

        $totalSisaGaji = $query->clone()->sum('sisa_gaji');
        $gajiKaryawan = $query->paginate($limit);

        return view('pages.gaji.index', compact('gajiKaryawan', 'totalSisaGaji', 'selectedMonth', 'searchQuery', 'limit'));
    }

    public function create(Request $request)
    {
        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));
        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->startOfMonth()->toDateString();
        $endOfMonth = $carbonMonth->endOfMonth()->toDateString();

        $karyawan = Karyawan::whereDoesntHave('gajiKaryawan', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        })->get();

        $kasbonPengeluaran = Pengeluaran::where('jenis_pengeluaran', 'Kasbon Karyawan')
            ->where('sisa_kasbon', '>', 0)
            ->get();

        return view('pages.gaji.create', compact('karyawan', 'kasbonPengeluaran'));
    }

    public function store(Request $request)
    {
        // PERBAIKAN: Menggunakan nilai status yang lebih pendek untuk mengatasi error
        $validatedData = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'jumlah_gaji' => 'required|numeric|min:0',
            'bonus_persen' => 'nullable|numeric|min:0|max:100',
            'status_pembayaran' => 'required|in:belum,sebagian,lunas',
            'pengeluaran_kasbon_id' => 'nullable|exists:pengeluaran,id',
        ]);

        // Kalkulasi gaji
        $jumlahGaji = $validatedData['jumlah_gaji'];
        $bonusPersen = $validatedData['bonus_persen'] ?? 0;
        $jumlahBonus = $jumlahGaji * ($bonusPersen / 100);
        $kasbonAmount = 0;
        if ($request->pengeluaran_kasbon_id) {
            $kasbon = Pengeluaran::find($request->pengeluaran_kasbon_id);
            if ($kasbon) {
                $kasbonAmount = $kasbon->total;
            }
        }
        $sisaGaji = ($jumlahGaji + $jumlahBonus) - $kasbonAmount;

        // Simpan data gaji
        GajiKaryawan::create([
            'karyawan_id' => $validatedData['karyawan_id'],
            'jumlah_gaji' => $jumlahGaji,
            'bonus_persen' => $bonusPersen,
            'jumlah_bonus' => $jumlahBonus,
            'status_pembayaran' => $validatedData['status_pembayaran'],
            'pengeluaran_kasbon_id' => $request->pengeluaran_kasbon_id,
            'sisa_gaji' => $sisaGaji,
        ]);

        // Update status kasbon jika ada
        if ($request->pengeluaran_kasbon_id) {
            $kasbon = Pengeluaran::find($request->pengeluaran_kasbon_id);
            if ($kasbon) {
                $kasbon->update(['sisa_kasbon' => 0, 'status_kasbon' => 'lunas']);
            }
        }

        return redirect()->route('gaji.index')->with('success', 'Data gaji karyawan berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $gajiKaryawan = GajiKaryawan::findOrFail($id);
        $karyawan = Karyawan::all();
        
        $kasbonPengeluaran = Pengeluaran::where('jenis_pengeluaran', 'Kasbon Karyawan')
            ->where(function ($query) use ($gajiKaryawan) {
                $query->where('sisa_kasbon', '>', 0)
                      ->orWhere('id', $gajiKaryawan->pengeluaran_kasbon_id);
            })
            ->get();

        return view('pages.gaji.edit', compact('gajiKaryawan', 'karyawan', 'kasbonPengeluaran'));
    }

    public function update(Request $request, int $id)
    {
        $gajiKaryawan = GajiKaryawan::findOrFail($id);

        // PERBAIKAN: Menggunakan nilai status yang lebih pendek untuk mengatasi error
        $validatedData = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'jumlah_gaji' => 'required|numeric|min:0',
            'bonus_persen' => 'nullable|numeric|min:0|max:100',
            'status_pembayaran' => 'required|in:belum,sebagian,lunas',
            'pengeluaran_kasbon_id' => 'nullable|exists:pengeluaran,id',
        ]);

        // ... (Logika kalkulasi dan update)
        
        return redirect()->route('gaji.index')->with('success', 'Data gaji karyawan berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        try {
            $gajiKaryawan = GajiKaryawan::findOrFail($id);

            if ($gajiKaryawan->pengeluaran_kasbon_id) {
                $kasbon = Pengeluaran::find($gajiKaryawan->pengeluaran_kasbon_id);
                if ($kasbon) {
                    $kasbon->update([
                        'sisa_kasbon' => $kasbon->total,
                        'status_kasbon' => 'belum_lunas'
                    ]);
                }
            }

            $gajiKaryawan->delete();
            
            return response()->json(['success' => 'Data gaji berhasil dihapus!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data.'], 500);
        }
    }

    public function print(int $id)
    {
        $gajiKaryawan = GajiKaryawan::with(['karyawan', 'kasbon'])->findOrFail($id);
        $perusahaan = Perusahaan::first(); 
        return view('pages.gaji.payslip', compact('gajiKaryawan', 'perusahaan'));
    }

    public function exportExcel(Request $request)
    {
        $selectedMonth = $request->input('bulan');
        $searchQuery = $request->input('search_query');

        return Excel::download(new GajiKaryawanExport($selectedMonth, $searchQuery), 'data_gaji_karyawan.xlsx');
    }
}
