<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerusahaanController extends Controller
{

    public function edit()
{
    $perusahaan = Perusahaan::firstOrNew([]);
    return view('.pages.perusahaan.edit', compact('perusahaan'));
}

    public function show()
    {
        $perusahaan = Perusahaan::firstOrNew([]);
        return view('pages.perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $request)
    {
        // Ambil data perusahaan pertama, atau buat instance baru jika belum ada.
        $perusahaan = Perusahaan::firstOrNew([]);

        $validatedData = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:perusahaan,email,' . $perusahaan->id,
            'alamat' => 'nullable|string|max:500',
            'alamat_tanggal' => 'nullable|string|max:255',
            'no_handphone' => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=1200,max_height=700',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=72,max_height=72',
            'logo_login' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=1200,max_height=700',
            'logo_lunas' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=500,max_height=300',
            'logo_belum_lunas' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=500,max_height=300',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=700,max_height=700',
            'id_card_desain' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nama_perusahaan.required' => 'Nama Perusahaan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'logo.dimensions' => 'Dimensi logo tidak sesuai (maks 1200x700 px).',
            'favicon.image' => 'File favicon harus berupa gambar.',
            'favicon.max' => 'Ukuran favicon maksimal 2MB.',
            'favicon.dimensions' => 'Dimensi favicon tidak sesuai (maks 72x72 px).',
            'logo_login.image' => 'File logo login harus berupa gambar.',
            'logo_login.max' => 'Ukuran logo login maksimal 2MB.',
            'logo_login.dimensions' => 'Dimensi logo login tidak sesuai (maks 1200x700 px).',
            'logo_lunas.image' => 'File logo lunas harus berupa gambar.',
            'logo_lunas.max' => 'Ukuran logo lunas maksimal 2MB.',
            'logo_lunas.dimensions' => 'Dimensi logo lunas tidak sesuai (maks 500x300 px).',
            'logo_belum_lunas.image' => 'File logo belum lunas harus berupa gambar.',
            'logo_belum_lunas.max' => 'Ukuran logo belum lunas maksimal 2MB.',
            'logo_belum_lunas.dimensions' => 'Dimensi logo belum lunas tidak sesuai (maks 500x300 px).',
            'qr_code.image' => 'File QR Code harus berupa gambar.',
            'qr_code.max' => 'Ukuran QR Code maksimal 2MB.',
            'qr_code.dimensions' => 'Dimensi QR Code tidak sesuai (maks 700x700 px).',
            'id_card_desain.image' => 'File desain ID Card harus berupa gambar.',
            'id_card_desain.max' => 'Ukuran desain ID Card maksimal 2MB.',
        ]);

        // Proses upload dan hapus file lama untuk logo
        if ($request->hasFile('logo')) {
            if ($perusahaan->logo && Storage::disk('public')->exists($perusahaan->logo)) {
                Storage::disk('public')->delete($perusahaan->logo);
            }
            $validatedData['logo'] = $request->file('logo')->store('uploads/perusahaan', 'public');
        }

        // Proses upload dan hapus file lama untuk favicon
        if ($request->hasFile('favicon')) {
            if ($perusahaan->favicon && Storage::disk('public')->exists($perusahaan->favicon)) {
                Storage::disk('public')->delete($perusahaan->favicon);
            }
            $validatedData['favicon'] = $request->file('favicon')->store('uploads/perusahaan', 'public');
        }

        // Proses upload dan hapus file lama untuk logo_login
        if ($request->hasFile('logo_login')) {
            if ($perusahaan->logo_login && Storage::disk('public')->exists($perusahaan->logo_login)) {
                Storage::disk('public')->delete($perusahaan->logo_login);
            }
            $validatedData['logo_login'] = $request->file('logo_login')->store('uploads/perusahaan', 'public');
        }

        // Proses upload dan hapus file lama untuk logo_lunas
        if ($request->hasFile('logo_lunas')) {
            if ($perusahaan->logo_lunas && Storage::disk('public')->exists($perusahaan->logo_lunas)) {
                Storage::disk('public')->delete($perusahaan->logo_lunas);
            }
            $validatedData['logo_lunas'] = $request->file('logo_lunas')->store('uploads/perusahaan', 'public');
        }

        // Proses upload dan hapus file lama untuk logo_belum_lunas
        if ($request->hasFile('logo_belum_lunas')) {
            if ($perusahaan->logo_belum_lunas && Storage::disk('public')->exists($perusahaan->logo_belum_lunas)) {
                Storage::disk('public')->delete($perusahaan->logo_belum_lunas);
            }
            $validatedData['logo_belum_lunas'] = $request->file('logo_belum_lunas')->store('uploads/perusahaan', 'public');
        }

        // Proses upload dan hapus file lama untuk qr_code
        if ($request->hasFile('qr_code')) {
            if ($perusahaan->qr_code && Storage::disk('public')->exists($perusahaan->qr_code)) {
                Storage::disk('public')->delete($perusahaan->qr_code);
            }
            $validatedData['qr_code'] = $request->file('qr_code')->store('uploads/perusahaan', 'public');
        }

        // Proses upload dan hapus file lama untuk id_card_desain
        if ($request->hasFile('id_card_desain')) {
            if ($perusahaan->id_card_desain && Storage::disk('public')->exists($perusahaan->id_card_desain)) {
                Storage::disk('public')->delete($perusahaan->id_card_desain);
            }
            $validatedData['id_card_desain'] = $request->file('id_card_desain')->store('uploads/perusahaan', 'public');
        }

        // Simpan atau perbarui data perusahaan
        $perusahaan->fill($validatedData);
        $perusahaan->save();

        return redirect()->route('perusahaan.edit')->with('success', 'Data perusahaan berhasil diperbarui!');
    }

    // Metode destroy dihapus sesuai permintaan
}
