<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Karyawan; // Import model Karyawan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tidak digunakan di sini, tapi bisa tetap ada jika diperlukan di metode lain

class PengeluaranController extends Controller
{
    /**
     * Menampilkan daftar semua pengeluaran.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pengeluaran = Pengeluaran::with('karyawan')->latest()->get(); // Ambil semua pengeluaran, urutkan terbaru, dan eager load karyawan
        $totalPengeluaran = $pengeluaran->sum('total'); // Hitung total seluruh pengeluaran
        return view('pages.pengeluaran.index', compact('pengeluaran', 'totalPengeluaran')); // Kirim totalPengeluaran ke view
    }

    /**
     * Menampilkan form untuk membuat pengeluaran baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $karyawan = Karyawan::all(); // Mengambil semua data karyawan untuk dropdown
        return view('pages.pengeluaran.create', compact('karyawan')); // Mengirimkan data karyawan ke view
    }

    /**
     * Menyimpan pengeluaran baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jenis_pengeluaran' => 'required|string|max:255',
            // karyawan_id adalah nullable, tetapi akan wajib secara kondisional jika jenis_pengeluaran adalah 'Kasbon Karyawan'
            'karyawan_id' => 'nullable|exists:karyawan,id',
            'keterangan' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ], [
            'jenis_pengeluaran.required' => 'Jenis Pengeluaran wajib diisi.',
            'karyawan_id.exists' => 'Karyawan tidak valid.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga minimal 0.',
        ]);

        // Logika validasi kondisional: Jika jenis pengeluaran adalah 'Kasbon Karyawan'
        // maka karyawan_id harus dipilih.
        if ($validatedData['jenis_pengeluaran'] === 'Kasbon Karyawan' && empty($validatedData['karyawan_id'])) {
            return redirect()->back()->withErrors(['karyawan_id' => 'Nama Karyawan wajib dipilih untuk Kasbon Karyawan.'])->withInput();
        }

        // Hitung total pengeluaran
        $total = $validatedData['jumlah'] * $validatedData['harga'];

        // Buat entri pengeluaran baru di database
        Pengeluaran::create([
            'jenis_pengeluaran' => $validatedData['jenis_pengeluaran'],
            // Set karyawan_id hanya jika jenis pengeluaran adalah 'Kasbon Karyawan', jika tidak set null
            'karyawan_id' => $validatedData['jenis_pengeluaran'] === 'Kasbon Karyawan' ? $validatedData['karyawan_id'] : null,
            'keterangan' => $validatedData['keterangan'],
            'jumlah' => $validatedData['jumlah'],
            'harga' => $validatedData['harga'],
            'total' => $total,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $karyawan = Karyawan::all(); // Ambil semua data karyawan untuk dropdown
        return view('pages.pengeluaran.edit', compact('pengeluaran', 'karyawan'));
    }

    
    public function update(Request $request, int $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        $validatedData = $request->validate([
            'jenis_pengeluaran' => 'required|string|max:255',
            'karyawan_id' => 'nullable|exists:karyawan,id',
            'keterangan' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ], [
            'jenis_pengeluaran.required' => 'Jenis Pengeluaran wajib diisi.',
            'karyawan_id.exists' => 'Karyawan tidak valid.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga minimal 0.',
        ]);

        if ($validatedData['jenis_pengeluaran'] === 'Kasbon Karyawan' && empty($validatedData['karyawan_id'])) {
            return redirect()->back()->withErrors(['karyawan_id' => 'Nama Karyawan wajib dipilih untuk Kasbon Karyawan.'])->withInput();
        }

        $total = $validatedData['jumlah'] * $validatedData['harga'];

        $pengeluaran->update([
            'jenis_pengeluaran' => $validatedData['jenis_pengeluaran'],
            'karyawan_id' => $validatedData['jenis_pengeluaran'] === 'Kasbon Karyawan' ? $validatedData['karyawan_id'] : null,
            'keterangan' => $validatedData['keterangan'],
            'jumlah' => $validatedData['jumlah'],
            'harga' => $validatedData['harga'],
            'total' => $total,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    /**
     * Menghapus pengeluaran tertentu dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
