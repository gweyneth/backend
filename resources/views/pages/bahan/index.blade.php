@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-box-open mr-2"></i>Data Bahan</h1>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-alt mr-2"></i>Daftar Bahan
                </h3>
                <div class="card-tools">
                    <a href="{{ route('bahan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Bahan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Bahan</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th style="width: 150px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bahans as $bahan)
                            <tr id="bahan-row-{{ $bahan->id }}">
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
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm" onclick="showBahanDetail('{{ $bahan->id }}')" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('bahan.edit', $bahan->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $bahan->id }}', '{{ $bahan->nama }}')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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
</div>

{{-- Modal Detail Bahan --}}
<div class="modal fade" id="detailBahanModal" tabindex="-1" role="dialog" aria-labelledby="detailBahanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="detailBahanModalLabel"><i class="fas fa-box-open mr-2"></i>Detail Bahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr><th style="width: 30%;">ID</th><td id="detail-id"></td></tr>
                    <tr><th>Nama Bahan</th><td id="detail-nama"></td></tr>
                    <tr><th>Kategori</th><td id="detail-kategori"></td></tr>
                    <tr><th>Stok</th><td id="detail-stok"></td></tr>
                    <tr><th>Status</th><td id="detail-status"></td></tr>
                    <tr><th>Dibuat Pada</th><td id="detail-created_at"></td></tr>
                    <tr><th>Diperbarui Pada</th><td id="detail-updated_at"></td></tr>
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
    function showBahanDetail(id) {
        fetch(`/bahan/${id}`)
            .then(response => response.json())
            .then(data => {
                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                document.getElementById('detail-id').innerText = data.id;
                document.getElementById('detail-nama').innerText = data.nama;
                document.getElementById('detail-kategori').innerText = data.kategori ? data.kategori.nama : 'N/A';
                
                let stokBadge = data.stok === 'Ada' ? '<span class="badge badge-success">Ada</span>' : '<span class="badge badge-danger">Kosong</span>';
                document.getElementById('detail-stok').innerHTML = stokBadge;

                let statusBadge = data.status === 'Aktif' ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non Aktif</span>';
                document.getElementById('detail-status').innerHTML = statusBadge;

                document.getElementById('detail-created_at').innerText = new Date(data.created_at).toLocaleDateString('id-ID', options);
                document.getElementById('detail-updated_at').innerText = new Date(data.updated_at).toLocaleDateString('id-ID', options);
                $('#detailBahanModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Gagal memuat detail bahan.', 'error');
            });
    }

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus bahan: <strong>${nama}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/bahan/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        Toast.fire({ icon: 'success', title: data.success });
                        document.getElementById(`bahan-row-${id}`).remove();
                    } else {
                        Swal.fire('Gagal!', data.error || 'Gagal menghapus data. Bahan ini mungkin sedang digunakan.', 'error');
                    }
                })
                .catch(error => Swal.fire('Error!', 'Tidak dapat memproses permintaan.', 'error'));
            }
        });
    }
</script>
@endpush
