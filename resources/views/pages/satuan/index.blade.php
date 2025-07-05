@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Satuan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Satuan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Satuan</h5>
                <span class="float-right">
                    <a href="{{ route('satuan.create') }}" class="btn btn-primary">Tambah Satuan</a>
                </span>
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

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Satuan</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($satuans as $satuan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $satuan->nama }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" onclick="showSatuanDetail('{{ $satuan->id }}')">Detail</button>
                                <a href="{{ route('satuan.edit', $satuan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $satuan->id }}')">Hapus</button>

                                <form id="delete-form-{{ $satuan->id }}" action="{{ route('satuan.destroy', $satuan->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data satuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailSatuanModal" tabindex="-1" role="dialog" aria-labelledby="detailSatuanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailSatuanModalLabel">Detail Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th>ID</th>
                        <td id="detail-id"></td>
                    </tr>
                    <tr>
                        <th>Nama Satuan</th>
                        <td id="detail-nama"></td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td id="detail-created_at"></td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td id="detail-updated_at"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showSatuanDetail(id) {
        fetch(`/satuan/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('detail-id').innerText = data.id;
                document.getElementById('detail-nama').innerText = data.nama;
                document.getElementById('detail-created_at').innerText = new Date(data.created_at).toLocaleString();
                document.getElementById('detail-updated_at').innerText = new Date(data.updated_at).toLocaleString();

                $('#detailSatuanModal').modal('show');
            })
            .catch(error => {
                console.error('Error fetching satuan detail:', error);
                Swal.fire('Error!', 'Gagal memuat detail satuan.', 'error');
            });
    }

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
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush