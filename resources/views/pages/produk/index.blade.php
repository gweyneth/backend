@extends('layouts.app')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-boxes mr-2"></i>Data Produk</h1>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-alt mr-2"></i>Daftar Produk
                </h3>
                <div class="card-tools">
                    <a href="{{ route('produk.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah Produk
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{-- Form Filter --}}
                <form action="{{ route('produk.index') }}" method="GET" class="mb-4">
                    <div class="form-row">
                        <div class="col-md-4 mb-2">
                            <label for="search_query">Cari Produk</label>
                            <input type="text" name="search_query" id="search_query" class="form-control" placeholder="Nama atau Kode Produk" value="{{ request('search_query') }}">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="kategori_id">Filter Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-control">
                                <option value="">Semua Kategori</option>
                                {{-- Pastikan variabel $kategoriBarangs dikirim dari controller --}}
                                @foreach($kategoriBarangs as $kategori)
                                    <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Cari</button>
                            <a href="{{ route('produk.index') }}" class="btn btn-secondary ml-2"><i class="fas fa-sync-alt"></i> Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th style="width: 15%;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produks as $produk)
                            <tr id="produk-row-{{ $produk->id }}">
                                <td>{{ $loop->iteration + ($produks->currentPage() - 1) * $produks->perPage() }}</td>
                                <td>
                                    <strong>{{ $produk->nama }}</strong>
                                    <small class="d-block text-muted">Kode: {{ $produk->kode }}</small>
                                </td>
                                <td>{{ $produk->kategori->nama ?? 'N/A' }}</td>
                                <td>{{ $produk->satuan->nama ?? 'N/A' }}</td>
                                <td>{{ $produk->jumlah }}</td>
                                <td>Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info btn-sm" onclick="showProdukDetail('{{ $produk->id }}')" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $produk->id }}', '{{ $produk->nama }}')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data produk ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Paginasi --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $produks->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Produk -->
<div class="modal fade" id="detailProdukModal" tabindex="-1" role="dialog" aria-labelledby="detailProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="detailProdukModalLabel"><i class="fas fa-box mr-2"></i>Detail Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr><th style="width: 30%;">ID</th><td id="detail-id"></td></tr>
                    <tr><th>Nama Produk</th><td id="detail-nama"></td></tr>
                    <tr><th>Kode</th><td id="detail-kode"></td></tr>
                    <tr><th>Bahan</th><td id="detail-bahan"></td></tr>
                    <tr><th>Kategori</th><td id="detail-kategori"></td></tr>
                    <tr><th>Satuan</th><td id="detail-satuan"></td></tr>
                    <tr><th>Ukuran</th><td id="detail-ukuran"></td></tr>
                    <tr><th>Jumlah (Stok)</th><td id="detail-jumlah"></td></tr>
                    <tr><th>Harga Beli</th><td id="detail-harga_beli"></td></tr>
                    <tr><th>Harga Jual</th><td id="detail-harga_jual"></td></tr>
                    <tr><th>Dibuat Pada</th><td id="detail-created_at"></td></tr>
                    <tr><th>Diperbarui Pada</th><td id="detail-updated_at"></td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showProdukDetail(id) {
        fetch(`/produk/${id}`)
            .then(response => response.json())
            .then(data => {
                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                document.getElementById('detail-id').innerText = data.id;
                document.getElementById('detail-nama').innerText = data.nama;
                document.getElementById('detail-kode').innerText = data.kode;
                document.getElementById('detail-bahan').innerText = data.bahan ? data.bahan.nama : 'N/A';
                document.getElementById('detail-kategori').innerText = data.kategori ? data.kategori.nama : 'N/A';
                document.getElementById('detail-satuan').innerText = data.satuan ? data.satuan.nama : 'N/A';
                document.getElementById('detail-ukuran').innerText = data.ukuran ?? '-';
                document.getElementById('detail-jumlah').innerText = data.jumlah;
                document.getElementById('detail-harga_beli').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data.harga_beli);
                document.getElementById('detail-harga_jual').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data.harga_jual);
                document.getElementById('detail-created_at').innerText = new Date(data.created_at).toLocaleDateString('id-ID', options);
                document.getElementById('detail-updated_at').innerText = new Date(data.updated_at).toLocaleDateString('id-ID', options);
                $('#detailProdukModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Gagal memuat detail produk.', 'error');
            });
    }

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus produk: <strong>${nama}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/produk/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        Toast.fire({ icon: 'success', title: data.success });
                        document.getElementById(`produk-row-${id}`).remove();
                    } else {
                        Swal.fire('Gagal!', data.error || 'Gagal menghapus data. Produk ini mungkin terkait dengan data lain.', 'error');
                    }
                })
                .catch(error => Swal.fire('Error!', 'Tidak dapat memproses permintaan.', 'error'));
            }
        });
    }
</script>
@endpush
