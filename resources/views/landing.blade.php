<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <title>{{ $perusahaan->nama_perusahaan ?? 'Digital Printing' }} - Solusi Cetak Anda</title>
    <style>
        .hero {
            background-image:
                linear-gradient(rgba(59, 93, 80, 0.7), rgba(59, 93, 80, 0.7)),
                url("{{ isset($heroBackground) ? asset('storage/' . $heroBackground->image_path) : asset('assets/images/default-hero.jpg') }}");
            background-size: cover;
            background-position: center;
            padding: 10rem 0;
        }

        .hero .intro-excerpt h1 {
            color: #ffffff;
        }

        .hero .intro-excerpt p {
            color: rgba(255, 255, 255, 0.8);
        }

        .map-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        padding-top: 50%; /* Rasio aspek 4:3 (tinggi adalah 75% dari lebar) */
        border-radius: 8px;
    }
    .map-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }
    </style>
</head>


<body>

    <!-- Start Header/Navigation -->
    <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

        <div class="container">
            <a class="navbar-brand"
                href="index.html">{{ $perusahaan->nama_perusahaan ?? 'Digital Printing' }}</span></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
                aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsFurni">
                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item {{ request()->routeIs('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('beranda') }}">Beranda</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('shop') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('shop') }}">Produk</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('about') }}">Tentang Kami</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('services') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('services') }}">Services</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('blog.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('blog.index') }}">Blog</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('testimonialsIndex') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('testimonialsIndex') }}">Testimoni</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('contact') }}">Kontak Kami</a>
                    </li>
                </ul>

                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <li><a class="nav-link" href="{{ route('login') }}"><img
                                src="{{ asset('assets/images/user.svg') }}"></a></li>
                </ul>
            </div>
        </div>

    </nav>
    <!-- End Header/Navigation -->

    <main class="main">

        @yield('interface')

    </main>

    <!-- Start Footer Section -->
    <footer class="footer-section">
        <div class="container relative">

            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <div class="mb-4 footer-logo-wrap"><a href="#"
                            class="footer-logo">{{ $perusahaan->nama_perusahaan ?? 'Digital Printing' }}<span>.</span></a>
                    </div>
                    <p class="mb-4">Kami adalah partner terpercaya untuk semua kebutuhan cetak Anda. Menyediakan
                        solusi cetak berkualitas tinggi untuk membantu mempromosikan dan mengembangkan bisnis Anda.</p>
                    <ul class="list-unstyled custom-social">
                        @if ($perusahaan && $perusahaan->facebook)
                            <li><a href="https://facebook.com/{{ $perusahaan->facebook }}" target="_blank"><span
                                        class="fa fa-brands fa-facebook-f"></span></a></li>
                        @endif
                        @if ($perusahaan && $perusahaan->twitter)
                            <li><a href="https://twitter.com/{{ $perusahaan->twitter }}" target="_blank"><span
                                        class="fa fa-brands fa-twitter"></span></a></li>
                        @endif
                        @if ($perusahaan && $perusahaan->instagram)
                            <li><a href="https://instagram.com/{{ $perusahaan->instagram }}" target="_blank"><span
                                        class="fa fa-brands fa-instagram"></span></a></li>
                        @endif
                        @if ($perusahaan && $perusahaan->youtube)
                            <li><a href="{{ $perusahaan->youtube }}" target="_blank"><span
                                        class="fa fa-brands fa-youtube"></span></a></li>
                        @endif
                    </ul>
                </div>

                <div class="col-lg-8">
                    <div class="row links-wrap">
                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                                <li><a href="{{ route('services') }}">Layanan</a></li>
                                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                                <li><a href="{{ route('contact') }}">Kontak Kami</a></li>
                            </ul>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3">
                            <ul class="list-unstyled">
                                <li><a href="{{ route('shop') }}">Spanduk & Banner</a></li>
                                <li><a href="{{ route('shop') }}">Kartu Nama</a></li>
                                <li><a href="{{ route('shop') }}">Brosur & Flyer</a></li>
                                <li><a href="{{ route('shop') }}">Stiker</a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-md-6">
                            @if ($perusahaan && $perusahaan->maps_url)
                                <div class="map-container">
                                    {!! $perusahaan->maps_url !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-top copyright">
                <div class="row pt-4">
                    <div class="col-lg-6">
                        <p class="mb-2 text-center text-lg-start">
                            Copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script>. All Rights Reserved. &mdash;
                            <strong>{{ $perusahaan->nama_perusahaan ?? 'Digital Printing' }}</strong>
                        </p>
                    </div>

                    <div class="col-lg-6 text-center text-lg-end">
                        <ul class="list-unstyled d-inline-flex ms-auto">
                            <li class="me-4"><a href="#">Syarat & Ketentuan</a></li>
                            <li><a href="#">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </footer>
    <!-- End Footer Section -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
