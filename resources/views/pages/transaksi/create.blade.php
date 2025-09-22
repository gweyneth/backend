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
            <form action="{{ route('transaksi.store') }}" method="POST" id="transaksi-form">
                @csrf
                <input type="hidden" name="no_transaksi" value="{{ $nextNoTransaksi }}">

                <div class="row g-4">
                    <div class="col-md-4">
                        <h6 class="mb-3 text-muted">Data Pemesan</h6>
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Nama Pemesan</label>
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
                        <p class="text-muted small">
                            <strong>Penting:</strong> Pastikan Anda juga mengubah input `qty` dan `harga` menjadi `type="text"` di dalam file `produk_item_row.blade.php` Anda.
                        </p>
                        <div id="produk-items-container" class="mb-3">
                            @include('pages.transaksi.produk_item_row', ['index' => 0, 'produks' => $produks])
                        </div>
                        <button type="button" class="btn btn-success btn-sm mb-4" id="add-produk-item">
                            <i class="fas fa-plus"></i> Tambah Baris
                        </button>

                        <div class="border rounded p-3 bg-light">
                            <div class="mb-3 row align-items-center">
                                <label for="total_keseluruhan" class="col-sm-4 col-form-label">Total Keseluruhan</label>
                                <div class="col-sm-8">
                                    <input type="text" id="total_keseluruhan" name="total_keseluruhan" class="form-control bg-white input-currency" value="{{ old('total_keseluruhan', 0) }}" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="uang_muka" class="col-sm-4 col-form-label">Uang Muka</label>
                                <div class="col-sm-8">
                                    <input type="text" id="uang_muka" name="uang_muka" class="form-control input-currency @error('uang_muka') is-invalid @enderror" value="{{ old('uang_muka', 0) }}">
                                    @error('uang_muka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="diskon" class="col-sm-4 col-form-label">Diskon</label>
                                <div class="col-sm-8">
                                    <input type="text" id="diskon" name="diskon" class="form-control input-currency @error('diskon') is-invalid @enderror" value="{{ old('diskon', 0) }}">
                                    @error('diskon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="sisa" class="col-sm-4 col-form-label">Sisa Pembayaran</label>
                                <div class="col-sm-8">
                                    <input type="text" id="sisa" name="sisa" class="form-control bg-white input-currency" value="{{ old('sisa', 0) }}" readonly>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <label for="status_pengerjaan" class="col-sm-4 col-form-label">Status Pengerjaan</label>
                                <div class="col-sm-8">
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
    // Inisialisasi indeks untuk baris produk baru
    let produkItemIndex = {{ old('nama_produk') ? count(old('nama_produk')) : (isset($transaksi) ? $transaksi->transaksiDetails->count() : 1) }};

    // Objek untuk menyimpan semua instance Cleave.js agar nilainya bisa dibaca
    const cleaveInstances = {};

    // Fungsi untuk menginisialisasi Cleave pada sebuah elemen
    function initCleave(selector) {
        document.querySelectorAll(selector).forEach(el => {
            const id = el.id || `cleave-${Date.now()}-${Math.random()}`;
            el.id = id;
            if (cleaveInstances[id]) {
                cleaveInstances[id].destroy(); // Hancurkan instance lama jika ada
            }

            cleaveInstances[id] = new Cleave(el, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                delimiter: '.'
            });
        });
    }

    // Fungsi untuk mendapatkan nilai angka mentah dari input
    function getRawValue(elementId) {
        if (cleaveInstances[elementId]) {
            return cleaveInstances[elementId].getRawValue() || 0;
        }
        const el = document.getElementById(elementId);
        // Fallback untuk elemen non-cleave
        return el ? parseFloat(el.value.replace(/\./g, '').replace(/,/g, '.')) || 0 : 0;
    }


    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Cleave untuk semua input mata uang yang ada
        initCleave('.input-currency');

        const pelangganSelect = document.getElementById('pelanggan_id');
        const alamatPelangganInput = document.getElementById('alamat_pelanggan');
        const telpPelangganInput = document.getElementById('telp_pelanggan');

        function updatePelangganInfo() {
            const selectedOption = pelangganSelect.options[pelangganSelect.selectedIndex];
            alamatPelangganInput.value = selectedOption ? (selectedOption.dataset.alamat || '') : '';
            telpPelangganInput.value = selectedOption ? (selectedOption.dataset.telp || '') : '';
        }

        pelangganSelect.addEventListener('change', updatePelangganInfo);
        updatePelangganInfo();

        function calculateGrandTotalAndRemaining() {
            let grandTotal = 0;
            document.querySelectorAll('.produk-item').forEach(row => {
                const totalEl = row.querySelector('.item-total');
                if (totalEl) { // Pastikan elemen ada
                    grandTotal += parseFloat(getRawValue(totalEl.id)) || 0;
                }
            });

            // Update total keseluruhan
            const totalKeseluruhanEl = document.getElementById('total_keseluruhan');
            if (cleaveInstances[totalKeseluruhanEl.id]) {
                cleaveInstances[totalKeseluruhanEl.id].setRawValue(grandTotal);
            } else {
                totalKeseluruhanEl.value = grandTotal;
            }

            const uangMuka = parseFloat(getRawValue('uang_muka')) || 0;
            const diskon = parseFloat(getRawValue('diskon')) || 0;

            let sisa = grandTotal - uangMuka - diskon;
            sisa = sisa < 0 ? 0 : sisa;

            // Update sisa
            const sisaEl = document.getElementById('sisa');
            if (cleaveInstances[sisaEl.id]) {
                cleaveInstances[sisaEl.id].setRawValue(sisa);
            } else {
                sisaEl.value = sisa;
            }
        }

        function calculateItemTotal(rowElement) {
            const qtyInput = rowElement.querySelector('.item-qty');
            const priceInput = rowElement.querySelector('.item-price');
            const totalInput = rowElement.querySelector('.item-total');

            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(getRawValue(priceInput.id)) || 0;
            const total = qty * price;

            if (cleaveInstances[totalInput.id]) {
                cleaveInstances[totalInput.id].setRawValue(total);
            } else {
                totalInput.value = total;
            }
            calculateGrandTotalAndRemaining();
        }

        function initializeProdukRow(row) {
            // Inisialisasi cleave untuk input harga & total di baris ini
            initCleave(`#${row.querySelector('.item-price').id}`);
            initCleave(`#${row.querySelector('.item-total').id}`);

            const inputsToWatch = ['.item-qty', '.item-price', '.produk-name'];
            inputsToWatch.forEach(selector => {
                row.querySelector(selector).addEventListener('input', () => {
                    if (selector === '.produk-name') {
                        const selectedOption = row.querySelector('.produk-name').options[row.querySelector('.produk-name').selectedIndex];
                        const priceEl = row.querySelector('.item-price');
                        if (selectedOption && selectedOption.dataset.harga) {
                            if (cleaveInstances[priceEl.id]) {
                                cleaveInstances[priceEl.id].setRawValue(selectedOption.dataset.harga);
                            } else {
                                priceEl.value = selectedOption.dataset.harga;
                            }
                        }
                    }
                    calculateItemTotal(row);
                });
            });

            // Panggil kalkulasi awal untuk baris ini
            calculateItemTotal(row);
        }

        // Event listener untuk input global (uang muka, diskon)
        ['uang_muka', 'diskon'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', calculateGrandTotalAndRemaining);
            }
        });

        // Inisialisasi semua baris yang ada saat halaman dimuat
        document.querySelectorAll('.produk-item').forEach(row => {
            initializeProdukRow(row);
        });

        document.getElementById('add-produk-item').addEventListener('click', function() {
            fetch('/transaksi/get-produk-item-row?index=' + produkItemIndex)
                .then(response => response.text())
                .then(html => {
                    const container = document.getElementById('produk-items-container');
                    container.insertAdjacentHTML('beforeend', html);
                    const newRow = container.lastElementChild;
                    initializeProdukRow(newRow);
                    produkItemIndex++;
                });
        });

        document.getElementById('produk-items-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-produk-item')) {
                e.target.closest('.produk-item').remove();
                calculateGrandTotalAndRemaining();
            }
        });

        calculateGrandTotalAndRemaining(); // Hitung pertama kali saat halaman dimuat
    });
</script>
@endpush