@extends('layouts.app')

@push('styles')
<style>
    /* Styling untuk toggle switch */
    .custom-switch .custom-control-label::before {
        left: -2.25rem;
        width: 3.5rem;
        height: 1.75rem;
        border-radius: 1.75rem;
    }
    .custom-switch .custom-control-label::after {
        top: calc(0.25rem + 2px);
        left: calc(-2.25rem + 2px);
        width: calc(1.75rem - 4px);
        height: calc(1.75rem - 4px);
        border-radius: 1.75rem;
    }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(1.75rem);
    }
    .rating-stars .fa-star {
        color: #ffc107;
    }
    .rating-stars .fa-star.text-muted {
        color: #e0e0e0;
    }
</style>
@endpush

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-comment-dots mr-2"></i>Manajemen Testimoni</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Testimoni</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list-alt mr-2"></i>Daftar Testimoni Masuk</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Pengirim</th>
                                <th>Testimoni</th>
                                <th class="text-center">Rating</th>
                                <th class="text-center">Tampilkan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($testimonials as $item)
                            <tr id="testimonial-row-{{ $item->id }}">
                                <td>{{ $loop->iteration + ($testimonials->currentPage() - 1) * $testimonials->perPage() }}</td>
                                <td>
                                    <strong>{{ $item->name }}</strong>
                                    <small class="d-block text-muted">{{ $item->position }}</small>
                                </td>
                                <td>{{ Str::limit($item->content, 150) }}</td>
                                <td class="text-center rating-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $item->rating ? '' : 'text-muted' }}"></i>
                                    @endfor
                                </td>
                                <td class="text-center">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="toggle-{{ $item->id }}" onchange="toggleStatus('{{ $item->id }}')" {{ $item->is_enabled ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="toggle-{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $item->id }}', '{{ $item->name }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada testimoni yang masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $testimonials->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleStatus(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/testimonials/${id}/toggle`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                Toast.fire({ icon: 'success', title: data.success });
            } else {
                Swal.fire('Gagal!', data.error || 'Gagal mengubah status.', 'error');
            }
        })
        .catch(error => Swal.fire('Error!', 'Tidak dapat memproses permintaan.', 'error'));
    }

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `Apakah Anda yakin ingin menghapus testimoni dari: <strong>${name}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch(`/testimonials/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                        Toast.fire({ icon: 'success', title: data.success });
                        document.getElementById(`testimonial-row-${id}`).remove();
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
