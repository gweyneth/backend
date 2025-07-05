@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Data Karyawan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}">Data Karyawan</a></li>
            <li class="breadcrumb-item active">Edit Karyawan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Form Edit Karyawan</h5>
            </div>
            <div class="card-body">
                {{-- Form untuk mengedit karyawan --}}
                {{-- Method PUT/PATCH digunakan untuk update data, dan enctype untuk upload file --}}
                <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf {{-- Token CSRF untuk keamanan --}}
                    @method('PUT') {{-- Method spoofing untuk HTTP PUT --}}

                    <div class="form-group">
                        <label for="nama_karyawan">Nama Karyawan</label>
                        <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control @error('nama_karyawan') is-invalid @enderror" value="{{ old('nama_karyawan', $karyawan->nama_karyawan) }}" required>
                        @error('nama_karyawan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="text" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $karyawan->nik) }}">
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $karyawan->jabatan) }}" required>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="">Pilih Status</option>
                            <option value="Tetap" {{ old('status', $karyawan->status) == 'Tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="Kontrak" {{ old('status', $karyawan->status) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="Magang" {{ old('status', $karyawan->status) == 'Magang' ? 'selected' : '' }}>Magang</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat', $karyawan->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="no_handphone">No. Handphone</label>
                        <input type="text" name="no_handphone" id="no_handphone" class="form-control @error('no_handphone') is-invalid @enderror" value="{{ old('no_handphone', $karyawan->no_handphone) }}" required>
                        @error('no_handphone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $karyawan->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="gaji_pokok">Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" id="gaji_pokok" class="form-control @error('gaji_pokok') is-invalid @enderror" value="{{ old('gaji_pokok', $karyawan->gaji_pokok) }}" step="0.01" required>
                        @error('gaji_pokok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="foto">Foto Karyawan</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="foto" id="foto" class="custom-file-input @error('foto') is-invalid @enderror">
                                <label class="custom-file-label" for="foto">Pilih file foto...</label>
                            </div>
                        </div>
                        @error('foto')
                            <div class="invalid-feedback d-block">{{ $message }}</div> {{-- d-block agar pesan error muncul --}}
                        @enderror
                        @if ($karyawan->foto)
                            <div class="mt-2">
                                <p>Foto saat ini:</p>
                                <img src="{{ asset('storage/' . $karyawan->foto) }}" alt="Foto {{ $karyawan->nama_karyawan }}" class="img-thumbnail" style="width: 100px; height: 133px; object-fit: cover;"> {{-- Rasio 3x4 --}}
                            </div>
                        @else
                            <div class="mt-2">
                                <p>Belum ada foto.</p>
                                <img src="https://placehold.co/100x133/cccccc/333333?text=No+Foto" alt="No Photo" class="img-thumbnail" style="width: 100px; height: 133px; object-fit: cover;">
                            </div>
                        @endif
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Karyawan</button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Memastikan label file input berubah saat file dipilih
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('foto');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const fileName = this.files[0] ? this.files[0].name : 'Pilih file foto...';
                const label = this.nextElementSibling;
                if (label && label.classList.contains('custom-file-label')) {
                    label.innerText = fileName;
                }
            });
        }
    });
</script>
@endpush
