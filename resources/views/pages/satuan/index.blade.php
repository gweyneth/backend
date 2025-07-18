@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-ruler-combined mr-2"></i>Data Satuan</h1>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-alt mr-2"></i>Daftar Satuan
                </h3>
                <div class="card-tools">
                    <a href="{{ route('satuan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Satuan
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- Notifikasi sekarang ditangani oleh komponen toast-alert global --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Satuan</th>
                                <th style="width: 150px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($satuans as $satuan)
                            <tr id="satuan-row-{{ $satuan->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $satuan->nama }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm" onclick="showSatuanDetail('{{ $satuan->id }}')" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('satuan.edit', $satuan->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $satuan->id }}', '{{ $satuan->nama }}')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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
</div>

{{-- Modal Detail Satuan --}}
<div class="modal fade" id="detailSatuanModal" tabindex="-1" role="dialog" aria-labelledby="detailSatuanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="detailSatuanModalLabel"><i class="fas fa-ruler mr-2"></i>Detail Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">ID</th>
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
<script>
    function showSatuanDetail(id) {
        fetch(`/satuan/${id}`)
            .then(response => response.json())
            .then(data => {
                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                document.getElementById('detail-id').innerText = data.id;
                document.getElementById('detail-nama').innerText = data.nama;
                document.getElementById('detail-created_at').innerText = new Date(data.created_at).toLocaleDateString('id-ID', options);
                document.getElementById('detail-updated_at').innerText = new Date(data.updated_at).toLocaleDateString('id-ID', options);
                $('#detailSatuanModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Gagal memuat detail satuan.', 'error');
            });
    }

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus satuan: <strong>${nama}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/satuan/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        Toast.fire({ icon: 'success', title: data.success });
                        document.getElementById(`satuan-row-${id}`).remove();
                    } else {
                        Swal.fire('Gagal!', data.error || 'Gagal menghapus data. Satuan ini mungkin sedang digunakan.', 'error');
                    }
                })
                .catch(error => Swal.fire('Error!', 'Tidak dapat memproses permintaan.', 'error'));
            }
        });
    }
</script>
@endpush
