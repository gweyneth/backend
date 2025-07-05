@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Tambah Produk</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Data Produk</a></li>
            <li class="breadcrumb-item active">Tambah Produk</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Produk</h3>
            </div>
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">Nama Produk</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="bahan_id">Bahan</label>
                        <select class="form-control @error('bahan_id') is-invalid @enderror" id="bahan_id" name="bahan_id" required>
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($bahans as $bahan)
                                <option value="{{ $bahan->id }}" {{ old('bahan_id') == $bahan->id ? 'selected' : '' }}>{{ $bahan->nama }}</option>
                            @endforeach
                        </select>
                        @error('bahan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select class="form-control @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriBarangs as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="satuan_id">Satuan</label>
                        <select class="form-control @error('satuan_id') is-invalid @enderror" id="satuan_id" name="satuan_id" required>
                            <option value="">-- Pilih Satuan --</option>
                            @foreach($satuans as $satuan)
                                <option value="{{ $satuan->id }}" {{ old('satuan_id') == $satuan->id ? 'selected' : '' }}>{{ $satuan->nama }}</option>
                            @endforeach
                        </select>
                        @error('satuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="ukuran">Ukuran (Opsional)</label>
                        <input type="text" class="form-control @error('ukuran') is-invalid @enderror" id="ukuran" name="ukuran" value="{{ old('ukuran') }}" placeholder="Contoh: S, M, L, 10x20cm">
                        @error('ukuran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" required min="0">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="harga_beli">Harga Beli</label>
                        <input type="number" class="form-control @error('harga_beli') is-invalid @enderror" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" required min="0" step="0.01">
                        @error('harga_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" required min="0" step="0.01">
                        @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection