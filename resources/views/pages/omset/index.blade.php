@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Omset Penjualan Produk</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Omset Penjualan Produk</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Laporan Omset Penjualan Produk</h5>
            </div>
            <div class="card-body">
                {{-- Form Filter --}}
                <form action="{{ route('omset.index') }}" method="GET" class="mb-4" id="omsetFilterForm">
                    <div class="form-row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label for="produk_id">Filter Produk:</label>
                            <select name="produk_id" id="produk_id" class="form-control">
                                <option value="all">Semua Produk</option>
                                @foreach ($produks as $produk)
                                    <option value="{{ $produk->id }}" {{ $selectedProdukId == $produk->id ? 'selected' : '' }}>
                                        {{ $produk->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="bulan">Filter Bulan:</label>
                            <input type="month" name="bulan" id="bulan" class="form-control" value="{{ $selectedMonth }}">
                        </div>
                        <div class="col-md-5 mb-2">
                            <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filter</button>
                            <a href="{{ route('omset.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</a>
                            <button type="button" class="btn btn-success" onclick="exportOmsetExcel()"><i class="fas fa-file-excel"></i> Cetak Omset</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Produk</th>
                                <th>Jumlah Terjual</th>
                                <th>Total Omset</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($omsetProduk as $index => $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data['nama_produk'] }}</td>
                                <td>{{ $data['jumlah'] }}</td>
                                <td>Rp{{ number_format($data['total'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data omset penjualan produk untuk periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Subtotal Omset Keseluruhan:</th>
                                <th>Rp{{ number_format($subtotalOmset, 0, ',', '.') }}</th>
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
    function exportOmsetExcel() {
        const form = document.getElementById('omsetFilterForm');
        const queryString = new URLSearchParams(new FormData(form)).toString();
        window.location.href = `{{ route('omset.export-excel') }}?${queryString}`;
    }
</script>
@endpush
