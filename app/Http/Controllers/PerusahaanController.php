<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan; // Import model Perusahaan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk mengelola file (upload, hapus)

class PerusahaanController extends Controller
{
    /**
     * Menampilkan form untuk mengelola data perusahaan (baik membuat baru atau mengedit yang sudah ada).
     * Metode ini dipanggil oleh rute GET /perusahaan (perusahaan.show)
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Mencari data perusahaan pertama atau membuat instance baru jika tidak ada.
        // firstOrNew([]) akan mencari record berdasarkan kriteria kosong,
        // jika tidak ada, ia akan membuat instance model baru tanpa menyimpannya ke DB.
        $perusahaan = Perusahaan::firstOrNew([]);

        // Mengirimkan instance perusahaan ke view. View akan menyesuaikan tampilan
        // berdasarkan apakah $perusahaan sudah ada (exists) atau belum.
        return view('pages.perusahaan.edit', compact('perusahaan'));
    }

    /**
     * Menyimpan atau memperbarui data perusahaan.
     * Metode ini akan dipanggil oleh rute PUT/PATCH /perusahaan (perusahaan.update)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Mengambil instance perusahaan yang ada atau membuat yang baru jika belum ada.
        // Ini memastikan kita selalu bekerja dengan objek model yang benar,
        // baik itu record yang sudah ada atau yang akan dibuat.
        $perusahaan = Perusahaan::firstOrNew([]);

        // Validasi data yang masuk dari form.
        // Untuk email, kita perlu pengecualian unique agar bisa mengupdate record yang sama.
        // 'NULL' digunakan jika $perusahaan belum ada (untuk memastikan validasi unique bekerja saat create).
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:perusahaan,email,' . ($perusahaan->exists ? $perusahaan->id : 'NULL'),
            'alamat' => 'nullable|string',
            'alamat_tanggal' => 'nullable|string|max:255',
            'no_handphone' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max 2MB
            'favicon' => 'nullable|image|mimes:ico,png,svg|max:2048',
            'logo_login' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_lunas' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_belum_lunas' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'id_card_desain' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Ambil semua data request kecuali token dan method spoofing
        $data = $request->except(['_token', '_method']);

        // Fungsi bantu untuk mengupload atau mengupdate file gambar
        $handleFileUpload = function ($file, $folder, $oldPath) {
            if ($file) {
                // Hapus file lama jika ada dan file-nya benar-benar ada di storage
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                // Simpan file baru di direktori 'uploads/perusahaan/<folder>' dalam 'public' disk
                $path = $file->store('uploads/perusahaan/' . $folder, 'public');
                return $path;
            }
            return $oldPath; // Kembalikan path lama jika tidak ada file baru diupload
        };

        // Proses setiap file yang diupload atau pertahankan path lama
        $data['logo'] = $handleFileUpload($request->file('logo'), 'logo', $perusahaan->logo);
        $data['favicon'] = $handleFileUpload($request->file('favicon'), 'favicon', $perusahaan->favicon);
        $data['logo_login'] = $handleFileUpload($request->file('logo_login'), 'logo_login', $perusahaan->logo_login);
        $data['logo_lunas'] = $handleFileUpload($request->file('logo_lunas'), 'logo_lunas', $perusahaan->logo_lunas);
        $data['logo_belum_lunas'] = $handleFileUpload($request->file('logo_belum_lunas'), 'logo_belum_lunas', $perusahaan->logo_belum_lunas);
        $data['qr_code'] = $handleFileUpload($request->file('qr_code'), 'qr_code', $perusahaan->qr_code);
        $data['id_card_desain'] = $handleFileUpload($request->file('id_card_desain'), 'id_card_desain', $perusahaan->id_card_desain);

        // Mengisi atribut model dengan data yang divalidasi dan path file
        $perusahaan->fill($data);
        // Menyimpan model. Jika ini instance baru, akan melakukan INSERT. Jika sudah ada, akan melakukan UPDATE.
        $perusahaan->save();

        return redirect()->route('perusahaan.show')->with('success', 'Data perusahaan berhasil disimpan!');
    }

    /**
     * Menghapus data perusahaan.
     * Metode ini akan dipanggil oleh rute DELETE /perusahaan (perusahaan.destroy)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $perusahaan = Perusahaan::first(); // Ambil satu-satunya record perusahaan

        if ($perusahaan) {
            // Hapus semua file terkait sebelum menghapus record dari database
            $filesToDelete = [
                $perusahaan->logo,
                $perusahaan->favicon,
                $perusahaan->logo_login,
                $perusahaan->logo_lunas,
                $perusahaan->logo_belum_lunas,
                $perusahaan->qr_code,
                $perusahaan->id_card_desain,
            ];

            foreach ($filesToDelete as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
            $perusahaan->delete(); // Hapus record dari database
            return redirect()->route('perusahaan.show')->with('success', 'Data perusahaan berhasil dihapus!');
        }
        return redirect()->route('perusahaan.show')->with('error', 'Data perusahaan tidak ditemukan.');
    }
}
