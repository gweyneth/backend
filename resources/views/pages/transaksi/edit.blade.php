@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6"><h1 class="m-0">Edit Transaksi</h1></div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Edit Transaksi</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title">Edit Transaksi #{{ $transaksi->no_transaksi }}</h5></div>
            <div class="card-body">
                <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="transaksi-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="no_transaksi" value="{{ $transaksi->no_transaksi }}">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group"><label>Nama Pemesan</label><select name="pelanggan_id" id="pelanggan_id" class="form-control">
                                @foreach ($pelanggan as $item)
                                    <option value="{{ $item->id }}" data-alamat="{{ $item->alamat }}" data-telp="{{ $item->no_hp }}" {{ old('pelanggan_id', $transaksi->pelanggan_id) == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select></div>
                            <div class="form-group"><label>Alamat</label><input type="text" id="alamat_pelanggan" class="form-control" readonly></div>
                            <div class="form-group"><label>Telp</label><input type="text" id="telp_pelanggan" class="form-control" readonly></div>
                            <div class="form-group"><label>Tanggal Order</label><input type="date" name="tanggal_order" class="form-control" value="{{ old('tanggal_order', $transaksi->tanggal_order->format('Y-m-d')) }}" required></div>
                            <div class="form-group"><label>Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $transaksi->tanggal_selesai ? $transaksi->tanggal_selesai->format('Y-m-d') : '') }}"></div>
                        </div>

                        <div class="col-md-9">
                            <h6>Detail Produk</h6>
                            <div id="produk-items-container">
                                @forelse ($transaksi->transaksiDetails as $index => $detail)
                                    @include('pages.transaksi.produk_item_row', ['index' => $index, 'produks' => $produks, 'detail' => $detail])
                                @empty
                                    @include('pages.transaksi.produk_item_row', ['index' => 0, 'produks' => $produks])
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-success btn-sm mt-2" id="add-produk-item">Tambah Baris</button>
                            <hr>
                            {{-- Nilai `value` di sini sekarang sudah angka bersih dari controller, bukan string bermasalah --}}
                            <div class="form-group"><label>Total Keseluruhan</label><input type="text" name="total_keseluruhan" id="total_keseluruhan" class="form-control" value="{{ old('total_keseluruhan', $transaksi->total) }}" readonly></div>
                            <div class="form-group"><label>Uang Muka</label><input type="text" name="uang_muka" id="uang_muka" class="form-control" value="{{ old('uang_muka', $transaksi->uang_muka) }}"></div>
                            <div class="form-group"><label>Diskon</label><input type="text" name="diskon" id="diskon" class="form-control" value="{{ old('diskon', $transaksi->diskon) }}"></div>
                            <div class="form-group"><label>Sisa Pembayaran</label><input type="text" name="sisa" id="sisa" class="form-control" value="{{ old('sisa', $transaksi->sisa) }}" readonly></div>
                            <div class="form-group"><label>Status Pengerjaan</label><select name="status_pengerjaan" class="form-control" required>
                                    <option value="menunggu export" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'menunggu export' ? 'selected' : '' }}>Menunggu Export</option>
                                    <option value="belum dikerjakan" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                                    <option value="proses desain" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'proses desain' ? 'selected' : '' }}>Proses Desain</option>
                                    <option value="proses produksi" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'proses produksi' ? 'selected' : '' }}>Proses Produksi</option>
                                    <option value="selesai" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select></div>
                        </div>
                    </div>
                    <div class="mt-4 text-right"><button type="submit" class="btn btn-primary">Update Transaksi</button><a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Kode ini sama persis dengan di create.blade.php, dengan tambahan inisialisasi nilai awal
    document.addEventListener('DOMContentLoaded', function() {
        let produkItemIndex = document.querySelectorAll('.produk-item').length;

        // --- Helper Functions ---
        const parseNumber = value => parseFloat(String(value).replace(/[^0-9]/g, '')) || 0;
        const formatRupiah = number => `Rp ${new Intl.NumberFormat('id-ID').format(number)}`;
        const formatNumber = number => new Intl.NumberFormat('id-ID').format(number);

        // --- Main Calculation Function ---
        function updateCalculations() {
            let grandTotal = 0;
            document.querySelectorAll('.produk-item').forEach(row => {
                const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
                const price = parseNumber(row.querySelector('.item-price').value);
                const total = qty * price;
                row.querySelector('.item-total').value = formatRupiah(total);
                grandTotal += total;
            });

            document.getElementById('total_keseluruhan').value = formatRupiah(grandTotal);

            const uangMuka = parseNumber(document.getElementById('uang_muka').value);
            const diskon = parseNumber(document.getElementById('diskon').value);
            const sisa = grandTotal - uangMuka - diskon;

            document.getElementById('sisa').value = formatRupiah(sisa < 0 ? 0 : sisa);
        }

        // --- Event Listener Setup ---
        function initializeRow(row) {
            const priceInput = row.querySelector('.item-price');
            // Format nilai awal dari database saat halaman dimuat
            priceInput.value = formatNumber(parseNumber(priceInput.value));

            row.addEventListener('input', e => {
                if (e.target.matches('.produk-name')) {
                    const selected = e.target.options[e.target.selectedIndex];
                    priceInput.value = formatNumber(selected.dataset.harga || 0);
                }
                updateCalculations();
            });
            row.addEventListener('blur', e => {
                if(e.target.matches('.item-price')){
                    e.target.value = formatNumber(parseNumber(e.target.value));
                }
            }, true);
        }

        // --- Initializations ---
        document.querySelectorAll('.produk-item').forEach(initializeRow);

        // Global inputs
        ['uang_muka', 'diskon'].forEach(id => {
            const el = document.getElementById(id);
            // Format nilai awal dari database saat halaman dimuat
            el.value = formatNumber(parseNumber(el.value)); 
            el.addEventListener('input', updateCalculations);
            el.addEventListener('blur', e => {
                 e.target.value = formatNumber(parseNumber(e.target.value));
            });
        });
        
        updateCalculations(); // Panggil kalkulasi di akhir untuk memastikan semua sinkron

        // Add Row Button
        document.getElementById('add-produk-item').addEventListener('click', () => {
            fetch(`/transaksi/get-produk-item-row?index=${produkItemIndex}`)
                .then(response => response.text())
                .then(html => {
                    const container = document.getElementById('produk-items-container');
                    container.insertAdjacentHTML('beforeend', html);
                    initializeRow(container.lastElementChild);
                    produkItemIndex++;
                });
        });

        // Remove Row Button (delegation)
        document.getElementById('produk-items-container').addEventListener('click', e => {
            if (e.target.closest('.remove-produk-item')) {
                e.target.closest('.produk-item').remove();
                updateCalculations();
            }
        });

        // Pelanggan Info
        const pelangganSelect = document.getElementById('pelanggan_id');
        function updatePelangganInfo() {
            const opt = pelangganSelect.options[pelangganSelect.selectedIndex];
            document.getElementById('alamat_pelanggan').value = opt ? opt.dataset.alamat || '' : '';
            document.getElementById('telp_pelanggan').value = opt ? opt.dataset.telp || '' : '';
        }
        pelangganSelect.addEventListener('change', updatePelangganInfo);
        updatePelangganInfo();
    });
</script>
@endpush

