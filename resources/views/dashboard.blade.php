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
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1" style="font-size: 2.5rem; width: 80px; height: 80px; line-height: 80px;">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1rem;">Orderan Total</span>
                        <span class="info-box-number" style="font-size: 1.8rem;">
                            {{ $totalOrderan }}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            {{-- Orderan hari ini --}}
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1" style="font-size: 2.5rem; width: 80px; height: 80px; line-height: 80px;">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1rem;">Orderan hari ini</span>
                        <span class="info-box-number" style="font-size: 1.8rem;">{{ $orderanHariIni }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            {{-- Orderan Bulan Ini --}}
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1" style="font-size: 2.5rem; width: 80px; height: 80px; line-height: 80px;">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1rem;">Orderan Bulan Ini</span>
                        <span class="info-box-number" style="font-size: 1.8rem;">{{ $orderanBulanIni }}</span>
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
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1" style="font-size: 2.5rem; width: 80px; height: 80px; line-height: 80px;">
                        <i class="fas fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1rem;">Total Konsumen</span>
                        <span class="info-box-number" style="font-size: 1.8rem;">
                            {{ $totalKonsumen }}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            {{-- Total omset hari ini --}}
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-secondary elevation-1" style="font-size: 2.5rem; width: 80px; height: 80px; line-height: 80px;">
                        <i class="fas fa-money-bill-wave"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1rem;">Total omset hari ini</span>
                        <span class="info-box-number" style="font-size: 1.8rem;">Rp{{ number_format($totalOmsetHariIni, 0, ',', '.') }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            {{-- Total pengeluaran hari ini --}}
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1" style="font-size: 2.5rem; width: 80px; height: 80px; line-height: 80px;">
                        <i class="fas fa-money-bill-wave"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-size: 1rem;">Total pengeluaran hari ini</span>
                        <span class="info-box-number" style="font-size: 1.8rem;">Rp{{ number_format($totalPengeluaranHariIni, 0, ',', '.') }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row mt-4">
            {{-- Card Transaksi Terbaru --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Transaksi</h3>
                        <div class="card-tools">
                            <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-primary">Semua ></a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Konsumen</th>
                                        <th>Status</th>
                                        {{-- Kolom Kasir dihapus --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transaksi.show', $transaction->id) }}">
                                                {{ $transaction->no_transaksi }}
                                            </a>
                                        </td>
                                        <td>{{ $transaction->pelanggan->nama ?? 'Umum' }}</td>
                                        <td>
                                            @php
                                                $statusClass = '';
                                                if ($transaction->sisa == 0) {
                                                    $statusClass = 'badge badge-success'; // Lunas
                                                } else if ($transaction->uang_muka > 0 && $transaction->sisa > 0) {
                                                    $statusClass = 'badge badge-warning'; // Bayar Sebagian
                                                } else {
                                                    $statusClass = 'badge badge-danger'; // Belum Lunas
                                                }
                                            @endphp
                                            <span class="{{ $statusClass }}">
                                                @if ($transaction->sisa == 0)
                                                    Lunas
                                                @elseif ($transaction->uang_muka > 0 && $transaction->sisa > 0)
                                                    Bayar Sebagian
                                                @else
                                                    Belum Lunas
                                                @endif
                                            </span>
                                        </td>
                                        {{-- Data Kasir dihapus --}}
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada transaksi terbaru.</td> {{-- colspan disesuaikan --}}
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->

            {{-- Card Piutang Terbesar --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Piutang Terbesar</h3>
                        <div class="card-tools">
                            <a href="{{ route('piutang.index') }}" class="btn btn-sm btn-primary">Semua ></a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Invoice</th>
                                        <th>Pelanggan</th>
                                        <th>Sisa Piutang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($largestReceivables as $index => $receivable)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('transaksi.show', $receivable->id) }}">
                                                {{ $receivable->no_transaksi }}
                                            </a>
                                        </td>
                                        <td>{{ $receivable->pelanggan->nama ?? 'Umum' }}</td>
                                        <td>Rp{{ number_format($receivable->sisa, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data piutang terbesar.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end', // Posisi di kanan atas
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Tampilkan pesan sukses dari sesi (misal: setelah berhasil login)
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        // Tampilkan pesan error dari sesi (misal: username/password salah)
        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                Toast.fire({
                    icon: 'error',
                    title: '{{ $error }}'
                });
            @endforeach
        @endif
    });
</script>

@push('styles')
    {{-- Tambahkan style khusus untuk halaman dashboard di sini jika diperlukan --}}
@endpush
