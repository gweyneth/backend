<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Exports\PelangganExport; 
use Maatwebsite\Excel\Facades\Excel;

class PelangganController extends Controller
{
    /**
     * Menampilkan daftar pelanggan dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        // Mengambil parameter dari request
        $limit = $request->input('limit', 10);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchQuery = $request->input('search_query');

        // Memulai query builder
        $query = Pelanggan::query();

        // Filter berdasarkan rentang tanggal
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Filter berdasarkan pencarian nama atau kode pelanggan
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('nama', 'like', '%' . $searchQuery . '%')
                  ->orWhere('kode_pelanggan', 'like', '%' . $searchQuery . '%');
            });
        }

        // Mengambil data dengan urutan terbaru dan paginasi
        $data = $query->latest()->paginate($limit);

        // PERUBAHAN: Menghitung total seluruh pelanggan dan mengirimkannya ke view
        $totalPelanggan = Pelanggan::count();

        return view('pages.pelanggan.index', compact('data', 'limit', 'startDate', 'endDate', 'searchQuery', 'totalPelanggan'));
    }

    /**
     * Menampilkan formulir untuk membuat pelanggan baru.
     */
    public function create()
    {
        $nextKodePelanggan = $this->generateNextKodePelanggan();
        return view('pages.pelanggan.create', compact('nextKodePelanggan'));
    }

    /**
     * Menyimpan data pelanggan baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_pelanggan' => 'required|string|unique:pelanggan,kode_pelanggan|max:20',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggan,email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
        ]);

        try {
            Pelanggan::create($validatedData);
            return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan pelanggan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mengambil data pelanggan spesifik untuk ditampilkan (misal: di modal).
     */
    public function show(int $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return response()->json($pelanggan);
    }

    /**
     * Menampilkan formulir untuk mengedit data pelanggan.
     */
    public function edit(int $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pages.pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Memperbarui data pelanggan di database.
     */
    public function update(Request $request, int $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validatedData = $request->validate([
            'kode_pelanggan' => 'required|string|max:20|unique:pelanggan,kode_pelanggan,' . $pelanggan->id,
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:pelanggan,email,' . $pelanggan->id,
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
        ]);

        try {
            $pelanggan->update($validatedData);
            return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pelanggan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus data pelanggan dari database.
     */
    public function destroy(int $id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->delete();

            return response()->json(['success' => 'Data pelanggan berhasil dihapus!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data. Mungkin data ini terkait dengan data lain.'], 500);
        }
    }

    private function generateNextKodePelanggan()
    {
        $latestPelanggan = Pelanggan::latest('id')->first();
        $nextId = ($latestPelanggan) ? $latestPelanggan->id + 1 : 1;
        return 'PLG-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function exportExcel()
    {
        return Excel::download(new PelangganExport, 'data-pelanggan-' . now()->format('Y-m-d') . '.xlsx');
    }

}
