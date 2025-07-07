@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Transaksi Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Transaksi Baru</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Transaksi Baru #{{ $nextNoTransaksi }}</h5>
            </div>
            <div class="card-body">
                {{-- Menampilkan pesan sukses --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Menampilkan pesan error umum --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('transaksi.store') }}" method="POST" id="transaksi-form">
                    @csrf
                    <input type="hidden" name="no_transaksi" value="{{ $nextNoTransaksi }}">

                    <div class="row">
                        {{-- Kolom Kiri: Detail Transaksi Utama --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pelanggan_id">Nama Pemesan</label>
                                <select name="pelanggan_id" id="pelanggan_id" class="form-control @error('pelanggan_id') is-invalid @enderror">
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach ($pelanggan as $item)
                                        {{-- Menggunakan $item->nama sesuai klarifikasi database --}}
                                        {{-- Menggunakan $item->no_hp sesuai klarifikasi terbaru --}}
                                        <option value="{{ $item->id }}" data-alamat="{{ $item->alamat }}" data-telp="{{ $item->no_hp }}" {{ old('pelanggan_id') == $item->id ? 'selected' : '' }}>
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
                                <input type="date" name="tanggal_order" id="tanggal_order" class="form-control @error('tanggal_order') is-invalid @enderror" value="{{ old('tanggal_order', date('Y-m-d')) }}" required>
                                @error('tanggal_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_selesai">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}">
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Kolom Kanan: Detail Produk Transaksi --}}
                        <div class="col-md-6">
                            <h6>Detail Produk</h6>
                            <div id="produk-items-container">
                                {{-- Baris produk akan ditambahkan di sini oleh JavaScript --}}
                                {{-- Baris pertama di-include langsung saat halaman dimuat --}}
                                {{-- Menggunakan nama partial yang diklarifikasi: produk_item_row (tanpa underscore) --}}
                                @include('pages.transaksi.produk_item_row', ['index' => 0, 'produks' => $produks])
                            </div>
                            <button type="button" class="btn btn-success btn-sm mt-2" id="add-produk-item">Tambah Baris Produk</button>

                            <hr>

                            <div class="form-group">
                                <label for="total_keseluruhan">Total Keseluruhan</label>
                                <input type="text" name="total_keseluruhan" id="total_keseluruhan" class="form-control" value="{{ old('total_keseluruhan', 0) }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="uang_muka">Uang Muka</label>
                                <input type="number" name="uang_muka" id="uang_muka" class="form-control @error('uang_muka') is-invalid @enderror" value="{{ old('uang_muka', 0) }}" min="0" step="0.01">
                                @error('uang_muka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="diskon">Diskon</label>
                                <input type="number" name="diskon" id="diskon" class="form-control @error('diskon') is-invalid @enderror" value="{{ old('diskon', 0) }}" min="0" step="0.01">
                                @error('diskon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="sisa">Sisa Pembayaran</label>
                                <input type="text" name="sisa" id="sisa" class="form-control" value="{{ old('sisa', 0) }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="id_pelunasan">ID Pelunasan</label>
                                <input type="text" name="id_pelunasan" id="id_pelunasan" class="form-control @error('id_pelunasan') is-invalid @enderror" value="{{ old('id_pelunasan') }}">
                                @error('id_pelunasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status_pengerjaan">Status Pengerjaan</label>
                                <select name="status_pengerjaan" id="status_pengerjaan" class="form-control @error('status_pengerjaan') is-invalid @enderror" required>
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

                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<script>
    // Inisialisasi indeks produk, menggunakan PHP untuk nilai awal
    let produkItemIndex = {{ old('nama_produk') ? count(old('nama_produk')) : 1 }};

    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi data pelanggan jika ada old input
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
        // Panggil saat DOMContentLoaded untuk mengisi data jika ada old input
        updatePelangganInfo();

        // Fungsi untuk format Rupiah
        function formatRupiah(angka) {
            if (angka === null || angka === undefined || isNaN(angka)) {
                return 'Rp 0';
            }
            var reverse = angka.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + ribuan;
        }

        // Fungsi untuk menghitung total keseluruhan dan sisa pembayaran
        function calculateGrandTotalAndRemaining() {
            let grandTotal = 0;
            document.querySelectorAll('.item-total').forEach(function(element) {
                // Ambil nilai numerik dari input (tanpa format Rupiah)
                grandTotal += parseFloat(element.value.replace(/[^0-9,-]+/g,"").replace(",", ".")) || 0;
            });

            document.getElementById('total_keseluruhan').value = formatRupiah(grandTotal);

            const uangMuka = parseFloat(document.getElementById('uang_muka').value) || 0;
            const diskon = parseFloat(document.getElementById('diskon').value) || 0;

            let sisa = grandTotal - uangMuka - diskon;
            if (sisa < 0) sisa = 0; // Pastikan sisa tidak negatif

            document.getElementById('sisa').value = formatRupiah(sisa);
        }

        // Event listener untuk uang muka dan diskon
        document.getElementById('uang_muka').addEventListener('input', calculateGrandTotalAndRemaining);
        document.getElementById('diskon').addEventListener('input', calculateGrandTotalAndRemaining);


        // Fungsi untuk menambahkan baris produk baru
        document.getElementById('add-produk-item').addEventListener('click', function() {
            const container = document.getElementById('produk-items-container');
            // Mengubah URL fetch agar konsisten dengan nama partial yang diklarifikasi
            fetch('/transaksi/get-produk-item-row?index=' + produkItemIndex)
                .then(response => {
                    if (!response.ok) {
                        // Jika respons tidak OK (misal status 4xx atau 5xx), lempar error
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
                    alert('Gagal menambahkan baris produk. Silakan cek konsol browser untuk detail error.');
                });
        });

        // Fungsi untuk menghapus baris produk
        document.getElementById('produk-items-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-produk-item') || e.target.closest('.remove-produk-item')) {
                const row = e.target.closest('.produk-item');
                if (row) {
                    row.remove();
                    calculateGrandTotalAndRemaining();
                }
            }
        });

        // Fungsi untuk menginisialisasi event listener pada baris produk
        function initializeProdukRow(index) {
            const row = document.querySelector(`.produk-item[data-index="${index}"]`);
            if (!row) return;

            const produkSelect = row.querySelector('.produk-name');
            const produkIdInput = row.querySelector('.produk-id');
            const produkBahanInput = row.querySelector('.produk-bahan');
            const produkUkuranInput = row.querySelector('.produk-ukuran');
            const produkSatuanInput = row.querySelector('.produk-satuan');
            const itemQtyInput = row.querySelector('.item-qty');
            const itemPriceInput = row.querySelector('.item-price');
            const itemTotalInput = row.querySelector('.item-total');

            // Event listener untuk perubahan pilihan produk di select
            produkSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) { // Pastikan opsi dipilih dan bukan "Pilih Produk"
                    produkIdInput.value = selectedOption.dataset.id || '';
                    produkBahanInput.value = selectedOption.dataset.bahan || '';
                    produkUkuranInput.value = selectedOption.dataset.ukuran || '';
                    produkSatuanInput.value = selectedOption.dataset.satuan || '';
                    itemPriceInput.value = parseFloat(selectedOption.dataset.harga) || 0;
                } else {
                    // Reset jika tidak ada pilihan yang cocok atau "Pilih Produk" dipilih
                    produkIdInput.value = '';
                    produkBahanInput.value = '';
                    produkUkuranInput.value = '';
                    produkSatuanInput.value = '';
                    itemPriceInput.value = 0;
                }
                calculateItemTotal(row);
            });

            // Event listener untuk perubahan Qty dan Harga
            itemQtyInput.addEventListener('input', function() { calculateItemTotal(row); });
            itemPriceInput.addEventListener('input', function() { calculateItemTotal(row); });

            // Fungsi untuk menghitung total per item
            function calculateItemTotal(rowElement) {
                const qty = parseFloat(rowElement.querySelector('.item-qty').value) || 0;
                const price = parseFloat(rowElement.querySelector('.item-price').value) || 0;
                const itemTotal = qty * price;
                rowElement.querySelector('.item-total').value = itemTotal; // Simpan sebagai angka untuk perhitungan grand total
                rowElement.querySelector('.item-total').value = formatRupiah(itemTotal); // Tampilkan dalam format Rupiah
                calculateGrandTotalAndRemaining(); // Hitung ulang total keseluruhan
            }

            // Panggil perhitungan awal untuk baris ini
            calculateItemTotal(row);

            // Trigger change event pada select produk jika ada nilai yang sudah dipilih (misal dari old input)
            if (produkSelect.value) {
                produkSelect.dispatchEvent(new Event('change'));
            }
        }

        // Inisialisasi baris produk yang sudah ada (misalnya dari old input)
        document.querySelectorAll('.produk-item').forEach(function(row) {
            const index = row.dataset.index;
            initializeProdukRow(index);
        });

        // Panggil perhitungan total keseluruhan saat halaman dimuat
        calculateGrandTotalAndRemaining();
    });
</script>
@endpush
