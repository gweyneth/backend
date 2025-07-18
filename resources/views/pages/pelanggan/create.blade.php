@extends('layouts.app')

@section('content_header')
    {{-- Header Halaman --}}
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><i class="fas fa-user-plus mr-2"></i>Tambah Data Pelanggan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Data Pelanggan</a></li>
                <li class="breadcrumb-item active">Tambah Data</li>
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
                        <i class="fas fa-plus-circle mr-2"></i>
                        Formulir Pelanggan Baru
                    </h3>
                    <div class="card-tools">
                        {{-- Tombol kembali yang lebih rapi di pojok kanan atas --}}
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('pelanggan.store') }}" method="POST">
                        @csrf

                        {{-- Kode Pelanggan (Otomatis) --}}
                        <div class="form-group">
                            <label for="kode_pelanggan">Kode Pelanggan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                </div>
                                <input type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan"
                                       value="{{ $nextKodePelanggan }}" readonly>
                            </div>
                            <small class="form-text text-muted">Kode pelanggan dibuat secara otomatis oleh sistem.</small>
                        </div>

                        {{-- Nama Pelanggan --}}
                        <div class="form-group">
                            <label for="nama">Nama <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                       name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                       name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Nomor Handphone --}}
                        <div class="form-group">
                            <label for="no_hp">Nomor Handphone <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp"
                                       name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required>
                                @error('no_hp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="form-group">
                            <label for="alamat">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat"
                                      rows="3" placeholder="Masukkan alamat lengkap pelanggan" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Tombol Aksi di Footer --}}
                        <div class="card-footer text-right bg-white">
                            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
