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
    {{-- PERBAIKAN: Ubah class div ini agar menjadi full-width --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calculator mr-1"></i>
                    Laporan Rekapitulasi
                </h3>
            </div>
            <div class="card-body">
                {{-- Form Filter --}}
                <div class="mb-4">
                    <form action="{{ route('rekapitulasi.index') }}" method="GET" id="filterForm">
                        <div class="row align-items-end">
                            <div class="col-md-4 form-group">
                                <label for="bulan">Filter Bulan</label>
                                <input type="month" name="bulan" id="bulan" class="form-control" value="{{ $selectedMonth }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Filter</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabel Rekapitulasi --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 70%;">Uraian</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="fas fa-arrow-down text-green mr-2"></i>Total Pemasukan (Omset)</td>
                                <td class="text-success font-weight-bold">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-arrow-up text-danger mr-2"></i>Total Pengeluaran</td>
                                <td class="text-danger font-weight-bold">(-) Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                            </tr>
                            <tr style="background-color: #f8f9fa;">
                                <td class="font-weight-bold text-right">LABA KOTOR (Pemasukan - Pengeluaran)</td>
                                <td class="font-weight-bold">Rp{{ number_format($labaKotor, 0, ',', '.') }}</td>
                            </tr>
                             <tr>
                                <td><i class="fas fa-hand-holding-usd text-warning mr-2"></i>Total Piutang (Tagihan Belum Dibayar)</td>
                                <td class="text-warning font-weight-bold">(-) Rp{{ number_format($totalPiutang, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-primary text-white">
                                <td class="font-weight-bold text-right">PERKIRAAN SALDO BERSIH (Kas di Tangan)</td>
                                <td class="font-weight-bold">Rp{{ number_format($saldoBersih, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="icon fas fa-info-circle"></i>
                    <strong>Catatan:</strong> "Perkiraan Saldo Bersih" adalah Laba Kotor dikurangi semua total tagihan yang belum dibayar (Piutang). Angka ini memberikan gambaran kas yang seharusnya Anda miliki jika semua piutang tidak ada.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection