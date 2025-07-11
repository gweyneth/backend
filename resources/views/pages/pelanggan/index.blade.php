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
                    <a href="{{ route('pelanggan.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pelanggan</a>
                </span>
            </div>
            <div class="card-body">
                @include('components.alert')
                <form action="{{ route('pelanggan.index') }}" method="GET" id="pelangganFilterForm" class="mb-4">
                    <div class="form-row align-items-end">
                        <div class="col-auto mb-2">
                            <div class="form-group mb-0">
                                <label for="limit" class="col-form-label mr-2">Limit:</label>
                                <select name="limit" id="limit" class="form-control" onchange="this.form.submit()">
                                    @foreach ([5, 10, 25, 50, 100] as $option)
                                        <option value="{{ $option }}" {{ request('limit', 10) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="start_date">Dari Tanggal:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', request('start_date')) }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="end_date">Sampai Tanggal:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', request('end_date')) }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="search_query">Cari Nama / Kode Pelanggan:</label>
                            <input type="text" name="search_query" id="search_query" class="form-control" placeholder="Nama atau Kode Pelanggan" value="{{ old('search_query', request('search_query')) }}">
                        </div>
                        <div class="col-md-auto mb-2">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</a>
                        </div>
                    </div>
                </form>
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
                        @forelse ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kode_pelanggan ?? '-' }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_hp }}</td>
                            <td>
                                {{-- Tombol Detail sekarang akan membuka modal --}}
                                <button type="button" class="btn btn-info btn-sm" onclick="showPelangganDetail('{{ $item->id }}')">Detail</button>
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

{{-- Modal Detail Pelanggan --}}
<div class="modal fade" id="pelangganDetailModal" tabindex="-1" role="dialog" aria-labelledby="pelangganDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pelangganDetailModalLabel">Detail Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Menggunakan div dengan flexbox untuk menempatkan label dan data berdampingan --}}
                <div class="d-flex justify-content-between mb-2">
                    <strong>Kode Pelanggan:</strong> <span id="detail_kode_pelanggan"></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Nama:</strong> <span id="detail_nama"></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Email:</strong> <span id="detail_email"></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <strong>No. HP:</strong> <span id="detail_no_hp"></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Alamat:</strong> <span id="detail_alamat"></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Tanggal Dibuat:</strong> <span id="detail_created_at"></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Terakhir Diperbarui:</strong> <span id="detail_updated_at"></span>
                </div>
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
    function confirmDelete(id) {
        console.log('confirmDelete function called for ID:', id); // Tambahkan log ini
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
                console.log('User confirmed deletion for ID:', id); // Tambahkan log ini
                document.getElementById('delete-form-' + id).submit();
            } else {
                console.log('User cancelled deletion for ID:', id); // Tambahkan log ini
            }
        }).catch(error => {
            console.error('SweetAlert2 error:', error); // Tangkap error SweetAlert2
        });
    }

    function showPelangganDetail(id) {
        fetch(`/pelanggan/${id}`) // Mengambil data pelanggan via AJAX
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Isi data ke dalam elemen-elemen di modal
                document.getElementById('detail_kode_pelanggan').innerText = data.kode_pelanggan || '-';
                document.getElementById('detail_nama').innerText = data.nama || '-';
                document.getElementById('detail_email').innerText = data.email || '-';
                document.getElementById('detail_no_hp').innerText = data.no_hp || '-';
                document.getElementById('detail_alamat').innerText = data.alamat || '-';
                document.getElementById('detail_created_at').innerText = new Date(data.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
                document.getElementById('detail_updated_at').innerText = new Date(data.updated_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });

                // Tampilkan modal
                $('#pelangganDetailModal').modal('show');
            })
            .catch(error => {
                console.error('Error fetching pelanggan data:', error);
                Swal.fire('Error!', 'Gagal memuat data pelanggan.', 'error');
            });
    }
</script>
@endpush
