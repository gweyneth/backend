@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Perusahaan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Perusahaan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{-- Judul dinamis berdasarkan apakah data sudah ada atau belum --}}
                <h5 class="card-title">{{ $perusahaan->exists ? 'Form Edit Data Perusahaan' : 'Form Tambah Data Perusahaan' }}</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Form action selalu mengarah ke route update untuk singleton resource --}}
                <form action="{{ route('perusahaan.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Selalu gunakan PUT untuk singleton update --}}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_perusahaan">Nama Perusahaan</label>
                                <input type="text" name="nama_perusahaan" id="nama_perusahaan"
                                    class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                    value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}" required>
                                @error('nama_perusahaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $perusahaan->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instagram">Instagram</label>
                                <input type="text" name="instagram" id="instagram"
                                    class="form-control @error('instagram') is-invalid @enderror"
                                    value="{{ old('instagram', $perusahaan->instagram) }}">
                                @error('instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alamat_tanggal">Alamat Tanggal (Kota/Lokasi)</label>
                                <input type="text" name="alamat_tanggal" id="alamat_tanggal"
                                    class="form-control @error('alamat_tanggal') is-invalid @enderror"
                                    value="{{ old('alamat_tanggal', $perusahaan->alamat_tanggal) }}">
                                @error('alamat_tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_handphone">No. Handphone</label>
                                <input type="text" name="no_handphone" id="no_handphone"
                                    class="form-control @error('no_handphone') is-invalid @enderror"
                                    value="{{ old('no_handphone', $perusahaan->no_handphone) }}">
                                @error('no_handphone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $perusahaan->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>Pengaturan Logo & QR Code</h5>

                    {{-- Logo Perusahaan --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="logo">Logo Perusahaan</label>
                                <div class="custom-file">
                                    <input type="file" name="logo" id="logo" class="custom-file-input @error('logo') is-invalid @enderror" onchange="previewImage(this, 'logo-preview')">
                                    <label class="custom-file-label" for="logo">Pilih file logo...</label>
                                </div>
                                @error('logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max 2MB (1200x700 px)</small>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="image-preview-container">
                                @if ($perusahaan->logo)
                                    <img id="logo-preview" src="{{ asset('storage/' . $perusahaan->logo) }}" alt="Logo Perusahaan" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @else
                                    <img id="logo-preview" src="https://placehold.co/150x100/cccccc/333333?text=No+Logo" alt="No Logo" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Favicon --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="favicon">Favicon</label>
                                <div class="custom-file">
                                    <input type="file" name="favicon" id="favicon" class="custom-file-input @error('favicon') is-invalid @enderror" onchange="previewImage(this, 'favicon-preview')">
                                    <label class="custom-file-label" for="favicon">Pilih file favicon...</label>
                                </div>
                                @error('favicon')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max 2MB (72x72 px)</small>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="image-preview-container">
                                @if ($perusahaan->favicon)
                                    <img id="favicon-preview" src="{{ asset('storage/' . $perusahaan->favicon) }}" alt="Favicon" class="img-thumbnail" style="max-width: 50px; max-height: 50px; object-fit: contain;">
                                @else
                                    <img id="favicon-preview" src="https://placehold.co/50x50/cccccc/333333?text=No+Favicon" alt="No Favicon" class="img-thumbnail" style="max-width: 50px; max-height: 50px; object-fit: contain;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Logo Login --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="logo_login">Logo Login</label>
                                <div class="custom-file">
                                    <input type="file" name="logo_login" id="logo_login" class="custom-file-input @error('logo_login') is-invalid @enderror" onchange="previewImage(this, 'logo_login-preview')">
                                    <label class="custom-file-label" for="logo_login">Pilih file logo login...</label>
                                </div>
                                @error('logo_login')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max 2MB (1200x700 px)</small>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="image-preview-container">
                                @if ($perusahaan->logo_login)
                                    <img id="logo_login-preview" src="{{ asset('storage/' . $perusahaan->logo_login) }}" alt="Logo Login" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @else
                                    <img id="logo_login-preview" src="https://placehold.co/150x100/cccccc/333333?text=No+Login+Logo" alt="No Login Logo" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Logo Lunas --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="logo_lunas">Logo Lunas</label>
                                <div class="custom-file">
                                    <input type="file" name="logo_lunas" id="logo_lunas" class="custom-file-input @error('logo_lunas') is-invalid @enderror" onchange="previewImage(this, 'logo_lunas-preview')">
                                    <label class="custom-file-label" for="logo_lunas">Pilih file logo lunas...</label>
                                </div>
                                @error('logo_lunas')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max 2MB (500x300 px)</small>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="image-preview-container">
                                @if ($perusahaan->logo_lunas)
                                    <img id="logo_lunas-preview" src="{{ asset('storage/' . $perusahaan->logo_lunas) }}" alt="Logo Lunas" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @else
                                    <img id="logo_lunas-preview" src="https://placehold.co/150x100/cccccc/333333?text=No+Lunas+Logo" alt="No Lunas Logo" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Logo Belum Lunas --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="logo_belum_lunas">Logo Belum Lunas</label>
                                <div class="custom-file">
                                    <input type="file" name="logo_belum_lunas" id="logo_belum_lunas" class="custom-file-input @error('logo_belum_lunas') is-invalid @enderror" onchange="previewImage(this, 'logo_belum_lunas-preview')">
                                    <label class="custom-file-label" for="logo_belum_lunas">Pilih file logo belum lunas...</label>
                                </div>
                                @error('logo_belum_lunas')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max 2MB (500x300 px)</small>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="image-preview-container">
                                @if ($perusahaan->logo_belum_lunas)
                                    <img id="logo_belum_lunas-preview" src="{{ asset('storage/' . $perusahaan->logo_belum_lunas) }}" alt="Logo Belum Lunas" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @else
                                    <img id="logo_belum_lunas-preview" src="https://placehold.co/150x100/cccccc/333333?text=No+Blm+Lunas+Logo" alt="No Belum Lunas Logo" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- QR Code --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="qr_code">QR Code</label>
                                <div class="custom-file">
                                    <input type="file" name="qr_code" id="qr_code" class="custom-file-input @error('qr_code') is-invalid @enderror" onchange="previewImage(this, 'qr_code-preview')">
                                    <label class="custom-file-label" for="qr_code">Pilih file QR Code...</label>
                                </div>
                                @error('qr_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max 2MB (700x700 px)</small>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="image-preview-container">
                                @if ($perusahaan->qr_code)
                                    <img id="qr_code-preview" src="{{ asset('storage/' . $perusahaan->qr_code) }}" alt="QR Code" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: contain;">
                                @else
                                    <img id="qr_code-preview" src="https://placehold.co/150x150/cccccc/333333?text=No+QR+Code" alt="No QR Code" class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: contain;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ID Card Desain --}}
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="id_card_desain">ID Card Desain</label>
                                <div class="custom-file">
                                    <input type="file" name="id_card_desain" id="id_card_desain" class="custom-file-input @error('id_card_desain') is-invalid @enderror" onchange="previewImage(this, 'id_card_desain-preview')">
                                    <label class="custom-file-label" for="id_card_desain">Pilih file desain ID Card...</label>
                                </div>
                                @error('id_card_desain')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Max 2MB</small>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="image-preview-container">
                                @if ($perusahaan->id_card_desain)
                                    <img id="id_card_desain-preview" src="{{ asset('storage/' . $perusahaan->id_card_desain) }}" alt="ID Card Desain" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @else
                                    <img id="id_card_desain-preview" src="https://placehold.co/150x100/cccccc/333333?text=No+ID+Card+Design" alt="No ID Card Design" class="img-thumbnail" style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                @endif
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                    @if ($perusahaan->exists)
                        <button type="button" class="btn btn-danger mt-3" onclick="confirmDelete()">Hapus Data Perusahaan</button>
                    @endif
                    {{-- <form id="delete-form" action="{{ route('perusahaan.destroy') }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form> --}}
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Pastikan SweetAlert2 terhubung --}}
<script>
    // Fungsi confirmDelete untuk SweetAlert2 (tidak perlu ID karena ini singleton)
    function confirmDelete() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Ini akan menghapus semua data perusahaan dan file terkait!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }

    // Fungsi untuk menampilkan preview gambar saat file dipilih
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            // Jika tidak ada file dipilih, kembalikan ke placeholder atau gambar lama
            // Anda mungkin perlu menyimpan URL gambar lama di atribut data-old-src
            // atau menggunakan placeholder default jika tidak ada gambar lama
            const oldSrc = preview.dataset.oldSrc || `https://placehold.co/${preview.style.maxWidth.replace('px', '')}x${preview.style.maxHeight.replace('px', '')}/cccccc/333333?text=No+Image`;
            preview.src = oldSrc; // Ini perlu disesuaikan jika placeholder berbeda per input
        }

        // Update label custom file input
        const fileName = input.files[0] ? input.files[0].name : 'Pilih file...';
        const customFileLabel = input.nextElementSibling;
        if (customFileLabel && customFileLabel.classList.contains('custom-file-label')) {
            customFileLabel.innerText = fileName;
        }
    }

    // Memastikan label file input berubah saat file dipilih dan menyimpan oldSrc
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            const previewId = input.id + '-preview';
            const previewElement = document.getElementById(previewId);
            if (previewElement && previewElement.src) {
                // Simpan src gambar lama ke atribut data-old-src
                previewElement.dataset.oldSrc = previewElement.src;
            }

            input.addEventListener('change', function() {
                previewImage(this, previewId);
            });

            // Inisialisasi label jika ada file yang sudah dipilih (misal dari old input)
            const fileName = this.files[0] ? this.files[0].name : 'Pilih file...';
            const customFileLabel = this.nextElementSibling;
            if (customFileLabel && customFileLabel.classList.contains('custom-file-label')) {
                customFileLabel.innerText = fileName;
            }
        });
    });
</script>
@endpush
