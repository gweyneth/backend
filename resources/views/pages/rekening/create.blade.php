@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-plus-circle mr-2"></i>Tambah Rekening Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rekening.index') }}">Data Rekening</a></li>
            <li class="breadcrumb-item active">Tambah Rekening</li>
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
                    <i class="fas fa-pencil-alt mr-2"></i>Form Tambah Rekening
                </h3>
            </div>
            <form action="{{ route('rekening.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="bank">Nama Bank <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-university"></i></span>
                            </div>
                            <input type="text" name="bank" id="bank" class="form-control @error('bank') is-invalid @enderror" value="{{ old('bank') }}" placeholder="Contoh: BCA, BRI, Mandiri" required>
                            @error('bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nomor_rekening">Nomor Rekening <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                            </div>
                            <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control @error('nomor_rekening') is-invalid @enderror" value="{{ old('nomor_rekening') }}" placeholder="Masukkan nomor rekening" required>
                            @error('nomor_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="atas_nama">Atas Nama <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="atas_nama" id="atas_nama" class="form-control @error('atas_nama') is-invalid @enderror" value="{{ old('atas_nama') }}" placeholder="Masukkan nama pemilik rekening" required>
                            @error('atas_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kode_bank">Kode Bank (Opsional)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            </div>
                            <input type="text" name="kode_bank" id="kode_bank" class="form-control @error('kode_bank') is-invalid @enderror" value="{{ old('kode_bank') }}" placeholder="Contoh: 014 untuk BCA">
                            @error('kode_bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('rekening.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Rekening</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection