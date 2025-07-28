@extends('layouts.app')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><i class="fas fa-blog mr-2"></i>Manajemen Blog</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Blog</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list-alt mr-2"></i>Daftar Postingan</h3>
                    <div class="card-tools">
                        <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i> Buat Post Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 15%;">Gambar</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Status</th>
                                    <th>Tanggal Terbit</th>
                                    <th class="text-center" style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($posts as $post)
                                    <tr id="post-row-{{ $post->id }}">
                                        <td>{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
                                        <td>
                                            <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/150x100/e9ecef/6c757d?text=No+Image' }}"
                                                alt="{{ $post->title }}" class="img-fluid rounded"
                                                style="width: 100%; height: auto; object-fit: cover;">
                                        </td>
                                        <td>
                                            <strong>{{ $post->title }}</strong>
                                            <small class="d-block text-muted">/{{ $post->slug }}</small>
                                        </td>
                                        <td>{{ $post->user->username ?? 'N/A' }}</td>
                                        <td>
                                            @if ($post->status == 'published')
                                                <span class="badge badge-success">Published</span>
                                            @else
                                                <span class="badge badge-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->created_at->format('d M Y') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                @if ($post->status == 'draft')
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="publishPost('{{ $post->id }}', '{{ $post->title }}')"
                                                        title="Publish">
                                                        <i class="fas fa-upload"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('posts.edit', $post->id) }}"
                                                    class="btn btn-warning btn-sm" title="Edit"><i
                                                        class="fas fa-edit"></i></a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete('{{ $post->id }}', '{{ $post->title }}')"
                                                    title="Hapus"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada postingan blog.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function publishPost(id, title) {
            Swal.fire({
                title: 'Konfirmasi Publikasi',
                html: `Apakah Anda yakin ingin mempublikasikan post: <strong>${title}</strong>?`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check"></i> Ya, Publikasikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch(`/posts/${id}/publish`, {
                            method: 'PATCH', // Menggunakan method PATCH
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                                Toast.fire({
                                    icon: 'success',
                                    title: data.success
                                });

                                const postRow = document.getElementById(`post-row-${id}`);
                                if (postRow) {
                                    postRow.querySelector('td:nth-child(5)').innerHTML =
                                        '<span class="badge badge-success">Published</span>';
                                    const publishButton = postRow.querySelector('button[title="Publish"]');
                                    if (publishButton) publishButton.remove();
                                }
                            } else {
                                Swal.fire('Gagal!', data.error || 'Gagal mempublikasikan data.', 'error');
                            }
                        })
                        .catch(error => Swal.fire('Error!', 'Tidak dapat memproses permintaan.', 'error'));
                }
            });
        }

        function confirmDelete(id, title) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `Apakah Anda yakin ingin menghapus post: <strong>${title}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch(`/posts/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                                Toast.fire({
                                    icon: 'success',
                                    title: data.success
                                });
                                document.getElementById(`post-row-${id}`).remove();
                            } else {
                                Swal.fire('Gagal!', data.error || 'Gagal menghapus data.', 'error');
                            }
                        })
                        .catch(error => Swal.fire('Error!', 'Tidak dapat memproses permintaan.', 'error'));
                }
            });
        }
    </script>
@endpush
