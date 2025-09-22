<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Rekening;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;
use App\Exports\PendapatanExport; 

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $searchQuery = $request->input('search_query');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Transaksi::with(['pelanggan'])->latest();

        // Terapkan filter
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('no_transaksi', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('pelanggan', function ($subq) use ($searchQuery) {
                      $subq->where('nama', 'like', '%' . $searchQuery . '%');
                  });
            });
        }
        if ($startDate) {
            $query->whereDate('tanggal_order', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal_order', '<=', $endDate);
        }

        // Hitung total sebelum paginasi
        $totalKeseluruhanTransaksi = $query->clone()->sum('total');
        $totalPiutang = $query->clone()->sum('sisa');

        $transaksi = $query->paginate($limit);
        
        // Data untuk modal pelunasan
        $rekening = Rekening::all();
        $perusahaan = Perusahaan::first();

        return view('pages.transaksi.index', compact(
            'transaksi', 
            'totalKeseluruhanTransaksi', 
            'totalPiutang', 
            'rekening', 
            'perusahaan',
            'searchQuery', 
            'startDate', 
            'endDate', 
            'limit'
        ));
    }


    public function create()
    {
        $latestTransaksi = Transaksi::latest()->first();
        $nextNoTransaksi = $this->generateNoTransaksi($latestTransaksi ? $latestTransaksi->no_transaksi : null);

        $pelanggan = Pelanggan::all();
        $produks = Produk::all();

        return view('pages.transaksi.create', compact(
            'nextNoTransaksi',
            'pelanggan',
            'produks'
        ));
    }

    public function store(Request $request)
    {
        try {
            $request->merge([
                'total_keseluruhan' => (float) str_replace(['Rp ', '.'], '', $request->input('total_keseluruhan')),
                'uang_muka' => (float) str_replace(['Rp ', '.'], '', $request->input('uang_muka')),
                'diskon' => (float) str_replace(['Rp ', '.'], '', $request->input('diskon')),
                'sisa' => (float) str_replace(['Rp ', '.'], '', $request->input('sisa')),
            ]);

            if ($request->has('harga') && is_array($request->input('harga'))) {
                $cleanedHarga = [];
                foreach ($request->input('harga') as $key => $value) {
                    $cleanedHarga[$key] = (float) str_replace(['Rp ', '.'], '', $value);
                }
                $request->merge(['harga' => $cleanedHarga]);
            }

            if ($request->has('total_item') && is_array($request->input('total_item'))) {
                $cleanedTotalItem = [];
                foreach ($request->input('total_item') as $key => $value) {
                    $cleanedTotalItem[$key] = (float) str_replace(['Rp ', '.'], '', $value);
                }
                $request->merge(['total_item' => $cleanedTotalItem]);
            }

            $validatedTransaksi = $request->validate([
                'no_transaksi' => 'required|string|unique:transaksi,no_transaksi|max:255',
                'pelanggan_id' => 'nullable|exists:pelanggan,id',
                'tanggal_order' => 'required|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_order',
                'total_keseluruhan' => 'required|numeric|min:0',
                'uang_muka' => 'nullable|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0',
                'status_pengerjaan' => 'required|in:menunggu export,belum dikerjakan,proses desain,proses produksi,selesai',
            ]);

            $request->validate([
                'produk_id.*' => 'nullable|exists:produk,id',
                'nama_produk.*' => 'required|string|max:255',
                'keterangan.*' => 'nullable|string',
                'qty.*' => 'required|integer|min:1',
                'ukuran.*' => 'nullable|string|max:255',
                'satuan.*' => 'nullable|string|max:255',
                'harga.*' => 'required|numeric|min:0',
                'total_item.*' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            $sisaPembayaran = ($validatedTransaksi['total_keseluruhan'] - ($validatedTransaksi['uang_muka'] ?? 0) - ($validatedTransaksi['diskon'] ?? 0));
            if ($sisaPembayaran < 0) $sisaPembayaran = 0;

            $transaksi = Transaksi::create([
                'no_transaksi' => $validatedTransaksi['no_transaksi'],
                'pelanggan_id' => $validatedTransaksi['pelanggan_id'],
                'tanggal_order' => $validatedTransaksi['tanggal_order'],
                'tanggal_selesai' => $validatedTransaksi['tanggal_selesai'],
                'total' => $validatedTransaksi['total_keseluruhan'],
                'uang_muka' => $validatedTransaksi['uang_muka'] ?? 0,
                'diskon' => $validatedTransaksi['diskon'] ?? 0,
                'sisa' => $sisaPembayaran,
                'status_pengerjaan' => $validatedTransaksi['status_pengerjaan'],
            ]);

            if ($request->has('nama_produk') && is_array($request->input('nama_produk'))) {
                foreach ($request->input('nama_produk') as $key => $nama_produk) {
                    TransaksiDetail::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $request->input('produk_id.' . $key),
                        'nama_produk' => $nama_produk,
                        'keterangan' => $request->input('keterangan.' . $key),
                        'qty' => $request->input('qty.' . $key),
                        'ukuran' => $request->input('ukuran.' . $key),
                        'satuan' => $request->input('satuan.' . $key),
                        'harga' => $request->input('harga.' . $key),
                        'total' => $request->input('total_item.' . $key),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

   
    public function show(int $id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'transaksiDetails.produk'])->findOrFail($id);
        return view('pages.transaksi.show', compact('transaksi'));
    }

   
    public function edit(int $id)
    {
        $transaksi = Transaksi::with('transaksiDetails')->findOrFail($id);
        $pelanggan = Pelanggan::all();
        $produks = Produk::all();

        return view('pages.transaksi.edit', compact('transaksi', 'pelanggan', 'produks'));
    }

    
    public function update(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        try {
            $request->merge([
                'total_keseluruhan' => (float) str_replace(['Rp ', '.'], '', $request->input('total_keseluruhan')),
                'uang_muka' => (float) str_replace(['Rp ', '.'], '', $request->input('uang_muka')),
                'diskon' => (float) str_replace(['Rp ', '.'], '', $request->input('diskon')),
                'sisa' => (float) str_replace(['Rp ', '.'], '', $request->input('sisa')),
            ]);

            if ($request->has('harga') && is_array($request->input('harga'))) {
                $cleanedHarga = [];
                foreach ($request->input('harga') as $key => $value) {
                    $cleanedHarga[$key] = (float) str_replace(['Rp ', '.'], '', $value);
                }
                $request->merge(['harga' => $cleanedHarga]);
            }

            if ($request->has('total_item') && is_array($request->input('total_item'))) {
                $cleanedTotalItem = [];
                foreach ($request->input('total_item') as $key => $value) {
                    $cleanedTotalItem[$key] = (float) str_replace(['Rp ', '.'], '', $value);
                }
                $request->merge(['total_item' => $cleanedTotalItem]);
            }

            $validatedTransaksi = $request->validate([
                'no_transaksi' => 'required|string|max:255|unique:transaksi,no_transaksi,' . $transaksi->id,
                'pelanggan_id' => 'nullable|exists:pelanggan,id',
                'tanggal_order' => 'required|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_order',
                'total_keseluruhan' => 'required|numeric|min:0',
                'uang_muka' => 'nullable|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0',
                'status_pengerjaan' => 'required|in:menunggu export,belum dikerjakan,proses desain,proses produksi,selesai',
            ]);

            $request->validate([
                'produk_id.*' => 'nullable|exists:produk,id',
                'nama_produk.*' => 'required|string|max:255',
                'keterangan.*' => 'nullable|string',
                'qty.*' => 'required|integer|min:1',
                'ukuran.*' => 'nullable|string|max:255',
                'satuan.*' => 'nullable|string|max:255',
                'harga.*' => 'required|numeric|min:0',
                'total_item.*' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            $sisaPembayaran = ($validatedTransaksi['total_keseluruhan'] - ($validatedTransaksi['uang_muka'] ?? 0) - ($validatedTransaksi['diskon'] ?? 0));
            if ($sisaPembayaran < 0) $sisaPembayaran = 0;

            $transaksi->update([
                'no_transaksi' => $validatedTransaksi['no_transaksi'],
                'pelanggan_id' => $validatedTransaksi['pelanggan_id'],
                'tanggal_order' => $validatedTransaksi['tanggal_order'],
                'tanggal_selesai' => $validatedTransaksi['tanggal_selesai'],
                'total' => $validatedTransaksi['total_keseluruhan'],
                'uang_muka' => $validatedTransaksi['uang_muka'] ?? 0,
                'diskon' => $validatedTransaksi['diskon'] ?? 0,
                'sisa' => $sisaPembayaran,
                'status_pengerjaan' => $validatedTransaksi['status_pengerjaan'],
            ]);

            $transaksi->transaksiDetails()->delete();

            if ($request->has('nama_produk') && is_array($request->input('nama_produk'))) {
                foreach ($request->input('nama_produk') as $key => $nama_produk) {
                    TransaksiDetail::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $request->input('produk_id.' . $key),
                        'nama_produk' => $nama_produk,
                        'keterangan' => $request->input('keterangan.' . $key),
                        'qty' => $request->input('qty.' . $key),
                        'ukuran' => $request->input('ukuran.' . $key),
                        'satuan' => $request->input('satuan.' . $key),
                        'harga' => $request->input('harga.' . $key),
                        'total' => $request->input('total_item.' . $key),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
    
  
   
    public function pelunasan(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validatedData = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:tunai,transfer_bank',
            'rekening_id' => 'nullable|exists:rekening,id', 
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'keterangan_pembayaran' => 'nullable|string|max:500',
            'id_pelunasan' => 'nullable|string|max:255',
        ]);

        if ($validatedData['metode_pembayaran'] === 'transfer_bank') {
            $request->validate([
                'rekening_id' => 'required|exists:rekening,id',
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'rekening_id.required' => 'Pilih rekening bank jika metode pembayaran adalah transfer.',
                'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah jika metode pembayaran adalah transfer.',
                'bukti_pembayaran.image' => 'File bukti pembayaran harus berupa gambar.',
                'bukti_pembayaran.mimes' => 'Format gambar yang diizinkan untuk bukti pembayaran: jpeg, png, jpg, gif.',
                'bukti_pembayaran.max' => 'Ukuran gambar bukti pembayaran maksimal 2MB.',
            ]);
        }

        $jumlahBayar = $validatedData['jumlah_bayar'];
        $idPelunasan = $validatedData['id_pelunasan'] ?? null;

        if (($transaksi->uang_muka + $jumlahBayar) > $transaksi->total) {
            return redirect()->back()->with('error', 'Jumlah pembayaran melebihi total transaksi.');
        }

        DB::beginTransaction();
        try {
            $newUangMuka = $transaksi->uang_muka + $jumlahBayar;
            $newSisa = $transaksi->total - $newUangMuka - $transaksi->diskon;
            if ($newSisa < 0) $newSisa = 0; 

            $pathBuktiPembayaran = $transaksi->bukti_pembayaran;
           
            if ($request->hasFile('bukti_pembayaran')) {
                // Hapus bukti pembayaran lama jika ada
                if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                    Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                }
                $pathBuktiPembayaran = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }

            $transaksi->update([
                'uang_muka' => $newUangMuka,
                'sisa' => $newSisa,
                'id_pelunasan' => $idPelunasan ?? $transaksi->id_pelunasan,
                'metode_pembayaran' => $validatedData['metode_pembayaran'],
                'bukti_pembayaran' => $pathBuktiPembayaran,
                'rekening_id' => $validatedData['rekening_id'] ?? null,
                'keterangan_pembayaran' => $validatedData['keterangan_pembayaran'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Pembayaran pelunasan berhasil diproses!');
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                Storage::disk('public')->delete($transaksi->bukti_pembayaran);
            }
            $transaksi->transaksiDetails()->delete();
            $transaksi->delete();
            return response()->json(['success' => 'Transaksi berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus. Transaksi ini mungkin terkait dengan data lain.'], 500);
        }
    }

   

    public function getProductDetails(Request $request)
    {
        $produkId = $request->input('produk_id');
        $produkName = $request->input('nama_produk');

        $produk = null;
        if ($produkId) {
            $produk = Produk::find($produkId);
        } elseif ($produkName) {
            $produk = Produk::where('nama', $produkName)->first();
        }

        if ($produk) {
            return response()->json([
                'nama_produk' => $produk->nama,
                'ukuran' => $produk->ukuran,
                'satuan' => $produk->satuan ?? '',
                'harga' => $produk->harga_jual,
            ]);
        }

        return response()->json(null, 404);
    }
 
    public function getProdukItemRow(Request $request)
    {
        $index = $request->input('index');
        $produks = Produk::all();

        return view('pages.transaksi.produk_item_row', compact('index', 'produks'));
    }

    private function generateNoTransaksi(?string $lastNoTransaksi): string
    {
        $prefix = 'KRP';
        $datePart = now()->format('ymd');
        $newNumber = 1;

        if ($lastNoTransaksi) {
            $lastDatePart = substr($lastNoTransaksi, 3, 6);

            if ($lastDatePart === $datePart) {
                $lastNum = (int) substr($lastNoTransaksi, -3);
                $newNumber = $lastNum + 1;
            }
        }

        return $prefix . $datePart . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function piutangIndex()
    {
        $piutangTransaksi = Transaksi::with('pelanggan')
                                    ->where('sisa', '>', 0)
                                    ->latest()
                                    ->get();

        $totalPiutang = $piutangTransaksi->sum('sisa');

        return view('pages.piutang.index', compact('piutangTransaksi', 'totalPiutang'));
    }

    public function pendapatanIndex(Request $request)
    {
        // Memulai query untuk transaksi yang sudah ada pembayarannya (lunas atau ada DP)
        $query = Transaksi::with(['pelanggan', 'rekening'])
            ->where(function ($q) {
                $q->where('sisa', '=', 0)
                  ->orWhere('uang_muka', '>', 0);
            })
            ->latest('updated_at'); // Diurutkan berdasarkan tanggal pembayaran terakhir

        // Terapkan semua filter secara kondisional menggunakan when()
        $query->when($request->filled('search_query'), function ($q) use ($request) {
            $search = $request->input('search_query');
            $q->where(function ($subq) use ($search) {
                $subq->where('no_transaksi', 'like', "%{$search}%")
                     ->orWhereHas('pelanggan', fn($pelangganQuery) => $pelangganQuery->where('nama', 'like', "%{$search}%"));
            });
        });

        $query->when($request->filled('start_date'), function ($q) use ($request) {
            $q->whereDate('updated_at', '>=', $request->input('start_date'));
        });

        $query->when($request->filled('end_date'), function ($q) use ($request) {
            $q->whereDate('updated_at', '<=', $request->input('end_date'));
        });

        $query->when($request->filled('metode_pembayaran') && $request->input('metode_pembayaran') !== 'all', function ($q) use ($request) {
            $q->where('metode_pembayaran', $request->input('metode_pembayaran'));
        });

        $query->when($request->input('metode_pembayaran') === 'transfer_bank' && $request->filled('rekening_id'), function ($q) use ($request) {
            $q->where('rekening_id', $request->input('rekening_id'));
        });

        // Hitung total pendapatan (uang yang masuk) dari query yang sudah difilter.
        // Penting: Kita menjumlahkan 'uang_muka' karena ini merepresentasikan total uang yang sudah dibayar.
        $totalPendapatan = $query->sum('total');

        // Ambil data untuk ditampilkan di halaman dengan paginasi agar lebih efisien
        $pendapatanTransaksi = $query->paginate(15)->withQueryString();

        // Ambil semua data rekening untuk dropdown filter
        $rekening = Rekening::all();

        return view('pages.pendapatan.index', compact(
            'pendapatanTransaksi',
            'totalPendapatan',
            'rekening'
        ));
    }

    /**
     * Menangani ekspor data pendapatan ke Excel.
     */
    public function exportExcelPendapatan(Request $request)
    {
        // Anda perlu membuat kelas App\Exports\PendapatanExport
        // Jalankan: php artisan make:export PendapatanExport
        $filters = $request->all();
        return Excel::download(new PendapatanExport($filters), 'laporan-pendapatan-' . date('d-m-Y') . '.xlsx');
    }
    public function printReceipt(int $id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'transaksiDetails.produk'])->findOrFail($id);
        $perusahaan = Perusahaan::first(); // Ambil data perusahaan

        return view('pages.transaksi.receipt', compact('transaksi', 'perusahaan'));
    }

   
    public function printInvoice(int $id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'transaksiDetails.produk'])->findOrFail($id);
        $perusahaan = Perusahaan::first(); // Ambil data perusahaan

        return view('pages.transaksi.invoice', compact('transaksi', 'perusahaan'));
    }

    public function exportExcel()
    {
        // Nama file Excel yang akan di-download
        $fileName = 'transaksi_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new TransaksiExport, $fileName);
    }
}