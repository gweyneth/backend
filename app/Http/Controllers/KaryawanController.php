<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\KaryawanExport; 

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchQuery = $request->input('search_query');

        $query = Karyawan::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('nama_karyawan', 'like', '%' . $searchQuery . '%')
                  ->orWhere('nik', 'like', '%' . $searchQuery . '%');
            });
        }

        $karyawan = $query->latest()->paginate($limit);

        return view('pages.karyawan.index', compact('karyawan', 'limit', 'startDate', 'endDate', 'searchQuery'));
    }

    public function create()
    {
        $nextIdKaryawan = $this->generateNextIdKaryawan();
        return view('pages.karyawan.create', compact('nextIdKaryawan'));
    }

    public function store(Request $request)
    {
        // PERBAIKAN: Menyesuaikan validasi agar konsisten dengan form (required)
        $validatedData = $request->validate([
            'id_karyawan' => 'required|string|unique:karyawan,id_karyawan|max:20', 
            'nama_karyawan' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20|unique:karyawan,nik',
            'jabatan' => 'required|string|max:100',
            'status' => 'required|in:Tetap,Kontrak,Magang', 
            'alamat' => 'required|string|max:500',
            'no_handphone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:karyawan,email', 
            'gaji_pokok' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            if ($request->hasFile('foto')) {
                $validatedData['foto'] = $request->file('foto')->store('uploads/karyawan_photos', 'public');
            }

            Karyawan::create($validatedData);
            return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan karyawan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(int $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return response()->json($karyawan);
    }

    public function edit(int $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('pages.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, int $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        // PERBAIKAN: Menyesuaikan validasi agar konsisten dengan form (required)
        $validatedData = $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20|unique:karyawan,nik,' . $karyawan->id,
            'jabatan' => 'required|string|max:100',
            'status' => 'required|in:Tetap,Kontrak,Magang',
            'alamat' => 'required|string|max:500',
            'no_handphone' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:karyawan,email,' . $karyawan->id,
            'gaji_pokok' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        try {
            if ($request->hasFile('foto')) {
                if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                    Storage::disk('public')->delete($karyawan->foto);
                }
                $validatedData['foto'] = $request->file('foto')->store('uploads/karyawan_photos', 'public');
            }

            $karyawan->update($validatedData);
            return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui karyawan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * PERBAIKAN: Mengubah metode destroy untuk mengembalikan JSON response
     * agar kompatibel dengan AJAX delete.
     */
    public function destroy(int $id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);
            
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            
            $karyawan->delete();
            
            return response()->json(['success' => 'Data karyawan berhasil dihapus!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data.'], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchQuery = $request->input('search_query');

        return Excel::download(new KaryawanExport($startDate, $endDate, $searchQuery), 'data_karyawan.xlsx');
    }

    private function generateNextIdKaryawan()
    {
        $latestKaryawan = Karyawan::latest('id')->first();
        $nextId = ($latestKaryawan) ? $latestKaryawan->id + 1 : 1;
        return 'KRY-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }
}
