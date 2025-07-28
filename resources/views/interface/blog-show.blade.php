@extends('landing') {{-- Pastikan ini mengarah ke layout utama landing page Anda --}}

@section('title', $post->title . ' - ' . ($perusahaan->nama_perusahaan ?? 'Digital Printing'))

@section('interface')
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-7">
                    <div class="intro-excerpt">
                        <h1>{{ $post->title }}</h1>
                        <p class="mb-4">
                            Dipublikasikan oleh <strong>{{ $post->user->name ?? 'Admin' }}</strong> 
                            pada <strong>{{ $post->created_at->format('d F Y') }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section before-footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    {{-- Gambar Unggulan --}}
                    @if($post->image)
                    <div class="mb-5 text-center">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="img-fluid rounded shadow-sm">
                    </div>
                    @endif

                    {{-- Konten Artikel --}}
                    <div class="post-content" style="line-height: 1.8; font-size: 1.1rem;">
                        {{-- Menggunakan {!! !!} untuk merender paragraf dan nl2br untuk mengubah baris baru menjadi <br> --}}
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
