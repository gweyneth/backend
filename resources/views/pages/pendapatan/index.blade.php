@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Rincian Pendapatan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Rincian Pendapatan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Pendapatan Transaksi</h5>
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
                <form action="{{ route('pendapatan.index') }}" method="GET" class="mb-4" id="filterForm">
                    <div class="form-row align-items-end">
                        {{-- <div class="col-md-3 mb-2">
                            <label for="start_date">Dari Tgl Order:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $startDate ?? '') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="end_date">Sampai Tgl Order:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $endDate ?? '') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="tanggal_bayar_start">Dari Tgl Bayar:</label>
                            <input type="date" name="tanggal_bayar_start" id="tanggal_bayar_start" class="form-control" value="{{ old('tanggal_bayar_start', $tanggalBayarStart ?? '') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="tanggal_bayar_end">Sampai Tgl Bayar:</label>
                            <input type="date" name="tanggal_bayar_end" id="tanggal_bayar_end" class="form-control" value="{{ old('tanggal_bayar_end', $tanggalBayarEnd ?? '') }}">
                        </div> --}}
                        <div class="col-md-2 mb-2">
                            <label for="metode_pembayaran">Jenis Pembayaran:</label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control">
                                <option value="all" {{ old('metode_pembayaran', $metodePembayaran ?? '') == 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="tunai" {{ old('metode_pembayaran', $metodePembayaran ?? '') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer_bank" {{ old('metode_pembayaran', $metodePembayaran ?? '') == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="rekening_id">Bank:</label>
                            <select name="rekening_id" id="rekening_id" class="form-control">
                                <option value="">Semua Bank</option>
                                @foreach($rekening as $rek)
                                    <option value="{{ $rek->id }}" {{ old('rekening_id', $rekeningId ?? '') == $rek->id ? 'selected' : '' }}>
                                        {{ $rek->bank }} ({{ $rek->nomor_rekening }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="search_query">Cari Pelanggan / No. Transaksi:</label>
                            <input type="text" name="search_query" id="search_query" class="form-control" placeholder="Nama Pelanggan atau No. Transaksi" value="{{ old('search_query', $searchQuery ?? '') }}">
                        </div>
                        <div class="col-md-4 mb-2">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('pendapatan.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</a>
                            <button type="button" class="btn btn-danger" onclick="printPdf()"><i class="fas fa-file-pdf"></i> Cetak PDF</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>No. Transaksi</th>
                                <th>Tanggal Order</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Bayar (Terakhir)</th>
                                <th>Jenis Pembayaran</th>
                                <th>Bank (Jika Transfer)</th>
                                <th>Lampiran (Bukti Bayar)</th>
                                <th>Jumlah Uang Muka</th>
                                <th style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendapatanTransaksi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_transaksi }}</td>
                                <td>{{ $item->tanggal_order->format('d/m/Y') }}</td>
                                <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td> {{-- Menggunakan updated_at sebagai tanggal bayar terakhir --}}
                                <td>{{ ucwords(str_replace('_', ' ', $item->metode_pembayaran ?? '-')) }}</td>
                                <td>{{ $item->rekening->bank ?? '-' }}</td>
                                <td>
                                    @if ($item->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-info">Lihat Bukti</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp{{ number_format($item->uang_muka, 2, ',', '.') }}</td> {{-- Menampilkan uang muka sebagai jumlah yang dibayar --}}
                                <td>
                                    <a href="{{ route('transaksi.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data pendapatan sesuai filter.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" class="text-right">Total Keseluruhan Uang Muka (Pendapatan):</th>
                                <th>Rp{{ number_format($totalPendapatan, 2, ',', '.') }}</th>
                                <th></th> {{-- Kolom aksi --}}
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
    function printPdf() {
        const form = document.getElementById('filterForm');
        const queryString = new URLSearchParams(new FormData(form)).toString();
        window.open(`{{ route('pendapatan.print-pdf') }}?${queryString}`, '_blank');
    }
</script>
@endpush
