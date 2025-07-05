@extends('layouts.app') {{-- Pastikan ini mengarah ke layout utama Anda --}}

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Tambah Bahan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bahan.index') }}">Data Bahan</a></li>
            <li class="breadcrumb-item active">Tambah Bahan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Bahan</h3>
            </div>
            <form action="{{ route('bahan.store') }}" method="POST">
                @csrf {{-- Token CSRF wajib untuk keamanan Laravel --}}
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">Nama Bahan</label>
                        <input type="text"
                               class="form-control @error('nama') is-invalid @enderror"
                               id="nama"
                               name="nama"
                               value="{{ old('nama') }}"
                               placeholder="Masukkan nama bahan"
                               required>
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select class="form-control @error('kategori_id') is-invalid @enderror"
                                id="kategori_id"
                                name="kategori_id"
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriBarangs as $kategori)
                                {{-- Pastikan nama kolom untuk kategori adalah 'nama' --}}
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <select class="form-control @error('stok') is-invalid @enderror"
                                id="stok"
                                name="stok"
                                required>
                            <option value="">-- Pilih Stok --</option>
                            <option value="Ada" {{ old('stok') === 'Ada' ? 'selected' : '' }}>Ada</option>
                            <option value="Kosong" {{ old('stok') === 'Kosong' ? 'selected' : '' }}>Kosong</option>
                        </select>
                        @error('stok')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror"
                                id="status"
                                name="status"
                                required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non Aktif" {{ old('status') === 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('bahan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
        </div>
</div>
@endsection