@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Tambah Data Pelanggan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Pelanggan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tambah Data Pelanggan</h5>
                <span class="float-right">
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-danger">Kembali</a>
                </span>
            </div>
            <div class="card-body">
                <form action="{{ route('pelanggan.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kode Pelanggan</label>
                                <input type="text" class="form-control" placeholder="Otomatis Terisi" readonly>
                            </div>
                        </div>

                     
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" name="alamat" required>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nomor Handphone</label>
                                <input type="text" class="form-control" name="no_hp" required>
                            </div>
                        </div>

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
