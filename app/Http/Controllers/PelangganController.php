<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Pelanggan::select([
                'id',
                'kode_pelanggan',
                'nama',
                'email',
                'alamat',
                'no_hp',
            ])->get();

            return view('pages.pelanggan.index', compact('data'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20', // Consider adding regex for phone number format
            'email' => 'required|email|unique:pelanggan,email', // 'unique:table,column'
        ]);


    // Simpan data awal (tanpa kode_pelanggan)
    $pelanggan = Pelanggan::create([
        'nama'   => $request->nama,
        'alamat' => $request->alamat,
        'no_hp'  => $request->no_hp,
        'email'  => $request->email,
    ]);

    // Generate kode pelanggan (contoh: PLG001)
    $kode = 'PLG' . str_pad($pelanggan->id, 3, '0', STR_PAD_LEFT);

    // Update kode pelanggan
    $pelanggan->update(['kode_pelanggan' => $kode]);

    return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $pelanggan)
    {
         return view('pages.pelanggan.show', compact('pelanggan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
         return view('pages.pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        try {
            $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            // Validasi email: unique kecuali untuk email pelanggan ini sendiri
            'email' => 'required|email|unique:pelanggan,email,' . $pelanggan->id,
        ]);

        // 2. Update Data Pelanggan
        $pelanggan->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            // 'kode_pelanggan' tidak diupdate karena otomatis/readonly
        ]);

        // 3. Redirect dengan pesan sukses
        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui!');
   
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggan $pelanggan)
    {
         try {
            $pelanggan->delete();
            // Redirect dengan pesan sukses
            return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil dihapus!');
        } catch (\Exception $e) {
            // Tangani error jika terjadi (misal, karena ada foreign key constraint)
            // Redirect dengan pesan error
            return redirect()->route('pelanggan.index')->with('error', 'Gagal menghapus data pelanggan. ' . $e->getMessage());
        }
    }
}
