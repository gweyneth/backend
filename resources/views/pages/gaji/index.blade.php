@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Gaji Karyawan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Gaji Karyawan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Gaji Karyawan</h5>
                <span class="float-right">
                    <a href="{{ route('gaji.create') }}" class="btn btn-primary">Input Gaji Karyawan</a>
                </span>
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
                                <th>Nama Pegawai</th>
                                <th>Jumlah Gaji</th>
                                <th>Bonus (%)</th>
                                <th>Jumlah Bonus</th>
                                <th>Kasbon</th>
                                <th>Sisa Gaji</th>
                                <th>Status Pembayaran</th>
                                <th>Tanggal</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gajiKaryawan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->karyawan->nama_karyawan ?? 'N/A' }}</td>
                                <td>Rp{{ number_format($item->jumlah_gaji, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->bonus_persen, 2, ',', '.') }}%</td>
                                <td>Rp{{ number_format($item->jumlah_bonus, 2, ',', '.') }}</td>
                                <td>
                                    @if ($item->kasbon)
                                        {{ $item->kasbon->keterangan }} (Rp{{ number_format($item->kasbon->total, 2, ',', '.') }})
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp{{ number_format($item->sisa_gaji, 2, ',', '.') }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $item->status_pembayaran)) }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('gaji.print', $item->id) }}" class="btn btn-info btn-sm mb-1" target="_blank">
                                        <i class="fas fa-print"></i> Cetak Slip Gaji
                                    </a>
                                    <a href="{{ route('gaji.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}')">Hapus</button>

                                    {{-- Form DELETE tersembunyi untuk SweetAlert --}}
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('gaji.destroy', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data gaji karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            {{-- <tr>
                                <th colspan="6" class="text-right">Total Keseluruhan Sisa Gaji:</th>
                                <th colspan="4">Rp{{ number_format($totalSisaGaji, 2, ',', '.') }}</th>
                            </tr> --}}
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Pastikan SweetAlert2 terhubung --}}
<script>
    // Fungsi confirmDelete untuk SweetAlert2
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
