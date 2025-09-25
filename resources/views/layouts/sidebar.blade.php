<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/dashboard') }}" class="brand-link">
        {{-- Menampilkan logo perusahaan dari data perusahaan atau placeholder --}}
        <img src="{{ isset($perusahaan) && $perusahaan->logo ? asset('storage/' . $perusahaan->logo) : asset('dist/img/AdminLTELogo.png') }}"
            alt="Logo Perusahaan" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span
            class="brand-text font-weight-light">{{ isset($perusahaan) ? $perusahaan->nama_perusahaan : 'Digital Printing' }}</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('dist/img/user2-160x160.jpg') }}"
                    class="img-circle elevation-2" alt="User Image"
                    style="width: 35px; height: 35px; object-fit: cover;">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->username ?? 'Pengguna' }}</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if (Auth::check() && Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('perusahaan.edit') }}"
                            class="nav-link {{ Request::is('perusahaan*') ? 'active' : '' }}">
                            <i class="fas fa-building nav-icon"></i>
                            <p>Data Perusahaan</p>
                        </a>
                    </li>
                    <li
                        class="nav-item has-treeview {{ Request::is('karyawan*') || Request::is('gaji*') || Request::is('kasbon-karyawan*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::is('karyawan*') || Request::is('gaji*') || Request::is('kasbon-karyawan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>
                                Manajemen Karyawan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('karyawan.index') }}"
                                    class="nav-link {{ Request::is('karyawan*') && !Request::is('kasbon-karyawan*') && !Request::is('gaji*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Karyawan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('gaji.index') }}"
                                    class="nav-link {{ Request::is('gaji*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Gaji Karyawan</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                            <a href="{{ route('kasbon-karyawan.index') }}"
                                class="nav-link {{ Request::is('kasbon-karyawan*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kasbon Karyawan</p>
                            </a>
                        </li> --}}
                        </ul>
                    </li>
                @endif {{-- End of Admin Menu --}}
                {{-- @if (Auth::check() && Auth::user()->isKasir()) jika hanya kasir --}}
                @if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isKasir()))
                    <li class="nav-item">
                        <a href="{{ route('pelanggan.index') }}"
                            class="nav-link {{ Request::is('pelanggan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>Data Pelanggan</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview {{ Request::is('transaksi*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('transaksi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>
                                Transaksi
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('transaksi.create') }}"
                                    class="nav-link {{ Request::is('transaksi/create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Transaksi Baru</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transaksi.index') }}"
                                    class="nav-link {{ Request::is('transaksi*') && !Request::is('transaksi/create') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Log Transaksi</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif {{-- End of Kasir Menu --}}
                @if (Auth::check() && Auth::user()->isAdmin())
                    <li
                        class="nav-item has-treeview {{ Request::is('produk*') || Request::is('kategoribarang*') || Request::is('bahan*') || Request::is('satuan*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::is('produk*') || Request::is('kategoribarang*') || Request::is('bahan*') || Request::is('satuan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>
                                Data Produk
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('produk.index') }}"
                                    class="nav-link {{ Request::is('produk*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Produk</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kategoribarang.index') }}"
                                    class="nav-link {{ Request::is('kategoribarang*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Kategori Barang</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('bahan.index') }}"
                                    class="nav-link {{ Request::is('bahan*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Bahan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('satuan.index') }}"
                                    class="nav-link {{ Request::is('satuan*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Satuan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item has-treeview {{ Request::is('pengeluaran*') || Request::is('pendapatan*') || Request::is('piutang*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::is('pengeluaran*') || Request::is('pendapatan*') || Request::is('piutang*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Laporan Keuangan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('pengeluaran.index') }}"
                                    class="nav-link {{ Request::is('pengeluaran*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengeluaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pendapatan.index') }}"
                                    class="nav-link {{ Request::is('pendapatan*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rincian Pendapatan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('piutang.index') }}"
                                    class="nav-link {{ Request::is('piutang*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Piutang Penjualan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('omset.index') }}"
                                    class="nav-link {{ Request::is('omset-penjualan*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Omset Penjualan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('rekapitulasi.index') }}"
                                    class="nav-link {{ Request::is('rekapitulasi*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon text-primary"></i>
                                    <p>Rekapitulasi</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('posts.index') }}"
                            class="nav-link {{ Request::is('posts*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-blog"></i>
                            <p>Blog</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('testimonials.index') }}"
                            class="nav-link {{ Request::is('testimonials*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comment-dots"></i>
                            <p>Testimoni</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview {{ Request::is('rekening*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('rekening*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Pengaturan Sistem
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('rekening.index') }}"
                                    class="nav-link {{ Request::is('rekening*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Rekening</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('backgrounds.index') }}"
                                    class="nav-link {{ Request::is('backgrounds*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengaturan Tampilan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="nav-header">AKUN</li>
                <li class="nav-item">
                    <a href="{{ route('profile.show') }}"
                        class="nav-link {{ Request::is('profile*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>Akun Saya</p>
                    </a>
                </li>
                <li class="nav-item">
                    {{-- PERBAIKAN: Link ini sekarang memanggil fungsi logout yang sama dengan di navbar --}}
                    <a href="{{ route('logout') }}" class="nav-link"
                        onclick="event.preventDefault(); confirmLogoutNavbar(event);">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

{{-- Tambahkan script SweetAlert untuk konfirmasi logout di sini atau di layouts.app Anda --}}
@push('scripts')
    {{-- Pastikan layouts.app memiliki @stack('scripts') --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout(event) {
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
                    document.getElementById('logout-form-sidebar').submit();
                }
            });
        }
    </script>
@endpush
