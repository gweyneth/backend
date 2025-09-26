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

                            {{-- PERBAIKAN: Mengubah type="number" menjadi type="text" --}}
                            <div class="form-group">
                                <label for="uang_muka">Uang Muka</label>
                                <input type="text" name="uang_muka" id="uang_muka" class="form-control currency @error('uang_muka') is-invalid @enderror" value="{{ old('uang_muka', $transaksi->uang_muka) }}">
                                @error('uang_muka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- PERBAIKAN: Mengubah type="number" menjadi type="text" --}}
                            <div class="form-group">
                                <label for="diskon">Diskon</label>
                                <input type="text" name="diskon" id="diskon" class="form-control currency @error('diskon') is-invalid @enderror" value="{{ old('diskon', $transaksi->diskon) }}">
                                @error('diskon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
{{-- PERBAIKAN: Seluruh blok JavaScript di bawah ini telah dirombak total --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // =================================================================================
    // BAGIAN 1: FUNGSI-FUNGSI UTAMA (FORMATTING & PARSING)
    // =================================================================================

    // Fungsi BARU dan MODERN untuk format ke Rupiah
    function formatRupiah(angka) {
        if (isNaN(angka) || angka === null || angka === '') return 'Rp 0';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(angka);
    }

    // Fungsi BARU dan AMAN untuk mengubah format Rupiah kembali ke angka
    function parseRupiah(rupiahString) {
        if (typeof rupiahString !== 'string') {
            rupiahString = rupiahString.toString();
        }
        // Menghapus semua karakter kecuali digit
        return parseFloat(rupiahString.replace(/[^0-9]/g, '')) || 0;
    }


    // =================================================================================
    // BAGIAN 2: KALKULASI UTAMA
    // =================================================================================

    // Fungsi untuk menghitung total dari semua baris produk
    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.produk-item .item-total').forEach(function(totalInput) {
            grandTotal += parseRupiah(totalInput.value);
        });
        document.getElementById('total_keseluruhan').value = formatRupiah(grandTotal);
        calculateSisa(); // Panggil kalkulasi sisa setiap total berubah
    }

    // Fungsi untuk menghitung sisa pembayaran
    function calculateSisa() {
        const grandTotal = parseRupiah(document.getElementById('total_keseluruhan').value);
        const uangMuka = parseRupiah(document.getElementById('uang_muka').value);
        const diskon = parseRupiah(document.getElementById('diskon').value);

        let sisa = grandTotal - uangMuka - diskon;
        if (sisa < 0) sisa = 0;

        document.getElementById('sisa').value = formatRupiah(sisa);
    }


    // =================================================================================
    // BAGIAN 3: PENGATURAN EVENT LISTENER PADA SETIAP BARIS PRODUK
    // =================================================================================

    function initializeProdukRow(row) {
        // Ambil semua elemen input dari baris
        const produkSelect = row.querySelector('.produk-name');
        const produkIdInput = row.querySelector('.produk-id');
        const qtyInput = row.querySelector('.item-qty');
        const priceInput = row.querySelector('.item-price');
        const totalInput = row.querySelector('.item-total');

        // Fungsi untuk menghitung total per item
        function calculateItemTotal() {
            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseRupiah(priceInput.value);
            const itemTotal = qty * price;
            totalInput.value = formatRupiah(itemTotal);
            calculateGrandTotal(); // Hitung ulang total keseluruhan
        }

        // Event listener untuk perubahan pilihan produk (dropdown)
        produkSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.value) {
                produkIdInput.value = selectedOption.dataset.id || '';
                // Set harga dan format langsung
                priceInput.value = formatRupiah(parseFloat(selectedOption.dataset.harga) || 0);
            } else {
                produkIdInput.value = '';
                priceInput.value = formatRupiah(0);
            }
            calculateItemTotal();
        });

        // Event listener untuk Qty dan Harga
        qtyInput.addEventListener('input', calculateItemTotal);
        priceInput.addEventListener('input', function() {
            // Saat user mengetik harga, format langsung
            const numericValue = parseRupiah(this.value);
            this.value = formatRupiah(numericValue);
            calculateItemTotal();
        });
        // Format harga saat kehilangan fokus juga
        priceInput.addEventListener('blur', function() {
            const numericValue = parseRupiah(this.value);
            this.value = formatRupiah(numericValue);
        });

        // Inisialisasi format harga dan kalkulasi total untuk baris ini
        priceInput.value = formatRupiah(parseRupiah(priceInput.value));
        calculateItemTotal();
    }


    // =================================================================================
    // BAGIAN 4: INISIALISASI HALAMAN DAN EVENT UTAMA
    // =================================================================================

    // Inisialisasi semua baris produk yang sudah ada saat halaman dimuat
    document.querySelectorAll('.produk-item').forEach(row => initializeProdukRow(row));

    // Event listener untuk Uang Muka dan Diskon
    const uangMukaInput = document.getElementById('uang_muka');
    const diskonInput = document.getElementById('diskon');

    [uangMukaInput, diskonInput].forEach(input => {
        input.addEventListener('input', function() {
            const numericValue = parseRupiah(this.value);
            this.value = formatRupiah(numericValue);
            calculateSisa();
        });
        input.addEventListener('blur', function() {
            this.value = formatRupiah(parseRupiah(this.value));
        });
        // Format nilai awal saat halaman dimuat
        input.value = formatRupiah(parseRupiah(input.value));
    });

    // Event listener untuk tombol 'Tambah Baris Produk'
    let produkItemIndex = document.querySelectorAll('.produk-item').length;
    document.getElementById('add-produk-item').addEventListener('click', function() {
        fetch('/transaksi/get-produk-item-row?index=' + produkItemIndex)
            .then(response => response.text())
            .then(html => {
                const container = document.getElementById('produk-items-container');
                container.insertAdjacentHTML('beforeend', html);
                const newRow = container.lastElementChild;
                initializeProdukRow(newRow);
                produkItemIndex++;
                calculateGrandTotal();
            })
            .catch(error => console.error('Error adding product row:', error));
    });

    // Event listener untuk menghapus baris produk
    document.getElementById('produk-items-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-produk-item')) {
            e.target.closest('.produk-item').remove();
            calculateGrandTotal();
        }
    });

    // Inisialisasi info pelanggan
    const pelangganSelect = document.getElementById('pelanggan_id');
    function updatePelangganInfo() {
        const selectedOption = pelangganSelect.options[pelangganSelect.selectedIndex];
        document.getElementById('alamat_pelanggan').value = selectedOption ? selectedOption.dataset.alamat || '' : '';
        document.getElementById('telp_pelanggan').value = selectedOption ? selectedOption.dataset.telp || '' : '';
    }
    pelangganSelect.addEventListener('change', updatePelangganInfo);
    updatePelangganInfo(); // Panggil saat awal
    
    // Panggil kalkulasi final saat halaman selesai dimuat
    calculateGrandTotal();
});
</script>
@endpush