@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Rekapitulasi Keuangan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Rekapitulasi Keuangan</li>
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
                    <i class="fas fa-calculator mr-1"></i>
                    Laporan Rekapitulasi: <strong>{{ $periodeJudul }}</strong>
                </h3>
            </div>
            <div class="card-body">
                {{-- Form Filter --}}
                <div class="mb-4">
                    <form action="{{ route('rekapitulasi.index') }}" method="GET" id="filterForm">
                         <div class="row align-items-end">
                            <div class="col-md-auto form-group">
                                <label for="bulan">Filter Per Bulan</label>
                                <input type="month" name="bulan" id="bulan" class="form-control" value="{{ $selectedMonth }}">
                            </div>
                            <div class="col-md-auto form-group">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Filter</button>
                                <a href="{{ route('rekapitulasi.index', ['periode' => 'all']) }}" class="btn btn-secondary">
                                    <i class="fas fa-globe mr-1"></i> Tampilkan Semua
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabel Rekapitulasi Detail --}}
                <div class="table-responsive">
                    <table class="table table-bordered">
                        {{-- Bagian Pemasukan --}}
                        <thead class="thead-light">
                            <tr>
                                <th><i class="fas fa-arrow-down text-green mr-2"></i> Uraian Pemasukan (Omset)</th>
                                <th class="text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($detailPemasukan as $item)
                                <tr>
                                    <td class="pl-4"><small>({{ $item->transaksi->no_transaksi ?? 'N/A' }}) - {{ $item->produk->nama ?? 'Produk Dihapus' }} ({{ $item->qty }}x)</small></td>
                                    <td class="text-right">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center"><em>Tidak ada pemasukan pada periode ini.</em></td>
                                </tr>
                            @endforelse
                            <tr class="bg-light">
                                <td class="font-weight-bold text-right">Total Pemasukan</td>
                                <td class="font-weight-bold text-right text-success">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>

                        {{-- Bagian Pengeluaran --}}
                        <thead class="thead-light mt-4">
                            <tr>
                                <th><i class="fas fa-arrow-up text-danger mr-2"></i> Uraian Pengeluaran</th>
                                <th class="text-right">Jumlah</th>
                            </tr>
                        </thead>
                         <tbody>
                            @forelse ($detailPengeluaran as $item)
                                <tr>
                                    <td class="pl-4"><small>{{ $item->keterangan }}</small></td>
                                    <td class="text-right">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center"><em>Tidak ada pengeluaran pada periode ini.</em></td>
                                </tr>
                            @endforelse
                            <tr class="bg-light">
                                <td class="font-weight-bold text-right">Total Pengeluaran</td>
                                <td class="font-weight-bold text-right text-danger">(-) Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>

                         {{-- Bagian Ringkasan Akhir --}}
                        <tfoot style="background-color: #e9ecef;">
                            <tr>
                                <td class="font-weight-bold text-right">LABA KOTOR (Pemasukan - Pengeluaran)</td>
                                <td class="font-weight-bold text-right">Rp{{ number_format($labaKotor, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-right">Total Piutang (Tagihan Belum Dibayar)</td>
                                <td class="font-weight-bold text-right text-warning">(-) Rp{{ number_format($totalPiutang, 0, ',', '.') }}</td>
                            </tr>
                             <tr class="bg-info text-white">
                                <td class="font-weight-bold text-right">PERKIRAAN SALDO BERSIH (Kas di Tangan)</td>
                                <td class="font-weight-bold text-right">Rp{{ number_format($saldoBersih, 0, ',', '.') }}</td>
                            </tr>
                             <tr class="bg-primary text-white">
                                <td class="font-weight-bold text-right">TOTAL ASET PERIODE INI (Kas + Piutang)</td>
                                <td class="font-weight-bold text-right">Rp{{ number_format($totalAset, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                 <div class="alert alert-info mt-3">
                    <i class="icon fas fa-info-circle"></i>
                    <strong>Catatan:</strong> "Total Aset Periode Ini" adalah representasi dari Laba Kotor Anda (Pemasukan dikurangi Pengeluaran). Angka ini menunjukkan nilai total yang dihasilkan, baik dalam bentuk kas maupun tagihan yang belum dibayar.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection