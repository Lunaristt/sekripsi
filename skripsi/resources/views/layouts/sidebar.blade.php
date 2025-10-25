<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <nav class="nav flex-column">
                <a class="nav-link fw-bold text-white {{ request()->is('barang/barang') ? 'active' : '' }}"
                    href="{{ route('barang.index') }}">
                    Lihat Stok Barang
                </a>

                {{-- Default Tambah Barang Baru --}}
                <a class="nav-link fw-bold text-white {{ request()->is('barang/tambahbarang*') ? 'active' : '' }}"
                    href="{{ route('tambahbarang.create') }}">
                    Tambah Barang Baru
                </a>

                {{-- Dropdown Tambah Kategori --}}
                <div class="ms-3">
                    <a class="nav-link text-white {{ request()->is('tambahkategori') ? 'active' : '' }}"
                        href="{{ route('tambahkategori') }}">
                        📂 Tambah Kategori
                    </a>
                    <a class="nav-link text-white {{ request()->is('tambahsatuan') ? 'active' : '' }}"
                        href="{{ route('tambahsatuan') }}">
                        📏 Tambah Satuan
                    </a>
                </div>

                <a class="nav-link fw-bold text-white {{ request()->is('pelanggan') ? 'active' : '' }}"
                    href="{{ route('pelanggan.index') }}">
                    List Pelanggan
                </a>
                {{-- Dropdown Tambah data pelanggan --}}
                <div class="ms-3">
                    <a class="nav-link text-white {{ request()->is('tambahpelanggan') ? 'active' : '' }}"
                        href="{{ route('tambahpelanggan') }}">
                        📂 Tambah Data Pelanggan
                    </a>
                </div>

                <a class="nav-link fw-bold text-white {{ request()->is('transaksi') ? 'active' : '' }}"
                    href="{{ route('transaksi.create') }}">
                    Buat Penjualan
                </a>
                {{-- Dropdown Tambah Kategori --}}
                <div class="ms-3">
                    <a class="nav-link text-white {{ request()->is('statustransaksi') ? 'active' : '' }}"
                        href="{{ route('statustransaksi.index') }}">
                        📂 Status Transaksi
                    </a>
                </div>
                <a class="nav-link fw-bold text-white {{ request()->is('pembelian') ? 'active' : '' }}"
                    href="{{route('pembelian.create')}}">
                    Buat Pembelian
                </a>

                {{-- ✅ Dropdown Laporan --}}
                <div class="dropdown-container mt-2">
                    <button class="btn btn-danger w-100 text-start fw-bold text-white border-0 dropdown-toggle"
                        type="button" id="laporanDropdown">
                        📊 Laporan
                    </button>
                    <div id="laporanMenu" class="dropdown-anim mt-1">
                        <a class="nav-link text-white ps-4 py-1 {{ request()->is('laporan/pengeluaran') ? 'active' : '' }}"
                            href="#">📉 Laporan Pengeluaran</a>
                        <a class="nav-link text-white ps-4 py-1 {{ request()->is('laporan/pemasukan') ? 'active' : '' }}"
                            href="laporan/pemasukan">📈 Laporan Pemasukan</a>
                    </div>
                </div>

                <a class="nav-link fw-bold text-white {{ request()->is('distributor') ? 'active' : '' }}"
                    href="{{ route('distributor.index') }}">
                    Daftar Distributor
                </a>
                <div class="ms-3">
                    <a class="nav-link text-white {{ request()->is('tambahdistributor') ? 'active' : '' }}"
                        href="{{ route('tambahdistributor') }}">
                        📂 Tambah Distributor
                    </a>
                </div>

                <a class="nav-link fw-bold text-white {{ request()->is('pajak') ? 'active' : '' }}" href="pajak">
                    Faktur Pajak
                </a>

            </nav>
        </div>

        <script>
            // Animasi toggle dropdown laporan
            document.addEventListener('DOMContentLoaded', function () {
                const toggle = document.getElementById('laporanDropdown');
                const menu = document.getElementById('laporanMenu');

                toggle.addEventListener('click', function () {
                    menu.classList.toggle('show');
                    toggle.classList.toggle('active');
                });
            });
        </script>