@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Satuan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('satuan.index') }}">Data Satuan</a></li>
            <li class="breadcrumb-item active">Edit Satuan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Satuan</h3>
            </div>
            <form action="{{ route('satuan.update', $satuan->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Penting untuk metode UPDATE --}}
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">Nama Satuan</label>
                        <input type="text"
                               class="form-control @error('nama') is-invalid @enderror"
                               id="nama"
                               name="nama"
                               value="{{ old('nama', $satuan->nama) }}" {{-- Pre-fill dengan data existing --}}
                               placeholder="Masukkan nama satuan"
                               required>
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('satuan.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection