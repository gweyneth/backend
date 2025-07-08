@extends('layouts.app')

@section('content_header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Welcome</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            {{-- Orderan Total --}}
            <div class="col-12 col-sm-6 col-md-4"> {{-- Diubah dari col-md-3 menjadi col-md-4 --}}
                <div class="info-box"> {{-- Latar belakang kembali putih --}}
                    <span class="info-box-icon bg-info elevation-1" style="font-size: 3rem; width: 90px; height: 90px; line-height: 90px;">
                        <i class="fas fa-shopping-cart"></i>
                    </span> {{-- Ikon tetap berwarna dan ukuran disesuaikan --}}
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1.1rem;">Orderan Total</span>
                        <span class="info-box-number" style="font-size: 2rem;">
                            {{ $totalOrderan }}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            {{-- Orderan hari ini --}}
            <div class="col-12 col-sm-6 col-md-4"> {{-- Diubah dari col-md-3 menjadi col-md-4 --}}
                <div class="info-box mb-3"> {{-- Latar belakang kembali putih --}}
                    <span class="info-box-icon bg-danger elevation-1" style="font-size: 3rem; width: 90px; height: 90px; line-height: 90px;">
                        <i class="fas fa-shopping-cart"></i>
                    </span> {{-- Ikon tetap berwarna dan ukuran disesuaikan --}}
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1.1rem;">Orderan hari ini</span>
                        <span class="info-box-number" style="font-size: 2rem;">{{ $orderanHariIni }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            {{-- Orderan Bulan Ini --}}
            <div class="col-12 col-sm-6 col-md-4"> {{-- Diubah dari col-md-3 menjadi col-md-4 --}}
                <div class="info-box mb-3"> {{-- Latar belakang kembali putih --}}
                    <span class="info-box-icon bg-success elevation-1" style="font-size: 3rem; width: 90px; height: 90px; line-height: 90px;">
                        <i class="fas fa-shopping-cart"></i>
                    </span> {{-- Ikon tetap berwarna dan ukuran disesuaikan --}}
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1.1rem;">Orderan Bulan Ini</span>
                        <span class="info-box-number" style="font-size: 2rem;">{{ $orderanBulanIni }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            {{-- Total Konsumen --}}
            <div class="col-12 col-sm-6 col-md-4"> {{-- Diubah dari col-md-3 menjadi col-md-4 --}}
                <div class="info-box"> {{-- Latar belakang kembali putih --}}
                    <span class="info-box-icon bg-info elevation-1" style="font-size: 3rem; width: 90px; height: 90px; line-height: 90px;">
                        <i class="fas fa-users"></i>
                    </span> {{-- Ikon tetap berwarna dan ukuran disesuaikan --}}
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1.1rem;">Total Konsumen</span>
                        <span class="info-box-number" style="font-size: 2rem;">
                            {{ $totalKonsumen }}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            {{-- Total omset hari ini --}}
            <div class="col-12 col-sm-6 col-md-4"> {{-- Diubah dari col-md-3 menjadi col-md-4 --}}
                <div class="info-box mb-3"> {{-- Latar belakang kembali putih --}}
                    <span class="info-box-icon bg-secondary elevation-1" style="font-size: 3rem; width: 90px; height: 90px; line-height: 90px;">
                        <i class="fas fa-money-bill-wave"></i>
                    </span> {{-- Ikon tetap berwarna dan ukuran disesuaikan --}}
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1.1rem;">Total omset hari ini</span>
                        <span class="info-box-number" style="font-size: 2rem;">Rp{{ number_format($totalOmsetHariIni, 0, ',', '.') }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            {{-- Total pengeluaran hari ini --}}
            <div class="col-12 col-sm-6 col-md-4"> 
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1" style="font-size: 3rem; width: 90px; height: 90px; line-height: 90px;">
                        <i class="fas fa-money-bill-wave"></i>
                    </span> {{-- Ikon tetap berwarna dan ukuran disesuaikan --}}
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1.1rem;">Total pengeluaran hari ini</span>
                        <span class="info-box-number" style="font-size: 2rem;">Rp{{ number_format($totalPengeluaranHariIni, 0, ',', '.') }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@push('scripts')
    {{-- Tambahkan script khusus untuk halaman dashboard di sini jika diperlukan --}}
@endpush

@push('styles')
    {{-- Tambahkan style khusus untuk halaman dashboard di sini jika diperlukan --}}
@endpush
