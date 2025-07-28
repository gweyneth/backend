@extends('landing') {{-- Pastikan ini mengarah ke layout utama landing page Anda --}}

@section('title', 'Kontak Kami - ' . ($perusahaan->nama_perusahaan ?? 'Digital Printing'))

@section('interface')
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Hubungi Kami</h1>
                        <p class="mb-4">Punya pertanyaan atau ingin mendiskusikan proyek Anda? Jangan ragu untuk menghubungi kami. Tim kami siap membantu Anda.</p>
                        <p><a href="{{ route('shop') }}" class="btn btn-secondary me-2">Lihat Produk</a></p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        <img src="{{ asset('assets/images/contact-hero.png') }}" class="img-fluid" onerror="this.onerror=null;this.src='{{asset('assets/images/couch.png')}}';">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <!-- Start Contact Section -->
    <div class="untree_co-section">
        <div class="container">
            <div class="block">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-8 pb-4">
                        {{-- Informasi Kontak --}}
                        <div class="row mb-5">
                            <div class="col-lg-4">
                                <div class="service no-shadow align-items-center link horizontal d-flex">
                                    <div class="service-icon color-1 mb-4"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="service-contents"><h6>Alamat</h6><p>{{ $perusahaan->alamat ?? 'Belum diatur' }}</p></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="service no-shadow align-items-center link horizontal d-flex">
                                    <div class="service-icon color-1 mb-4"><i class="fas fa-envelope"></i></div>
                                    <div class="service-contents"><h6>Email</h6><p>{{ $perusahaan->email ?? 'Belum diatur' }}</p></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="service no-shadow align-items-center link horizontal d-flex">
                                    <div class="service-icon color-1 mb-4"><i class="fab fa-whatsapp"></i></div>
                                    <div class="service-contents"><h6>WhatsApp</h6><p>{{ $perusahaan->no_handphone ?? 'Belum diatur' }}</p></div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi WhatsApp --}}
                        <div class="text-center border p-4 rounded">
                            <h3 class="mb-3">Kirim Pesan Cepat via WhatsApp</h3>
                            <p class="text-muted">Klik tombol di bawah untuk memulai percakapan dengan tim kami.</p>
                            
                            @php
                                // Membersihkan nomor telepon dari karakter selain angka
                                $whatsappNumber = preg_replace('/[^0-9]/', '', $perusahaan->no_handphone ?? '');
                                // Mengganti awalan 0 dengan 62 (kode negara Indonesia)
                                if (substr($whatsappNumber, 0, 1) === '0') {
                                    $whatsappNumber = '62' . substr($whatsappNumber, 1);
                                }
                                // Pesan default yang akan muncul di WhatsApp
                                $whatsappMessage = urlencode("Halo, saya ingin bertanya tentang layanan digital printing Anda.");
                            @endphp

                            <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}" target="_blank" class="btn btn-primary-hover-outline btn-lg mt-3">
                                <i class="fab fa-whatsapp mr-2"></i> Hubungi Kami di WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact Section -->
@endsection
