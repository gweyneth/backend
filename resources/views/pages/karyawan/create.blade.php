@extends('layouts.app')

@push('styles')
<style>
    .image-upload-container {
        border: 2px dashed #ced4da;
        border-radius: .25rem;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        position: relative;
        background-color: #f8f9fa;
    }
    .image-upload-container:hover {
        border-color: #007bff;
    }
    .image-preview {
        width: 100%;
        height: 250px; /* Disesuaikan untuk rasio potret */
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
        <h1 class="m-0"><i class="fas fa-user-plus mr-2"></i>Tambah Karyawan Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}">Data Karyawan</a></li>
            <li class="breadcrumb-item active">Tambah Karyawan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('karyawan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        {{-- Kolom Kiri untuk Foto --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Foto Karyawan</label>
                                <label for="foto" class="image-upload-container">
                                    <img id="foto-preview" 
                                         src="https://placehold.co/300x400/cccccc/333333?text=Pilih+Foto" 
                                         alt="Foto Karyawan" 
                                         class="image-preview">
                                    <span class="text-primary">Klik untuk memilih foto</span>
                                    <input type="file" name="foto" id="foto" class="@error('foto') is-invalid @enderror">
                                </label>
                                <small class="form-text text-muted">Ukuran maks 2MB.</small>
                                @error('foto')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Kolom Kanan untuk Data --}}
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_karyawan">ID Karyawan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-card"></i></span></div>
                                            <input type="text" id="id_karyawan" name="id_karyawan" class="form-control" value="{{ $nextIdKaryawan }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nik">NIK</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-badge"></i></span></div>
                                            <input type="text" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}">
                                            @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="nama_karyawan">Nama Karyawan <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                            <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control @error('nama_karyawan') is-invalid @enderror" value="{{ old('nama_karyawan') }}" required>
                                            @error('nama_karyawan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jabatan">Jabatan <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-tag"></i></span></div>
                                            <input type="text" name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" required>
                                            @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="">Pilih Status</option>
                                            <option value="Tetap" {{ old('status') == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                                            <option value="Kontrak" {{ old('status') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                            <option value="Magang" {{ old('status') == 'Magang' ? 'selected' : '' }}>Magang</option>
                                        </select>
                                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_handphone">No. Handphone <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                                            <input type="text" name="no_handphone" id="no_handphone" class="form-control @error('no_handphone') is-invalid @enderror" value="{{ old('no_handphone') }}" required>
                                            @error('no_handphone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="gaji_pokok">Gaji Pokok <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                            <input type="number" name="gaji_pokok" id="gaji_pokok" class="form-control @error('gaji_pokok') is-invalid @enderror" value="{{ old('gaji_pokok') }}" required>
                                            @error('gaji_pokok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                        <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat') }}</textarea>
                                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Karyawan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
