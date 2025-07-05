<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\KategoriBarang; // Pastikan ini diimport
use Illuminate\Http\Request;

class BahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahans = Bahan::with('kategori')->get();
        return view('pages.bahan.index', compact('bahans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriBarangs = KategoriBarang::all();
        return view('pages.bahan.create', compact('kategoriBarangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:bahan,nama',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|in:Ada,Kosong',
            'status' => 'required|in:Aktif,Non Aktif',
        ], [
            'nama.required' => 'Nama bahan wajib diisi.',
            'nama.unique' => 'Nama bahan sudah ada.',
            'nama.max' => 'Nama bahan tidak boleh lebih dari 255 karakter.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'stok.required' => 'Pilihan stok wajib diisi.',
            'stok.in' => 'Pilihan stok tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.',
        ]);

        try {
            Bahan::create([
                'nama' => $request->nama,
                'kategori_id' => $request->kategori_id,
                'stok' => $request->stok,
                'status' => $request->status,
            ]);

            session()->flash('success', 'Data bahan berhasil ditambahkan.');
            return redirect()->route('bahan.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menambahkan bahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bahan = Bahan::with('kategori')->find($id);

        if (!$bahan) {
            return response()->json(['message' => 'Bahan tidak ditemukan.'], 404);
        }

        return response()->json($bahan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Temukan bahan yang akan diedit
        $bahan = Bahan::findOrFail($id);

        // Ambil semua kategori untuk dropdown
        $kategoriBarangs = KategoriBarang::all();

        // Kirim data bahan dan kategori ke view
        return view('pages.bahan.edit', compact('bahan', 'kategoriBarangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Temukan bahan yang akan diupdate
        $bahan = Bahan::findOrFail($id);

        // 1. Validasi Data
        $request->validate([
            // 'unique:bahan,nama,' . $id berarti nama harus unik kecuali untuk ID bahan yang sedang diupdate
            'nama' => 'required|string|max:255|unique:bahan,nama,' . $id,
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|in:Ada,Kosong',
            'status' => 'required|in:Aktif,Non Aktif',
        ], [
            'nama.required' => 'Nama bahan wajib diisi.',
            'nama.unique' => 'Nama bahan sudah ada.',
            'nama.max' => 'Nama bahan tidak boleh lebih dari 255 karakter.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'stok.required' => 'Pilihan stok wajib diisi.',
            'stok.in' => 'Pilihan stok tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.',
        ]);

        // 2. Simpan Perubahan ke Database
        try {
            $bahan->update([
                'nama' => $request->nama,
                'kategori_id' => $request->kategori_id,
                'stok' => $request->stok,
                'status' => $request->status,
            ]);

            // 3. Beri Notifikasi Sukses
            session()->flash('success', 'Data bahan berhasil diperbarui.');

            // 4. Redirect ke halaman daftar bahan
            return redirect()->route('bahan.index');

        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menyimpan
            session()->flash('error', 'Terjadi kesalahan saat memperbarui bahan: ' . $e->getMessage());
            return redirect()->back()->withInput(); // Kembali ke form dengan input sebelumnya
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Temukan bahan berdasarkan ID
            $bahan = Bahan::findOrFail($id);

            // Hapus bahan dari database
            $bahan->delete();

            // Beri Notifikasi Sukses
            session()->flash('success', 'Data bahan berhasil dihapus.');

            // Redirect kembali ke halaman daftar bahan
            return redirect()->route('bahan.index');

        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menghapus
            session()->flash('error', 'Terjadi kesalahan saat menghapus bahan: ' . $e->getMessage());
            return redirect()->back(); // Kembali ke halaman sebelumnya jika gagal
        }
    }
}