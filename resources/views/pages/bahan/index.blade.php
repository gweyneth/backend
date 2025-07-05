@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Bahan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Bahan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Bahan</h5>
                <span class="float-right">
                    <a href="{{ route('bahan.create') }}" class="btn btn-primary">Tambah Bahan</a>
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
                            <th>Nama Bahan</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bahans as $bahan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $bahan->nama }}</td>
                            <td>{{ $bahan->kategori->nama ?? 'N/A' }}</td>
                            <td>
                                @if($bahan->stok == 'Ada')
                                    <span class="badge badge-success">Ada</span>
                                @else
                                    <span class="badge badge-danger">Kosong</span>
                                @endif
                            </td>
                            <td>
                                @if($bahan->status == 'Aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Non Aktif</span>
                                @endif
                            </td>
                            <td>
                                {{-- Ubah tombol detail untuk memanggil fungsi JavaScript --}}
                                <button type="button" class="btn btn-info btn-sm" onclick="showBahanDetail('{{ $bahan->id }}')">Detail</button>
                                <a href="{{ route('bahan.edit', $bahan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $bahan->id }}')">Hapus</button>

                                <form id="delete-form-{{ $bahan->id }}" action="{{ route('bahan.destroy', $bahan->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data bahan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailBahanModal" tabindex="-1" role="dialog" aria-labelledby="detailBahanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailBahanModalLabel">Detail Bahan</h5>
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
                        <th>Nama Bahan</th>
                        <td id="detail-nama"></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td id="detail-kategori"></td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td id="detail-stok"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="detail-status"></td>
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
{{-- Pastikan jQuery dan Bootstrap JS Anda sudah terhubung di layout utama --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi untuk menampilkan detail bahan menggunakan modal
    function showBahanDetail(id) {
        // Lakukan AJAX request ke endpoint show() di controller Anda
        fetch(`/bahan/${id}`) // Menggunakan fetch API modern, atau bisa pakai jQuery $.ajax
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Isi data ke dalam elemen-elemen modal
                document.getElementById('detail-id').innerText = data.id;
                document.getElementById('detail-nama').innerText = data.nama;
                // Pastikan kategori ada sebelum mengakses namanya
                document.getElementById('detail-kategori').innerText = data.kategori ? data.kategori.nama : 'N/A';
                document.getElementById('detail-stok').innerText = data.stok;
                document.getElementById('detail-status').innerText = data.status;
                document.getElementById('detail-created_at').innerText = new Date(data.created_at).toLocaleString();
                document.getElementById('detail-updated_at').innerText = new Date(data.updated_at).toLocaleString();

                // Tampilkan modal
                $('#detailBahanModal').modal('show');
            })
            .catch(error => {
                console.error('Error fetching bahan detail:', error);
                Swal.fire('Error!', 'Gagal memuat detail bahan.', 'error');
            });
    }

    // Fungsi confirmDelete untuk SweetAlert2 (sudah ada sebelumnya)
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