@extends('layouts.app')

@push('styles')
<style>
    .profile-user-img {
        width: 128px;
        height: 128px;
        object-fit: cover;
    }
</style>
@endpush

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-user-circle mr-2"></i>Akun Saya</h1>
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
    <div class="col-md-4">
        {{-- Kartu Profil Pengguna --}}
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('dist/img/user2-160x160.jpg') }}"
                         alt="Foto Profil Pengguna">
                </div>
                <h3 class="profile-username text-center mt-3">{{ $user->name }}</h3>
                <p class="text-muted text-center">{{ $user->email }}</p>

                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#updatePhotoModal">
                    <b><i class="fas fa-camera mr-1"></i> Ubah Foto Profil</b>
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-profile-tab" data-toggle="pill" href="#tab-profile" role="tab" aria-controls="tab-profile" aria-selected="true">
                            <i class="fas fa-user-edit mr-2"></i>Ubah Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-password-tab" data-toggle="pill" href="#tab-password" role="tab" aria-controls="tab-password" aria-selected="false">
                            <i class="fas fa-key mr-2"></i>Ganti Password
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    {{-- Tab 1: Ubah Profil --}}
                    <div class="tab-pane fade show active" id="tab-profile" role="tabpanel" aria-labelledby="tab-profile-tab">
                        <form action="{{ route('profile.update-profile') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="username">Username</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>

                    {{-- Tab 2: Ganti Password --}}
                    <div class="tab-pane fade" id="tab-password" role="tabpanel" aria-labelledby="tab-password-tab">
                        <form action="{{ route('profile.change-password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="current_password">Password Saat Ini</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock-open"></i></span></div>
                                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Password Baru</label>
                                <div class="input-group">
                                     <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                    <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                                    @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-danger"><i class="fas fa-key mr-1"></i> Ganti Password</button>
                            </div>
                        </form>
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
                        <label for="photo_modal">Pilih foto baru</label>
                        <div class="custom-file">
                            <input type="file" name="photo" id="photo_modal" class="custom-file-input @error('photo') is-invalid @enderror" onchange="updateFileName(this, 'photo_modal_label')">
                            <label class="custom-file-label" for="photo_modal" id="photo_modal_label">Pilih file...</label>
                        </div>
                        @error('photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Max 2MB (jpeg, png, jpg, gif, svg)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Foto</button>
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
        const fileName = input.files[0] ? input.files[0].name : 'Pilih file...';
        document.getElementById(labelId).innerText = fileName;
    }

    // Script untuk membuka kembali modal jika ada error validasi
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->has('photo'))
            $('#updatePhotoModal').modal('show');
        @endif

        // Script untuk mengaktifkan tab yang benar jika ada error validasi
        @if ($errors->has('username') || $errors->has('email'))
            $('#tab-profile-tab').tab('show');
        @endif

        @if ($errors->has('current_password') || $errors->has('new_password'))
            $('#tab-password-tab').tab('show');
        @endif
    });
</script>
@endpush
