@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Karyawan</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Karyawan</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Karyawan</h5>
                <span class="float-right">
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary">Tambah Karyawan</a>
                </span>
            </div>
            <div class="card-body">
                @include('components.alert')
                <form action="{{ route('karyawan.index') }}" method="GET" id="karyawanFilterForm">
                    <div class="form-row align-items-end mb-4">
                        <div class="col-auto mb-2">
                            <div class="form-group mb-0">
                                <label for="limit" class="col-form-label mr-2">Limit:</label>
                                <select name="limit" id="limit" class="form-control" onchange="this.form.submit()">
                                    @foreach ([5, 10, 25, 50, 100] as $option)
                                        <option value="{{ $option }}" {{ request('limit', 10) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="start_date">Dari Tanggal:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', request('start_date')) }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="end_date">Sampai Tanggal:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', request('end_date')) }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="search_query">Cari karyawan / NIK:</label>
                            <input type="text" name="search_query" id="search_query" class="form-control" placeholder="Nama Karyawan atau NIK" value="{{ old('search_query', request('search_query')) }}">
                        </div>
                        <div class="col-md-auto mb-2">
                            <button type="submit" class="btn btn-info" id="btnCari"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Reset</a>
                            <button type="button" class="btn btn-success" id="btnCetakExcel"><i class="fas fa-file-excel"></i> Cetak Excel</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>ID Karyawan</th> 
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Email</th>
                                <th>No. Handphone</th>
                                <th>Gaji Pokok</th>
                                <th>Foto</th>
                                <th style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawan as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($karyawan->currentPage() - 1) * $karyawan->perPage() }}</td>
                                <td>{{ $item->id_karyawan }}</td> 
                                <td>{{ $item->nik ?? '-' }}</td>
                                <td>{{ $item->nama_karyawan }}</td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->no_handphone }}</td>
                                <td>Rp{{ number_format($item->gaji_pokok, 2, ',', '.') }}</td>
                                <td>
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_karyawan }}" class="img-thumbnail" style="width: 50px; height: 67px; object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/50x67/cccccc/333333?text=No+Foto';" loading="lazy">
                                    @else
                                        <img src="https://placehold.co/50x67/cccccc/333333?text=No+Foto" alt="No Photo" class="img-thumbnail" style="width: 50px; height: 67px; object-fit: cover;">
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" onclick="showKaryawanDetail('{{ $item->id }}')">Detail</button>
                                    <a href="{{ route('karyawan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}')">Hapus</button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('karyawan.destroy', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data karyawan.</td> 
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $karyawan->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Karyawan -->
<div class="modal fade" id="detailKaryawanModal" tabindex="-1" role="dialog" aria-labelledby="detailKaryawanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailKaryawanModalLabel">Detail Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="detail-foto" src="https://placehold.co/150x200/cccccc/333333?text=No+Foto" alt="Foto Karyawan" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 200px; object-fit: cover;">
                        <h4 id="detail-nama_karyawan-modal"></h4>
                        <p class="text-muted" id="detail-jabatan-modal"></p>
                    </div>
                    <div class="col-md-8">
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>ID Database</b> <a class="float-right" id="detail-primary-id"></a> {{-- ID Primary Key --}}
                            </li>
                            <li class="list-group-item">
                                <b>ID Karyawan</b> <a class="float-right" id="detail-id-karyawan"></a> {{-- ID Karyawan yang otomatis --}}
                            </li>
                            <li class="list-group-item">
                                <b>NIK</b> <a class="float-right" id="detail-nik"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> <a class="float-right" id="detail-status"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right" id="detail-email"></a>
                            </li>
                            <li class="list-group-item">
                                <b>No. Handphone</b> <a class="float-right" id="detail-no_handphone"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Gaji Pokok</b> <a class="float-right" id="detail-gaji_pokok"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Alamat</b> <a class="float-right text-wrap" id="detail-alamat"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Dibuat Pada</b> <a class="float-right" id="detail-created_at"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Diperbarui Pada</b> <a class="float-right" id="detail-updated_at"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    // JavaScript untuk tombol Cetak Excel
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('karyawanFilterForm');
        const btnCetakExcel = document.getElementById('btnCetakExcel');
        const originalFormAction = form.action;

        if (btnCetakExcel) {
            btnCetakExcel.addEventListener('click', function() {
                form.action = "{{ route('karyawan.export_excel') }}";
                form.submit();
                setTimeout(() => {
                    form.action = originalFormAction;
                }, 100);
            });
        }
    });

    // Fungsi untuk menampilkan detail karyawan menggunakan modal
    function showKaryawanDetail(id) {
        // Lakukan AJAX request ke endpoint show() di controller Anda
        fetch(`/karyawan/${id}`) // Menggunakan fetch API modern
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Isi data ke dalam elemen-elemen modal
                document.getElementById('detail-primary-id').innerText = data.id; // ID Primary Key
                document.getElementById('detail-id-karyawan').innerText = data.id_karyawan; // ID Karyawan yang otomatis
                document.getElementById('detail-nik').innerText = data.nik ?? '-';
                document.getElementById('detail-nama_karyawan-modal').innerText = data.nama_karyawan;
                document.getElementById('detail-jabatan-modal').innerText = data.jabatan;
                document.getElementById('detail-status').innerText = data.status;
                document.getElementById('detail-alamat').innerText = data.alamat;
                document.getElementById('detail-no_handphone').innerText = data.no_handphone;
                document.getElementById('detail-email').innerText = data.email;
                document.getElementById('detail-gaji_pokok').innerText = formatRupiah(data.gaji_pokok);
                document.getElementById('detail-created_at').innerText = new Date(data.created_at).toLocaleString();
                document.getElementById('detail-updated_at').innerText = new Date(data.updated_at).toLocaleString();

                // Tampilkan foto karyawan di modal
                const fotoElement = document.getElementById('detail-foto');
                if (data.foto) {
                    // Path foto diubah ke 'uploads/karyawan_photos'
                    fotoElement.src = `{{ asset('storage') }}/${data.foto}`;
                } else {
                    fotoElement.src = 'https://placehold.co/150x200/cccccc/333333?text=No+Foto'; // Placeholder 3x4
                }
                fotoElement.onerror = function() {
                    this.onerror=null;
                    this.src='https://placehold.co/150x200/cccccc/333333?text=No+Foto'; // Placeholder 3x4
                };


                // Tampilkan modal
                $('#detailKaryawanModal').modal('show');
            })
            .catch(error => {
                console.error('Error fetching karyawan detail:', error);
                Swal.fire('Error!', 'Gagal memuat detail karyawan.', 'error');
            });
    }

    // Fungsi bantu untuk format Rupiah
    function formatRupiah(angka) {
        if (angka === null || angka === undefined || isNaN(angka)) {
            return 'Rp 0';
        }
        var reverse = angka.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return 'Rp ' + ribuan;
    }

    // Fungsi confirmDelete untuk SweetAlert2 (sudah ada sebelumnya)
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
