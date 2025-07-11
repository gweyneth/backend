<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}"> {{-- Perubahan di sini --}}
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> {{-- Perubahan di sini --}}
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}"> {{-- Perubahan di sini --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @stack('styles')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('/dashboard') }}" class="nav-link">Home</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        
<li class="nav-item dropdown">
    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        {{ Auth::user()->name ?? 'Pengguna' }}
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="confirmLogoutNavbar(event);"> {{-- Panggil fungsi JS baru di sini --}}
            {{ __('Logout') }}
        </a>

        {{-- Form logout tersembunyi --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</li>

{{-- Tambahkan script SweetAlert untuk konfirmasi logout di sini atau di bagian script layouts.app --}}
@push('scripts') {{-- Pastikan layouts.app memiliki @stack('scripts') --}}
<script>
    // Fungsi untuk konfirmasi logout dari sidebar (jika ada)
    // Jika Anda sudah memiliki fungsi ini, pastikan namanya berbeda atau bisa di-reuse
    // Contoh: function confirmLogoutSidebar(event) { ... }

    // Fungsi baru untuk konfirmasi logout dari navbar dropdown
    function confirmLogoutNavbar(event) {
        event.preventDefault(); // Mencegah submit form default
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Apakah Anda yakin ingin keluar dari aplikasi?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form logout dengan ID 'logout-form'
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@endpush
    </ul>
  </nav>
  @include('layouts.sidebar')

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        @yield('content_header')
      </div></div>
    <section class="content">
        @if(session('success'))
<script>
    toast.fire({
        toast: true, // Tambahkan ini untuk mode toast
        position: 'top-end', // Tambahkan ini untuk posisi kanan atas
        icon: 'success',
        title: `@json(session('success'))`, // Menggunakan template literal untuk mengamankan string
        showConfirmButton: false,
        timer: 3000, 
        timerProgressBar: true, // Opsional: menambahkan progress bar
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: `@json(session('error'))`, // Menggunakan template literal untuk mengamankan string
        showConfirmButton: true 
    });
</script>
@endif

      <div class="container-fluid">
        @yield('content')
      </div></section>
    </div>
  <aside class="control-sidebar control-sidebar-dark">
    </aside>
  @include('layouts.footer')
</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script> 
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>     
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>     
<script src="{{ asset('dist/js/adminlte.js') }}"></script>

<script src="{{ asset('plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script> 
<script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script> 
<script src="{{ asset('plugins/jquery-mapael/jquery.mapael.min.js') }}"></script> 
<script src="{{ asset('plugins/jquery-mapael/maps/usa_states.min.js') }}"></script> 
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script> 

<script src="{{ asset('dist/js/demo.js') }}"></script> 
<script src="{{ asset('dist/js/pages/dashboard2.js') }}"></script> 


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('scripts')
</body>
</html>