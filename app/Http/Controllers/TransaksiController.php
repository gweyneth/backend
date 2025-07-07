<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Pelanggan;
use App\Models\Produk; // Menggunakan model Produk
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException; // Import untuk menangkap ValidationException

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan'])->latest();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate) {
            $query->whereDate('tanggal_order', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_order', '<=', $endDate);
        }

        $searchQuery = $request->input('search_query');
        if ($searchQuery) {
            $query->whereHas('pelanggan', function ($q) use ($searchQuery) {
                $q->where('nama', 'like', '%' . $searchQuery . '%');
            })->orWhere('no_transaksi', 'like', '%' . $searchQuery . '%');
        }

        $transaksi = $query->get();

        $totalUangMuka = $transaksi->sum('uang_muka');
        $totalPiutang = $transaksi->sum('sisa');
        $totalKeseluruhanTransaksi = $transaksi->sum('total');

        return view('pages.transaksi.index', compact(
            'transaksi',
            'totalUangMuka',
            'totalPiutang',
            'totalKeseluruhanTransaksi',
            'startDate',
            'endDate',
            'searchQuery'
        ));
    }

    public function create()
    {
        $latestTransaksi = Transaksi::latest()->first();
        $nextNoTransaksi = $this->generateNoTransaksi($latestTransaksi ? $latestTransaksi->no_transaksi : null);

        $pelanggan = Pelanggan::all();
        $produks = Produk::with(['bahan', 'satuan'])->get();

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
                'id_pelunasan' => 'nullable|string|max:255',
                'status_pengerjaan' => 'required|in:menunggu export,belum dikerjakan,proses desain,proses produksi,selesai',
            ]);


            $request->validate([
                'produk_id.*' => 'nullable|exists:produk,id',
                'nama_produk.*' => 'required|string|max:255',
                'keterangan.*' => 'nullable|string',
                'bahan.*' => 'nullable|string|max:255',
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
                'id_pelunasan' => $validatedTransaksi['id_pelunasan'],
                'status_pengerjaan' => $validatedTransaksi['status_pengerjaan'],
            ]);

            if ($request->has('nama_produk') && is_array($request->input('nama_produk'))) {
                foreach ($request->input('nama_produk') as $key => $nama_produk) {
                    TransaksiDetail::create([
                        'transaksi_id' => $transaksi->id,
                        'produk_id' => $request->input('produk_id.' . $key),
                        'nama_produk' => $nama_produk,
                        'keterangan' => $request->input('keterangan.' . $key),
                        'bahan' => $request->input('bahan.' . $key),
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
        $produks = Produk::with(['bahan', 'satuan'])->get();

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
                'id_pelunasan' => 'nullable|string|max:255',
                'status_pengerjaan' => 'required|in:menunggu export,belum dikerjakan,proses desain,proses produksi,selesai',
            ]);

            $request->validate([
                'produk_id.*' => 'nullable|exists:produk,id',
                'nama_produk.*' => 'required|string|max:255',
                'keterangan.*' => 'nullable|string',
                'bahan.*' => 'nullable|string|max:255',
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
                'id_pelunasan' => $validatedTransaksi['id_pelunasan'],
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
                        'bahan' => $request->input('bahan.' . $key),
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

    /**
     * Menghapus transaksi tertentu dari database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->transaksiDetails()->delete();
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus!');
    }

    public function getProductDetails(Request $request)
    {
        $produkId = $request->input('produk_id');
        $produkName = $request->input('nama_produk'); 

        $produk = null;
        if ($produkId) {
            $produk = Produk::with(['bahan', 'satuan'])->find($produkId);
        } elseif ($produkName) {
            $produk = Produk::with(['bahan', 'satuan'])->where('nama', $produkName)->first();
        }

        if ($produk) {
            return response()->json([
                'nama_produk' => $produk->nama,
                'bahan' => $produk->bahan->nama ?? '', 
                'ukuran' => $produk->ukuran,
                'satuan' => $produk->satuan->nama ?? '', 
                'harga' => $produk->harga_jual,
            ]);
        }

        return response()->json(null, 404);
    }

    public function getProdukItemRow(Request $request)
    {
        $index = $request->input('index');
     
        $produks = Produk::with(['bahan', 'satuan'])->get();

        return view('pages.transaksi._produk_item_row', compact('index', 'produks'));
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
}
