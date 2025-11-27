<div class="col-md-2 sidebar text-white p-3">
    <nav class="nav flex-column">

        <!-- 📦 Barang -->
        <div class="mb-2">
            <a class="nav-link fw-bold text-white" data-bs-toggle="collapse" data-bs-target="#menuBarang" role="button">
                📦 Barang
            </a>
            <div class="collapse ms-3 {{ request()->is('barang*') || request()->is('tambahbarang*') || request()->is('tambahkategori') || request()->is('tambahsatuan') ? 'show' : '' }}"
                id="menuBarang">

                <!-- Semua role bisa -->
                <a href="{{ route('barang.index') }}"
                    class="nav-link text-white {{ request()->is('barang*') ? 'fw-bold' : '' }}">Lihat Stok</a>

                <a href="{{ route('tambahbarang.create') }}"
                    class="nav-link text-white {{ request()->is('tambahbarang*') ? 'fw-bold' : '' }}">Barang Baru</a>

                <!-- Hanya Admin -->
                @if (session('role') === 'Admin')
                    <a href="{{ route('tambahkategori') }}"
                        class="nav-link text-white {{ request()->is('tambahkategori') ? 'fw-bold' : '' }}">Kategori</a>
                    <a href="{{ route('tambahsatuan') }}"
                        class="nav-link text-white {{ request()->is('tambahsatuan') ? 'fw-bold' : '' }}">Satuan</a>
                @endif
            </div>
        </div>

        <!-- 👥 Pelanggan -->
        <div class="mb-2">
            <a class="nav-link fw-bold text-white" data-bs-toggle="collapse" data-bs-target="#menuPelanggan"
                role="button">
                👥 Pelanggan
            </a>
            <div class="collapse ms-3 {{ request()->is('pelanggan*') || request()->is('tambahpelanggan') ? 'show' : '' }}"
                id="menuPelanggan">
                <a href="{{ route('pelanggan.index') }}"
                    class="nav-link text-white {{ request()->is('pelanggan*') ? 'fw-bold' : '' }}">Daftar Pelanggan</a>

                <!-- Hanya Admin -->
                @if (session('role') === 'Admin')
                    <a href="{{ route('pelanggan.create') }}"
                        class="nav-link text-white {{ request()->is('tambahpelanggan') ? 'fw-bold' : '' }}">Tambah
                        Pelanggan</a>
                @endif
            </div>
        </div>

        <!-- 💰 Transaksi -->
        <div class="mb-2">
            <a class="nav-link fw-bold text-white" data-bs-toggle="collapse" data-bs-target="#menuTransaksi"
                role="button">
                💰 Transaksi
            </a>
            <div class="collapse ms-3 {{ request()->is('transaksi*') || request()->is('statustransaksi*') || request()->is('pembelian*') ? 'show' : '' }}"
                id="menuTransaksi">
                <a href="{{ route('transaksi.create') }}"
                    class="nav-link text-white {{ request()->is('transaksi*') ? 'fw-bold' : '' }}">Penjualan</a>
                <a href="{{ route('statustransaksi.index') }}"
                    class="nav-link text-white {{ request()->is('statustransaksi*') ? 'fw-bold' : '' }}">List
                    Penjualan</a>
                <a href="{{ route('pembelian.create') }}"
                    class="nav-link text-white {{ request()->is('pembelian*') ? 'fw-bold' : '' }}">Pembelian</a>
                <a href="{{ route('pembelian.index') }}"
                    class="nav-link text-white {{ request()->is('pembelian*') ? 'fw-bold' : '' }}">List
                    Pembelian</a>
            </div>
        </div>

        <!-- 📊 Laporan (Hanya Admin) -->
        @if (session('role') === 'Admin')
            <div class="mb-2">
                <a class="nav-link fw-bold text-white" data-bs-toggle="collapse" data-bs-target="#menuLaporan"
                    role="button">
                    📊 Laporan
                </a>
                <div class="collapse ms-3 {{ request()->is('laporan*') ? 'show' : '' }}" id="menuLaporan">
                    <a href="{{ route('laporan.pengeluaran') }}" class="nav-link text-white">Laporan Pembelian</a>
                    <a href="{{ route('laporan.pemasukan') }}"
                        class="nav-link text-white {{ request()->is('laporan/pemasukan') ? 'fw-bold' : '' }}">Laporan
                        Penjualan</a>
                </div>
            </div>
        @endif

        <!-- 🏢 Distributor -->
        <div class="mb-2">
            <a class="nav-link fw-bold text-white" data-bs-toggle="collapse" data-bs-target="#menuDistributor"
                role="button">
                🏢 Distributor
            </a>
            <div class="collapse ms-3 {{ request()->is('distributor*') || request()->is('tambahdistributor') ? 'show' : '' }}"
                id="menuDistributor">
                <a href="{{ route('distributor.index') }}"
                    class="nav-link text-white {{ request()->is('distributor*') ? 'fw-bold' : '' }}">Daftar
                    Distributor</a>
                <a href="{{ route('tambahdistributor') }}"
                    class="nav-link text-white {{ request()->is('tambahdistributor') ? 'fw-bold' : '' }}">Tambah
                    Distributor</a>
            </div>
        </div>

        <!-- 🧾 Pajak (Hanya Admin) -->
        @if (session('role') === 'Admin')
            <a href="{{ route('pajak.index') }}"
                class="nav-link fw-bold text-white {{ request()->is('pajak*') ? 'text-warning' : '' }}">🧾 Faktur Pajak</a>
        @endif

        <!-- 👤 Pengguna (khusus Dashboard) -->
        @if (request()->is('dashboard*'))
            <div class="mb-2">
                <a class="nav-link fw-bold text-white" data-bs-toggle="collapse" data-bs-target="#menuPengguna"
                    role="button">
                    👤 Pengguna
                </a>
                <div class="collapse ms-3 {{ request()->is('pengguna*') || request()->is('tambahpengguna') ? 'show' : '' }}"
                    id="menuPengguna">
                    <a href="{{ route('pengguna.index') }}"
                        class="nav-link text-white {{ request()->is('pengguna*') ? 'fw-bold' : '' }}">Daftar Pengguna</a>
                    <a href="{{ route('register') }}"
                        class="nav-link text-white {{ request()->is('tambahpengguna') ? 'fw-bold' : '' }}">Tambah
                        Pengguna</a>
                </div>
            </div>
        @endif

    </nav>
</div>