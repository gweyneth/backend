@extends('layouts.app')

@section('content_header')
<div class="row mb-3 align-items-center">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Transaksi</h1>
    </div>
    <div class="col-sm-6 text-end">
        <ol class="breadcrumb float-sm-right mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Log Transaksi</a></li>
            <li class="breadcrumb-item active">Edit Transaksi</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-primary text-white rounded-top">
            <h5 class="mb-0">Edit Transaksi #{{ $transaksi->no_transaksi }}</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="transaksi-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="no_transaksi" value="{{ $transaksi->no_transaksi }}">

                <div class="row g-4">
                    <div class="col-md-4">
                        <h6 class="mb-3 text-muted">Data Pemesan</h6>
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Nama Pemesan</label>
                            <select name="pelanggan_id" id="pelanggan_id" class="form-control @error('pelanggan_id') is-invalid @enderror" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($pelanggan as $item)
                                <option value="{{ $item->id }}" data-alamat="{{ $item->alamat }}" data-telp="{{ $item->no_hp }}" {{ old('pelanggan_id', $transaksi->pelanggan_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('pelanggan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="alamat_pelanggan" class="form-label">Alamat</label>
                            <input type="text" id="alamat_pelanggan" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="telp_pelanggan" class="form-label">Telp</label>
                            <input type="text" id="telp_pelanggan" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_order" class="form-label">Tanggal Order</label>
                            <input type="date" name="tanggal_order" id="tanggal_order" class="form-control @error('tanggal_order') is-invalid @enderror" value="{{ old('tanggal_order', $transaksi->tanggal_order->format('Y-m-d')) }}" required>
                            @error('tanggal_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $transaksi->tanggal_selesai ? $transaksi->tanggal_selesai->format('Y-m-d') : '') }}">
                            @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-8">
                        <h6 class="mb-3 text-muted">Detail Produk</h6>
                        <div id="produk-items-container">
                            @forelse ($transaksi->transaksiDetails as $index => $detail)
                                @include('pages.transaksi.produk_item_row', ['index' => $index, 'produks' => $produks, 'detail' => $detail])
                            @empty
                                @include('pages.transaksi.produk_item_row', ['index' => 0, 'produks' => $produks])
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-success btn-sm mt-2 mb-4" id="add-produk-item">
                            <i class="bi bi-plus-lg"></i> Tambah Baris Produk
                        </button>

                        <div class="border rounded p-3 bg-light">
                            <div class="mb-3 row align-items-center">
                                <label for="total_keseluruhan_display" class="col-sm-4 col-form-label">Total Keseluruhan</label>
                                <div class="col-sm-8">
                                    <input type="text" id="total_keseluruhan_display" class="form-control bg-white text-end fw-bold" readonly>
                                    <input type="hidden" id="total_keseluruhan" name="total_keseluruhan" value="{{ old('total_keseluruhan', $transaksi->total) }}">
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="uang_muka" class="col-sm-4 col-form-label">Uang Muka</label>
                                <div class="col-sm-8">
                                    <input type="number" id="uang_muka" name="uang_muka" min="0" class="form-control text-end" value="{{ old('uang_muka', $transaksi->uang_muka) }}">
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="diskon" class="col-sm-4 col-form-label">Diskon</label>
                                <div class="col-sm-8">
                                    <input type="number" id="diskon" name="diskon" min="0" class="form-control text-end" value="{{ old('diskon', $transaksi->diskon) }}">
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="sisa_display" class="col-sm-4 col-form-label">Sisa Pembayaran</label>
                                <div class="col-sm-8">
                                    <input type="text" id="sisa_display" class="form-control bg-white text-end fw-bold" readonly>
                                    <input type="hidden" id="sisa" name="sisa" value="{{ old('sisa', $transaksi->sisa) }}">
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <label for="status_pengerjaan" class="col-sm-4 col-form-label">Status Pengerjaan</label>
                                <div class="col-sm-8">
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
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">Update Transaksi</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- MENGGUNAKAN SCRIPT YANG SAMA DENGAN HALAMAN CREATE --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let produkItemIndex = document.querySelectorAll('.produk-item').length;
        const container = document.getElementById('produk-items-container');

        function formatRupiah(angka) {
            if (isNaN(angka)) return 'Rp 0';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        function parseRupiah(rupiahString) {
            if (!rupiahString) return 0;
            return parseInt(String(rupiahString).replace(/[^0-9]/g, ''), 10) || 0;
        }

        function calculateAllTotals() {
            let grandTotal = 0;
            document.querySelectorAll('.produk-item').forEach(row => {
                const totalVal = parseFloat(row.querySelector('.item-total').value) || 0;
                grandTotal += totalVal;
            });

            document.getElementById('total_keseluruhan').value = grandTotal;
            document.getElementById('total_keseluruhan_display').value = formatRupiah(grandTotal);

            const uangMuka = parseFloat(document.getElementById('uang_muka').value) || 0;
            const diskon = parseFloat(document.getElementById('diskon').value) || 0;
            let sisa = grandTotal - uangMuka - diskon;
            sisa = sisa < 0 ? 0 : sisa;

            document.getElementById('sisa').value = sisa.toFixed(0);
            document.getElementById('sisa_display').value = formatRupiah(sisa);
        }

        function initializeProdukRow(rowElement) {
            const produkSelect = rowElement.querySelector('.produk-name');
            const itemQtyInput = rowElement.querySelector('.item-qty');
            const itemPriceDisplay = rowElement.querySelector('.item-price-display');
            const itemPriceHidden = rowElement.querySelector('.item-price');

            // Inisialisasi format harga saat load
            itemPriceDisplay.value = formatRupiah(itemPriceHidden.value);

            const calculateItemTotal = () => {
                const qty = parseFloat(itemQtyInput.value) || 0;
                const price = parseFloat(rowElement.querySelector('.item-price').value) || 0;
                const total = qty * price;

                rowElement.querySelector('.item-total').value = total.toFixed(0);
                rowElement.querySelector('.item-total-display').value = formatRupiah(total);
                calculateAllTotals();
            };

            produkSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const row = this.closest('.produk-item');

                if (selectedOption && selectedOption.value) {
                    const harga = parseFloat(selectedOption.dataset.harga) || 0;
                    row.querySelector('.produk-id').value = selectedOption.dataset.id || '';
                    row.querySelector('.produk-ukuran').value = selectedOption.dataset.ukuran || '';
                    row.querySelector('.item-price').value = harga;
                    row.querySelector('.item-price-display').value = formatRupiah(harga);
                } else {
                    row.querySelector('.produk-id').value = '';
                    row.querySelector('.produk-ukuran').value = '';
                    row.querySelector('.item-price').value = 0;
                    row.querySelector('.item-price-display').value = formatRupiah(0);
                }
                calculateItemTotal();
            });

            itemQtyInput.addEventListener('input', calculateItemTotal);
            itemPriceDisplay.addEventListener('input', function() {
                const rawValue = parseRupiah(this.value);
                rowElement.querySelector('.item-price').value = rawValue;
                calculateItemTotal();
            });
            itemPriceDisplay.addEventListener('blur', function() {
                const rawValue = parseRupiah(this.value);
                this.value = formatRupiah(rawValue);
            });
        }

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-produk-item')) {
                e.target.closest('.produk-item').remove();
                calculateAllTotals();
            }
        });

        document.getElementById('uang_muka').addEventListener('input', calculateAllTotals);
        document.getElementById('diskon').addEventListener('input', calculateAllTotals);

        document.getElementById('add-produk-item').addEventListener('click', function() {
            fetch('/transaksi/get-produk-item-row?index=' + produkItemIndex)
                .then(response => response.text())
                .then(html => {
                    container.insertAdjacentHTML('beforeend', html);
                    const newRow = container.querySelector(`.produk-item[data-index="${produkItemIndex}"]`);
                    if (newRow) initializeProdukRow(newRow);
                    produkItemIndex++;
                })
                .catch(error => console.error('Error adding product row:', error));
        });

        // --- INISIALISASI HALAMAN EDIT ---
        document.querySelectorAll('.produk-item').forEach(initializeProdukRow);

        const pelangganSelect = document.getElementById('pelanggan_id');
        function updatePelangganInfo() {
            if (pelangganSelect.selectedIndex < 0) return;
            const selectedOption = pelangganSelect.options[pelangganSelect.selectedIndex];
            document.getElementById('alamat_pelanggan').value = selectedOption.dataset.alamat || '';
            document.getElementById('telp_pelanggan').value = selectedOption.dataset.telp || '';
        }
        pelangganSelect.addEventListener('change', updatePelangganInfo);
        
        // Panggil semua fungsi kalkulasi & update di awal
        updatePelangganInfo();
        calculateAllTotals();
    });
</script>
@endpush