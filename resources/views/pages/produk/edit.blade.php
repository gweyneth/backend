@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-edit mr-2"></i>Edit Produk</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Data Produk</a></li>
            <li class="breadcrumb-item active">Edit Produk</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('produk.update', $produk->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Informasi Dasar</h4>
                            <hr>
                            <div class="form-group">
                                <label for="nama">Nama Produk <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-box-open"></i></span></div>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $produk->nama) }}" required>
                                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="kode">Kode Produk</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-barcode"></i></span></div>
                                    <input type="text" class="form-control" id="kode" name="kode" value="{{ $produk->kode }}" readonly>
                                </div>
                                <small class="form-text text-muted">Kode produk dibuat otomatis.</small>
                            </div>
                            <div class="form-group">
                                <label for="kategori_id">Kategori <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                                    @foreach($kategoriBarangs as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $produk->kategori_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Detail & Harga</h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bahan_id">Bahan <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('bahan_id') is-invalid @enderror" id="bahan_id" name="bahan_id" required>
                                            @foreach($bahans as $bahan)
                                                <option value="{{ $bahan->id }}" {{ old('bahan_id', $produk->bahan_id) == $bahan->id ? 'selected' : '' }}>{{ $bahan->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('bahan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="satuan_id">Satuan <span class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('satuan_id') is-invalid @enderror" id="satuan_id" name="satuan_id" required>
                                            @foreach($satuans as $satuan)
                                                <option value="{{ $satuan->id }}" {{ old('satuan_id', $produk->satuan_id) == $satuan->id ? 'selected' : '' }}>{{ $satuan->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('satuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ukuran">Ukuran (Opsional)</label>
                                        <input type="text" class="form-control @error('ukuran') is-invalid @enderror" id="ukuran" name="ukuran" value="{{ old('ukuran', $produk->ukuran) }}" placeholder="Contoh: S, M, L">
                                        @error('ukuran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jumlah">Stok <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', $produk->jumlah) }}" required min="0">
                                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="harga_beli">Harga Beli <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                            <input type="number" class="form-control @error('harga_beli') is-invalid @enderror" id="harga_beli" name="harga_beli" value="{{ old('harga_beli', $produk->harga_beli) }}" required min="0">
                                            @error('harga_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="harga_jual">Harga Jual <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                            <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $produk->harga_jual) }}" required min="0">
                                            @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update Produk</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi Select2 pada elemen dengan class 'select2'
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });
</script>
@endpush
