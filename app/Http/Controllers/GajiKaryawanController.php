<?php

namespace App\Http\Controllers;

use App\Models\GajiKaryawan;
use App\Models\Karyawan; // Untuk mengambil data karyawan
use App\Models\Pengeluaran; // Untuk mengambil data kasbon
use App\Models\Perusahaan; // Untuk mengambil data kasbon
use Illuminate\Http\Request;

class GajiKaryawanController extends Controller
{
    
    public function index(Request $request)
    {
        $gajiKaryawan = GajiKaryawan::with(['karyawan', 'kasbon'])->latest()->get();

        $totalSisaGaji = $gajiKaryawan->sum('sisa_gaji');
        return view('pages.gaji.index', compact('gajiKaryawan', 'totalSisaGaji'));
    }

  
    public function create()
    {
        $karyawan = Karyawan::all();
      
        $kasbonPengeluaran = Pengeluaran::where('jenis_pengeluaran', 'Kasbon Karyawan')->get();
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
                $kasbonAmount = $kasbon->total; // Mengambil nilai total dari kasbon
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

        return redirect()->route('gaji.index')->with('success', 'Data gaji karyawan berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit entri gaji karyawan tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $gajiKaryawan = GajiKaryawan::findOrFail($id);
        $karyawan = Karyawan::all();
        $kasbonPengeluaran = Pengeluaran::where('jenis_pengeluaran', 'Kasbon Karyawan')->get();
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

        $gajiKaryawan->update([
            'karyawan_id' => $validatedData['karyawan_id'],
            'jumlah_gaji' => $jumlahGaji,
            'bonus_persen' => $bonusPersen,
            'jumlah_bonus' => $jumlahBonus,
            'status_pembayaran' => $validatedData['status_pembayaran'],
            'pengeluaran_kasbon_id' => $validatedData['pengeluaran_kasbon_id'],
            'sisa_gaji' => $sisaGaji,
        ]);

        return redirect()->route('gaji.index')->with('success', 'Data gaji karyawan berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $gajiKaryawan = GajiKaryawan::findOrFail($id);
        $gajiKaryawan->delete();

        return redirect()->route('gaji.index')->with('success', 'Data gaji karyawan berhasil dihapus!');
    }

    public function print(int $id)
    {
        $gajiKaryawan = GajiKaryawan::with(['karyawan', 'kasbon'])->findOrFail($id);
        $perusahaan = Perusahaan::first(); // Ambil data perusahaan
        return view('pages.gaji.payslip', compact('gajiKaryawan', 'perusahaan'));
    }
}
