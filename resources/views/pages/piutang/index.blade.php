@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Piutang</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Piutang</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Transaksi Piutang (Belum Lunas)</h5>
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
                                <th>No. Transaksi</th>
                                <th>Nama Pelanggan</th>
                                <th>Jumlah Transaksi (Total)</th>
                                <th>Total Piutang (Sisa)</th>
                                {{-- <th style="width: 100px;">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($piutangTransaksi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_transaksi }}</td>
                                <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                                <td>Rp{{ number_format($item->total, 2, ',', '.') }}</td>
                                <td>Rp{{ number_format($item->sisa, 2, ',', '.') }}</td>
                                {{-- <td>
                                    <a href="{{ route('transaksi.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                                </td> --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data piutang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total Keseluruhan Piutang:</th>
                                <th>Rp{{ number_format($totalPiutang, 2, ',', '.') }}</th>
                                {{-- <th></th> Kolom aksi --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Anda bisa menambahkan fungsi JavaScript tambahan jika diperlukan di sini
</script>
@endpush
