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
                    <h5 class="card-title">
                        {{ $perusahaan->exists ? 'Form Edit Data Perusahaan' : 'Form Tambah Data Perusahaan' }}</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
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
                                    <label for="alamat_tanggal">Alamat Tinggal (Kota/Lokasi)</label>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo">Logo Perusahaan</label>
                                    <input type="file" name="logo" id="logo"
                                        class="form-control-file @error('logo') is-invalid @enderror">
                                    @error('logo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @if ($perusahaan->logo)
                                        <div class="mt-2">
                                            <p>Saat ini: <br><img src="{{ asset('storage/' . $perusahaan->logo) }}"
                                                    alt="Logo Perusahaan" class="img-thumbnail" style="max-width: 150px;">
                                            </p>
                                        </div>
                                    @endif
                                    <small class="form-text text-muted">Max 2MB (1200x700 px)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="favicon">Favicon</label>
                                    <input type="file" name="favicon" id="favicon"
                                        class="form-control-file @error('favicon') is-invalid @enderror">
                                    @error('favicon')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @if ($perusahaan->favicon)
                                        <div class="mt-2">
                                            <p>Saat ini: <br><img src="{{ asset('storage/' . $perusahaan->favicon) }}"
                                                    alt="Favicon" class="img-thumbnail" style="max-width: 50px;"></p>
                                        </div>
                                    @endif
                                    <small class="form-text text-muted">Max 2MB (72x72 px)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo_login">Logo Login</label>
                                    <input type="file" name="logo_login" id="logo_login"
                                        class="form-control-file @error('logo_login') is-invalid @enderror">
                                    @error('logo_login')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @if ($perusahaan->logo_login)
                                        <div class="mt-2">
                                            <p>Saat ini: <br><img src="{{ asset('storage/' . $perusahaan->logo_login) }}"
                                                    alt="Logo Login" class="img-thumbnail" style="max-width: 150px;"></p>
                                        </div>
                                    @endif
                                    <small class="form-text text-muted">Max 2MB (1200x700 px)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo_lunas">Logo Lunas</label>
                                    <input type="file" name="logo_lunas" id="logo_lunas"
                                        class="form-control-file @error('logo_lunas') is-invalid @enderror">
                                    @error('logo_lunas')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @if ($perusahaan->logo_lunas)
                                        <div class="mt-2">
                                            <p>Saat ini: <br><img src="{{ asset('storage/' . $perusahaan->logo_lunas) }}"
                                                    alt="Logo Lunas" class="img-thumbnail" style="max-width: 150px;"></p>
                                        </div>
                                    @endif
                                    <small class="form-text text-muted">Max 2MB (500x300 px)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo_belum_lunas">Logo Belum Lunas</label>
                                    <input type="file" name="logo_belum_lunas" id="logo_belum_lunas"
                                        class="form-control-file @error('logo_belum_lunas') is-invalid @enderror">
                                    @error('logo_belum_lunas')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @if ($perusahaan->logo_belum_lunas)
                                        <div class="mt-2">
                                            <p>Saat ini: <br><img
                                                    src="{{ asset('storage/' . $perusahaan->logo_belum_lunas) }}"
                                                    alt="Logo Belum Lunas" class="img-thumbnail"
                                                    style="max-width: 150px;"></p>
                                        </div>
                                    @endif
                                    <small class="form-text text-muted">Max 2MB (500x300 px)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qr_code">QR Code</label>
                                    <input type="file" name="qr_code" id="qr_code"
                                        class="form-control-file @error('qr_code') is-invalid @enderror">
                                    @error('qr_code')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @if ($perusahaan->qr_code)
                                        <div class="mt-2">
                                            <p>Saat ini: <br><img src="{{ asset('storage/' . $perusahaan->qr_code) }}"
                                                    alt="QR Code" class="img-thumbnail" style="max-width: 150px;"></p>
                                        </div>
                                    @endif
                                    <small class="form-text text-muted">Max 2MB (700x700 px)</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="id_card_desain">ID Card Desain</label>
                            <input type="file" name="id_card_desain" id="id_card_desain"
                                class="form-control-file @error('id_card_desain') is-invalid @enderror">
                            @error('id_card_desain')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if ($perusahaan->id_card_desain)
                                <div class="mt-2">
                                    <p>Saat ini: <br><img src="{{ asset('storage/' . $perusahaan->id_card_desain) }}"
                                            alt="ID Card Desain" class="img-thumbnail" style="max-width: 150px;"></p>
                                </div>
                            @endif
                            <small class="form-text text-muted">Max 2MB</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        {{-- Tombol Hapus hanya muncul jika data perusahaan sudah ada --}}
                        @if ($perusahaan->exists)
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus Data
                                Perusahaan</button>
                        @endif

                        {{-- Form DELETE tersembunyi untuk SweetAlert --}}
                        {{-- Action mengarah ke route destroy untuk singleton resource --}}
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
        // function confirmDelete() {
        //     Swal.fire({
        //         title: 'Apakah Anda yakin?',
        //         text: "Ini akan menghapus semua data perusahaan dan file terkait!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#d33',
        //         cancelButtonColor: '#3085d6',
        //         confirmButtonText: 'Ya, hapus!',
        //         cancelButtonText: 'Batal'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             document.getElementById('delete-form').submit();
        //         }
        //     });
        // }

        // Script untuk menampilkan nama file yang dipilih pada input file
        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const fileName = this.files[0] ? this.files[0].name : 'Pilih file...';
                    // Menyesuaikan label untuk input file Bootstrap 4 custom-file-input
                    const customFileLabel = this.nextElementSibling;
                    if (customFileLabel && customFileLabel.classList.contains(
                        'custom-file-label')) {
                        customFileLabel.innerText = fileName;
                    } else {
                        // Fallback jika bukan custom-file-input (misalnya form-control-file biasa)
                        console.log('Selected file: ' + fileName);
                    }
                });
            });
        });
    </script>
@endpush
