<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Exports\PengeluaranExport; // Import Class Export
use Maatwebsite\Excel\Facades\Excel;

class PengeluaranController extends Controller
{
    /**
     * Menampilkan daftar semua pengeluaran dengan filter dan pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Mendapatkan nilai filter dari request
        $searchQuery = $request->input('search_query');
        $jenisPengeluaran = $request->input('jenis_pengeluaran');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // PERUBAHAN UTAMA: Mengganti latest() dengan orderBy('id', 'asc') untuk menampilkan data terlama di atas.
        $query = Pengeluaran::with('karyawan')->orderBy('id', 'asc');

        if ($searchQuery) {
            $query->where('keterangan', 'like', '%' . $searchQuery . '%');
        }

        if ($jenisPengeluaran) {
            $query->where('jenis_pengeluaran', $jenisPengeluaran);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Menggunakan pagination
        $pengeluaran = $query->paginate(10)->withQueryString();

        // Menghitung total pengeluaran dari data yang sudah difilter sebelum paginasi
        // Untuk ini kita perlu clone query sebelum paginasi
        $totalPengeluaran = $query->clone()->sum('total');

        return view('pages.pengeluaran.index', compact('pengeluaran', 'totalPengeluaran'));
    }

    /**
     * Menampilkan form untuk membuat pengeluaran baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $karyawan = Karyawan::all();
        return view('pages.pengeluaran.create', compact('karyawan'));
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
            'karyawan_id' => [
                'nullable',
                Rule::requiredIf($request->jenis_pengeluaran === 'Kasbon Karyawan'),
                'exists:karyawan,id',
            ],
            'keterangan' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            // Total tidak perlu divalidasi karena dihitung di controller
        ], [
            'jenis_pengeluaran.required' => 'Jenis Pengeluaran wajib diisi.',
            'karyawan_id.required' => 'Nama Karyawan wajib dipilih untuk Kasbon Karyawan.',
            'karyawan_id.exists' => 'Karyawan tidak valid.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga minimal 0.',
        ]);

        $total = $validatedData['jumlah'] * $validatedData['harga'];

        Pengeluaran::create([
            'jenis_pengeluaran' => $validatedData['jenis_pengeluaran'],
            'karyawan_id' => $validatedData['karyawan_id'],
            'keterangan' => $validatedData['keterangan'],
            'jumlah' => $validatedData['jumlah'],
            'harga' => $validatedData['harga'],
            'total' => $total,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit pengeluaran.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $pengeluaran = Pengeluaran::with('karyawan')->findOrFail($id);
        $karyawan = Karyawan::all();
        return view('pages.pengeluaran.edit', compact('pengeluaran', 'karyawan'));
    }

    /**
     * Memperbarui pengeluaran di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        $validatedData = $request->validate([
            'jenis_pengeluaran' => 'required|string|max:255',
            'karyawan_id' => [
                'nullable',
                Rule::requiredIf($request->jenis_pengeluaran === 'Kasbon Karyawan'),
                'exists:karyawan,id',
            ],
            'keterangan' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
        ], [
            'jenis_pengeluaran.required' => 'Jenis Pengeluaran wajib diisi.',
            'karyawan_id.required' => 'Nama Karyawan wajib dipilih untuk Kasbon Karyawan.',
            'karyawan_id.exists' => 'Karyawan tidak valid.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga minimal 0.',
        ]);

        $total = $validatedData['jumlah'] * $validatedData['harga'];

        $pengeluaran->update([
            'jenis_pengeluaran' => $validatedData['jenis_pengeluaran'],
            // Jika jenis bukan 'Kasbon Karyawan', pastikan karyawan_id disetel null
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);
            $pengeluaran->delete();

            return response()->json([
                'success' => 'Pengeluaran berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus pengeluaran.',
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['search_query', 'jenis_pengeluaran', 'start_date', 'end_date']);
        
        $fileName = 'Laporan-Pengeluaran-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new PengeluaranExport($filters), $fileName);
    }
}