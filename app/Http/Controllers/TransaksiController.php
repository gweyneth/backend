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
    /**
     * Menampilkan halaman log transaksi.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $searchQuery = $request->input('search_query');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Mengurutkan dari data terlama (ascending)
        $query = Transaksi::with(['pelanggan'])->orderBy('id', 'asc');

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

        $totalKeseluruhanTransaksi = $query->clone()->sum('total');
        $totalPiutang = $query->clone()->sum('sisa');
        $transaksi = $query->paginate($limit)->withQueryString();
        $rekening = Rekening::all();
        $perusahaan = Perusahaan::first();

        return view('pages.transaksi.index', compact(
            'transaksi', 'totalKeseluruhanTransaksi', 'totalPiutang', 'rekening', 'perusahaan',
            'searchQuery', 'startDate', 'endDate', 'limit'
        ));
    }

    /**
     * Menampilkan form untuk membuat transaksi baru.
     */
    public function create()
    {
        $latestTransaksi = Transaksi::latest('id')->first();
        $nextNoTransaksi = $this->generateNoTransaksi($latestTransaksi ? $latestTransaksi->no_transaksi : null);
        $pelanggan = Pelanggan::orderBy('nama', 'asc')->get();
        $produks = Produk::orderBy('nama', 'asc')->get();
        return view('pages.transaksi.create', compact('nextNoTransaksi', 'pelanggan', 'produks'));
    }

    /**
     * Menyimpan transaksi baru ke database.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'no_transaksi' => 'required|string|unique:transaksi,no_transaksi|max:255',
                'pelanggan_id' => 'nullable|exists:pelanggan,id',
                'tanggal_order' => 'required|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_order',
                'total_keseluruhan' => 'required|numeric|min:0',
                'uang_muka' => 'nullable|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0',
                'sisa' => 'required|numeric|min:0',
                'status_pengerjaan' => 'required|in:menunggu export,belum dikerjakan,proses desain,proses produksi,selesai',
                'produk_id.*' => 'nullable|exists:produk,id',
                'nama_produk.*' => 'required|string|max:255',
                'keterangan.*' => 'nullable|string',
                'qty.*' => 'required|integer|min:1',
                'ukuran.*' => 'nullable|string|max:255',
                'satuan.*' => 'nullable|string|max:255',
                'harga.*' => 'required|numeric|min:0',
                'total_item.*' => 'required|numeric|min:0',
            ]);

            DB::transaction(function () use ($validatedData) {
                // Menentukan status bayar berdasarkan sisa
                $status_bayar = ($validatedData['sisa'] == 0) ? 'LUNAS' : 'BELUM LUNAS';

                $transaksi = Transaksi::create([
                    'no_transaksi' => $validatedData['no_transaksi'],
                    'pelanggan_id' => $validatedData['pelanggan_id'],
                    'tanggal_order' => $validatedData['tanggal_order'],
                    'tanggal_selesai' => $validatedData['tanggal_selesai'],
                    'total' => $validatedData['total_keseluruhan'],
                    'uang_muka' => $validatedData['uang_muka'] ?? 0,
                    'diskon' => $validatedData['diskon'] ?? 0,
                    'sisa' => $validatedData['sisa'],
                    'status_pengerjaan' => $validatedData['status_pengerjaan'],
                    'status_bayar' => $status_bayar, // Menambahkan status bayar saat create
                ]);

                if (isset($validatedData['nama_produk'])) {
                    foreach ($validatedData['nama_produk'] as $key => $nama_produk) {
                        TransaksiDetail::create([
                            'transaksi_id' => $transaksi->id,
                            'produk_id' => $validatedData['produk_id'][$key] ?? null,
                            'nama_produk' => $nama_produk,
                            'keterangan' => $validatedData['keterangan'][$key] ?? null,
                            'qty' => $validatedData['qty'][$key],
                            'ukuran' => $validatedData['ukuran'][$key] ?? null,
                            'satuan' => $validatedData['satuan'][$key] ?? null,
                            'harga' => $validatedData['harga'][$key],
                            'total' => $validatedData['total_item'][$key],
                        ]);
                    }
                }
            });

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Menampilkan detail satu transaksi.
     */
    public function show(int $id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'transaksiDetails.produk'])->findOrFail($id);
        return view('pages.transaksi.show', compact('transaksi'));
    }

    /**
     * Menampilkan form untuk mengedit transaksi.
     */
    public function edit(int $id)
    {
        $transaksi = Transaksi::with('transaksiDetails')->findOrFail($id);
        $pelanggan = Pelanggan::orderBy('nama', 'asc')->get();
        $produks = Produk::orderBy('nama', 'asc')->get();
        return view('pages.transaksi.edit', compact('transaksi', 'pelanggan', 'produks'));
    }

    /**
     * Memperbarui data transaksi di database.
     */
    public function update(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        try {
            $validatedData = $request->validate([
                'no_transaksi' => 'required|string|max:255|unique:transaksi,no_transaksi,' . $transaksi->id,
                'pelanggan_id' => 'nullable|exists:pelanggan,id',
                'tanggal_order' => 'required|date',
                'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_order',
                'total_keseluruhan' => 'required|numeric|min:0',
                'uang_muka' => 'nullable|numeric|min:0',
                'diskon' => 'nullable|numeric|min:0',
                'sisa' => 'required|numeric|min:0',
                'status_pengerjaan' => 'required|in:menunggu export,belum dikerjakan,proses desain,proses produksi,selesai',
                'produk_id.*' => 'nullable|exists:produk,id',
                'nama_produk.*' => 'required|string|max:255',
                'keterangan.*' => 'nullable|string',
                'qty.*' => 'required|integer|min:1',
                'ukuran.*' => 'nullable|string|max:255',
                'satuan.*' => 'nullable|string|max:255',
                'harga.*' => 'required|numeric|min:0',
                'total_item.*' => 'required|numeric|min:0',
            ]);

            DB::transaction(function () use ($transaksi, $validatedData) {
                // Menentukan status bayar berdasarkan sisa
                $status_bayar = ($validatedData['sisa'] == 0) ? 'LUNAS' : 'BELUM LUNAS';

                $transaksi->update([
                    'no_transaksi' => $validatedData['no_transaksi'],
                    'pelanggan_id' => $validatedData['pelanggan_id'],
                    'tanggal_order' => $validatedData['tanggal_order'],
                    'tanggal_selesai' => $validatedData['tanggal_selesai'],
                    'total' => $validatedData['total_keseluruhan'],
                    'uang_muka' => $validatedData['uang_muka'] ?? 0,
                    'diskon' => $validatedData['diskon'] ?? 0,
                    'sisa' => $validatedData['sisa'],
                    'status_pengerjaan' => $validatedData['status_pengerjaan'],
                    'status_bayar' => $status_bayar, // Menambahkan status bayar saat update
                ]);

                $transaksi->transaksiDetails()->delete();
                
                if (isset($validatedData['nama_produk'])) {
                    foreach ($validatedData['nama_produk'] as $key => $nama_produk) {
                        TransaksiDetail::create([
                            'transaksi_id' => $transaksi->id,
                            'produk_id' => $validatedData['produk_id'][$key] ?? null,
                            'nama_produk' => $nama_produk,
                            'keterangan' => $validatedData['keterangan'][$key] ?? null,
                            'qty' => $validatedData['qty'][$key],
                            'ukuran' => $validatedData['ukuran'][$key] ?? null,
                            'satuan' => $validatedData['satuan'][$key] ?? null,
                            'harga' => $validatedData['harga'][$key],
                            'total' => $validatedData['total_item'][$key],
                        ]);
                    }
                }
            });

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * METHOD DIPERBARUI: Memproses pembayaran dan pelunasan.
     */
    public function pelunasan(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $rules = [
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:tunai,transfer_bank,qris',
            'keterangan_pembayaran' => 'nullable|string',
        ];

        if ($request->input('metode_pembayaran') === 'transfer_bank') {
            $rules['rekening_id'] = 'required|exists:rekening,id';
            // Jadikan bukti pembayaran opsional jika diperlukan
            $rules['bukti_pembayaran'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $transaksi) {
                $jumlahBayar = (float) $request->input('jumlah_bayar');

                // Update uang muka dan sisa
                $transaksi->uang_muka += $jumlahBayar;
                $transaksi->sisa -= $jumlahBayar;

                // Pastikan sisa tidak menjadi negatif
                if ($transaksi->sisa < 0) {
                    $transaksi->sisa = 0;
                }
                
                // Update status bayar jika sisa 0
                if ($transaksi->sisa == 0) {
                    $transaksi->status_bayar = 'LUNAS';
                }

                // Simpan detail pembayaran
                $transaksi->metode_pembayaran = $request->input('metode_pembayaran');
                if ($request->input('metode_pembayaran') === 'transfer_bank') {
                    $transaksi->rekening_id = $request->input('rekening_id');
                    if ($request->hasFile('bukti_pembayaran')) {
                        // Hapus bukti lama jika ada
                        if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                        }
                        // Simpan bukti baru
                        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
                        $transaksi->bukti_pembayaran = $path;
                    }
                }
                
                $transaksi->save();
            });

            $pesan = ($transaksi->status_bayar == 'LUNAS') ? 'berhasil dilunasi' : 'berhasil dibayar sebagian';
            return redirect()->back()->with('success', 'Transaksi ' . $transaksi->no_transaksi . ' ' . $pesan . '.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data transaksi.
     */
    public function destroy(int $id)
    {
        try {
            DB::transaction(function() use ($id) {
                $transaksi = Transaksi::findOrFail($id);
                if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                    Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                }
                $transaksi->transaksiDetails()->delete();
                $transaksi->delete();
            });
            return response()->json(['success' => 'Transaksi berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus. Transaksi ini mungkin terkait dengan data lain.'], 500);
        }
    }

    /**
     * Mengambil baris HTML baru untuk item produk.
     */
    public function getProdukItemRow(Request $request)
    {
        $index = $request->input('index');
        $produks = Produk::orderBy('nama', 'asc')->get();
        return view('pages.transaksi.produk_item_row', compact('index', 'produks'));
    }

    /**
     * Membuat nomor transaksi baru secara otomatis.
     */
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
    
    /**
     * Menampilkan halaman piutang.
     */
    public function piutangIndex()
    {
        $piutangTransaksi = Transaksi::with('pelanggan')
            ->where('sisa', '>', 0)
            ->orderBy('id', 'asc')
            ->get();

        $totalPiutang = $piutangTransaksi->sum('sisa');
        return view('pages.piutang.index', compact('piutangTransaksi', 'totalPiutang'));
    }

    /**
     * Menampilkan halaman rincian pendapatan.
     */
    public function pendapatanIndex(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'rekening'])
            ->where(function ($q) {
                $q->where('sisa', '=', 0)->orWhere('uang_muka', '>', 0);
            })
            ->orderBy('id', 'asc');

        $query->when($request->filled('search_query'), function ($q) use ($request) {
            $search = $request->input('search_query');
            $q->where(function ($subq) use ($search) {
                $subq->where('no_transaksi', 'like', "%{$search}%")->orWhereHas('pelanggan', fn($pelangganQuery) => $pelangganQuery->where('nama', 'like', "%{$search}%"));
            });
        });
        $query->when($request->filled('start_date'), fn($q) => $q->whereDate('updated_at', '>=', $request->input('start_date')));
        $query->when($request->filled('end_date'), fn($q) => $q->whereDate('updated_at', '<=', $request->input('end_date')));
        $query->when($request->filled('metode_pembayaran') && $request->input('metode_pembayaran') !== 'all', fn($q) => $q->where('metode_pembayaran', $request->input('metode_pembayaran')));
        $query->when($request->input('metode_pembayaran') === 'transfer_bank' && $request->filled('rekening_id'), fn($q) => $q->where('rekening_id', $request->input('rekening_id')));

        $totalPendapatan = $query->clone()->sum('uang_muka');
        $pendapatanTransaksi = $query->paginate(15)->withQueryString();
        $rekening = Rekening::all();

        return view('pages.pendapatan.index', compact('pendapatanTransaksi', 'totalPendapatan', 'rekening'));
    }

    /**
     * Mengekspor data pendapatan ke Excel.
     */
    public function exportExcelPendapatan(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new PendapatanExport($filters), 'laporan-pendapatan-' . date('d-m-Y') . '.xlsx');
    }

    /**
     * Mengekspor data log transaksi ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $fileName = 'transaksi_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new TransaksiExport($request->query()), $fileName);
    }
}

