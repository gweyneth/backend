@extends('layouts.app')

@push('styles')
<style>
    .image-upload-container {
        border: 2px dashed #ced4da;
        border-radius: .25rem;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .15s ease-in-out;
        position: relative;
        background-color: #f8f9fa;
    }
    .image-upload-container:hover {
        border-color: #007bff;
    }
    .image-preview {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: .25rem;
        margin-bottom: 1rem;
    }
    .image-upload-container input[type="file"] {
        display: none;
    }
</style>
@endpush

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
<form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        {{-- Kolom Kiri: Foto & Deskripsi --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="form-group">
                        <label>Foto Produk</label>
                        <label for="foto" class="image-upload-container">
                            <img id="foto-preview" src="{{ $produk->foto ? asset('storage/' . $produk->foto) : 'https://placehold.co/300x300/e9ecef/6c757d?text=Pilih+Foto' }}" alt="Pratinjau Foto" class="image-preview">
                            <span class="text-primary mt-2">Klik untuk mengubah foto</span>
                            <input type="file" name="foto" id="foto" class="@error('foto') is-invalid @enderror">
                        </label>
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah. Maks 2MB.</small>
                        @error('foto')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Produk</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" placeholder="Masukkan deskripsi singkat produk">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Produk --}}
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $produk->nama) }}" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode">Kode Produk</label>
                                <input type="text" name="kode" id="kode" class="form-control" value="{{ $produk->kode }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kategori_id">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" id="kategori_id" class="form-control select2 @error('kategori_id') is-invalid @enderror" required>
                                    @foreach($kategoriBarangs as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $produk->kategori_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bahan_id">Bahan <span class="text-danger">*</span></label>
                                <select name="bahan_id" id="bahan_id" class="form-control select2 @error('bahan_id') is-invalid @enderror" required>
                                    @foreach($bahans as $bahan)
                                        <option value="{{ $bahan->id }}" {{ old('bahan_id', $produk->bahan_id) == $bahan->id ? 'selected' : '' }}>{{ $bahan->nama }}</option>
                                    @endforeach
                                </select>
                                @error('bahan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="satuan_id">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan_id" id="satuan_id" class="form-control select2 @error('satuan_id') is-invalid @enderror" required>
                                    @foreach($satuans as $satuan)
                                        <option value="{{ $satuan->id }}" {{ old('satuan_id', $produk->satuan_id) == $satuan->id ? 'selected' : '' }}>{{ $satuan->nama }}</option>
                                    @endforeach
                                </select>
                                @error('satuan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ukuran">Ukuran (Opsional)</label>
                                <input type="text" name="ukuran" id="ukuran" class="form-control @error('ukuran') is-invalid @enderror" value="{{ old('ukuran', $produk->ukuran) }}">
                                @error('ukuran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="jumlah">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', $produk->jumlah) }}" required min="0">
                                @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="harga_beli">Harga Beli <span class="text-danger">*</span></label>
                                <input type="number" name="harga_beli" id="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" value="{{ old('harga_beli', $produk->harga_beli) }}" required min="0">
                                @error('harga_beli')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="harga_jual">Harga Jual <span class="text-danger">*</span></label>
                                <input type="number" name="harga_jual" id="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" value="{{ old('harga_jual', $produk->harga_jual) }}" required min="0">
                                @error('harga_jual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update Produk</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Skrip untuk pratinjau foto
        const fotoInput = document.getElementById('foto');
        const fotoPreview = document.getElementById('foto-preview');
        fotoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fotoPreview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush
