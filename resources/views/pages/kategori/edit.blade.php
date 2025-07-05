@extends('layouts.app') {{-- Pastikan ini mengarah ke layout utama Anda --}}

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Edit Kategori Barang</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kategoribarang.index') }}">Data Kategori Barang</a></li>
            <li class="breadcrumb-item active">Edit Kategori Barang</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Kategori Barang</h3>
            </div>
            {{-- Perhatikan: method="POST" dan @method('PUT') --}}
            {{-- Action mengarah ke route 'kategoribarang.update' dengan ID kategori --}}
            <form action="{{ route('kategoribarang.update', $kategoriBarang->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Laravel akan menafsirkan ini sebagai permintaan PUT/PATCH --}}
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">Nama Kategori Barang</label>
                        <input type="text"
                               class="form-control @error('nama') is-invalid @enderror"
                               id="nama"
                               name="nama"
                               {{-- Menggunakan $kategoriBarang->nama sebagai nilai default, dan old('nama') jika ada error --}}
                               value="{{ old('nama', $kategoriBarang->nama) }}"
                               placeholder="Masukkan nama kategori barang"
                               required>
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror"
                                id="status"
                                name="status"
                                required>
                            <option value="">-- Pilih Status --</option>
                            {{-- Membandingkan nilai status dari database dengan opsi value --}}
                            <option value="Aktif" {{ old('status', $kategoriBarang->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non Aktif" {{ old('status', $kategoriBarang->status) === 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Tambahkan field lain di sini jika diperlukan --}}

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('kategoribarang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
        </div>
</div>
@endsection