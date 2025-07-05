@extends('layouts.app') {{-- Pastikan ini mengarah ke layout utama Anda --}}

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Data Produk</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Produk</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Produk</h5>
                <span class="float-right">
                    {{-- Tombol Tambah Produk --}}
                    <a href="{{ route('produk.create') }}" class="btn btn-primary">Tambah Produk</a>
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

                {{-- Tabel responsif untuk menangani banyak kolom --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Produk</th>
                                <th>Kode</th>
                                <th>Bahan</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Ukuran</th>
                                <th>Jumlah</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produks as $produk) {{-- Loop melalui data $produks --}}
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $produk->nama }}</td>
                                <td>{{ $produk->kode }}</td>
                                <td>{{ $produk->bahan->nama ?? 'N/A' }}</td>
                                <td>{{ $produk->kategori->nama ?? 'N/A' }}</td>
                                <td>{{ $produk->satuan->nama ?? 'N/A' }}</td>
                                <td>{{ $produk->ukuran ?? '-' }}</td> {{-- Tampilkan '-' jika ukuran null --}}
                                <td>{{ $produk->jumlah }}</td>
                                <td>Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</td> {{-- Format mata uang --}}
                                <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td> {{-- Format mata uang --}}
                                <td>
                                    {{-- Tombol Detail --}}
                                    <button type="button" class="btn btn-info btn-sm" onclick="showProdukDetail('{{ $produk->id }}')">Detail</button>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    {{-- Tombol Hapus --}}
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $produk->id }}')">Hapus</button>

                                    {{-- Form DELETE tersembunyi untuk SweetAlert --}}
                                    <form id="delete-form-{{ $produk->id }}" action="{{ route('produk.destroy', $produk->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">Tidak ada data produk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Opsional: Tambahkan pagination jika Anda menggunakan paginate() di controller --}}
            {{-- <div class="card-footer clearfix">
                {{ $produks->links() }}
            </div> --}}
        </div>
    </div>
</div>

<!-- Modal Detail Produk -->
<div class="modal fade" id="detailProdukModal" tabindex="-1" role="dialog" aria-labelledby="detailProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> {{-- Menggunakan modal-lg untuk tampilan yang lebih luas --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailProdukModalLabel">Detail Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th>ID</th>
                        <td id="detail-id"></td>
                    </tr>
                    <tr>
                        <th>Nama Produk</th>
                        <td id="detail-nama"></td>
                    </tr>
                    <tr>
                        <th>Kode</th>
                        <td id="detail-kode"></td>
                    </tr>
                    <tr>
                        <th>Bahan</th>
                        <td id="detail-bahan"></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td id="detail-kategori"></td>
                    </tr>
                    <tr>
                        <th>Satuan</th>
                        <td id="detail-satuan"></td>
                    </tr>
                    <tr>
                        <th>Ukuran</th>
                        <td id="detail-ukuran"></td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td id="detail-jumlah"></td>
                    </tr>
                    <tr>
                        <th>Harga Beli</th>
                        <td id="detail-harga_beli"></td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td id="detail-harga_jual"></td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td id="detail-created_at"></td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td id="detail-updated_at"></td>
                    </tr>
                </table>
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
    // Fungsi untuk menampilkan detail produk menggunakan modal
    function showProdukDetail(id) {
        // Lakukan AJAX request ke endpoint show() di controller Anda
        fetch(`/produk/${id}`) // Menggunakan fetch API modern
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Isi data ke dalam elemen-elemen modal
                document.getElementById('detail-id').innerText = data.id;
                document.getElementById('detail-nama').innerText = data.nama;
                document.getElementById('detail-kode').innerText = data.kode;
                document.getElementById('detail-bahan').innerText = data.bahan ? data.bahan.nama : 'N/A';
                document.getElementById('detail-kategori').innerText = data.kategori ? data.kategori.nama : 'N/A';
                document.getElementById('detail-satuan').innerText = data.satuan ? data.satuan.nama : 'N/A';
                document.getElementById('detail-ukuran').innerText = data.ukuran ?? '-';
                document.getElementById('detail-jumlah').innerText = data.jumlah;
                document.getElementById('detail-harga_beli').innerText = formatRupiah(data.harga_beli);
                document.getElementById('detail-harga_jual').innerText = formatRupiah(data.harga_jual);
                document.getElementById('detail-created_at').innerText = new Date(data.created_at).toLocaleString();
                document.getElementById('detail-updated_at').innerText = new Date(data.updated_at).toLocaleString();

                // Tampilkan modal
                $('#detailProdukModal').modal('show');
            })
            .catch(error => {
                console.error('Error fetching produk detail:', error);
                Swal.fire('Error!', 'Gagal memuat detail produk.', 'error');
            });
    }

    // Fungsi bantu untuk format Rupiah
    function formatRupiah(angka) {
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
            if (result.isConfirmed) { // Bagian yang sebelumnya terpotong
                // Jika user konfirmasi, submit form delete yang tersembunyi
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush