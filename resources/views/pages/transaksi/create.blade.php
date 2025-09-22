@extends('layouts.app')

@section('content_header')
<div class="row mb-3 align-items-center">
    <div class="col-sm-6"><h1 class="m-0 text-dark">Transaksi Baru</h1></div>
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
        <div class="card-header bg-primary text-white rounded-top"><h5 class="mb-0">Transaksi Baru #{{ $nextNoTransaksi }}</h5></div>
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
                                    <option value="{{ $item->id }}" data-alamat="{{ $item->alamat }}" data-telp="{{ $item->no_hp }}" {{ old('pelanggan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @error('pelanggan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3"><label for="alamat_pelanggan" class="form-label">Alamat</label><input type="text" id="alamat_pelanggan" class="form-control" readonly></div>
                        <div class="mb-3"><label for="telp_pelanggan" class="form-label">Telp</label><input type="text" id="telp_pelanggan" class="form-control" readonly></div>
                        <div class="mb-3"><label for="tanggal_order" class="form-label">Tanggal Order</label><input type="date" name="tanggal_order" id="tanggal_order" class="form-control @error('tanggal_order') is-invalid @enderror" value="{{ old('tanggal_order', date('Y-m-d')) }}" required>@error('tanggal_order')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        <div class="mb-3"><label for="tanggal_selesai" class="form-label">Tanggal Selesai</label><input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}">@error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    </div>

                    <div class="col-md-8">
                        <h6 class="mb-3 text-muted">Detail Produk</h6>
                        <div id="produk-items-container" class="mb-3">
                            @include('pages.transaksi.produk_item_row', ['index' => 0, 'produks' => $produks])
                        </div>
                        <button type="button" class="btn btn-success btn-sm mb-4" id="add-produk-item"><i class="bi bi-plus-lg"></i> Tambah Baris Produk</button>

                        <div class="border rounded p-3 bg-light">
                            <div class="mb-3 row align-items-center"><label for="total_keseluruhan" class="col-sm-4 col-form-label">Total Keseluruhan</label><div class="col-sm-8"><input type="text" id="total_keseluruhan" name="total_keseluruhan" class="form-control bg-white" value="Rp 0" readonly></div></div>
                            <div class="mb-3 row align-items-center"><label for="uang_muka" class="col-sm-4 col-form-label">Uang Muka</label><div class="col-sm-8"><input type="text" id="uang_muka" name="uang_muka" class="form-control" value="0"></div></div>
                            <div class="mb-3 row align-items-center"><label for="diskon" class="col-sm-4 col-form-label">Diskon</label><div class="col-sm-8"><input type="text" id="diskon" name="diskon" class="form-control" value="0"></div></div>
                            <div class="mb-3 row align-items-center"><label for="sisa" class="col-sm-4 col-form-label">Sisa Pembayaran</label><div class="col-sm-8"><input type="text" id="sisa" name="sisa" class="form-control bg-white" value="Rp 0" readonly></div></div>
                            <div class="row align-items-center"><label for="status_pengerjaan" class="col-sm-4 col-form-label">Status Pengerjaan</label><div class="col-sm-8">
                                <select name="status_pengerjaan" id="status_pengerjaan" class="form-select @error('status_pengerjaan') is-invalid @enderror" required>
                                    <option value="menunggu export">Menunggu Export</option>
                                    <option value="belum dikerjakan">Belum Dikerjakan</option>
                                    <option value="proses desain">Proses Desain</option>
                                    <option value="proses produksi">Proses Produksi</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Simpan Transaksi</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary px-4"><i class="bi bi-x-lg"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let produkItemIndex = 1;

        const parseNumber = value => parseFloat(String(value).replace(/[^0-9]/g, '')) || 0;
        const formatRupiah = number => `Rp ${new Intl.NumberFormat('id-ID').format(number)}`;

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

        function initializeRow(row) {
            row.addEventListener('input', e => {
                if (e.target.matches('.produk-name')) {
                    const selected = e.target.options[e.target.selectedIndex];
                    const priceInput = row.querySelector('.item-price');
                    priceInput.value = formatRupiah(selected.dataset.harga || 0).replace('Rp ', '');
                }
                updateCalculations();
            });
            row.addEventListener('blur', e => {
                if(e.target.matches('.item-price, #uang_muka, #diskon')){
                    e.target.value = formatRupiah(parseNumber(e.target.value)).replace('Rp ', '');
                    updateCalculations();
                }
            }, true);
        }

        document.querySelectorAll('.produk-item').forEach(initializeRow);
        ['uang_muka', 'diskon'].forEach(id => {
            const el = document.getElementById(id);
            el.addEventListener('input', updateCalculations);
            el.addEventListener('blur', e => {
                 e.target.value = formatRupiah(parseNumber(e.target.value)).replace('Rp ', '');
            });
        });
        
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

        document.getElementById('produk-items-container').addEventListener('click', e => {
            if (e.target.closest('.remove-produk-item')) {
                e.target.closest('.produk-item').remove();
                updateCalculations();
            }
        });

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
