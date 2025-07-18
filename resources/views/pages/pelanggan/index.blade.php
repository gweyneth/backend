@extends('layouts.app')

@section('content_header')
    {{-- Header Halaman --}}
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><i class="fas fa-users mr-2"></i>Data Pelanggan</h1>
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
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt mr-2"></i>
                        Daftar Pelanggan
                        @if (isset($totalPelanggan))
                            <span class="badge badge-info ml-2">{{ $totalPelanggan }} Total</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('pelanggan.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Tambah Pelanggan
                        </a>
                        <a href="{{ route('pelanggan.export') }}" class="btn btn-success btn-sm ml-2">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('pelanggan.index') }}" method="GET" class="mb-4">
                        <div class="form-row">
                            <div class="col-md-4 mb-2">
                                <label for="search_query">Cari (Nama / Kode)</label>
                                <div class="input-group">
                                    <input type="text" name="search_query" id="search_query" class="form-control"
                                           placeholder="Masukkan nama atau kode pelanggan" value="{{ request('search_query') }}">
                                </div>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="start_date">Dari Tanggal</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                       value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="end_date">Sampai Tanggal</label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                       value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-1 mb-2">
                                <label for="limit">Limit</label>
                                <select name="limit" id="limit" class="form-control" onchange="this.form.submit()">
                                    @foreach ([10, 25, 50, 100] as $option)
                                        <option value="{{ $option }}" {{ request('limit', 10) == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Cari</button>
                                <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary ml-2"><i class="fas fa-sync-alt"></i> Reset</a>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel Data Pelanggan --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Kode Pelanggan</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>No. HP</th>
                                    <th style="width: 15%;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    {{-- Menambahkan ID unik untuk setiap baris agar bisa dihapus dengan JavaScript --}}
                                    <tr id="pelanggan-row-{{ $item->id }}">
                                        <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                        <td><span class="badge badge-secondary">{{ $item->kode_pelanggan ?? '-' }}</span></td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->no_hp }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-info btn-sm" onclick="showPelangganDetail('{{ $item->id }}')" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('pelanggan.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- Mengirim nama pelanggan ke fungsi confirmDelete untuk konfirmasi --}}
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}', '{{ $item->nama }}')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>Tidak ada data pelanggan yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginasi --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Pelanggan (Kode tidak berubah) --}}
    <div class="modal fade" id="pelangganDetailModal" tabindex="-1" role="dialog" aria-labelledby="pelangganDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="pelangganDetailModalLabel"><i class="fas fa-user-circle mr-2"></i>Detail Pelanggan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 30%;">Kode Pelanggan</th>
                                <td id="detail_kode_pelanggan"></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td id="detail_nama"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="detail_email"></td>
                            </tr>
                            <tr>
                                <th>No. HP</th>
                                <td id="detail_no_hp"></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td id="detail_alamat"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td id="detail_created_at"></td>
                            </tr>
                            <tr>
                                <th>Terakhir Diperbarui</th>
                                <td id="detail_updated_at"></td>
                            </tr>
                        </tbody>
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
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch(`/pelanggan/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Menampilkan notifikasi sukses
                            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
                            Toast.fire({ icon: 'success', title: data.success });
                            
                            // Menghapus baris dari tabel dengan animasi
                            const row = document.getElementById(`pelanggan-row-${id}`);
                            row.style.transition = 'opacity 0.5s ease-out';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                            }, 500);

                        } else {
                            throw new Error(data.error || 'Terjadi kesalahan.');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.error || 'Tidak dapat memproses permintaan.',
                        });
                    });
                }
            });
        }

        // Fungsi menampilkan detail pelanggan (tidak ada perubahan)
        function showPelangganDetail(id) {
            fetch(`/pelanggan/${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('detail_kode_pelanggan').innerText = data.kode_pelanggan || '-';
                    document.getElementById('detail_nama').innerText = data.nama || '-';
                    document.getElementById('detail_email').innerText = data.email || '-';
                    document.getElementById('detail_no_hp').innerText = data.no_hp || '-';
                    document.getElementById('detail_alamat').innerText = data.alamat || '-';
                    
                    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    document.getElementById('detail_created_at').innerText = new Date(data.created_at).toLocaleDateString('id-ID', options);
                    document.getElementById('detail_updated_at').innerText = new Date(data.updated_at).toLocaleDateString('id-ID', options);

                    $('#pelangganDetailModal').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching pelanggan data:', error);
                    Swal.fire('Error!', 'Gagal memuat data pelanggan.', 'error');
                });
        }
    </script>
@endpush
