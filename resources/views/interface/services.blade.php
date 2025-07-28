@extends('landing') {{-- Pastikan ini mengarah ke layout utama landing page Anda --}}

@section('title', 'Layanan Kami - ' . ($perusahaan->nama_perusahaan ?? 'Digital Printing'))

@section('interface')
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Layanan Kami</h1>
                        <p class="mb-4">Kami menawarkan berbagai solusi cetak untuk kebutuhan personal dan bisnis, dengan kualitas terbaik dan harga yang kompetitif untuk membantu mewujudkan ide-ide Anda.</p>
                        <p><a href="{{ route('shop') }}" class="btn btn-secondary me-2">Lihat Produk</a><a href="#kontak" class="btn btn-white-outline">Hubungi Kami</a></p>
                    </div>
                </div>
                {{-- <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        Ganti gambar ini dengan yang relevan, misal tumpukan hasil cetak
                        <img src="{{ asset('assets/images/services-hero.png') }}" class="img-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/couch.png')}}';">
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <!-- Start Services Section -->
    <div class="why-choose-section">
        <div class="container">
            <div class="row my-5">
                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon"><img src="{{asset('assets/images/digital-print-icon.svg')}}" alt="Image" class="imf-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/truck.svg')}}';"></div>
                        <h3>Cetak Digital</h3>
                        <p>Solusi cetak cepat untuk spanduk, stiker, poster, dan kebutuhan promosi lainnya dengan kualitas warna yang tajam.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon"><img src="{{asset('assets/images/offset-print-icon.svg')}}" alt="Image" class="imf-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/bag.svg')}}';"></div>
                        <h3>Cetak Offset</h3>
                        <p>Ideal untuk cetak dalam jumlah besar seperti brosur, majalah, buku, dan kalender dengan hasil yang konsisten.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon"><img src="{{asset('assets/images/business-card-icon.svg')}}" alt="Image" class="imf-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/support.svg')}}';"></div>
                        <h3>Kartu Nama & Kop Surat</h3>
                        <p>Ciptakan identitas bisnis yang profesional dengan desain kartu nama dan kop surat yang eksklusif.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon"><img src="{{asset('assets/images/merchandise-icon.svg')}}" alt="Image" class="imf-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/return.svg')}}';"></div>
                        <h3>Merchandise & Souvenir</h3>
                        <p>Cetak custom untuk mug, kaos, pin, gantungan kunci, dan berbagai souvenir lainnya untuk acara atau brand Anda.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon"><img src="{{asset('assets/images/large-format-icon.svg')}}" alt="Image" class="imf-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/truck.svg')}}';"></div>
                        <h3>Cetak Ukuran Besar</h3>
                        <p>Layanan cetak untuk media besar seperti baliho, spanduk, wallpaper, dan branding kendaraan.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon"><img src="{{asset('assets/images/packaging-icon.svg')}}" alt="Image" class="imf-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/bag.svg')}}';"></div>
                        <h3>Kemasan Produk</h3>
                        <p>Buat kemasan produk yang menarik dengan cetak dus, label, dan stiker kemasan berkualitas tinggi.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon"><img src="{{asset('assets/images/invitation-icon.svg')}}" alt="Image" class="imf-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/support.svg')}}';"></div>
                        <h3>Undangan & Kartu Ucapan</h3>
                        <p>Desain dan cetak undangan pernikahan, ulang tahun, dan berbagai kartu ucapan untuk momen spesial Anda.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon"><img src="{{asset('assets/images/binding-icon.svg')}}" alt="Image" class="imf-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/return.svg')}}';"></div>
                        <h3>Jilid & Finishing</h3>
                        <p>Layanan finishing profesional seperti laminasi, jilid spiral, hardcover, dan potong presisi untuk hasil akhir yang sempurna.</p>
                    </div>
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

@endsection
