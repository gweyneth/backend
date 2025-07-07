<?php

namespace App\Http\Controllers;

use App\Models\Produk; // Import Model Produk
use App\Models\Bahan; // Import Model Bahan
use App\Models\KategoriBarang; // Import Model KategoriBarang
use App\Models\Satuan; // Import Model Satuan
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Untuk generate string acak/kode

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relasi bahan, kategori, dan satuan untuk menghindari N+1 problem
        $produks = Produk::with(['bahan', 'kategori', 'satuan'])->get();
        // Jika Anda ingin pagination: $produks = Produk::with(['bahan', 'kategori', 'satuan'])->paginate(10);

        return view('pages.produk.index', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil data untuk dropdown
        $bahans = Bahan::all();
        $kategoriBarangs = KategoriBarang::all();
        $satuans = Satuan::all();

        return view('pages.produk.create', compact('bahans', 'kategoriBarangs', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'nama' => 'required|string|max:255', // Kode akan di-generate, jadi tidak perlu unique di sini
            'bahan_id' => 'required|exists:bahan,id',
            'ukuran' => 'nullable|string|max:100', // Ukuran bisa kosong
            'jumlah' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli', // Harga jual harus >= harga beli
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
        ], [
            'nama.required' => 'Nama produk wajib diisi.',
            'nama.max' => 'Nama produk tidak boleh lebih dari 255 karakter.',
            'bahan_id.required' => 'Bahan wajib dipilih.',
            'bahan_id.exists' => 'Bahan tidak valid.',
            'ukuran.max' => 'Ukuran tidak boleh lebih dari 100 karakter.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.numeric' => 'Harga beli harus berupa angka.',
            'harga_beli.min' => 'Harga beli tidak boleh kurang dari 0.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',
            'harga_jual.gte' => 'Harga jual tidak boleh lebih rendah dari harga beli.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'satuan_id.required' => 'Satuan wajib dipilih.',
            'satuan_id.exists' => 'Satuan tidak valid.',
        ]);

        // 2. Generate Kode Produk (Barcode/QR Code)
        // Contoh sederhana: kombinasi timestamp dan string acak
        // Anda bisa menggunakan library seperti milon/barcode atau simple-qrcode untuk generate gambar barcode/QR
        $kodeProduk = 'PROD-' . time() . '-' . Str::random(5);
        // Pastikan kode unik, bisa ditambahkan loop jika ada kemungkinan duplikasi
        while (Produk::where('kode', $kodeProduk)->exists()) {
            $kodeProduk = 'PROD-' . time() . '-' . Str::random(5);
        }

        // 3. Simpan Data ke Database
        try {
            Produk::create([
                'nama' => $request->nama,
                'kode' => $kodeProduk, // Simpan kode yang sudah di-generate
                'bahan_id' => $request->bahan_id,
                'ukuran' => $request->ukuran,
                'jumlah' => $request->jumlah,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'kategori_id' => $request->kategori_id,
                'satuan_id' => $request->satuan_id,
            ]);

            // 4. Beri Notifikasi Sukses
            session()->flash('success', 'Data produk berhasil ditambahkan.');

            // 5. Redirect ke halaman daftar produk
            return redirect()->route('produk.index');

        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menyimpan
            session()->flash('error', 'Terjadi kesalahan saat menambahkan produk: ' . $e->getMessage());
            return redirect()->back()->withInput(); // Kembali ke form dengan input sebelumnya
        }
    }

    /**
     * Display the specified resource.
     */
     public function show(string $id)
    {
        
        $produk = Produk::with(['bahan', 'kategori', 'satuan'])->find($id);

        if (!$produk) {
            // Mengembalikan respons JSON 404 jika produk tidak ditemukan
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

       
        return response()->json($produk);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Temukan produk yang akan diedit
        $produk = Produk::findOrFail($id);

        // Ambil data untuk dropdown
        $bahans = Bahan::all();
        $kategoriBarangs = KategoriBarang::all();
        $satuans = Satuan::all();

        // Kirim data produk dan data dropdown ke view
        return view('pages.produk.edit', compact('produk', 'bahans', 'kategoriBarangs', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $produk = Produk::findOrFail($id);

        // 1. Validasi Data
        $request->validate([
            // Nama produk harus unik, kecuali untuk produk yang sedang diupdate
            'nama' => 'required|string|max:255|unique:produk,nama,' . $id,
            'bahan_id' => 'required|exists:bahan,id',
            'ukuran' => 'nullable|string|max:100',
            'jumlah' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan,id',
        ], [
            'nama.required' => 'Nama produk wajib diisi.',
            'nama.unique' => 'Nama produk sudah ada.',
            'nama.max' => 'Nama produk tidak boleh lebih dari 255 karakter.',
            'bahan_id.required' => 'Bahan wajib dipilih.',
            'bahan_id.exists' => 'Bahan tidak valid.',
            'ukuran.max' => 'Ukuran tidak boleh lebih dari 100 karakter.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.numeric' => 'Harga beli harus berupa angka.',
            'harga_beli.min' => 'Harga beli tidak boleh kurang dari 0.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',
            'harga_jual.gte' => 'Harga jual tidak boleh lebih rendah dari harga beli.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'satuan_id.required' => 'Satuan wajib dipilih.',
            'satuan_id.exists' => 'Satuan tidak valid.',
        ]);

        // 2. Simpan Perubahan ke Database
        try {
            // Kode produk tidak diupdate di sini, karena diasumsikan auto-generate saat create saja
            $produk->update([
                'nama' => $request->nama,
                'bahan_id' => $request->bahan_id,
                'ukuran' => $request->ukuran,
                'jumlah' => $request->jumlah,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'kategori_id' => $request->kategori_id,
                'satuan_id' => $request->satuan_id,
            ]);

            // 3. Beri Notifikasi Sukses
            session()->flash('success', 'Data produk berhasil diperbarui.');

            // 4. Redirect ke halaman daftar produk
            return redirect()->route('produk.index');

        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah saat menyimpan
            session()->flash('error', 'Terjadi kesalahan saat memperbarui produk: ' . $e->getMessage());
            return redirect()->back()->withInput(); // Kembali ke form dengan input sebelumnya
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Temukan dan hapus produk
            $produk = Produk::findOrFail($id);
            $produk->delete();

            session()->flash('success', 'Data produk berhasil dihapus.');
            return redirect()->route('produk.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus produk: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}