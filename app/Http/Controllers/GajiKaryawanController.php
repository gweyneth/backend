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

        $gajiKaryawan = $query->get();
        $totalSisaGaji = $gajiKaryawan->sum('sisa_gaji');
        return view('pages.gaji.index', compact('gajiKaryawan', 'totalSisaGaji', 'selectedMonth', 'searchQuery'));
    }

    public function create(Request $request)
    {
        $selectedMonth = $request->input('bulan', Carbon::now()->format('Y-m'));
        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->startOfMonth()->toDateString();
        $endOfMonth = $carbonMonth->endOfMonth()->toDateString();
        $karyawan = Karyawan::whereDoesntHave('gajiKaryawan', function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                  ->where('status_pembayaran', 'lunas');
        })->get();
        $kasbonPengeluaran = Pengeluaran::where('jenis_pengeluaran', 'Kasbon Karyawan')
            ->where('sisa_kasbon', '>', 0) 
            ->get();
        return view('pages.gaji.create', compact('karyawan', 'kasbonPengeluaran'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'jumlah_gaji' => 'required|numeric|min:0',
            'bonus_persen' => 'nullable|numeric|min:0|max:100',
            'status_pembayaran' => 'required|in:belum dibayar,bayar sebagian,bayar setengah,lunas',
            'pengeluaran_kasbon_id' => 'nullable|exists:pengeluaran,id',
        ], [
            'karyawan_id.required' => 'Nama Pegawai wajib diisi.',
            'karyawan_id.exists' => 'Nama Pegawai tidak valid.',
            'jumlah_gaji.required' => 'Jumlah Gaji wajib diisi.',
            'jumlah_gaji.numeric' => 'Jumlah Gaji harus berupa angka.',
            'bonus_persen.numeric' => 'Bonus harus berupa angka.',
            'bonus_persen.min' => 'Bonus minimal 0.',
            'bonus_persen.max' => 'Bonus maksimal 100.',
            'status_pembayaran.required' => 'Status Pembayaran wajib dipilih.',
            'pengeluaran_kasbon_id.exists' => 'Pilihan Kasbon tidak valid.',
        ]);

        $jumlahGaji = $validatedData['jumlah_gaji'];
        $bonusPersen = $validatedData['bonus_persen'] ?? 0;
        $jumlahBonus = $jumlahGaji * ($bonusPersen / 100);

        $kasbonAmount = 0;
        if ($validatedData['pengeluaran_kasbon_id']) {
            $kasbon = Pengeluaran::find($validatedData['pengeluaran_kasbon_id']);
            if ($kasbon) {
                $kasbonAmount = $kasbon->total; 
            }
        }

        $sisaGaji = ($jumlahGaji + $jumlahBonus) - $kasbonAmount;

        GajiKaryawan::create([
            'karyawan_id' => $validatedData['karyawan_id'],
            'jumlah_gaji' => $jumlahGaji,
            'bonus_persen' => $bonusPersen,
            'jumlah_bonus' => $jumlahBonus,
            'status_pembayaran' => $validatedData['status_pembayaran'],
            'pengeluaran_kasbon_id' => $validatedData['pengeluaran_kasbon_id'],
            'sisa_gaji' => $sisaGaji,
        ]);

        if ($validatedData['pengeluaran_kasbon_id']) {
            $kasbon = Pengeluaran::find($validatedData['pengeluaran_kasbon_id']);
            if ($kasbon) {
                $kasbon->update([
                    'sisa_kasbon' => 0,
                    'status_kasbon' => 'lunas'
                ]);
            }
        }

        return redirect()->route('gaji.index')->with('success', 'Data gaji karyawan berhasil ditambahkan!');
    }

    public function edit(int $id, Request $request)
    {
        $gajiKaryawan = GajiKaryawan::findOrFail($id);

        $selectedMonth = $gajiKaryawan->created_at->format('Y-m');
        $carbonMonth = Carbon::parse($selectedMonth);
        $startOfMonth = $carbonMonth->startOfMonth()->toDateString();
        $endOfMonth = $carbonMonth->endOfMonth()->toDateString();
        $karyawan = Karyawan::whereDoesntHave('gajiKaryawan', function ($query) use ($startOfMonth, $endOfMonth, $gajiKaryawan) {
            $query->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                  ->where('status_pembayaran', 'lunas')
                  ->where('id', '!=', $gajiKaryawan->id); 
        })->orWhere('id', $gajiKaryawan->karyawan_id)->get();
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

        $validatedData = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'jumlah_gaji' => 'required|numeric|min:0',
            'bonus_persen' => 'nullable|numeric|min:0|max:100',
            'status_pembayaran' => 'required|in:belum dibayar,bayar sebagian,bayar setengah,lunas',
            'pengeluaran_kasbon_id' => 'nullable|exists:pengeluaran,id',
        ], [
            'karyawan_id.required' => 'Nama Pegawai wajib diisi.',
            'karyawan_id.exists' => 'Nama Pegawai tidak valid.',
            'jumlah_gaji.required' => 'Jumlah Gaji wajib diisi.',
            'jumlah_gaji.numeric' => 'Jumlah Gaji harus berupa angka.',
            'bonus_persen.numeric' => 'Bonus harus berupa angka.',
            'bonus_persen.min' => 'Bonus minimal 0.',
            'bonus_persen.max' => 'Bonus maksimal 100.',
            'status_pembayaran.required' => 'Status Pembayaran wajib dipilih.',
            'pengeluaran_kasbon_id.exists' => 'Pilihan Kasbon tidak valid.',
        ]);

        $jumlahGaji = $validatedData['jumlah_gaji'];
        $bonusPersen = $validatedData['bonus_persen'] ?? 0;
        $jumlahBonus = $jumlahGaji * ($bonusPersen / 100);

        $kasbonAmount = 0;
        if ($validatedData['pengeluaran_kasbon_id']) {
            $kasbon = Pengeluaran::find($validatedData['pengeluaran_kasbon_id']);
            if ($kasbon) {
                $kasbonAmount = $kasbon->total;
            }
        }

        $sisaGaji = ($jumlahGaji + $jumlahBonus) - $kasbonAmount;

        // Simpan ID kasbon lama sebelum update
        $oldKasbonId = $gajiKaryawan->pengeluaran_kasbon_id;

        $gajiKaryawan->update([
            'karyawan_id' => $validatedData['karyawan_id'],
            'jumlah_gaji' => $jumlahGaji,
            'bonus_persen' => $bonusPersen,
            'jumlah_bonus' => $jumlahBonus,
            'status_pembayaran' => $validatedData['status_pembayaran'],
            'pengeluaran_kasbon_id' => $validatedData['pengeluaran_kasbon_id'],
            'sisa_gaji' => $sisaGaji,
        ]);

        if ($validatedData['pengeluaran_kasbon_id']) {
            $currentKasbon = Pengeluaran::find($validatedData['pengeluaran_kasbon_id']);
            if ($currentKasbon) {
                $currentKasbon->update([
                    'sisa_kasbon' => 0,
                    'status_kasbon' => 'lunas'
                ]);
            }
        }
        if ($oldKasbonId && $oldKasbonId != $validatedData['pengeluaran_kasbon_id']) {
            $previousKasbon = Pengeluaran::find($oldKasbonId);
            if ($previousKasbon) {
                $previousKasbon->update([
                    'sisa_kasbon' => $previousKasbon->total,
                    'status_kasbon' => 'belum_lunas'
                ]);
            }
        }

        return redirect()->route('gaji.index')->with('success', 'Data gaji karyawan berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
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

        return redirect()->route('gaji.index')->with('success', 'Data gaji karyawan berhasil dihapus!');
    }

    public function printPayslip(int $id)
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
