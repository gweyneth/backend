@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-money-check-alt mr-2"></i>Data Gaji Karyawan</h1>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-alt mr-2"></i>Daftar Gaji Karyawan
                </h3>
                <div class="card-tools">
                    <a href="{{ route('gaji.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Input Gaji
                    </a>
                    <button type="button" class="btn btn-success btn-sm ml-2" onclick="exportGajiKaryawanExcel()">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('gaji.index') }}" method="GET" class="mb-4" id="gajiKaryawanFilterForm">
                    <div class="form-row">
                        <div class="col-md-4 mb-2">
                            <label for="search_query">Cari Karyawan</label>
                            <input type="text" name="search_query" id="search_query" class="form-control" placeholder="Nama Karyawan" value="{{ $searchQuery ?? '' }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="bulan">Filter Bulan</label>
                            <input type="month" name="bulan" id="bulan" class="form-control" value="{{ $selectedMonth ?? date('Y-m') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="limit">Limit</label>
                            <select name="limit" id="limit" class="form-control" onchange="this.form.submit()">
                                @foreach ([10, 25, 50, 100] as $option)
                                    <option value="{{ $option }}" {{ request('limit', 10) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('gaji.index') }}" class="btn btn-secondary ml-2"><i class="fas fa-sync-alt"></i> Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
                                <th>Jumlah Gaji</th>
                                <th>Kasbon</th>
                                <th>Sisa Gaji</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($gajiKaryawan as $item)
                                <tr id="gaji-row-{{ $item->id }}">
                                    <td>{{ $loop->iteration + ($gajiKaryawan->currentPage() - 1) * $gajiKaryawan->perPage() }}</td>
                                    <td>{{ $item->karyawan->nama_karyawan ?? 'N/A' }}</td>
                                    <td>Rp{{ number_format($item->jumlah_gaji, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($item->kasbon->total ?? 0, 0, ',', '.') }}</td>
                                    <td><strong>Rp{{ number_format($item->sisa_gaji, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @php
                                            $statusClass = '';
                                            switch ($item->status_pembayaran) {
                                                case 'lunas': $statusClass = 'badge-success'; break;
                                                case 'belum_lunas': $statusClass = 'badge-danger'; break;
                                                case 'dibayar_sebagian': $statusClass = 'badge-warning'; break;
                                                default: $statusClass = 'badge-secondary';
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucwords(str_replace('_', ' ', $item->status_pembayaran)) }}</span>
                                    </td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('gaji.print', $item->id) }}" class="btn btn-info btn-sm" target="_blank" title="Cetak Slip Gaji">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a href="{{ route('gaji.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}', '{{ $item->karyawan->nama_karyawan ?? 'N/A' }}')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data gaji karyawan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(isset($totalSisaGaji) && $gajiKaryawan->isNotEmpty())
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="4" class="text-right">Total Keseluruhan Sisa Gaji:</th>
                                <th colspan="4">Rp{{ number_format($totalSisaGaji, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $gajiKaryawan->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus data gaji untuk <strong>${nama}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/gaji/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        Toast.fire({ icon: 'success', title: data.success });
                        document.getElementById(`gaji-row-${id}`).remove();
                    } else {
                        Swal.fire('Gagal!', data.error || 'Gagal menghapus data.', 'error');
                    }
                })
                .catch(error => Swal.fire('Error!', 'Tidak dapat memproses permintaan.', 'error'));
            }
        });
    }

    function exportGajiKaryawanExcel() {
        const bulan = document.getElementById('bulan').value;
        const searchQuery = document.getElementById('search_query').value;
        let url = `{{ route('gaji.export-excel') }}?bulan=${bulan}`;
        if (searchQuery) {
            url += `&search_query=${searchQuery}`;
        }
        window.location.href = url;
    }
</script>
@endpush
