<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan; // Pastikan model Pelanggan sudah ada
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request) // Tambahkan Request $request
    {
        // Ambil parameter filter dari request
        $limit = $request->input('limit', 10); // Default 10 data per halaman
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchQuery = $request->input('search_query');

        $query = Pelanggan::query(); // Mulai query

        // Terapkan filter tanggal jika ada
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Terapkan filter pencarian jika ada (berdasarkan nama atau kode_pelanggan)
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('nama', 'like', '%' . $searchQuery . '%')
                  ->orWhere('kode_pelanggan', 'like', '%' . $searchQuery . '%');
            });
        }

        // Ambil data dengan paginasi, urutkan berdasarkan yang terbaru dibuat
        $data = $query->latest()->paginate($limit);

        // Kirim semua variabel yang diperlukan ke view
        return view('pages.pelanggan.index', compact('data', 'limit', 'startDate', 'endDate', 'searchQuery'));
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
            'email' => 'required|email|unique:pelanggan,email|max:255',
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
            'email' => 'required|email|max:255|unique:pelanggan,email,' . $pelanggan->id,
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
