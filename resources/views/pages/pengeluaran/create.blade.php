@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Tambah Pengeluaran Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pengeluaran.index') }}">Data Pengeluaran</a></li>
            <li class="breadcrumb-item active">Tambah Pengeluaran</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Tambah Pengeluaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pengeluaran.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                        <select name="jenis_pengeluaran" id="jenis_pengeluaran" class="form-control @error('jenis_pengeluaran') is-invalid @enderror" required>
                            <option value="">Pilih Jenis Pengeluaran</option>
                            <option value="Kasbon Karyawan" {{ old('jenis_pengeluaran') == 'Kasbon Karyawan' ? 'selected' : '' }}>Kasbon Karyawan</option>
                            <option value="Uang Makan" {{ old('jenis_pengeluaran') == 'Uang Makan' ? 'selected' : '' }}>Uang Makan</option>
                            <option value="Token Listrik" {{ old('jenis_pengeluaran') == 'Token Listrik' ? 'selected' : '' }}>Token Listrik</option>
                            <option value="Air PDAM" {{ old('jenis_pengeluaran') == 'Air PDAM' ? 'selected' : '' }}>Air PDAM</option>
                            <option value="Modal" {{ old('jenis_pengeluaran') == 'Modal' ? 'selected' : '' }}>Modal</option>
                            <option value="Gaji Karyawan" {{ old('jenis_pengeluaran') == 'Gaji Karyawan' ? 'selected' : '' }}>Gaji Karyawan</option>
                            <option value="Beli Bahan" {{ old('jenis_pengeluaran') == 'Beli Bahan' ? 'selected' : '' }}>Beli Bahan</option>
                            <option value="Sumbangan" {{ old('jenis_pengeluaran') == 'Sumbangan' ? 'selected' : '' }}>Sumbangan</option>
                            <option value="Paket COD" {{ old('jenis_pengeluaran') == 'Paket COD' ? 'selected' : '' }}>Paket COD</option>
                            <option value="Perlengkapan" {{ old('jenis_pengeluaran') == 'Perlengkapan' ? 'selected' : '' }}>Perlengkapan</option>
                            <option value="Transportasi" {{ old('jenis_pengeluaran') == 'Transportasi' ? 'selected' : '' }}>Transportasi</option>
                            <option value="Lain-lain" {{ old('jenis_pengeluaran') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                        @error('jenis_pengeluaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Bagian ini akan muncul hanya jika Jenis Pengeluaran adalah 'Kasbon Karyawan' --}}
                    <div class="form-group" id="karyawan_select_group" style="display: {{ old('jenis_pengeluaran') == 'Kasbon Karyawan' ? 'block' : 'none' }};">
                        <label for="karyawan_id">Karyawan</label>
                        <select name="karyawan_id" id="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror">
                            <option value="">Pilih Karyawan</option>
                            @foreach ($karyawan as $item)
                                <option value="{{ $item->id }}" {{ old('karyawan_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_karyawan }} (NIK: {{ $item->nik ?? '-' }})</option>
                            @endforeach
                        </select>
                        @error('karyawan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', 1) }}" min="1" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga (per unit)</label>
                        <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', 0) }}" min="0" step="0.01" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="text" name="total" id="total" class="form-control" value="{{ old('total', 0) }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Pengeluaran</button>
                    <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const jenisPengeluaranSelect = document.getElementById('jenis_pengeluaran');
        const karyawanSelectGroup = document.getElementById('karyawan_select_group');
        const jumlahInput = document.getElementById('jumlah');
        const hargaInput = document.getElementById('harga');
        const totalInput = document.getElementById('total');

        // Fungsi untuk menghitung total
        function calculateTotal() {
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const harga = parseFloat(hargaInput.value) || 0;
            const total = jumlah * harga;
            totalInput.value = formatRupiah(total);
        }

        // Fungsi bantu untuk format Rupiah (dari contoh sebelumnya)
        function formatRupiah(angka) {
            if (angka === null || angka === undefined || isNaN(angka)) {
                return 'Rp 0';
            }
            var reverse = angka.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + ribuan;
        }

        // Event listener untuk perubahan jenis pengeluaran
        jenisPengeluaranSelect.addEventListener('change', function() {
            if (this.value === 'Kasbon Karyawan') {
                karyawanSelectGroup.style.display = 'block';
            } else {
                karyawanSelectGroup.style.display = 'none';
                document.getElementById('karyawan_id').value = ''; // Reset pilihan karyawan
            }
        });

        // Event listeners untuk perubahan jumlah dan harga
        jumlahInput.addEventListener('input', calculateTotal);
        hargaInput.addEventListener('input', calculateTotal);

        // Panggil calculateTotal saat halaman dimuat untuk nilai awal (jika ada old input)
        calculateTotal();

        // Trigger change event jika ada old input pada jenis_pengeluaran saat halaman dimuat
        if (jenisPengeluaranSelect.value === 'Kasbon Karyawan') {
            jenisPengeluaranSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
