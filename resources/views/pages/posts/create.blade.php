@extends('layouts.app')

@push('styles')
<style>
    .image-upload-container {
        border: 2px dashed #ced4da;
        border-radius: .25rem;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .15s ease-in-out;
        position: relative;
        background-color: #f8f9fa;
    }
    .image-upload-container:hover {
        border-color: #007bff;
    }
    .image-preview {
        width: 100%;
        height: 200px;
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
        <h1 class="m-0"><i class="fas fa-plus-circle mr-2"></i>Buat Post Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Blog</a></li>
            <li class="breadcrumb-item active">Buat Post Baru</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        {{-- Kolom Kiri: Judul & Konten --}}
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Judul Post <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Masukkan judul yang menarik..." required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="content">Konten <span class="text-danger">*</span></label>
                        {{-- Untuk pengalaman terbaik, Anda bisa mengganti textarea ini dengan editor WYSIWYG seperti TinyMCE atau CKEditor --}}
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="15" placeholder="Tulis konten blog Anda di sini...">{{ old('content') }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Gambar & Pengaturan --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Pengaturan & Gambar</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Gambar Unggulan</label>
                        <label for="image" class="image-upload-container">
                            <img id="image-preview" src="https://placehold.co/600x400/e9ecef/6c757d?text=Pilih+Gambar" alt="Pratinjau Gambar" class="image-preview">
                            <span class="text-primary mt-2">Klik untuk memilih gambar</span>
                            <input type="file" name="image" id="image" class="@error('image') is-invalid @enderror">
                        </label>
                        <small class="form-text text-muted">Ukuran maks 2MB.</small>
                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> Simpan Postingan</button>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-block mt-2">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');

        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush
