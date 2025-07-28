@extends('landing') {{-- Pastikan ini mengarah ke layout utama landing page Anda --}}

@section('title', 'Produk & Layanan - ' . ($perusahaan->nama_perusahaan ?? 'Digital Printing'))

@section('interface')
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Produk & Layanan</h1>
                        <p class="mb-4">Temukan berbagai solusi cetak berkualitas tinggi yang kami tawarkan untuk memenuhi semua kebutuhan Anda, mulai dari personal hingga bisnis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <div class="untree_co-section product-section before-footer-section">
        <div class="container">
            <div class="row">

                @forelse ($produks as $produk)
                    @php
                        $whatsappNumber = preg_replace('/[^0-9]/', '', $perusahaan->no_handphone ?? '');
                        if (substr($whatsappNumber, 0, 1) === '0') {
                            $whatsappNumber = '62' . substr($whatsappNumber, 1);
                        }
                        $message = urlencode("Halo, saya tertarik dengan produk: " . $produk->nama);
                        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$message}";
                    @endphp

                    <!-- Start Column -->
                    <div class="col-12 col-md-4 col-lg-3 mb-5">
                        <a class="product-item" href="{{ $whatsappUrl }}" target="_blank">
                            
                            <img src="{{ $produk->foto ? asset('storage/' . $produk->foto) : 'https://placehold.co/300x300/e8e8e8/000000?text=No+Image' }}" class="img-fluid product-thumbnail">
                            
                            <h3 class="product-title">{{ $produk->nama }}</h3>
                            
                            <p class="product-description">{{ Str::limit($produk->deskripsi, 50) }}</p>

                            <strong class="product-price">Rp{{ number_format($produk->harga_jual, 0, ',', '.') }}</strong>

                            {{-- PERBAIKAN: Mengganti ikon silang dengan ikon WhatsApp --}}
                            <span class="icon-cross">
                                <i class="fab fa-whatsapp" style="font-size: 28px; color: white;"></i>
                            </span>
                        </a>
                    </div> 
                    <!-- End Column -->
                @empty
                    <div class="col-12">
                        <p class="text-center">Belum ada produk yang tersedia saat ini.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
@endsection
