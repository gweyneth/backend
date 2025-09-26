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
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    {{-- PERBAIKAN: Judul dinamis berdasarkan filter --}}
                    Laporan Omset Penjualan 
                    @if($selectedMonth)
                        <span class="text-bold"> - {{ \Carbon\Carbon::parse($selectedMonth)->isoFormat('MMMM YYYY') }}</span>
                    @else
                        <span class="text-bold"> - Semua Waktu</span>
                    @endif
                </h3>
            </div>
            <div class="card-body">
                {{-- Form Filter --}}
                <div class="mb-4">
                    <form action="{{ route('omset.index') }}" method="GET" id="omsetFilterForm">
                        <div class="row align-items-end">
                            <div class="col-md-3 form-group">
                                <label for="produk_id">Filter Produk</label>
                                <select name="produk_id" id="produk_id" class="form-control">
                                    <option value="all">Semua Produk</option>
                                    @foreach ($produks as $produk)
                                        <option value="{{ $produk->id }}" {{ $selectedProdukId == $produk->id ? 'selected' : '' }}>
                                            {{ $produk->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="bulan">Filter Bulan</label>
                                <input type="month" name="bulan" id="bulan" class="form-control" value="{{ $selectedMonth }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Filter</button>
                                {{-- PERBAIKAN: Tombol baru untuk menampilkan semua omset --}}
                                <button type="button" class="btn btn-info" onclick="showAllOmset()"><i class="fas fa-globe mr-1"></i> Tampilkan Semua</button>
                                <a href="{{ route('omset.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i></a>
                                <button type="button" class="btn btn-success" onclick="exportOmsetExcel()"><i class="fas fa-file-excel mr-1"></i> Cetak Omset</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabel Data --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Produk</th>
                                <th>Jumlah Terjual</th>
                                <th>Total Omset</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($omsetProduk as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data['nama_produk'] }}</td>
                                <td>{{ $data['jumlah'] }}</td>
                                <td>Rp{{ number_format($data['total'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data omset penjualan untuk periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($omsetProduk->isNotEmpty())
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Subtotal Omset Keseluruhan:</th>
                                <th class="text-bold">Rp{{ number_format($subtotalOmset, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                        @endif
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

    // FUNGSI BARU: Untuk menghapus filter bulan dan submit form
    function showAllOmset() {
        // Mengosongkan nilai input bulan
        document.getElementById('bulan').value = '';
        // Submit form untuk memuat ulang data tanpa filter bulan
        document.getElementById('omsetFilterForm').submit();
    }
</script>
@endpush