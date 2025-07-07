@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Transaksi</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Transaksi</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Transaksi</h5>
                <span class="float-right">
                    <a href="{{ route('transaksi.create') }}" class="btn btn-primary">Tambah Transaksi</a>
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

                {{-- Form Filter Tanggal dan Pencarian --}}
                <form action="{{ route('transaksi.index') }}" method="GET" class="mb-4">
                    <div class="form-row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label for="start_date">Dari Tanggal:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $startDate ?? '') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="end_date">Sampai Tanggal:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $endDate ?? '') }}">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="search_query">Cari Pelanggan / No. Order:</label>
                            <input type="text" name="search_query" id="search_query" class="form-control" placeholder="Nama Pelanggan atau No. Order" value="{{ old('search_query', $searchQuery ?? '') }}">
                        </div>
                        <div class="col-md-auto mb-2">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>No Order</th>
                                <th>Pelanggan</th>
                                <th>Tgl Order</th>
                                <th>Total</th>
                                <th>Uang Muka</th>
                                <th>Diskon</th>
                                <th>Sisa</th>
                                <th>Status Pembayaran</th>
                                <th>Status Pengerjaan</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_transaksi }}</td>
                                <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                                <td>{{ $item->tanggal_order->format('d/m/Y') }}</td>
                                <td>Rp{{ number_format($item->total, 2, ',', '.') }}</td>
                                <td>Rp{{ number_format($item->uang_muka, 2, ',', '.') }}</td>
                                <td>Rp{{ number_format($item->diskon, 2, ',', '.') }}</td>
                                <td>Rp{{ number_format($item->sisa, 2, ',', '.') }}</td>
                                <td>
                                    @if ($item->sisa <= 0)
                                        <span class="badge badge-success">LUNAS</span>
                                    @else
                                        <span class="badge badge-warning">BELUM LUNAS</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = '';
                                        switch ($item->status_pengerjaan) {
                                            case 'menunggu export': $statusClass = 'badge-secondary'; break;
                                            case 'belum dikerjakan': $statusClass = 'badge-danger'; break;
                                            case 'proses desain': $statusClass = 'badge-info'; break;
                                            case 'proses produksi': $statusClass = 'badge-primary'; break;
                                            case 'selesai': $statusClass = 'badge-success'; break;
                                            default: $statusClass = 'badge-light'; break;
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucwords(str_replace('_', ' ', $item->status_pengerjaan)) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('transaksi.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}')">Hapus</button>

                                    {{-- Form DELETE tersembunyi untuk SweetAlert --}}
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('transaksi.destroy', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data transaksi.</td> {{-- Sesuaikan colspan --}}
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total Keseluruhan Transaksi:</th> {{-- Sesuaikan colspan --}}
                                <th>Rp{{ number_format($totalKeseluruhanTransaksi, 2, ',', '.') }}</th>
                                <th colspan="2" class="text-right">Total Uang Muka:</th>
                                <th>Rp{{ number_format($totalUangMuka, 2, ',', '.') }}</th>
                                <th colspan="2" class="text-right">Total Piutang:</th> {{-- Sesuaikan colspan --}}
                                <th colspan="1">Rp{{ number_format($totalPiutang, 2, ',', '.') }}</th> {{-- Sesuaikan colspan --}}
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
