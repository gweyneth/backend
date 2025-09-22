@extends('layouts.app')

@section('content_header')
<div class="row mb-3 align-items-center">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Transaksi Baru</h1>
    </div>
    <div class="col-sm-6 text-end">
        <ol class="breadcrumb float-sm-right mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Transaksi</a></li>
            <li class="breadcrumb-item active">Transaksi Baru</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-primary text-white rounded-top">
            <h5 class="mb-0">Form Transaksi Baru #{{ $nextNoTransaksi }}</h5>
        </div>
        <div class="card-body p-4">
            {{-- Tampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <h5 class="alert-heading">Terdapat Kesalahan Input!</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('transaksi.store') }}" method="POST" id="transaksi-form">
                @csrf
                <input type="hidden" name="no_transaksi" value="{{ $nextNoTransaksi }}">

                <div class="row g-4">
                    <div class="col-md-4">
                        <h6 class="mb-3 text-muted">Data Pemesan</h6>
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Nama Pemesan</label>
                            {{-- PERBAIKAN: Menambahkan kembali class 'form-select' --}}
                            <select name="pelanggan_id" id="pelanggan_id" class="form-select @error('pelanggan_id') is-invalid @enderror" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($pelanggan as $item)
                                <option value="{{ $item->id }}" data-alamat="{{ $item->alamat }}" data-telp="{{ $item->no_hp }}" {{ old('pelanggan_id') == $item->id ? 'selected' : '' }}>
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
                            <input type="date" name="tanggal_order" id="tanggal_order" class="form-control @error('tanggal_order') is-invalid @enderror" value="{{ old('tanggal_order', date('Y-m-d')) }}" required>
                            @error('tanggal_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-8">
                        <h6 class="mb-3 text-muted">Detail Produk</h6>
                        <div id="produk-items-container" class="mb-3">
                            {{-- Pastikan file partial `produk_item_row` ada dan benar --}}
                            @include('pages.transaksi.produk_item_row', ['index' => 0, 'produks' => $produks])
                        </div>
                        <button type="button" class="btn btn-success btn-sm mb-4" id="add-produk-item">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>

                        <div class="border rounded p-3 bg-light">
                            <div class="mb-3 row align-items-center">
                                <label for="total_keseluruhan" class="col-sm-4 col-form-label">Total Keseluruhan</label>
                                <div class="col-sm-8">
                                    {{-- PERBAIKAN: Menambahkan kembali class 'form-control' --}}
                                    <input type="text" id="total_keseluruhan" name="total_keseluruhan" class="form-control bg-white input-currency" value="{{ old('total_keseluruhan', 0) }}" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="uang_muka" class="col-sm-4 col-form-label">Uang Muka</label>
                                <div class="col-sm-8">
                                    {{-- PERBAIKAN: Menambahkan kembali class 'form-control' --}}
                                    <input type="text" id="uang_muka" name="uang_muka" class="form-control input-currency @error('uang_muka') is-invalid @enderror" value="{{ old('uang_muka', 0) }}">
                                    @error('uang_muka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="diskon" class="col-sm-4 col-form-label">Diskon</label>
                                <div class="col-sm-8">
                                    {{-- PERBAIKAN: Menambahkan kembali class 'form-control' --}}
                                    <input type="text" id="diskon" name="diskon" class="form-control input-currency @error('diskon') is-invalid @enderror" value="{{ old('diskon', 0) }}">
                                    @error('diskon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="sisa" class="col-sm-4 col-form-label">Sisa Pembayaran</label>
                                <div class="col-sm-8">
                                    {{-- PERBAIKAN: Menambahkan kembali class 'form-control' --}}
                                    <input type="text" id="sisa" name="sisa" class="form-control bg-white input-currency" value="{{ old('sisa', 0) }}" readonly>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <label for="status_pengerjaan" class="col-sm-4 col-form-label">Status Pengerjaan</label>
                                <div class="col-sm-8">
                                    {{-- PERBAIKAN: Menambahkan kembali class 'form-select' --}}
                                    <select name="status_pengerjaan" id="status_pengerjaan" class="form-select @error('status_pengerjaan') is-invalid @enderror" required>
                                        <option value="menunggu export" {{ old('status_pengerjaan') == 'menunggu export' ? 'selected' : '' }}>Menunggu Export</option>
                                        <option value="belum dikerjakan" {{ old('status_pengerjaan') == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                                        <option value="proses desain" {{ old('status_pengerjaan') == 'proses desain' ? 'selected' : '' }}>Proses Desain</option>
                                        <option value="proses produksi" {{ old('status_pengerjaan') == 'proses produksi' ? 'selected' : '' }}>Proses Produksi</option>
                                        <option value="selesai" {{ old('status_pengerjaan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> Simpan Transaksi
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library untuk format mata uang otomatis --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let produkItemIndex = document.querySelectorAll('.produk-item').length;
        const cleaveInstances = {};

        function initCleave(element) {
            if (!element || cleaveInstances[element.id]) return;
            
            const id = element.id || `cleave-${Date.now()}-${Math.random()}`;
            element.id = id;

            cleaveInstances[element.id] = new Cleave(element, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                delimiter: '.'
            });
        }

        function getRawValue(element) {
            if (element && cleaveInstances[element.id]) {
                return cleaveInstances[element.id].getRawValue() || 0;
            }
            if (element) {
                return parseFloat(element.value.replace(/\./g, '')) || 0;
            }
            return 0;
        }

        function calculateGrandTotalAndRemaining() {
            let grandTotal = 0;
            document.querySelectorAll('.produk-item').forEach(row => {
                const totalEl = row.querySelector('.item-total');
                grandTotal += parseFloat(getRawValue(totalEl));
            });
            
            const totalKeseluruhanEl = document.getElementById('total_keseluruhan');
            cleaveInstances[totalKeseluruhanEl.id].setRawValue(grandTotal);

            const uangMuka = getRawValue(document.getElementById('uang_muka'));
            const diskon = getRawValue(document.getElementById('diskon'));

            let sisa = grandTotal - uangMuka - diskon;
            sisa = sisa < 0 ? 0 : sisa;

            cleaveInstances[document.getElementById('sisa').id].setRawValue(sisa);
        }

        function calculateItemTotal(rowElement) {
            const qtyInput = rowElement.querySelector('.item-qty');
            const priceInput = rowElement.querySelector('.item-price');
            const totalInput = rowElement.querySelector('.item-total');

            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(getRawValue(priceInput));
            const total = qty * price;

            cleaveInstances[totalInput.id].setRawValue(total);
            calculateGrandTotalAndRemaining();
        }

        function initializeProdukRow(row) {
            ['.item-price', '.item-total'].forEach(selector => initCleave(row.querySelector(selector)));
            
            const produkSelect = row.querySelector('.produk-name');
            const qtyInput = row.querySelector('.item-qty');
            const priceInput = row.querySelector('.item-price');

            // PERBAIKAN: Event listener untuk dropdown produk diubah menjadi 'change'
            produkSelect.addEventListener('change', () => {
                const selectedOption = produkSelect.options[produkSelect.selectedIndex];
                const ukuranInput = row.querySelector('.produk-ukuran');
                const satuanInput = row.querySelector('.produk-satuan');

                if (selectedOption && selectedOption.value) {
                    if (ukuranInput) ukuranInput.value = selectedOption.dataset.ukuran || '';
                    if (satuanInput) satuanInput.value = selectedOption.dataset.satuan || '';
                    cleaveInstances[priceInput.id].setRawValue(selectedOption.dataset.harga || 0);
                } else {
                    if (ukuranInput) ukuranInput.value = '';
                    if (satuanInput) satuanInput.value = '';
                    cleaveInstances[priceInput.id].setRawValue(0);
                }
                calculateItemTotal(row);
            });

            // Event listener untuk qty dan harga tetap 'input'
            qtyInput.addEventListener('input', () => calculateItemTotal(row));
            priceInput.addEventListener('input', () => calculateItemTotal(row));
            
            // Panggil event 'change' sekali untuk memuat data awal jika produk sudah terpilih
            if (produkSelect.value) {
                produkSelect.dispatchEvent(new Event('change'));
            }
        }

        // Inisialisasi cleave untuk input global
        ['.input-currency'].forEach(selector => document.querySelectorAll(selector).forEach(el => initCleave(el)));

        // Inisialisasi semua baris produk yang ada saat halaman dimuat
        document.querySelectorAll('.produk-item').forEach(row => initializeProdukRow(row));

        // Event listener untuk input global (uang muka, diskon)
        ['uang_muka', 'diskon'].forEach(id => {
            document.getElementById(id).addEventListener('input', calculateGrandTotalAndRemaining);
        });

        // Event listener untuk menambah baris
        document.getElementById('add-produk-item').addEventListener('click', function() {
            fetch(`/transaksi/get-produk-item-row?index=${produkItemIndex}`)
                .then(response => response.text())
                .then(html => {
                    const container = document.getElementById('produk-items-container');
                    container.insertAdjacentHTML('beforeend', html);
                    const newRow = container.lastElementChild;
                    initializeProdukRow(newRow);
                    produkItemIndex++;
                });
        });

        // Event listener untuk menghapus baris (menggunakan event delegation)
        document.getElementById('produk-items-container').addEventListener('click', function(e) {
            if (e.target && e.target.closest('.remove-produk-item')) {
                e.target.closest('.produk-item').remove();
                calculateGrandTotalAndRemaining();
            }
        });

        // Kalkulasi awal saat halaman dimuat
        calculateGrandTotalAndRemaining();
        
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