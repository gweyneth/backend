@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Transaksi</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Edit Transaksi</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Transaksi #{{ $transaksi->no_transaksi }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="transaksi-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="no_transaksi" value="{{ $transaksi->no_transaksi }}">

                    <div class="row">
                        <div class="col-md-3">
                            {{-- Detail Pelanggan & Tanggal --}}
                            <div class="form-group">
                                <label for="pelanggan_id">Nama Pemesan</label>
                                <select name="pelanggan_id" id="pelanggan_id" class="form-control">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach ($pelanggan as $item)
                                        <option value="{{ $item->id }}" data-alamat="{{ $item->alamat }}" data-telp="{{ $item->no_hp }}" {{ old('pelanggan_id', $transaksi->pelanggan_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group"><label>Alamat</label><input type="text" id="alamat_pelanggan" class="form-control" readonly></div>
                            <div class="form-group"><label>Telp</label><input type="text" id="telp_pelanggan" class="form-control" readonly></div>
                            <div class="form-group">
                                <label for="tanggal_order">Tanggal Order</label>
                                <input type="date" name="tanggal_order" id="tanggal_order" class="form-control" value="{{ old('tanggal_order', $transaksi->tanggal_order->format('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_selesai">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $transaksi->tanggal_selesai ? $transaksi->tanggal_selesai->format('Y-m-d') : '') }}">
                            </div>
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
                            <button type="button" class="btn btn-success btn-sm mt-2" id="add-produk-item">Tambah Baris Produk</button>
                            <hr>
                            <div class="form-group">
                                <label for="total_keseluruhan">Total Keseluruhan</label>
                                <input type="text" name="total_keseluruhan" id="total_keseluruhan" class="form-control" value="{{ old('total_keseluruhan', (int)$transaksi->total) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="uang_muka">Uang Muka</label>
                                <input type="text" name="uang_muka" id="uang_muka" class="form-control" value="{{ old('uang_muka', (int)$transaksi->uang_muka) }}">
                            </div>
                            <div class="form-group">
                                <label for="diskon">Diskon</label>
                                <input type="text" name="diskon" id="diskon" class="form-control" value="{{ old('diskon', (int)$transaksi->diskon) }}">
                            </div>
                            <div class="form-group">
                                <label for="sisa">Sisa Pembayaran</label>
                                <input type="text" name="sisa" id="sisa" class="form-control" value="{{ old('sisa', (int)$transaksi->sisa) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="status_pengerjaan">Status Pengerjaan</label>
                                <select name="status_pengerjaan" id="status_pengerjaan" class="form-control" required>
                                    <option value="menunggu export" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'menunggu export' ? 'selected' : '' }}>Menunggu Export</option>
                                    <option value="belum dikerjakan" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                                    <option value="proses desain" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'proses desain' ? 'selected' : '' }}>Proses Desain</option>
                                    <option value="proses produksi" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'proses produksi' ? 'selected' : '' }}>Proses Produksi</option>
                                    <option value="selesai" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">Update Transaksi</button>
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- FUNGSI UTAMA ---
    const formatRupiah = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka || 0);
    const parseRupiah = (rupiah) => parseFloat(String(rupiah).replace(/[^0-9]/g, '')) || 0;

    // --- KALKULASI UTAMA ---
    function calculateAllTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.produk-item').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 1;
            const price = parseRupiah(row.querySelector('.item-price').value);
            const total = qty * price;
            
            row.querySelector('.item-total-display').value = formatRupiah(total);
            row.querySelector('.item-total-hidden').value = total;
            grandTotal += total;
        });

        document.getElementById('total_keseluruhan').value = formatRupiah(grandTotal);

        const uangMuka = parseRupiah(document.getElementById('uang_muka').value);
        const diskon = parseRupiah(document.getElementById('diskon').value);
        const sisa = grandTotal - uangMuka - diskon;
        document.getElementById('sisa').value = formatRupiah(sisa < 0 ? 0 : sisa);
    }

    // --- PENGATURAN EVENT LISTENER ---
    function setupRowListeners(row) {
        row.querySelector('.produk-name').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const priceInput = row.querySelector('.item-price');
            priceInput.value = formatRupiah(selected.dataset.harga || 0);
            
            // Auto-fill ukuran dan satuan
            row.querySelector('.produk-ukuran').value = selected.dataset.ukuran || '';
            row.querySelector('.produk-satuan').value = selected.dataset.satuan || '';

            calculateAllTotals();
        });

        row.querySelector('.item-qty').addEventListener('input', calculateAllTotals);
        row.querySelector('.item-price').addEventListener('input', function(e) {
            const numericValue = parseRupiah(e.target.value);
            e.target.value = formatRupiah(numericValue);
            calculateAllTotals();
        });
    }

    // --- INISIALISASI HALAMAN ---
    document.querySelectorAll('.produk-item').forEach(setupRowListeners);

    ['uang_muka', 'diskon'].forEach(id => {
        const input = document.getElementById(id);
        input.addEventListener('input', function(e) {
             const numericValue = parseRupiah(e.target.value);
             e.target.value = formatRupiah(numericValue);
             calculateAllTotals();
        });
        input.value = formatRupiah(input.value);
    });

    const pelangganSelect = document.getElementById('pelanggan_id');
    const updatePelangganInfo = () => {
        const selected = pelangganSelect.options[pelangganSelect.selectedIndex];
        document.getElementById('alamat_pelanggan').value = selected ? selected.dataset.alamat || '' : '';
        document.getElementById('telp_pelanggan').value = selected ? selected.dataset.telp || '' : '';
    };
    pelangganSelect.addEventListener('change', updatePelangganInfo);
    updatePelangganInfo();

    calculateAllTotals();

    let produkItemIndex = document.querySelectorAll('.produk-item').length;
    document.getElementById('add-produk-item').addEventListener('click', function() {
        fetch(`/transaksi/get-produk-item-row?index=${produkItemIndex}`)
            .then(res => res.text()).then(html => {
                const container = document.getElementById('produk-items-container');
                container.insertAdjacentHTML('beforeend', html);
                setupRowListeners(container.lastElementChild);
                produkItemIndex++;
            });
    });
    
    document.getElementById('produk-items-container').addEventListener('click', e => {
        if (e.target.closest('.remove-produk-item')) {
            e.target.closest('.produk-item').remove();
            calculateAllTotals();
        }
    });
});
</script>
@endpush