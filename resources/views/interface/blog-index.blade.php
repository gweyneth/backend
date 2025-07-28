@extends('landing') {{-- Pastikan ini mengarah ke layout utama landing page Anda --}}

@section('title', 'Blog - ' . ($perusahaan->nama_perusahaan ?? 'Digital Printing'))

@section('interface')
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Blog &amp; Artikel</h1>
                        <p class="mb-4">Temukan tips, inspirasi, dan informasi terbaru seputar dunia percetakan dan desain dari tim kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <!-- Start Blog Section -->
    <div class="blog-section">
        <div class="container">
            <div class="row">

                @forelse ($posts as $post)
                <div class="col-12 col-sm-6 col-md-4 mb-5">
                    <div class="post-entry">
                        <a href="{{ route('blog.show', $post->slug) }}" class="post-thumbnail">
                            <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://placehold.co/600x400/e9ecef/6c757d?text=Artikel' }}" alt="{{ $post->title }}" class="img-fluid">
                        </a>
                        <div class="post-content-entry">
                            <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                            <div class="meta">
                                <span>by <a href="#">{{ $post->user->name ?? 'Admin' }}</a></span> <span>on <a href="#">{{ $post->created_at->format('d M, Y') }}</a></span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-center">Belum ada postingan blog yang dipublikasikan.</p>
                </div>
                @endforelse

            </div>
            
            {{-- Menampilkan Paginasi --}}
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            </div>

        </div>
    </div>
    <!-- End Blog Section -->
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
