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
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Karyawan</a>
                </span>
            </div>
            <div class="card-body">
                {{-- Bagian untuk menampilkan pesan flash (success/error) --}}
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

                {{-- Form untuk memilih jumlah data per halaman --}}
                {{-- PENTING: Membungkus dropdown limit dalam form terpisah --}}
                <form action="{{ route('karyawan.index') }}" method="GET" id="limitForm">
                    <div class="form-group row align-items-center mb-3">
                        <label for="limit" class="col-auto col-form-label mr-2">Tampilkan:</label>
                        <div class="col-auto">
                            <select name="limit" id="limit" class="form-control form-control-sm" onchange="this.form.submit()">
                                @foreach ([5, 10, 25, 50, 100] as $option)
                                    <option value="{{ $option }}" {{ $limit == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto col-form-label">data per halaman</div>
                    </div>
                </form>


                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Email</th>
                                <th>No. Handphone</th>
                                <th>Foto</th>
                                <th style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($karyawan as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($karyawan->currentPage() - 1) * $karyawan->perPage() }}</td>
                                <td>{{ $item->nik ?? '-' }}</td>
                                <td>{{ $item->nama_karyawan }}</td>
                                <td>{{ $item->jabatan }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->no_handphone }}</td>
                                <td>
                                    @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_karyawan }}" class="img-thumbnail" style="width: 50px; height: 67px; object-fit: cover;" onerror="this.onerror=null;this.src='https:placehold.co/50x67/cccccc/333333?text=No+Foto';">
                                    @else
                                        <img src="https://placehold.co/50x50/cccccc/333333?text=No+Foto" alt="No Photo" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                </td>
                                <td>
                                    {{-- Tombol Detail yang memicu modal --}}
                                    <button type="button" class="btn btn-info btn-sm" onclick="showKaryawanDetail('{{ $item->id }}')">Detail</button>
                                    <a href="{{ route('karyawan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    {{-- Tombol Hapus yang memicu SweetAlert --}}
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}')">Hapus</button>

                                    {{-- Form DELETE tersembunyi (akan disubmit oleh JavaScript) --}}
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('karyawan.destroy', $item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tautan Paginasi --}}
                <div class="d-flex justify-content-center mt-3">
                    {{-- Pastikan appends(request()->query()) digunakan agar parameter limit tetap ada saat navigasi paginasi --}}
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
                        <img id="detail-foto" src="https://placehold.co/150x150/cccccc/333333?text=No+Foto" alt="Foto Karyawan" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 id="detail-nama_karyawan-modal"></h4>
                        <p class="text-muted" id="detail-jabatan-modal"></p>
                    </div>
                    <div class="col-md-8">
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>ID</b> <a class="float-right" id="detail-id"></a>
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

{{-- Script untuk SweetAlert2 dan Modal Detail --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Pastikan SweetAlert2 terhubung --}}
<script>
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
                document.getElementById('detail-id').innerText = data.id;
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
                    fotoElement.src = `{{ asset('storage') }}/${data.foto}`;
                } else {
                    fotoElement.src = 'https://placehold.co/150x150/cccccc/333333?text=No+Foto';
                }
                fotoElement.onerror = function() {
                    this.onerror=null;
                    this.src='https://placehold.co/150x150/cccccc/333333?text=No+Foto';
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
        if (angka === null || angka === undefined) {
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
