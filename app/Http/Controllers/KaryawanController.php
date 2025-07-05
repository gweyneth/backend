<?php

namespace App\Http\Controllers;

use App\Models\Karyawan; // Import model Karyawan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk upload file foto

class KaryawanController extends Controller
{

    /**
     * Menampilkan daftar semua karyawan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $karyawan = Karyawan::all();
        return view('pages.karyawan.index', compact('karyawan'));
    }

    /**
     * Menampilkan form untuk membuat karyawan baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.karyawan.create');
    }

    /**
     * Menyimpan karyawan baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $request->validate([
            // 'id_karyawan' telah dihapus dari validasi karena tidak lagi digunakan
            'nama_karyawan' => 'required|string|max:255',
            'nik' => 'nullable|string|max:255',
            'jabatan' => 'required|string|max:255',
            'status' => 'required|in:Tetap,Kontrak,Magang',
            'alamat' => 'required|string',
            'no_handphone' => 'required|string|max:20',
            'email' => 'required|email|unique:karyawan,email|max:255', // Pastikan email unik
            'gaji_pokok' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
        ], [
            // Pesan kustom untuk validasi
            'nama_karyawan.required' => 'Nama Karyawan wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_handphone.required' => 'Nomor Handphone wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'gaji_pokok.required' => 'Gaji Pokok wajib diisi.',
            'gaji_pokok.numeric' => 'Gaji Pokok harus berupa angka.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, gif.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $path_foto = null;
        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            // Simpan foto di direktori 'uploads' dalam 'public' disk
            // Laravel akan mengembalikan path relatif dari disk 'public', contoh: uploads/namafileunik.jpg
            $path_foto = $request->file('foto')->store('uploads', 'public');
        }

        // Buat data karyawan baru
        Karyawan::create([
            // 'id_karyawan' telah dihapus dari sini karena tidak lagi digunakan
            'nama_karyawan' => $request->nama_karyawan,
            'nik' => $request->nik,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
            'alamat' => $request->alamat,
            'no_handphone' => $request->no_handphone,
            'email' => $request->email,
            'gaji_pokok' => $request->gaji_pokok,
            'foto' => $path_foto, // Simpan path foto
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    /**
     * Mengambil detail karyawan tertentu dalam format JSON.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        // Cari karyawan berdasarkan ID utama (primary key), jika tidak ditemukan akan menampilkan 404
        $karyawan = Karyawan::findOrFail($id);

        // Mengembalikan data karyawan dalam format JSON
        return response()->json($karyawan);
    }

    /**
     * Menampilkan form untuk mengedit karyawan tertentu.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('pages.karyawan.edit', compact('karyawan'));
    }

    /**
     * Memperbarui data karyawan tertentu di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        // Validasi data yang masuk untuk update
        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'nik' => 'nullable|string|max:255',
            'jabatan' => 'required|string|max:255',
            'status' => 'required|in:Tetap,Kontrak,Magang',
            'alamat' => 'required|string',
            'no_handphone' => 'required|string|max:20',
            // Email harus unik kecuali untuk email karyawan itu sendiri
            'email' => 'required|email|max:255|unique:karyawan,email,' . $karyawan->id,
            'gaji_pokok' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
        ], [
            // Pesan kustom untuk validasi
            'nama_karyawan.required' => 'Nama Karyawan wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_handphone.required' => 'Nomor Handphone wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'gaji_pokok.required' => 'Gaji Pokok wajib diisi.',
            'gaji_pokok.numeric' => 'Gaji Pokok harus berupa angka.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang diizinkan: jpeg, png, jpg, gif.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Ambil semua data request kecuali token, method, dan foto (foto akan ditangani terpisah)
        $data = $request->except(['_token', '_method', 'foto']);

        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada dan file-nya benar-benar ada di storage
            // Path penyimpanan foto lama sekarang juga disesuaikan ke 'uploads'
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            // Simpan foto baru di public/storage/uploads
            $path_foto = $request->file('foto')->store('uploads', 'public');
            $data['foto'] = $path_foto; // Tambahkan path foto baru ke data yang akan diupdate
        }

        // Perbarui data karyawan di database
        $karyawan->update($data);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        // Hapus foto terkait jika ada
        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        // Hapus data karyawan dari database
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}
