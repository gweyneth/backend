<?php

namespace App\Http\Controllers;

use App\Models\Rekening; // Import model Rekening
use Illuminate\Http\Request;

class RekeningController extends Controller
{
   
    public function index()
    {
        $rekening = Rekening::latest()->get(); // Ambil semua data rekening, urutkan terbaru
        return view('pages.rekening.index', compact('rekening'));
    }

   
    public function create()
    {
        return view('pages.rekening.create');
    }

   
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $request->validate([
            'nomor_rekening' => 'required|string|max:255|unique:rekening,nomor_rekening', // Nomor rekening harus unik
            'atas_nama' => 'required|string|max:255',
            'bank' => 'required|string|max:255',
            'kode_bank' => 'nullable|string|max:10',
        ], [
            'nomor_rekening.required' => 'Nomor Rekening wajib diisi.',
            'nomor_rekening.unique' => 'Nomor Rekening ini sudah terdaftar.',
            'atas_nama.required' => 'Atas Nama wajib diisi.',
            'bank.required' => 'Nama Bank wajib diisi.',
        ]);

        // Buat entri rekening baru di database
        Rekening::create([
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'bank' => $request->bank,
            'kode_bank' => $request->kode_bank,
        ]);

        return redirect()->route('rekening.index')->with('success', 'Data rekening berhasil ditambahkan!');
    }

    
    public function edit(int $id)
    {
        $rekening = Rekening::findOrFail($id);
        return view('pages.rekening.edit', compact('rekening'));
    }

    public function update(Request $request, int $id)
    {
        $rekening = Rekening::findOrFail($id);

        // Validasi data yang masuk untuk update
        $request->validate([
            'nomor_rekening' => 'required|string|max:255|unique:rekening,nomor_rekening,' . $rekening->id, // Nomor rekening harus unik kecuali untuk dirinya sendiri
            'atas_nama' => 'required|string|max:255',
            'bank' => 'required|string|max:255',
            'kode_bank' => 'nullable|string|max:10',
        ], [
            'nomor_rekening.required' => 'Nomor Rekening wajib diisi.',
            'nomor_rekening.unique' => 'Nomor Rekening ini sudah terdaftar.',
            'atas_nama.required' => 'Atas Nama wajib diisi.',
            'bank.required' => 'Nama Bank wajib diisi.',
        ]);

        // Perbarui data rekening di database
        $rekening->update([
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'bank' => $request->bank,
            'kode_bank' => $request->kode_bank,
        ]);

        return redirect()->route('rekening.index')->with('success', 'Data rekening berhasil diperbarui!');
    }

    /**
     * Menghapus rekening tertentu dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $rekening = Rekening::findOrFail($id);
        $rekening->delete();

        return redirect()->route('rekening.index')->with('success', 'Data rekening berhasil dihapus!');
    }
}
