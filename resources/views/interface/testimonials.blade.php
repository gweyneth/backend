@extends('landing') {{-- Pastikan ini mengarah ke layout utama landing page Anda --}}

@section('title', 'Testimoni - ' . ($perusahaan->nama_perusahaan ?? 'Digital Printing'))

@section('interface')

    <!-- Menampilkan pesan sukses setelah mengirim testimoni -->
    {{-- Pesan ini diletakkan di dalam container agar paddingnya konsisten --}}


    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Testimoni Pelanggan</h1>
                        <p class="mb-4">Dengarkan pengalaman dari para pelanggan dan mitra yang telah mempercayai kami.
                            Kepuasan Anda adalah prioritas utama kami.</p>
                        <p><a href="#form-testimoni" class="btn btn-secondary me-2">Berikan Testimoni</a><a
                                href="{{ route('shop') }}" class="btn btn-white-outline">Lihat Produk</a></p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        {{-- Ganti gambar ini dengan yang relevan dengan testimoni/pelanggan --}}
                        <img src="{{ asset('assets/images/testimonial-hero.png') }}" class="img-fluid"
                            onerror="this.onerror=null;this.src='{{ asset('assets/images/couch.png') }}';">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <!-- Start Testimonial Slider -->
    <div class="testimonial-section before-footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="section-title">Apa Kata Mereka</h2>
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

                            @if ($testimonials->count() > 0)
                                @foreach ($testimonials as $testimonial)
                                    <div class="item">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-8 mx-auto">
                                                <div class="testimonial-block text-center">
                                                    <blockquote class="mb-5">
                                                        <p>&ldquo;{{ $testimonial->content }}&rdquo;</p>
                                                    </blockquote>

                                                    <div class="author-info">
                                                        <div class="author-pic">
                                                            @if ($testimonial->photo)
                                                                <img src="{{ asset('storage/' . $testimonial->photo) }}"
                                                                    alt="Foto {{ $testimonial->name }}" class="img-fluid"
                                                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/person-1.png') }}';">
                                                            @else
                                                                {{-- Fallback jika tidak ada foto --}}
                                                                <img src="{{ asset('assets/images/person-1.png') }}"
                                                                    alt="Foto default" class="img-fluid">
                                                            @endif
                                                        </div>
                                                        <h3 class="font-weight-bold">{{ $testimonial->name }}</h3>
                                                        <span
                                                            class="position d-block mb-3">{{ $testimonial->position ?? 'Pelanggan' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                {{-- Tampilan jika tidak ada testimoni --}}
                                <div class="item">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 mx-auto">
                                            <div class="testimonial-block text-center">
                                                <blockquote class="mb-5">
                                                    <p>&ldquo;Belum ada testimoni yang ditampilkan. Jadilah yang pertama
                                                        memberikan ulasan!&rdquo;</p>
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Testimonial Slider -->

    <!-- Bagian Form untuk Mengirim Testimoni (Diperbarui dengan class Bootstrap) -->
    <div id="form-testimoni" class="untree_co-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    @if (session('testimonial_success'))
                        <div class="alert alert-success mt-4" role="alert">
                            <strong>Terima Kasih!</strong> {{ session('testimonial_success') }}
                        </div>
                    @endif
                    <h2 class="section-title">Bagikan Pengalaman Anda</h2>
                    <p>Kami sangat menghargai setiap masukan yang Anda berikan untuk membantu kami menjadi lebih baik.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="text-black" for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label class="text-black" for="position">Posisi / Perusahaan (Opsional)</label>
                                    <input type="text" class="form-control @error('position') is-invalid @enderror"
                                        id="position" name="position" value="{{ old('position') }}">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-black" for="rating">Rating Anda</label>
                            <select class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating"
                                required>
                                <option value="" disabled {{ old('rating') ? '' : 'selected' }}>Pilih rating...
                                </option>
                                <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>★★★★★ (Sangat Baik)
                                </option>
                                <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>★★★★☆ (Baik)</option>
                                <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>★★★☆☆ (Cukup)</option>
                                <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>★★☆☆☆ (Kurang)</option>
                                <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>★☆☆☆☆ (Buruk)</option>
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-black" for="content">Testimoni Anda</label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" id="content" cols="30"
                                rows="5" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="text-black" for="photo">Unggah Foto (Opsional)</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                id="photo" name="photo" accept="image/jpeg,image/png,image/jpg">
                            <small class="form-text text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB.</small>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary-hover-outline">Kirim Testimoni</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.testimonial-slider')) {
                var slider = tns({
                    container: '.testimonial-slider',
                    items: 1,
                    axis: "horizontal",
                    controlsContainer: "#testimonial-nav",
                    swipeAngle: false,
                    speed: 700,
                    nav: false, // Menonaktifkan titik-titik navigasi di bawah
                    mouseDrag: true,
                    responsive: {
                        // Tampilkan 2 item jika lebar layar di atas 768px
                        768: {
                            items: 2,
                            gutter: 20
                        },
                        // Tampilkan 3 item jika lebar layar di atas 992px
                        992: {
                            items: 3,
                            gutter: 20
                        }
                    }
                });
            }
        });
    </script>
@endpush
