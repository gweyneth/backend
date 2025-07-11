<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan ini di-import jika digunakan

class PelangganController extends Controller
{
    /**
     * Menampilkan daftar semua pelanggan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = Pelanggan::latest()->get(); // Mengambil semua data pelanggan terbaru
        return view('pages.pelanggan.index', compact('data'));
    }

    /**
     * Menampilkan form untuk membuat pelanggan baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $nextKodePelanggan = $this->generateNextKodePelanggan();
        return view('pages.pelanggan.create', compact('nextKodePelanggan'));
    }

    /**
     * Menyimpan pelanggan baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_pelanggan' => 'required|string|unique:pelanggan,kode_pelanggan|max:20',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        try {
            Pelanggan::create($validatedData);
            return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mengambil data pelanggan tertentu dalam format JSON untuk modal detail.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        // Mengembalikan data pelanggan dalam format JSON
        return response()->json($pelanggan);
    }

    /**
     * Menampilkan form untuk mengedit pelanggan tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pages.pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Memperbarui data pelanggan tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validatedData = $request->validate([
            'kode_pelanggan' => 'required|string|max:20|unique:pelanggan,kode_pelanggan,' . $pelanggan->id,
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        try {
            $pelanggan->update($validatedData);
            return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pelanggan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus pelanggan tertentu dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        try {
            $pelanggan->delete();
            return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pelanggan: ' . $e->getMessage());
        }
    }

    /**
     * Menghasilkan kode pelanggan berikutnya.
     *
     * @return string
     */
    private function generateNextKodePelanggan()
    {
        $latestPelanggan = Pelanggan::latest('id')->first();
        $nextId = ($latestPelanggan) ? $latestPelanggan->id + 1 : 1;
        return 'PLG-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }
}
