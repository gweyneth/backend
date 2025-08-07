@extends('layouts.app')

@section('content_header')
<div class="row mb-3 align-items-center">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Transaksi Baru</h1>
    </div>
    <div class="col-sm-6 text-end">
        <ol class="breadcrumb float-sm-right mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Transaksi Baru</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-primary text-white rounded-top">
            <h5 class="mb-0">Transaksi Baru #{{ $nextNoTransaksi }}</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('transaksi.store') }}" method="POST" id="transaksi-form">
                @csrf
                <input type="hidden" name="no_transaksi" value="{{ $nextNoTransaksi }}">

                <div class="row g-4">
                    <!-- Bagian Data Pelanggan -->
                    <div class="col-md-4">
                        <h6 class="mb-3 text-muted">Data Pemesan</h6>
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Nama Pemesan</label>
                            <select name="pelanggan_id" id="pelanggan_id"
                                class="form-select @error('pelanggan_id') is-invalid @enderror" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($pelanggan as $item)
                                <option value="{{ $item->id }}" data-alamat="{{ $item->alamat }}"
                                    data-telp="{{ $item->no_hp }}"
                                    {{ old('pelanggan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}
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
                            <input type="date" name="tanggal_order" id="tanggal_order"
                                class="form-control @error('tanggal_order') is-invalid @enderror" required
                                value="{{ old('tanggal_order', date('Y-m-d')) }}">
                            @error('tanggal_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Bagian Detail Produk -->
                    <div class="col-md-8">
                        <h6 class="mb-3 text-muted">Detail Produk</h6>
                        <div id="produk-items-container" class="mb-3">
                            @include('pages.transaksi.produk_item_row', ['index' => 0, 'produks' => $produks])
                        </div>
                        <button type="button" class="btn btn-success btn-sm mb-4" id="add-produk-item">
                            <i class="bi bi-plus-lg"></i> Tambah Baris Produk
                        </button>

                        <!-- Ringkasan dan Perhitungan -->
                        <div class="border rounded p-3 bg-light">
                            <div class="mb-3 row align-items-center">
                                <label for="total_keseluruhan" class="col-sm-4 col-form-label">Total Keseluruhan</label>
                                <div class="col-sm-8">
                                    <input type="text" id="total_keseluruhan" name="total_keseluruhan"
                                        class="form-control bg-white" value="{{ old('total_keseluruhan', 0) }}" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="uang_muka" class="col-sm-4 col-form-label">Uang Muka</label>
                                <div class="col-sm-8">
                                    <input type="number" id="uang_muka" name="uang_muka" min="0" step="0.01"
                                        class="form-control" value="{{ old('uang_muka', 0) }}">
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="diskon" class="col-sm-4 col-form-label">Diskon</label>
                                <div class="col-sm-8">
                                    <input type="number" id="diskon" name="diskon" min="0" step="0.01"
                                        class="form-control" value="{{ old('diskon', 0) }}">
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="sisa" class="col-sm-4 col-form-label">Sisa Pembayaran</label>
                                <div class="col-sm-8">
                                    <input type="text" id="sisa" name="sisa" class="form-control bg-white"
                                        value="{{ old('sisa', 0) }}" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label for="status_pengerjaan" class="col-sm-4 col-form-label">Status Pengerjaan</label>
                                <div class="col-sm-8">
                                    <select name="status_pengerjaan" id="status_pengerjaan"
                                        class="form-select @error('status_pengerjaan') is-invalid @enderror" required>
                                        <option value="menunggu export"
                                            {{ old('status_pengerjaan') == 'menunggu export' ? 'selected' : '' }}>Menunggu Export
                                        </option>
                                        <option value="belum dikerjakan"
                                            {{ old('status_pengerjaan') == 'belum dikerjakan' ? 'selected' : '' }}>Belum Dikerjakan
                                        </option>
                                        <option value="proses desain"
                                            {{ old('status_pengerjaan') == 'proses desain' ? 'selected' : '' }}>Proses Desain
                                        </option>
                                        <option value="proses produksi"
                                            {{ old('status_pengerjaan') == 'proses produksi' ? 'selected' : '' }}>Proses Produksi
                                        </option>
                                        <option value="selesai" {{ old('status_pengerjaan') == 'selesai' ? 'selected' : '' }}>Selesai
                                        </option>
                                    </select>
                                    @error('status_pengerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan dan Batal -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Simpan Transaksi
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-x-lg"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Pastikan icon Bootstrap digunakan jika pakai icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

<script>
    let produkItemIndex = {{ old('nama_produk') ? count(old('nama_produk')) : 1 }};

    document.addEventListener('DOMContentLoaded', function() {
        // Update info pelanggan saat berubah
        const pelangganSelect = document.getElementById('pelanggan_id');
        const alamatPelangganInput = document.getElementById('alamat_pelanggan');
        const telpPelangganInput = document.getElementById('telp_pelanggan');

        function updatePelangganInfo() {
            const selectedOption = pelangganSelect.options[pelangganSelect.selectedIndex];
            if (selectedOption) {
                alamatPelangganInput.value = selectedOption.dataset.alamat || '';
                telpPelangganInput.value = selectedOption.dataset.telp || '';
            } else {
                alamatPelangganInput.value = '';
                telpPelangganInput.value = '';
            }
        }

        pelangganSelect.addEventListener('change', updatePelangganInfo);
        updatePelangganInfo();

        // Format rupiah
        function formatRupiah(angka) {
            if (angka === null || angka === undefined || isNaN(angka)) {
                return 'Rp 0';
            }
            var reverse = angka.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + ribuan;
        }

        // Hitung total dan sisa
        function calculateGrandTotalAndRemaining() {
            let grandTotal = 0;
            document.querySelectorAll('.item-total').forEach(function(element) {
                grandTotal += parseFloat(element.value.replace(/[^0-9,-]+/g,"").replace(",", ".")) || 0;
            });
            document.getElementById('total_keseluruhan').value = formatRupiah(grandTotal);

            const uangMuka = parseFloat(document.getElementById('uang_muka').value) || 0;
            const diskon = parseFloat(document.getElementById('diskon').value) || 0;
            let sisa = grandTotal - uangMuka - diskon;
            if (sisa < 0) sisa = 0;
            document.getElementById('sisa').value = formatRupiah(sisa);
        }

        // Event input perubahan
        document.getElementById('uang_muka').addEventListener('input', calculateGrandTotalAndRemaining);
        document.getElementById('diskon').addEventListener('input', calculateGrandTotalAndRemaining);

        // Tambah baris produk
        document.getElementById('add-produk-item').addEventListener('click', function() {
            const container = document.getElementById('produk-items-container');
            fetch('/transaksi/get-produk-item-row?index=' + produkItemIndex)
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.text();
                })
                .then(html => {
                    container.insertAdjacentHTML('beforeend', html);
                    initializeProdukRow(produkItemIndex);
                    produkItemIndex++;
                    calculateGrandTotalAndRemaining();
                })
                .catch(error => {
                    console.error('Error adding product row:', error);
                    Swal.fire('Gagal', 'Gagal menambahkan baris produk.', 'error');
                });
        });

        // Hapus baris produk
        document.getElementById('produk-items-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-produk-item') || e.target.closest('.remove-produk-item')) {
                const row = e.target.closest('.produk-item');
                if (row) {
                    row.remove();
                    calculateGrandTotalAndRemaining();
                }
            }
        });

        // Inisialisasi baris produk
        function initializeProdukRow(index) {
            const row = document.querySelector(`.produk-item[data-index="${index}"]`);
            if (!row) return;

            const produkSelect = row.querySelector('.produk-name');
            const produkIdInput = row.querySelector('.produk-id');
            const produkUkuranInput = row.querySelector('.produk-ukuran');
            const produkSatuanInput = row.querySelector('.produk-satuan');
            const itemQtyInput = row.querySelector('.item-qty');
            const itemPriceInput = row.querySelector('.item-price');
            const itemTotalInput = row.querySelector('.item-total');

            produkSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    produkIdInput.value = selectedOption.dataset.id || '';
                    produkUkuranInput.value = selectedOption.dataset.ukuran || '';
                    produkSatuanInput.value = selectedOption.dataset.satuan || '';
                    itemPriceInput.value = parseFloat(selectedOption.dataset.harga) || 0;
                } else {
                    produkIdInput.value = '';
                    produkUkuranInput.value = '';
                    produkSatuanInput.value = '';
                    itemPriceInput.value = 0;
                }
                calculateItemTotal(row);
            });

            // Hitung total item saat qty atau harga diubah
            row.querySelector('.item-qty').addEventListener('input', () => calculateItemTotal(row));
            row.querySelector('.item-price').addEventListener('input', () => calculateItemTotal(row));

            function calculateItemTotal(rowElement) {
                const qty = parseFloat(rowElement.querySelector('.item-qty').value) || 0;
                const price = parseFloat(rowElement.querySelector('.item-price').value) || 0;
                const total = qty * price;
                rowElement.querySelector('.item-total').value = formatRupiah(total);
                calculateGrandTotalAndRemaining();
            }

            // Inisialisasi
            calculateItemTotal(row);
            if (produkSelect.value) {
                produkSelect.dispatchEvent(new Event('change'));
            }
        }

        // Initialize semua baris yang ada
        document.querySelectorAll('.produk-item').forEach(row => {
            const index = row.dataset.index;
            initializeProdukRow(index);
        });

        // Hitung total awal
        calculateGrandTotalAndRemaining();
    });
</script>
@endpush