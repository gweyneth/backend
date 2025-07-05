<?php

namespace App\Http\Controllers;

use App\Models\Satuan; 
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $satuans = Satuan::all(); // Mengambil semua data satuan
        return view('pages.satuan.index', compact('satuans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.satuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'nama' => 'required|string|max:255|unique:satuan,nama', // Pastikan nama unik di tabel 'satuan'
        ], [
            'nama.required' => 'Nama satuan wajib diisi.',
            'nama.unique' => 'Nama satuan sudah ada.',
            'nama.max' => 'Nama satuan tidak boleh lebih dari 255 karakter.',
        ]);

        try {
            // Simpan data baru
            Satuan::create([
                'nama' => $request->nama,
            ]);

            session()->flash('success', 'Data satuan berhasil ditambahkan.');
            return redirect()->route('satuan.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menambahkan satuan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Temukan satuan berdasarkan ID
        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json(['message' => 'Satuan tidak ditemukan.'], 404);
        }

        // Kembalikan data satuan dalam format JSON untuk modal
        return response()->json($satuan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Temukan satuan yang akan diedit
        $satuan = Satuan::findOrFail($id);
        return view('pages.satuan.edit', compact('satuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Temukan satuan yang akan diupdate
        $satuan = Satuan::findOrFail($id);

        // Validasi data input, abaikan nama yang sama untuk record ini sendiri
        $request->validate([
            'nama' => 'required|string|max:255|unique:satuan,nama,' . $id,
        ], [
            'nama.required' => 'Nama satuan wajib diisi.',
            'nama.unique' => 'Nama satuan sudah ada.',
            'nama.max' => 'Nama satuan tidak boleh lebih dari 255 karakter.',
        ]);

        try {
            // Perbarui data
            $satuan->update([
                'nama' => $request->nama,
            ]);

            session()->flash('success', 'Data satuan berhasil diperbarui.');
            return redirect()->route('satuan.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat memperbarui satuan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Temukan dan hapus satuan
            $satuan = Satuan::findOrFail($id);
            $satuan->delete();

            session()->flash('success', 'Data satuan berhasil dihapus.');
            return redirect()->route('satuan.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus satuan: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}