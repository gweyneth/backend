@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Detail Data Pelanggan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Data Pelanggan</a></li>
            <li class="breadcrumb-item active">Detail Data Pelanggan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Detail Pelanggan</h5>
                <span class="float-right">
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-danger">Kembali</a>
                    {{-- Tombol Edit, opsional --}}
                    <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="btn btn-primary ml-2">Edit Data</a>
                </span>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Kode Pelanggan:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $pelanggan->kode_pelanggan ?? '-' }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nama:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $pelanggan->nama }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Email:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $pelanggan->email }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Alamat:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $pelanggan->alamat }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Nomor Handphone:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $pelanggan->no_hp }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Dibuat Pada:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $pelanggan->created_at->format('d M Y, H:i:s') }}</p>
                        {{-- Menggunakan Carbon untuk format tanggal/waktu --}}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Terakhir Diperbarui:</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $pelanggan->updated_at->format('d M Y, H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection