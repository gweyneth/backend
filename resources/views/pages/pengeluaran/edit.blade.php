@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Pengeluaran</h1>
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
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Edit Pengeluaran</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Menggunakan method PUT untuk update --}}

                    <div class="form-group">
                        <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                        <select name="jenis_pengeluaran" id="jenis_pengeluaran" class="form-control @error('jenis_pengeluaran') is-invalid @enderror" required>
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

                    {{-- Bagian ini akan muncul hanya jika Jenis Pengeluaran adalah 'Kasbon Karyawan' --}}
                    <div class="form-group" id="karyawan_select_group" style="display: {{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) == 'Kasbon Karyawan' ? 'block' : 'none' }};">
                        <label for="karyawan_id">Karyawan</label>
                        <select name="karyawan_id" id="karyawan_id" class="form-control @error('karyawan_id') is-invalid @enderror">
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
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $pengeluaran->jumlah) }}" min="1" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga (per unit)</label>
                        <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $pengeluaran->harga) }}" min="0" step="0.01" required>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="total">Total</label>
                        <input type="text" name="total" id="total" class="form-control" value="{{ old('total', $pengeluaran->total) }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Pengeluaran</button>
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
            totalInput.value = formatRupiah(tota