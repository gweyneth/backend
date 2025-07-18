@extends('layouts.app')

@push('styles')
<style>
    .profile-user-img-modal {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid #adb5bd;
    }
</style>
@endpush

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-user-tie mr-2"></i>Data Karyawan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Karyawan</li>
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
                    <i class="fas fa-list-alt mr-2"></i>Daftar Karyawan
                </h3>
                <div class="card-tools">
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Karyawan
                    </a>
                    {{-- Tombol Export dengan parameter filter yang aktif --}}
                    <a href="{{ route('karyawan.export_excel', request()->query()) }}" class="btn btn-success btn-sm ml-2">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- Form Filter --}}
                <form action="{{ route('karyawan.index') }}" method="GET" class="mb-4">
                    <div class="form-row">
                        <div class="col-md-4 mb-2">
                            <label for="search_query">Cari (Nama / NIK)</label>
                            <input type="text" name="search_query" id="search_query" class="form-control" placeholder="Masukkan nama atau NIK" value="{{ request('search_query') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="start_date">Dari Tanggal</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="end_date">Sampai Tanggal</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-1 mb-2">
                            <label for="limit">Limit</label>
                            <select name="limit" id="limit" class="form-control" onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $option)
                                    <option value="{{ $option }}" {{ request('limit', 10) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary ml-2"><i class="fas fa-sync-alt"></i> Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Foto</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>No. Handphone</th>
                                <th style="width: 15%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawan as $item)
                            <tr id="karyawan-row-{{ $item->id }}">
                                <td>{{ $loop->iteration + ($karyawan->currentPage() - 1) * $karyawan->perPage() }}</td>
                                <td class="text-center">
                                    <img src="{{ $item->foto ? asset('storage/' . $item->foto) : 'https://placehold.co/50x50/cccccc/333333?text=N/A' }}" alt="{{ $item->nama_karyawan }}" class="img-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                </td>
                                <td>
                                    <strong>{{ $item->nama_karyawan }}</strong>
                                    <small class="d-block text-muted">NIK: {{ $item->nik ?? '-' }}</small>
                                </td>
                                <td>{{ $item->jabatan }}</td>
                                <td>
                                    {{-- PERBAIKAN: Menampilkan status kepegawaian dengan badge berwarna --}}
                                    @php
                                        $statusClass = '';
                                        switch ($item->status) {
                                            case 'Tetap':
                                                $statusClass = 'badge-success';
                                                break;
                                            case 'Kontrak':
                                                $statusClass = 'badge-warning';
                                                break;
                                            case 'Magang':
                                                $statusClass = 'badge-info';
                                                break;
                                            default:
                                                $statusClass = 'badge-secondary';
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $item->status }}</span>
                                </td>
                                <td>{{ $item->no_handphone }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm" onclick="showKaryawanDetail('{{ $item->id }}')" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('karyawan.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}', '{{ $item->nama_karyawan }}')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data karyawan ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $karyawan->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Karyawan -->
<div class="modal fade" id="detailKaryawanModal" tabindex="-1" role="dialog" aria-labelledby="detailKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="detailKaryawanModalLabel"><i class="fas fa-user-tie mr-2"></i>Detail Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="detail-foto" src="https://placehold.co/150x150/cccccc/333333?text=N/A" alt="Foto Karyawan" class="profile-user-img-modal img-fluid img-circle mb-3">
                        <h4 id="detail-nama_karyawan-modal" class="font-weight-bold"></h4>
                        <p class="text-muted" id="detail-jabatan-modal"></p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr><th style="width: 30%;">ID Karyawan</th><td id="detail-id-karyawan"></td></tr>
                            <tr><th>NIK</th><td id="detail-nik"></td></tr>
                            <tr><th>Status</th><td id="detail-status"></td></tr>
                            <tr><th>Email</th><td id="detail-email"></td></tr>
                            <tr><th>No. Handphone</th><td id="detail-no_handphone"></td></tr>
                            <tr><th>Gaji Pokok</th><td id="detail-gaji_pokok"></td></tr>
                            <tr><th>Alamat</th><td id="detail-alamat"></td></tr>
                            <tr><th>Tanggal Bergabung</th><td id="detail-created_at"></td></tr>
                        </table>
                    </div>
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
<script>
    // Fungsi untuk menampilkan detail karyawan
    function showKaryawanDetail(id) {
        fetch(`/karyawan/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('detail-id-karyawan').innerText = data.id_karyawan;
                document.getElementById('detail-nik').innerText = data.nik ?? '-';
                document.getElementById('detail-nama_karyawan-modal').innerText = data.nama_karyawan;
                document.getElementById('detail-jabatan-modal').innerText = data.jabatan;
                
                // PERBAIKAN: Menampilkan status kepegawaian di modal
                let statusBadge = '';
                switch (data.status) {
                    case 'Tetap': statusBadge = '<span class="badge badge-success">Tetap</span>'; break;
                    case 'Kontrak': statusBadge = '<span class="badge badge-warning">Kontrak</span>'; break;
                    case 'Magang': statusBadge = '<span class="badge badge-info">Magang</span>'; break;
                    default: statusBadge = `<span class="badge badge-secondary">${data.status}</span>`;
                }
                document.getElementById('detail-status').innerHTML = statusBadge;

                document.getElementById('detail-email').innerText = data.email;
                document.getElementById('detail-no_handphone').innerText = data.no_handphone;
                document.getElementById('detail-gaji_pokok').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data.gaji_pokok);
                document.getElementById('detail-alamat').innerText = data.alamat;
                document.getElementById('detail-created_at').innerText = new Date(data.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                
                const fotoElement = document.getElementById('detail-foto');
                fotoElement.src = data.foto ? `{{ asset('storage') }}/${data.foto}` : 'https://placehold.co/150x150/cccccc/333333?text=N/A';

                $('#detailKaryawanModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Gagal memuat detail karyawan.', 'error');
            });
    }

    // Fungsi konfirmasi hapus menggunakan AJAX
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus data: <strong>${nama}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/karyawan/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        Toast.fire({ icon: 'success', title: data.success });
                        document.getElementById(`karyawan-row-${id}`).remove();
                    } else {
                        Swal.fire('Gagal!', data.error || 'Gagal menghapus data.', 'error');
                    }
                })
                .catch(error => Swal.fire('Error!', 'Tidak dapat memproses permintaan.', 'error'));
            }
        });
    }
</script>
@endpush
