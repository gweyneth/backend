@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Transaksi</h1>
    </div>
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
            <div class="card-header">
                <h5 class="card-title">Edit Transaksi #{{ $transaksi->no_transaksi }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="transaksi-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="no_transaksi" value="{{ $transaksi->no_transaksi }}">

                    <div class="row">
                        {{-- Kolom Kiri: Detail Transaksi Utama --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pelanggan_id">Nama Pemesan</label>
                                <select name="pelanggan_id" id="pelanggan_id" class="form-control @error('pelanggan_id') is-invalid @enderror">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach ($pelanggan as $item)
                                        <option value="{{ $item->id }}"
                                                data-alamat="{{ $item->alamat }}"
                                                data-telp="{{ $item->no_hp }}"
                                                {{ old('pelanggan_id', $transaksi->pelanggan_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pelanggan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="alamat_pelanggan">Alamat</label>
                                <input type="text" id="alamat_pelanggan" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="telp_pelanggan">Telp</label>
                                <input type="text" id="telp_pelanggan" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_order">Tanggal Order</label>
                                <input type="date" name="tanggal_order" id="tanggal_order" class="form-control @error('tanggal_order') is-invalid @enderror" value="{{ old('tanggal_order', $transaksi->tanggal_order->format('Y-m-d')) }}" required>
                                @error('tanggal_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tanggal_selesai">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $transaksi->tanggal_selesai ? $transaksi->tanggal_selesai->format('Y-m-d') : '') }}">
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Kolom Kanan: Detail Produk Transaksi --}}
                        <div class="col-md-9">
                            <h6>Detail Produk</h6>
                            <div id="produk-items-container">
                                @forelse ($transaksi->transaksiDetails as $index => $detail)
                                    @include('pages.transaksi.produk_item_row', [
                                        'index' => $index,
                                        'produks' => $produks,
                                        'detail' => $detail
                                    ])
                                @empty
                                    @include('pages.transaksi.produk_item_row', ['index' => 0, 'produks' => $produks])
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-success btn-sm mt-2" id="add-produk-item">Tambah Baris Produk</button>
                            <hr>

                            <div class="form-group">
                                <label for="total_keseluruhan">Total Keseluruhan</label>
                                <input type="text" name="total_keseluruhan" id="total_keseluruhan" class="form-control" value="{{ old('total_keseluruhan', $transaksi->total) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="uang_muka">Uang Muka</label>
                                <input type="text" name="uang_muka" id="uang_muka" class="form-control @error('uang_muka') is-invalid @enderror" value="{{ old('uang_muka', $transaksi->uang_muka) }}">
                            </div>
                            <div class="form-group">
                                <label for="diskon">Diskon</label>
                                <input type="text" name="diskon" id="diskon" class="form-control @error('diskon') is-invalid @enderror" value="{{ old('diskon', $transaksi->diskon) }}">
                            </div>
                            <div class="form-group">
                                <label for="sisa">Sisa Pembayaran</label>
                                <input type="text" name="sisa" id="sisa" class="form-control" value="{{ old('sisa', $transaksi->sisa) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="status_pengerjaan">Status Pengerjaan</label>
                                <select name="status_pengerjaan" id="status_pengerjaan" class="form-control @error('status_pengerjaan') is-invalid @enderror" required>
                                    <option value="menunggu export" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'menunggu export' ? 'selected' : '' }}>Menunggu Export</option>
                                    <option value="belum dikerjakan" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                                    <option value="proses desain" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'proses desain' ? 'selected' : '' }}>Proses Desain</option>
                                    <option value="proses produksi" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'proses produksi' ? 'selected' : '' }}>Proses Produksi</option>
                                    <option value="selesai" {{ old('status_pengerjaan', $transaksi->status_pengerjaan) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status_pengerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
{{-- KODE JAVASCRIPT BARU YANG LEBIH STABIL --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let produkItemIndex = document.querySelectorAll('.produk-item').length;

    // --- FUNGSI UTAMA UNTUK ANGKA ---
    // 1. Mengambil angka murni dari string (e.g., "Rp 500.000" -> 500000)
    const parseCurrency = value => parseFloat(String(value).replace(/[^0-9]/g, '')) || 0;
    
    // 2. Memformat angka menjadi format ribuan Indonesia (e.g., 500000 -> "500.000")
    const formatNumber = number => new Intl.NumberFormat('id-ID').format(number);

    // --- FUNGSI KALKULASI ---
    function calculateGrandTotalAndRemaining() {
        let grandTotal = 0;
        document.querySelectorAll('.item-total').forEach(el => {
            grandTotal += parseCurrency(el.value);
        });
        
        document.getElementById('total_keseluruhan').value = formatNumber(grandTotal);

        const uangMuka = parseCurrency(document.getElementById('uang_muka').value);
        const diskon = parseCurrency(document.getElementById('diskon').value);

        let sisa = grandTotal - uangMuka - diskon;
        sisa = sisa < 0 ? 0 : sisa;

        document.getElementById('sisa').value = formatNumber(sisa);
    }

    function calculateItemTotal(rowElement) {
        const qty = parseFloat(rowElement.querySelector('.item-qty').value) || 0;
        const price = parseCurrency(rowElement.querySelector('.item-price').value);
        const total = qty * price;
        
        rowElement.querySelector('.item-total').value = formatNumber(total);
        calculateGrandTotalAndRemaining();
    }

    // --- FUNGSI INISIALISASI & EVENT LISTENER ---
    function initializeProdukRow(row) {
        const produkSelect = row.querySelector('.produk-name');
        const qtyInput = row.querySelector('.item-qty');
        const priceInput = row.querySelector('.item-price');

        // Event saat dropdown produk diganti
        produkSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.dataset.harga || 0;
            priceInput.value = formatNumber(harga);
            calculateItemTotal(row);
        });

        // Event saat quantity atau harga diketik
        qtyInput.addEventListener('input', () => calculateItemTotal(row));
        priceInput.addEventListener('input', () => calculateItemTotal(row));

        // Event saat selesai mengetik di input harga (untuk merapikan format)
        priceInput.addEventListener('blur', () => {
            priceInput.value = formatNumber(parseCurrency(priceInput.value));
        });

        // Panggil kalkulasi awal untuk baris ini
        calculateItemTotal(row);
    }

    // Event listener untuk input Uang Muka dan Diskon
    ['uang_muka', 'diskon'].forEach(id => {
        const el = document.getElementById(id);
        el.addEventListener('input', calculateGrandTotalAndRemaining);
        el.addEventListener('blur', () => {
            el.value = formatNumber(parseCurrency(el.value));
        });
    });

    // Inisialisasi setiap baris produk yang sudah ada di halaman
    document.querySelectorAll('.produk-item').forEach(row => initializeProdukRow(row));

    // Format nilai awal Uang Muka dan Diskon saat halaman dimuat
    document.getElementById('uang_muka').value = formatNumber(parseCurrency(document.getElementById('uang_muka').value));
    document.getElementById('diskon').value = formatNumber(parseCurrency(document.getElementById('diskon').value));
    
    // Panggil kalkulasi utama sekali di akhir untuk memastikan semua total benar
    calculateGrandTotalAndRemaining();

    // Event listener untuk menambah baris baru
    document.getElementById('add-produk-item').addEventListener('click', function() {
        fetch(`/transaksi/get-produk-item-row?index=${produkItemIndex}`)
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('produk-items-container');
                container.insertAdjacentHTML('beforeend', html);
                initializeProdukRow(container.lastElementChild);
                produkItemIndex++;
            });
    });

    // Event listener untuk menghapus baris
    document.getElementById('produk-items-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-produk-item')) {
            e.target.closest('.produk-item').remove();
            calculateGrandTotalAndRemaining();
        }
    });

    // Inisialisasi info pelanggan
    const pelangganSelect = document.getElementById('pelanggan_id');
    function updatePelangganInfo() {
        const selectedOption = pelangganSelect.options[pelangganSelect.selectedIndex];
        document.getElementById('alamat_pelanggan').value = selectedOption ? (selectedOption.dataset.alamat || '') : '';
        document.getElementById('telp_pelanggan').value = selectedOption ? (selectedOption.dataset.telp || '') : '';
    }
    pelangganSelect.addEventListener('change', updatePelangganInfo);
    updatePelangganInfo();
});
</script>
@endpush