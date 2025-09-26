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

    public function create()
    {
        $latestTransaksi = Transaksi::latest()->first();
        $nextNoTransaksi = $this->generateNoTransaksi($latestTransaksi ? $latestTransaksi->no_transaksi : null);
        $pelanggan = Pelanggan::all();
        $produks = Produk::all();
        return view('pages.transaksi.create', compact('nextNoTransaksi', 'pelanggan', 'produks'));
    }

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

    public function pelunasan(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $validatedData = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:tunai,transfer_bank,qris',
            'rekening_id' => 'nullable|exists:rekening,id',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan_pembayaran' => 'nullable|string|max:500',
            'id_pelunasan' => 'nullable|string|max:255',
        ]);

        if ($validatedData['metode_pembayaran'] === 'transfer_bank') {
            $request->validate(['rekening_id' => 'required|exists:rekening,id', 'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',], ['rekening_id.required' => 'Pilih rekening bank jika metode pembayaran adalah transfer.', 'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah jika metode pembayaran adalah transfer.', 'bukti_pembayaran.image' => 'File bukti pembayaran harus berupa gambar.', 'bukti_pembayaran.mimes' => 'Format gambar yang diizinkan untuk bukti pembayaran: jpeg, png, jpg, gif.', 'bukti_pembayaran.max' => 'Ukuran gambar bukti pembayaran maksimal 2MB.',]);
        }
        $jumlahBayar = $validatedData['jumlah_bayar'];
        if ($jumlahBayar > $transaksi->sisa) {
            return redirect()->back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan.');
        }

        DB::beginTransaction();
        try {
            $newUangMuka = $transaksi->uang_muka + $jumlahBayar;
            $newSisa = $transaksi->total - $newUangMuka - $transaksi->diskon;
            if ($newSisa < 0) $newSisa = 0;
            $pathBuktiPembayaran = $transaksi->bukti_pembayaran;
            if ($request->hasFile('bukti_pembayaran')) {
                if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                    Storage::disk('public')->delete($transaksi->bukti_pembayaran);
                }
                $pathBuktiPembayaran = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }
            $transaksi->update(['uang_muka' => $newUangMuka, 'sisa' => $newSisa, 'id_pelunasan' => $validatedData['id_pelunasan'] ?? $transaksi->id_pelunasan, 'metode_pembayaran' => $validatedData['metode_pembayaran'], 'bukti_pembayaran' => $pathBuktiPembayaran, 'rekening_id' => $validatedData['rekening_id'] ?? null, 'keterangan_pembayaran' => $validatedData['keterangan_pembayaran'] ?? null,]);
            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Pembayaran pelunasan berhasil diproses!');
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(int $id)
    {
        try {
            DB::beginTransaction();
            $transaksi = Transaksi::findOrFail($id);
            if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                Storage::disk('public')->delete($transaksi->bukti_pembayaran);
            }
            $transaksi->transaksiDetails()->delete();
            $transaksi->delete();
            DB::commit();
            return response()->json(['success' => 'Transaksi berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
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
            return response()->json(['nama_produk' => $produk->nama, 'ukuran' => $produk->ukuran, 'satuan' => $produk->satuan ?? '', 'harga' => $produk->harga_jual,]);
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
        $piutangTransaksi = Transaksi::with('pelanggan')->where('sisa', '>', 0)->latest()->get();
        $totalPiutang = $piutangTransaksi->sum('sisa');
        return view('pages.piutang.index', compact('piutangTransaksi', 'totalPiutang'));
    }

    public function pendapatanIndex(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'rekening'])->where(function ($q) {
            $q->where('sisa', '=', 0)->orWhere('uang_muka', '>', 0);
        })->latest('updated_at');

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

    public function exportExcelPendapatan(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new PendapatanExport($filters), 'laporan-pendapatan-' . date('d-m-Y') . '.xlsx');
    }

    public function printReceipt(int $id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'transaksiDetails.produk'])->findOrFail($id);
        $perusahaan = Perusahaan::first();
        return view('pages.transaksi.receipt', compact('transaksi', 'perusahaan'));
    }

    public function printInvoice(int $id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'transaksiDetails.produk'])->findOrFail($id);
        $perusahaan = Perusahaan::first();
        return view('pages.transaksi.invoice', compact('transaksi', 'perusahaan'));
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'transaksi_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new TransaksiExport($request->query()), $fileName);
    }
}