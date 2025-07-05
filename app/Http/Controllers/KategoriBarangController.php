<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use RealRashid\SweetAlert\Facades\Alert; // Hapus baris ini atau jadikan komentar

class KategoriBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriBarangs = KategoriBarang::all();
        return view('pages.kategori.index', compact('kategoriBarangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            // Pastikan nama tabel di unique sesuai dengan migrasi Anda
            'nama' => 'required|string|max:255|unique:kategori,nama',
            // UBAH ATURAN VALIDASI UNTUK STATUS
            'status' => 'required|in:Aktif,Non Aktif',
        ], [
            'nama.required' => 'Nama kategori barang wajib diisi.',
            'nama.unique' => 'Nama kategori barang sudah ada.',
            'nama.max' => 'Nama kategori barang tidak boleh lebih dari 255 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.', // Pesan error baru untuk aturan 'in'
        ]);

        // 2. Simpan Data ke Database
        try {
            KategoriBarang::create([
                'nama' => $request->nama,
                'status' => $request->status, // Ini akan menyimpan string 'Aktif' atau 'Non Aktif'
            ]);

            session()->flash('success', 'Data kategori barang berhasil ditambahkan.');

            return redirect()->route('kategoribarang.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menambahkan kategori barang: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Temukan kategori barang berdasarkan ID
        // Jika tidak ditemukan, akan otomatis melempar 404
        $kategoriBarang = KategoriBarang::findOrFail($id);

        // Kirim data kategori barang ke view edit
        return view('pages.kategori.edit', compact('kategoriBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Temukan kategori barang yang akan diupdate
        $kategoriBarang = KategoriBarang::findOrFail($id);

        // 1. Validasi Data
        $request->validate([
            // 'unique:kategori,nama,' . $id berarti nama harus unik kecuali untuk ID kategori yang sedang diupdate
            'nama' => 'required|string|max:255|unique:kategori,nama,' . $id,
            'status' => 'required|in:Aktif,Non Aktif',
        ], [
            'nama.required' => 'Nama kategori barang wajib diisi.',
            'nama.unique' => 'Nama kategori barang sudah ada.',
            'nama.max' => 'Nama kategori barang tidak boleh lebih dari 255 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Pilihan status tidak valid.',
        ]);

        // 2. Simpan Perubahan ke Database
        try {
            $kategoriBarang->update([
                'nama' => $request->nama,
                'status' => $request->status,
                // Tambahkan kolom lain jika ada
            ]);

            // 3. Beri Notifikasi Sukses
            session()->flash('success', 'Data kategori barang berhasil diperbarui.');

            // 4. Redirect ke halaman daftar kategori
            return redirect()->route('kategoribarang.index');

        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menyimpan
            session()->flash('error', 'Terjadi kesalahan saat memperbarui kategori barang: ' . $e->getMessage());
            return redirect()->back()->withInput(); // Kembali ke form dengan input sebelumnya
        }
    }

    public function destroy(string $id)
    {
        try {
            // Temukan kategori barang berdasarkan ID
            $kategoriBarang = KategoriBarang::findOrFail($id);

            // Hapus kategori barang dari database
            $kategoriBarang->delete();

            // Beri Notifikasi Sukses
            session()->flash('success', 'Data kategori barang berhasil dihapus.');

            // Redirect kembali ke halaman daftar kategori
            return redirect()->route('kategoribarang.index');

        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menghapus
            session()->flash('error', 'Terjadi kesalahan saat menghapus kategori barang: ' . $e->getMessage());
            return redirect()->back(); // Kembali ke halaman sebelumnya jika gagal
        }
    }
}