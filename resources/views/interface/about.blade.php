@extends('landing') {{-- Pastikan ini mengarah ke layout utama landing page Anda --}}

@section('title', 'Tentang Kami - ' . ($perusahaan->nama_perusahaan ?? 'Digital Printing'))

@section('interface')
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-12">
                    <div class="intro-excerpt">
                        <h1>Tentang Kami</h1>
                        <p class="mb-4">Kami adalah partner terpercaya untuk semua kebutuhan cetak Anda. Pelajari lebih lanjut tentang visi, misi, dan tim profesional di balik layanan berkualitas kami.</p>
                        <p><a href="{{ route('shop') }}" class="btn btn-secondary me-2">Lihat Produk</a><a href="#tim-kami" class="btn btn-white-outline">Lihat Tim Kami</a></p>
                    </div>
                </div>
                {{-- <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        Ganti gambar ini dengan yang relevan, misal foto kantor atau tim
                        <img src="{{ asset('assets/images/about-hero.png') }}" class="img-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/couch.png')}}';">
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <!-- Start Why Choose Us Section -->
    <div class="why-choose-section">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title">Mengapa Memilih Kami?</h2>
                    <p>Kami berkomitmen untuk memberikan hasil cetak terbaik dengan layanan yang memuaskan untuk mendukung kesuksesan bisnis Anda.</p>
                    <div class="row my-5">
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon"><img src="{{asset('assets/images/truck.svg')}}" alt="Image" class="imf-fluid"></div>
                                <h3>Cepat & Tepat Waktu</h3>
                                <p>Proses produksi yang efisien memastikan pesanan Anda selesai sesuai jadwal.</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon"><img src="{{asset('assets/images/bag.svg')}}" alt="Image" class="imf-fluid"></div>
                                <h3>Harga Kompetitif</h3>
                                <p>Dapatkan penawaran harga terbaik tanpa mengorbankan kualitas hasil cetakan.</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon"><img src="{{asset('assets/images/support.svg')}}" alt="Image" class="imf-fluid"></div>
                                <h3>Layanan Pelanggan</h3>
                                <p>Tim kami siap membantu Anda dari proses konsultasi hingga pesanan selesai.</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon"><img src="{{asset('assets/images/return.svg')}}" alt="Image" class="imf-fluid"></div>
                                <h3>Kualitas Terjamin</h3>
                                <p>Kami menggunakan bahan dan teknologi cetak terbaik untuk hasil yang tajam dan tahan lama.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="img-wrap">
                        <img src="{{asset('assets/images/why-choose-us-img.jpg')}}" alt="Image" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Why Choose Us Section -->

    <!-- Start Team Section -->
    <div class="untree_co-section" id="tim-kami">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-5 mx-auto text-center">
                    <h2 class="section-title">Tim Profesional Kami</h2>
                </div>
            </div>
            <div class="row">
                @forelse ($karyawan as $item)
                <!-- Start Column -->
                <div class="col-12 col-md-6 col-lg-3 mb-5 mb-md-0">
                    <img src="{{ $item->foto ? asset('storage/' . $item->foto) : 'https://placehold.co/300x400/e9ecef/6c757d?text=Foto' }}" class="img-fluid mb-4 rounded">
                    <h3><a href="#"><span class="">{{ $item->nama_karyawan }}</span></a></h3>
                    <span class="d-block position mb-4">{{ $item->jabatan }}</span>
                    <p>{{-- Anda bisa menambahkan deskripsi singkat karyawan di sini jika ada di database --}}</p>
                </div> 
                <!-- End Column -->
                @empty
                <div class="col-12 text-center">
                    <p>Informasi tim akan segera ditampilkan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- End Team Section -->

@endsection
