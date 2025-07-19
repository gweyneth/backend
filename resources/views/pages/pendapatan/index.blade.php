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
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-invoice-dollar mr-1"></i>
                    Daftar Pendapatan Transaksi
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm" onclick="exportExcel()">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                {{-- Form Filter --}}
                <div class="mb-4">
                    <form action="{{ route('pendapatan.index') }}" method="GET" id="filterForm">
                        <div class="row align-items-end">
                            <div class="col-md-auto form-group">
                                <label for="search_query">Cari (No. Transaksi / Pelanggan)</label>
                                <input type="text" name="search_query" id="search_query" class="form-control" placeholder="Masukkan kata kunci..." value="{{ request('search_query') }}">
                            </div>
                            <div class="col-md-auto form-group">
                                <label for="start_date">Dari Tanggal</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                             <div class="col-md-auto form-group">
                                <label for="end_date">Sampai Tanggal</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-auto form-group">
                                <label for="metode_pembayaran">Metode Bayar</label>
                                <select name="metode_pembayaran" id="metode_pembayaran" class="form-control">
                                    <option value="all">Semua</option>
                                    <option value="tunai" {{ request('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="transfer_bank" {{ request('metode_pembayaran') == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                                </select>
                            </div>
                            <div class="col-md-auto form-group" id="rekening_filter_container" style="display: {{ request('metode_pembayaran') == 'transfer_bank' ? 'block' : 'none' }};">
                                <label for="rekening_id">Bank</label>
                                <select name="rekening_id" id="rekening_id" class="form-control">
                                    <option value="">Semua Bank</option>
                                    @foreach($rekening as $rek)
                                        <option value="{{ $rek->id }}" {{ request('rekening_id') == $rek->id ? 'selected' : '' }}>
                                            {{ $rek->bank }} ({{ $rek->nomor_rekening }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-auto form-group">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Filter</button>
                                <a href="{{ route('pendapatan.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>No. Transaksi</th>
                                <th>Pelanggan</th>
                                <th>Tgl. Bayar</th>
                                <th>Metode Bayar</th>
                                <th>Bank</th>
                                <th>Jumlah Dibayar</th>
                                <th>Total Transaksi</th>
                                {{-- <th>Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendapatanTransaksi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->no_transaksi }}</td>
                                <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                                <td>{{ $item->updated_at->format('d M Y') }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $item->metode_pembayaran ?? '-')) }}</td>
                                <td>{{ $item->rekening->bank ?? '-' }}</td>
                                <td>Rp{{ number_format($item->uang_muka, 0, ',', '.') }}</td>
                                <td class="font-weight-bold">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                                {{-- <td>
                                    @if ($item->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-secondary" title="Lihat Bukti Bayar"><i class="fas fa-receipt"></i></a>
                                    @endif --}}
                                    {{-- <a href="{{ route('transaksi.show', $item->id) }}" class="btn btn-info btn-sm" title="Lihat Detail Transaksi"><i class="fas fa-eye"></i></a> --}}
                                {{-- </td> --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data pendapatan sesuai filter.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-right">Total Keseluruhan Pendapatan:</th>
                                <th colspan="2">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</th>
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
<script>
    function exportExcel() {
        const form = document.getElementById('filterForm');
        const queryString = new URLSearchParams(new FormData(form)).toString();
        // Pastikan Anda membuat route 'pendapatan.export-excel'
        window.open(`{{ route('pendapatan.export-excel') }}?${queryString}`, '_blank');
    }

    document.getElementById('metode_pembayaran').addEventListener('change', function() {
        const rekeningFilter = document.getElementById('rekening_filter_container');
        if (this.value === 'transfer_bank') {
            rekeningFilter.style.display = 'block';
        } else {
            rekeningFilter.style.display = 'none';
            document.getElementById('rekening_id').value = ''; // Reset pilihan bank
        }
    });
</script>
@endpush
