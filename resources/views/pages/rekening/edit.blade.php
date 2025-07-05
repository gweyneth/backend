@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Rekening</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rekening.index') }}">Data Rekening</a></li>
            <li class="breadcrumb-item active">Edit Rekening</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Edit Rekening</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('rekening.update', $rekening->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Menggunakan method PUT untuk update --}}

                    <div class="form-group">
                        <label for="nomor_rekening">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control @error('nomor_rekening') is-invalid @enderror" value="{{ old('nomor_rekening', $rekening->nomor_rekening) }}" required>
                        @error('nomor_rekening')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="atas_nama">Atas Nama</label>
                        <input type="text" name="atas_nama" id="atas_nama" class="form-control @error('atas_nama') is-invalid @enderror" value="{{ old('atas_nama', $rekening->atas_nama) }}" required>
                        @error('atas_nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bank">Bank</label>
                        <input type="text" name="bank" id="bank" class="form-control @error('bank') is-invalid @enderror" value="{{ old('bank', $rekening->bank) }}" required>
                        @error('bank')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kode_bank">Kode Bank (Opsional)</label>
                        <input type="text" name="kode_bank" id="kode_bank" class="form-control @error('kode_bank') is-invalid @enderror" value="{{ old('kode_bank', $rekening->kode_bank) }}">
                        @error('kode_bank')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Rekening</button>
                    <a href="{{ route('rekening.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
