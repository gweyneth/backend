@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Data Pelanggan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Data Pelanggan</a></li>
            <li class="breadcrumb-item active">Edit Data Pelanggan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Data Pelanggan</h5>
                <span class="float-right">
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-danger">Kembali</a>
                </span>
            </div>
            <div class="card-body">
                {{-- Form untuk mengupdate data pelanggan --}}
                <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
                    @csrf {{-- Token CSRF untuk keamanan form --}}
                    @method('PUT') {{-- Menggunakan metode PUT untuk update data --}}

                    <div class="row">
                        {{-- Kode Pelanggan (Hanya Tampil, tidak bisa diubah) --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kode Pelanggan</label>
                                {{-- Jika kode_pelanggan otomatis, tampilkan nilainya --}}
                                <input type="text" class="form-control" value="{{ $pelanggan->kode_pelanggan ?? 'Otomatis Generate' }}" readonly>
                                {{-- Gunakan null coalescing operator ?? jika kode_pelanggan bisa null --}}
                            </div>
                        </div>

                        {{-- Nama --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required>
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" value="{{ old('alamat', $pelanggan->alamat) }}" required>
                                @error('alamat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- No HP --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nomor Handphone</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp) }}" required>
                                @error('no_hp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $pelanggan->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Update Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection