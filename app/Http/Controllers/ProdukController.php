<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Bahan;
use App\Models\KategoriBarang;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        // PERBAIKAN: Mengubah urutan default menjadi ascending (data terlama dulu)
        $query = Produk::with(['kategori'])->orderBy('id', 'asc');

        if ($request->has('search_query') && $request->search_query) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search_query . '%')
                  ->orWhere('kode', 'like', '%' . $request->search_query . '%');
            });
        }

        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $produks = $query->paginate(10)->withQueryString(); // Tambah withQueryString agar filter tetap saat paginasi
        $kategoriBarangs = KategoriBarang::all();

        return view('pages.produk.index', compact('produks', 'kategoriBarangs'));
    }

    /**
     * Menampilkan formulir untuk membuat produk baru.
     */
    public function create()
    {
        $bahans = Bahan::all();
        $kategoriBarangs = KategoriBarang::all();
        $satuans = Satuan::all();
        $nextKodeProduk = $this->generateNextKodeProduk();

        return view('pages.produk.create', compact('bahans', 'kategoriBarangs', 'satuans', 'nextKodeProduk'));
    }

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        // PERBAIKAN: Menyesuaikan nama tabel (plural) di aturan validasi sesuai konvensi Laravel
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:produks,kode',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bahan_id' => 'required|exists:bahans,id',
            'ukuran' => 'nullable|string|max:100',
            'jumlah' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'kategori_id' => 'required|exists:kategori_barangs,id',
            'satuan_id' => 'required|exists:satuans,id',
        ], [
            'harga_jual.gte' => 'Harga jual tidak boleh lebih rendah dari harga beli.',
        ]);

        if ($request->hasFile('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('produk_foto', 'public');
        }

        Produk::create($validatedData);
        return redirect()->route('produk.index')->with('success', 'Data produk berhasil ditambahkan.');
    }

    /**
     * Mengambil data produk spesifik untuk ditampilkan di modal.
     */
    public function show(string $id)
    {
        $produk = Produk::with(['bahan', 'kategori', 'satuan'])->findOrFail($id);
        return response()->json($produk);
    }

    /**
     * Menampilkan formulir untuk mengedit produk.
     */
    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        $bahans = Bahan::all();
        $kategoriBarangs = KategoriBarang::all();
        $satuans = Satuan::all();

        return view('pages.produk.edit', compact('produk', 'bahans', 'kategoriBarangs', 'satuans'));
    }

    /**
     * Memperbarui data produk di database.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        // PERBAIKAN: Menyesuaikan nama tabel (plural) di aturan validasi
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bahan_id' => 'required|exists:bahans,id',
            'ukuran' => 'nullable|string|max:100',
            'jumlah' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'kategori_id' => 'required|exists:kategori_barangs,id',
            'satuan_id' => 'required|exists:satuans,id',
        ]);

        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }
            $validatedData['foto'] = $request->file('foto')->store('produk_foto', 'public');
        }

        $produk->update($validatedData);
        return redirect()->route('produk.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(string $id)
    {
        try {
            $produk = Produk::findOrFail($id);
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }
            $produk->delete();
            return response()->json(['success' => 'Data produk berhasil dihapus.']);
        } catch (\Exception $e) {
            // PERBAIKAN: Menambahkan penanganan error foreign key
            if ($e instanceof \Illuminate\Database\QueryException && str_contains($e->getMessage(), 'foreign key constraint fails')) {
                return response()->json(['error' => 'Gagal menghapus: Produk ini terkait dengan data transaksi.'], 422);
            }
            return response()->json(['error' => 'Gagal menghapus data. Terjadi kesalahan server.'], 500);
        }
    }

    private function generateNextKodeProduk()
    {
        $latestProduk = Produk::orderBy('id', 'desc')->first();
        $nextId = ($latestProduk) ? $latestProduk->id + 1 : 1;
        return 'PROD-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}
