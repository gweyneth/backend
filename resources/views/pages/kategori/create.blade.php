@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Tambah Kategori Barang</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kategoribarang.index') }}">Data Kategori Barang</a></li>
            <li class="breadcrumb-item active">Tambah Kategori Barang</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Kategori Barang</h3>
            </div>
            <form action="{{ route('kategoribarang.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">Nama Kategori Barang</label>
                        <input type="text"
                               class="form-control @error('nama') is-invalid @enderror"
                               id="nama"
                               name="nama"
                               value="{{ old('nama') }}"
                               placeholder="Masukkan nama kategori barang"
                               required>
                        @error('nama')
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
                            {{-- UBAH NILAI VALUE MENJADI STRING 'Aktif' dan 'Non Aktif' --}}
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
                    <a href="{{ route('kategoribarang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection