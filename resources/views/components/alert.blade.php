<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500, 
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        // Menampilkan notifikasi 'error' jika ada di session
        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        // Menampilkan notifikasi untuk setiap error validasi
        @if ($errors->any())
            // Menggabungkan semua pesan error menjadi satu string HTML
            let errorMessages = '<ul>';
            @foreach ($errors->all() as $error)
                errorMessages += '<li>{{ $error }}</li>';
            @endforeach
            errorMessages += '</ul>';

            // Menampilkan notifikasi error validasi yang lebih besar dan jelas
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan Validasi',
                html: errorMessages,
                confirmButtonColor: '#3085d6',
            });
        @endif
    });
</script>
