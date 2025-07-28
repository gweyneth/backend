@extends('landing')

@section('title', $perusahaan->nama_perusahaan ?? 'Digital Printing & Advertising')

@section('interface')

    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-12">
                    <div class="intro-excerpt">
                        <h1>Solusi Cetak <span class="d-block">Kebutuhan Bisnis Anda</span></h1>
                        <p class="mb-4">Dari kartu nama hingga spanduk, kami menyediakan layanan cetak berkualitas tinggi
                            dengan cepat, mudah, dan harga terjangkau.</p>
                        <p><a href="{{ route('shop') }}" class="btn btn-secondary me-2">Lihat Produk</a><a
                                href="{{ route('contact') }}" class="btn btn-white-outline">Hubungi Kami</a></p>
                    </div>
                </div>
                {{-- <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        <img src="{{ isset($heroBackground) ? asset('storage/' . $heroBackground->image_path) : asset('assets/images/couch.png') }}" class="img-fluid">
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="product-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                    <h2 class="mb-4 section-title">Produk & Layanan Kami.</h2>
                    <p class="mb-4">Kami menyediakan berbagai macam produk cetak untuk memenuhi segala kebutuhan promosi
                        dan administrasi bisnis Anda.</p>
                    <p><a href="{{ route('shop') }}" class="btn">Selengkapnya</a></p>
                </div>
                @forelse ($produks as $produk)
                    @php
                        // Menyiapkan nomor WhatsApp dari data perusahaan
                        $whatsappNumber = preg_replace('/[^0-9]/', '', $perusahaan->no_handphone ?? '');
                        if (substr($whatsappNumber, 0, 1) === '0') {
                            $whatsappNumber = '62' . substr($whatsappNumber, 1);
                        }

                        // Menyiapkan pesan otomatis dengan nama produk
                        $message = urlencode('Halo, saya tertarik dengan produk: ' . $produk->nama);

                        // Membuat URL lengkap untuk WhatsApp
                        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$message}";
                    @endphp
                    <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                        {{-- PERBAIKAN: Link sekarang mengarah ke WhatsApp --}}
                        <a class="product-item" href="{{ $whatsappUrl }}" target="_blank">
                            <img src="{{ $produk->foto ? asset('storage/' . $produk->foto) : 'https://placehold.co/300x300/e8e8e8/000000?text=' . urlencode($produk->nama) }}"
                                class="img-fluid product-thumbnail">
                            <h3 class="product-title">{{ $produk->nama }}</h3>
                            <strong class="product-price">Rp{{ number_format($produk->harga_jual, 0, ',', '.') }}</strong>

                            {{-- PERBAIKAN: Ikon diubah menjadi ikon WhatsApp --}}
                            <span class="icon-cross">
                                <i class="fab fa-whatsapp" style="font-size: 28px; color: white;"></i>
                            </span>
                        </a>
                    </div>
                @empty
                    <div class="col">
                        <p>Produk unggulan akan segera ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- End Product Section -->

    <!-- Start Why Choose Us Section -->
    <div class="why-choose-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <h2 class="section-title">Mengapa Memilih Kami?</h2>
                    <p>Kami berkomitmen untuk memberikan hasil cetak terbaik dengan layanan yang memuaskan untuk mendukung
                        kesuksesan bisnis Anda.</p>
                    <div class="row my-5">
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon"><img src="{{ asset('assets/images/truck.svg') }}" alt="Image"
                                        class="imf-fluid"></div>
                                <h3>Cepat &amp; Tepat Waktu</h3>
                                <p>Proses produksi efisien memastikan pesanan Anda selesai sesuai jadwal.</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon"><img src="{{ asset('assets/images/bag.svg') }}" alt="Image"
                                        class="imf-fluid"></div>
                                <h3>Harga Kompetitif</h3>
                                <p>Dapatkan penawaran harga terbaik tanpa mengorbankan kualitas hasil cetakan.</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon"><img src="{{ asset('assets/images/support.svg') }}" alt="Image"
                                        class="imf-fluid"></div>
                                <h3>Layanan Pelanggan</h3>
                                <p>Tim kami siap membantu Anda dari proses konsultasi hingga pesanan selesai.</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon"><img src="{{ asset('assets/images/return.svg') }}" alt="Image"
                                        class="imf-fluid"></div>
                                <h3>Kualitas Terjamin</h3>
                                <p>Kami menggunakan bahan dan teknologi cetak terbaik untuk hasil yang tajam dan tahan lama.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="img-wrap"><img src="{{ asset('assets/images/why-choose-us-img.jpg') }}" alt="Image"
                            class="img-fluid"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Why Choose Us Section -->

    <!-- Start Testimonial Slider -->
    <div class="testimonial-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="section-title">Testimoni</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="testimonial-slider-wrap text-center">
                        <div id="testimonial-nav">
                            <span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
                            <span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
                        </div>
                        <div class="testimonial-slider">
                            @forelse($testimonials as $testimonial)
                                <div class="item">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 mx-auto">
                                            <div class="testimonial-block text-center">
                                                <blockquote class="mb-5">
                                                    <p>&ldquo;{{ $testimonial->content }}&rdquo;</p>
                                                </blockquote>
                                                <div class="author-info">
                                                    <div class="author-pic"><img
                                                            src="{{ $testimonial->photo ? asset('storage/' . $testimonial->photo) : asset('assets/images/person-1.png') }}"
                                                            alt="Foto {{ $testimonial->name }}" class="img-fluid"></div>
                                                    <h3 class="font-weight-bold">{{ $testimonial->name }}</h3>
                                                    <span
                                                        class="position d-block mb-3">{{ $testimonial->position ?? 'Pelanggan' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="item">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 mx-auto">
                                            <p>Jadilah yang pertama memberikan ulasan!</p>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Testimonial Slider -->

    <!-- Start Blog Section -->
    <div class="blog-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-6">
                    <h2 class="section-title">Blog Terbaru</h2>
                </div>
                <div class="col-md-6 text-start text-md-end"><a href="{{ route('blog.index') }}" class="more">Lihat
                        Semua Postingan</a></div>
            </div>
            <div class="row">
                @forelse($posts as $post)
                    <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                        <div class="post-entry">
                            <a href="{{ route('blog.show', $post->slug) }}" class="post-thumbnail"><img
                                    src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/600x400/e8e8e8/000000?text=Blog' }}"
                                    alt="Gambar Blog" class="img-fluid"></a>
                            <div class="post-content-entry">
                                <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                <div class="meta">
                                    <span>by <a href="#">{{ $post->user->name ?? 'Admin' }}</a></span>
                                    <span>on <a href="#">{{ $post->created_at->format('d M, Y') }}</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col text-center">
                        <p>Belum ada postingan blog untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- End Blog Section -->
    <!-- End Blog Section -->
@endsection

@push('js')
    <script>
        // Script ini akan dijalankan setelah semua konten halaman dimuat.
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah elemen slider ada di halaman ini sebelum menginisialisasi
            if (document.querySelector('.testimonial-slider')) {
                var slider = tns({
                    container: '.testimonial-slider',
                    items: 1,
                    axis: "horizontal",
                    controlsContainer: "#testimonial-nav",
                    swipeAngle: false,
                    speed: 700,
                    nav: false,
                    mouseDrag: true
                });
            }
        });
    </script>
@endpush
