@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-edit mr-2"></i>Edit Pengeluaran</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pengeluaran.index') }}">Data Pengeluaran</a></li>
            <li class="breadcrumb-item active">Edit Pengeluaran</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-pencil-alt mr-2"></i>Form Edit Pengeluaran
                </h3>
            </div>
            <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="jenis_pengeluaran">Jenis Pengeluaran <span class="text-danger">*</span></label>
                        <select name="jenis_pengeluaran" id="jenis_pengeluaran" class="form-control select2 @error('jenis_pengeluaran') is-invalid @enderror" required>
                            <option value="">Pilih Jenis Pengeluaran</option>
                            <option value="Kasbon Karyawan" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Kasbon Karyawan' ? 'selected' : '' }}>Kasbon Karyawan</option>
                            <option value="Uang Makan" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Uang Makan' ? 'selected' : '' }}>Uang Makan</option>
                            <option value="Token Listrik" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Token Listrik' ? 'selected' : '' }}>Token Listrik</option>
                            <option value="Air PDAM" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Air PDAM' ? 'selected' : '' }}>Air PDAM</option>
                            <option value="Modal" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Modal' ? 'selected' : '' }}>Modal</option>
                            <option value="Gaji Karyawan" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Gaji Karyawan' ? 'selected' : '' }}>Gaji Karyawan</option>
                            <option value="Beli Bahan" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Beli Bahan' ? 'selected' : '' }}>Beli Bahan</option>
                            <option value="Sumbangan" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Sumbangan' ? 'selected' : '' }}>Sumbangan</option>
                            <option value="Paket COD" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Paket COD' ? 'selected' : '' }}>Paket COD</option>
                            <option value="Perlengkapan" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Perlengkapan' ? 'selected' : '' }}>Perlengkapan</option>
                            <option value="Transportasi" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Transportasi' ? 'selected' : '' }}>Transportasi</option>
                            <option value="Lain-lain" {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                        @error('jenis_pengeluaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" id="karyawan_select_group" style="display: {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Kasbon Karyawan' ? 'block' : 'none' }};">
                        <label for="karyawan_id">Karyawan (untuk Kasbon)</label>
                        <select name="karyawan_id" id="karyawan_id" class="form-control select2 @error('karyawan_id') is-invalid @enderror">
                            <option value="">Pilih Karyawan</option>
                            @foreach ($karyawan as $item)
                                <option value="{{ $item->id }}" {{ old('karyawan_id', $pengeluaran->karyawan_id) == $item->id ? 'selected' : '' }}>{{ $item->nama_karyawan }} (NIK: {{ $item->nik ?? '-' }})</option>
                            @endforeach
                        </select>
                        @error('karyawan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3" required>{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $pengeluaran->jumlah) }}" min="1" required>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="harga">Harga (per unit) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $pengeluaran->harga) }}" min="0" required>
                                    @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="total">Total (Otomatis)</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                            <input type="text" id="total_display" class="form-control font-weight-bold" value="0" readonly>
                            <input type="hidden" name="total" id="total" value="{{ old('total', $pengeluaran->total) }}">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update Pengeluaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    const jenisPengeluaranSelect = document.getElementById('jenis_pengeluaran');
    const karyawanSelectGroup = document.getElementById('karyawan_select_group');
    const jumlahInput = document.getElementById('jumlah');
    const hargaInput = document.getElementById('harga');
    const totalInput = document.getElementById('total');
    const totalDisplay = document.getElementById('total_display');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function calculateTotal() {
        const jumlah = parseFloat(jumlahInput.value) || 0;
        const harga = parseFloat(hargaInput.value) || 0;
        const total = jumlah * harga;
        totalDisplay.value = formatRupiah(total);
        totalInput.value = total;
    }

    function toggleKaryawanSelect() {
        if (jenisPengeluaranSelect.value === 'Kasbon Karyawan') {
            karyawanSelectGroup.style.display = 'block';
        } else {
            karyawanSelectGroup.style.display = 'none';
        }
    }

    // Event listeners
    $('#jenis_pengeluaran').on('change', toggleKaryawanSelect);
    jumlahInput.addEventListener('input', calculateTotal);
    hargaInput.addEventListener('input', calculateTotal);

    // Panggil fungsi saat halaman dimuat untuk inisialisasi
    toggleKaryawanSelect();
    calculateTotal();
});
</script>
@endpush
