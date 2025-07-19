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
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-hand-holding-usd mr-1"></i>
                    Daftar Transaksi Piutang (Belum Lunas)
                </h3>
            </div>
            <div class="card-body">
                {{-- Form Filter --}}
                <div class="mb-4">
                    <form action="{{-- route('piutang.index') --}}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-4 form-group">
                                <label for="search">Cari (No. Transaksi / Nama)</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Masukkan no. transaksi atau nama pelanggan..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="start_date">Dari Tanggal</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="end_date">Sampai Tanggal</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-2 form-group">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Filter</button>
                                <a href="{{-- route('piutang.index') --}}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>No. Transaksi</th>
                                <th>Nama Pelanggan</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total Piutang</th>
                                {{-- <th style="width: 150px;">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($piutangTransaksi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('transaksi.show', $item->id) }}">{{ $item->no_transaksi }}</a>
                                    <br>
                                    <small class="text-muted">{{ $item->tanggal_order->format('d M Y') }}</small>
                                </td>
                                <td>{{ $item->pelanggan->nama ?? 'Umum' }}</td>
                                <td>Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                                <td class="font-weight-bold">Rp{{ number_format($item->sisa, 0, ',', '.') }}</td>
                                {{-- <td> --}}
                                    {{-- Tombol untuk modal pembayaran
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#paymentModal" data-id="{{ $item->id }}" data-sisa="{{ $item->sisa }}">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Bayar
                                    </button> --}}
                                    {{-- <a href="{{ route('transaksi.show', $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a> --}}
                                {{-- </td> --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data piutang yang cocok dengan filter.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total Keseluruhan Piutang:</th>
                                <th colspan="1">Rp{{ number_format($totalPiutang, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
{{-- <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Form Pembayaran Piutang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="route('piutang.bayar')" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="transaksi_id" id="transaksi_id">
                    <div class="form-group">
                        <label>Sisa Piutang</label>
                        <input type="text" id="sisa_piutang_display" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_bayar">Jumlah Pembayaran</label>
                        <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@endsection

@push('scripts')
<script>
    // document.addEventListener('DOMContentLoaded', function () {
    //     $('#paymentModal').on('show.bs.modal', function (event) {
    //         var button = $(event.relatedTarget);
    //         var transaksiId = button.data('id');
    //         var sisa = button.data('sisa');

    //         var modal = $(this);
    //         modal.find('.modal-body #transaksi_id').val(transaksiId);
            
    //         // Format sisa ke Rupiah
    //         var sisaFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(sisa);
    //         modal.find('.modal-body #sisa_piutang_display').val(sisaFormatted);
            
    //         modal.find('.modal-body #jumlah_bayar').attr('max', sisa);
    //         modal.find('.modal-body #jumlah_bayar').val(sisa);
    //     });
    // });
</script>
@endpush
