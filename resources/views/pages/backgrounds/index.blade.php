@extends('layouts.app')

@push('styles')
<style>
    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    .image-card {
        border: 1px solid #dee2e6;
        border-radius: .25rem;
        overflow: hidden;
        position: relative;
        box-shadow: 0 1px 3px rgba(0,0,0,.1);
    }
    .image-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    .image-card-body {
        padding: 1rem;
    }
    .image-card .active-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.8rem;
    }
    .image-card .btn-group {
        width: 100%;
    }
</style>
@endpush

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-images mr-2"></i>Pengaturan Tampilan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pengaturan Tampilan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    {{-- Kolom untuk Upload Gambar Baru --}}
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-upload mr-2"></i>Unggah Gambar Baru</h3>
            </div>
            <form action="{{ route('backgrounds.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="section_name">Pilih Bagian Halaman</label>
                        <select name="section_name" id="section_name" class="form-control @error('section_name') is-invalid @enderror" required>
                            <option value="">-- Pilih Bagian --</option>
                            <option value="hero" {{ old('section_name') == 'hero' ? 'selected' : '' }}>Hero Section (Halaman Utama)</option>
                            {{-- Anda bisa menambahkan opsi lain di sini, contoh: --}}
                            {{-- <option value="footer_promo">Promo di Footer</option> --}}
                        </select>
                        @error('section_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Pilih File Gambar</label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" id="image" required>
                            <label class="custom-file-label" for="image">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted">Maks 2MB. Format: jpg, png, webp.</small>
                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> Unggah</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Kolom untuk Galeri Gambar --}}
    <div class="col-md-8">
        @forelse ($backgrounds as $sectionName => $images)
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Galeri untuk: <strong>{{ ucwords(str_replace('_', ' ', $sectionName)) }}</strong></h3>
            </div>
            <div class="card-body">
                <div class="image-gallery">
                    @foreach ($images as $image)
                    <div class="image-card">
                        @if($image->is_active)
                            <span class="badge badge-success active-badge">Aktif</span>
                        @endif
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Background Image">
                        <div class="image-card-body">
                            <div class="btn-group">
                                <form action="{{ route('backgrounds.set_active', $image->id) }}" method="POST" style="width: 50%;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm btn-block" {{ $image->is_active ? 'disabled' : '' }}>
                                        <i class="fas fa-check"></i> Aktifkan
                                    </button>
                                </form>
                                <form action="{{ route('backgrounds.destroy', $image->id) }}" method="POST" style="width: 50%;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-block" onclick="confirmDelete(this)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center">
                <p>Belum ada gambar background yang diunggah. Silakan unggah gambar baru melalui form di sebelah kiri.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script untuk menampilkan nama file di input
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('image');
        if(fileInput) {
            fileInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
                const label = this.nextElementSibling;
                if(label) {
                    label.innerText = fileName;
                }
            });
        }
    });

    // Fungsi konfirmasi hapus
    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan gambar ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endpush
