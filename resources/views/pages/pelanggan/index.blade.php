@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Pelanggan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Pelanggan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Pelanggan</h5>
                <span class="float-right">
                    <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">Tambah Pelanggan</a>
                </span>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pelanggan</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item) {{-- Pastikan $data adalah variabel yang berisi koleksi pelanggan --}}
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kode_pelanggan ?? '-' }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_hp }}</td>
                            <td>
                                <a href="{{ route('pelanggan.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ route('pelanggan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                {{-- Tombol Hapus yang memicu SweetAlert --}}
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}')">Hapus</button>

                                {{-- Form DELETE tersembunyi (akan disubmit oleh JavaScript) --}}
                                <form id="delete-form-{{ $item->id }}" action="{{ route('pelanggan.destroy', $item->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pelanggan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Bagian Script untuk SweetAlert2 --}}
@push('scripts') {{-- Jika Anda menggunakan stack('scripts') di layouts.app --}}
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user konfirmasi, submit form delete yang tersembunyi
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush