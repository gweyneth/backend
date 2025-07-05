<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Digital Printing</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name ?? 'Kasir' }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('perusahaan.show') }}" class="nav-link {{ Request::is('perusahaan*') ? 'active' : '' }}">
                        <i class="fas fa-building nav-icon"></i>
                        <p>Data Perusahaan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pelanggan.index') }}"
                        class="nav-link {{ Request::is('pelanggan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pelanggan</p>
                    </a>
                </li>

                <li class="nav-item has-treeview {{ Request::is('karyawan*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('karyawan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i> 
                        <p>
                            Karyawan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('karyawan.index') }}"
                                class="nav-link {{ Request::is('karyawan*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Karyawan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('gaji.index') }}"
                                class="nav-link {{ Request::is('Gaji-karyawan*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gaji Karyawan</p>
                            </a>
                        </li>
                    </ul>
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
                            <a href="#" class="nav-link {{ Request::is('transaksi') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Transaksi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview {{ Request::is('laporan*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('laporan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pengeluaran.index') }}"
                                class="nav-link {{ Request::is('laporan/pengeluaran') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengeluaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('laporan/pembelian') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pembelian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('laporan/pendapatan') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rincian Pendapatan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('laporan/piutang') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Piutang Penjualan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ Request::is('laporan/log') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Log Transaksi</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item has-treeview {{ Request::is('pengaturan*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('pengaturan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>
                            Data Produk
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('kategoribarang.index') }}"
                                class="nav-link {{ Request::is('kategori') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bahan.index') }}"
                                class="nav-link {{ Request::is('bahan') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Bahan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('satuan.index') }}"
                                class="nav-link {{ Request::is('satuan') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Satuan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('produk.index') }}"
                                class="nav-link {{ Request::is('produk') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{ Request::is('pengaturan*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('pengaturan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Pengaturan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#"
                                class="nav-link {{ Request::is('pengaturan/factory') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Factory</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('rekening.index') }}"
                                class="nav-link {{ Request::is('pengaturan/rekening') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rekening</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="confirmLogout(event);"> {{-- Panggil fungsi JS di sini --}}
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>

{{-- Tambahkan script SweetAlert untuk konfirmasi logout di sini atau di layouts.app Anda --}}
@push('scripts')
    {{-- Pastikan layouts.app memiliki @stack('scripts') --}}
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
