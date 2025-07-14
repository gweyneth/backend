@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Akun Saya</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Akun Saya</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informasi Akun</h5>
            </div>
            <div class="card-body">
                @include('components.alert')
                <div class="row">
                    <div class="col-md-4 text-center">
                        {{-- Foto profil --}}
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('dist/img/user2-160x160.jpg') }}"
                            alt="Foto Profil" class="img-fluid rounded-circle mb-3"
                            style="width: 150px; height: 150px; object-fit: cover;">
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>

                        <hr>
                        {{-- Tombol untuk membuka modal Ubah Foto Profil --}}
                        <button type="button" class="btn btn-primary btn-sm mb-2" data-toggle="modal" data-target="#updatePhotoModal">
                            <i class="fas fa-camera"></i> Ubah Foto Profil
                        </button>
                    </div>
                    <div class="col-md-8">
                        <h5>Data Akun</h5>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Username</b> <a class="float-right">{{ $user->username }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                            </li>
                        </ul>
                        {{-- Tombol untuk membuka modal Ubah Data Akun --}}
                        <button type="button" class="btn btn-warning btn-sm mb-2" data-toggle="modal" data-target="#updateProfileModal">
                            <i class="fas fa-edit"></i> Ubah Data Akun
                        </button>

                        <hr class="mt-4">

                        <h5>Pengaturan Keamanan</h5>
                        {{-- Tombol untuk membuka modal Ganti Password --}}
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#changePasswordModal">
                            <i class="fas fa-key"></i> Ganti Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ubah Foto Profil --}}
<div class="modal fade" id="updatePhotoModal" tabindex="-1" role="dialog" aria-labelledby="updatePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePhotoModalLabel">Ubah Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="photo">Pilih foto baru</label>
                        <div class="custom-file">
                            <input type="file" name="photo" id="photo_modal" class="custom-file-input @error('photo') is-invalid @enderror" onchange="updateFileName(this, 'photo_modal_label')">
                            <label class="custom-file-label" for="photo_modal" id="photo_modal_label">Pilih foto baru</label>
                        </div>
                        @error('photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Max 2MB (jpeg, png, jpg, gif, svg)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Ubah Data Akun --}}
<div class="modal fade" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProfileModalLabel">Ubah Data Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('profile.update-profile') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name_modal">Username</label>
                        <input type="text" name="username" id="name_modal" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email_modal">Email</label>
                        <input type="email" name="email" id="email_modal" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Ganti Password --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('profile.change-password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password_modal">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password_modal" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="new_password_modal">Password Baru</label>
                        <input type="password" name="new_password" id="new_password_modal" class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation_modal">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation_modal" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ganti Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk memperbarui nama file di label custom file input
    function updateFileName(input, labelId) {
        const fileName = input.files[0] ? input.files[0].name : 'Pilih foto baru';
        document.getElementById(labelId).innerText = fileName;
    }

    // Script untuk menampilkan modal jika ada error validasi setelah submit
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->has('photo'))
            $('#updatePhotoModal').modal('show');
        @endif

        @if ($errors->has('username') || $errors->has('email'))
            $('#updateProfileModal').modal('show');
        @endif

        @if ($errors->has('current_password') || $errors->has('new_password'))
            $('#changePasswordModal').modal('show');
        @endif
    });
</script>
@endpush
