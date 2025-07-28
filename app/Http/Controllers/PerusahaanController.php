<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerusahaanController extends Controller
{
    /**
     * Menampilkan form untuk mengedit data perusahaan.
     * Method ini dipanggil oleh route GET /perusahaan/edit.
     */
    public function edit()
    {
        $perusahaan = Perusahaan::firstOrNew([]);
        return view('pages.perusahaan.edit', compact('perusahaan'));
    }

    /**
     * Memperbarui data perusahaan.
     * Method ini dipanggil oleh route PUT /perusahaan.
     */
    public function update(Request $request)
    {
        $perusahaan = Perusahaan::firstOrNew([]);

        $validatedData = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:perusahaan,email,' . $perusahaan->id,
            'alamat' => 'nullable|string|max:500',
            'alamat_tanggal' => 'nullable|string|max:255',
            'no_handphone' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'logo_login' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'logo_lunas' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'logo_belum_lunas' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'id_card_desain' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $dataToUpdate = $request->except(['_token', '_method']);
        
        $imageFields = [
            'logo', 'favicon', 'logo_login', 'logo_lunas', 
            'logo_belum_lunas', 'qr_code', 'id_card_desain'
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $path = $this->handleUpload($request, $field, $perusahaan->{$field}, $field);
                $dataToUpdate[$field] = $path;
            }
        }

        $perusahaan->fill($dataToUpdate);
        $perusahaan->save();

        return redirect()->route('perusahaan.edit')->with('success', 'Data perusahaan berhasil diperbarui!');
    }

    /**
     * Fungsi helper untuk menangani upload file ke subfolder yang benar.
     */
    private function handleUpload(Request $request, string $fieldName, ?string $oldFilePath, string $subfolder): string
    {
        if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
            Storage::disk('public')->delete($oldFilePath);
        }
        $path = 'uploads/perusahaan_photos/' . $subfolder;
        return $request->file($fieldName)->store($path, 'public');
    }
}
