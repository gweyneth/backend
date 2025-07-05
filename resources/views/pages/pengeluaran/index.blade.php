@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Pengeluaran</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Pengeluaran</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Pengeluaran</h5>
                <span class="float-right">
                    <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary">Tambah Pengeluaran</a>
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

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Jenis Pengeluaran</th>
                                <th>Karyawan (Jika Kasbon)</th>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengeluaran as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->jenis_pengeluaran }}</td>
                                <td>
                                    @if ($item->jenis_pengeluaran == 'Kasbon Karyawan' && $item->karyawan)
                                        {{ $item->karyawan->nama_karyawan }} (NIK: {{ $item->karyawan->nik ?? '-' }})
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->keterangan }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>Rp{{ number_format($item->harga, 2, ',', '.') }}</td>
                                <td>Rp{{ number_format($item->total, 2, ',', '.') }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('pengeluaran.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}')">Hapus</button>

                                    {{-- Form DELETE tersembunyi untuk SweetAlert --}}
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data pengeluaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-right">Total Keseluruhan Pengeluaran:</th>
                                <th colspan="3">Rp{{ number_format($totalPengeluaran, 2, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Pastikan SweetAlert2 terhubung --}}
<script>
    // Fungsi confirmDelete untuk SweetAlert2
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
