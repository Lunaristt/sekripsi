@extends('layouts.app')

@section('title', 'Transaksi Baru - Toko Sumber Rejeki')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <h4 class="mb-4">Transaksi Baru (ID: {{ $penjualan->ID_Penjualan }})</h4>

    {{-- 🔹 Informasi Pelanggan --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <h6 class="fw-bold">Nama Pelanggan</h6>
            <select id="namaPelanggan" class="form-control" style="width: 100%;">
                <option value="">-- Pilih atau Ketik Pelanggan --</option>
                @foreach($pelanggan as $plg)
                    <option value="{{ $plg->Nama_Pelanggan }}" data-telp="{{ $plg->No_Telp }}" data-alamat="{{ $plg->Alamat }}">
                        {{ $plg->Nama_Pelanggan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <h6 class="fw-bold">Nomor Telepon</h6>
            <input type="text" id="noTelp" name="No_Telp" class="form-control" placeholder="Nomor telepon...">
        </div>

        <div class="col-md-4">
            <h6 class="fw-bold">Alamat</h6>
            <input type="text" id="alamatPelanggan" name="Alamat" class="form-control" placeholder="Alamat pelanggan...">
        </div>
    </div>

    {{-- 🔹 Tabel Barang --}}
    <table class="table table-bordered align-middle" id="tabelTransaksi">
        <thead class="table-light">
            <tr>
                <th>Nama Barang</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="listBarang">
            <tr id="emptyRow">
                <td colspan="6" class="text-center">Belum ada barang dalam transaksi</td>
            </tr>
        </tbody>
    </table>

    {{-- 🔹 Form Tambah Barang --}}
    <form id="formAddItem" class="mt-3">
        <div class="mb-3">
            <label for="ID_Barang" class="form-label">Pilih Barang</label>
            <select name="ID_Barang" id="ID_Barang" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barang as $b)
                    <option value="{{ $b->ID_Barang }}" data-harga="{{ $b->Harga_Barang }}"
                        data-deskripsi="{{ $b->Deskripsi_Barang }}">
                        {{ $b->Nama_Barang }} - {{ $b->Deskripsi_Barang }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="Jumlah" class="form-label">Jumlah</label>
            <input type="number" name="Jumlah" id="Jumlah" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Tambah Barang</button>
    </form>

    {{-- 🔹 Total --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h6 id="grandTotal" class="fw-bold">Total: Rp 0</h6>
    </div>

    {{-- 🔹 Tombol Aksi --}}
    <div class="d-flex justify-content-end mt-3">
        <form action="{{ route('transaksi.cancel') }}" method="POST" class="me-2">
            @csrf
            <button type="submit" class="btn btn-danger"
                onclick="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')">
                Batalkan Pesanan
            </button>
        </form>

        <form action="{{ route('transaksi.checkout') }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="Nama_Pelanggan" id="checkoutNama">
            <input type="hidden" name="No_Telp" id="checkoutTelp">
            <input type="hidden" name="Alamat" id="checkoutAlamat">
            <button type="submit" class="btn btn-success">Selesaikan Pesanan</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // === Inisialisasi Select2 ===
            $('#namaPelanggan').select2({
                placeholder: 'Ketik atau pilih pelanggan...',
                tags: true,
                allowClear: true,
                width: '100%'
            });

            // === Autofill Pelanggan ===
            $('#namaPelanggan').on('change', function () {
                const selected = $(this).find(':selected');
                $('#noTelp').val(selected.data('telp') || '');
                $('#alamatPelanggan').val(selected.data('alamat') || '');
            });

            // === Data Barang (keranjang) ===
            let keranjang = [];

            function renderTabel() {
                const tbody = $('#listBarang');
                tbody.empty();

                if (keranjang.length === 0) {
                    tbody.html('<tr><td colspan="6" class="text-center">Belum ada barang dalam transaksi</td></tr>');
                } else {
                    keranjang.forEach((item, i) => {
                        tbody.append(`
                                <tr>
                                    <td>${item.nama}</td>
                                    <td>${item.deskripsi}</td>
                                    <td>${item.jumlah}</td>
                                    <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
                                    <td>Rp ${item.total.toLocaleString('id-ID')}</td>
                                    <td><button class="btn btn-danger btn-sm" onclick="hapusBarang(${i})">Hapus</button></td>
                                </tr>
                            `);
                    });
                }

                const total = keranjang.reduce((sum, item) => sum + item.total, 0);
                $('#grandTotal').text('Total: Rp ' + total.toLocaleString('id-ID'));
            }

            window.hapusBarang = function (index) {
                keranjang.splice(index, 1);
                renderTabel();
            }

            // === Tambah Barang ===
            $('#formAddItem').on('submit', function (e) {
                e.preventDefault();

                const idBarang = $('#ID_Barang').val();
                const namaBarang = $('#ID_Barang option:selected').text().split('-')[0].trim();
                const deskripsi = $('#ID_Barang option:selected').data('deskripsi') || '-';
                const harga = parseInt($('#ID_Barang option:selected').data('harga'));
                const jumlah = parseInt($('#Jumlah').val());

                if (!idBarang || jumlah <= 0) {
                    Swal.fire('Peringatan', 'Pilih barang dan jumlah yang valid.', 'warning');
                    return;
                }

                const existingItem = keranjang.find(item => item.id === idBarang);

                if (existingItem) {
                    existingItem.jumlah += jumlah;
                    existingItem.total = existingItem.harga * existingItem.jumlah;
                    Swal.fire({ icon: 'info', title: 'Jumlah diperbarui!', timer: 1000, showConfirmButton: false });
                } else {
                    keranjang.push({ id: idBarang, nama: namaBarang, deskripsi, jumlah, harga, total: jumlah * harga });
                    Swal.fire({ icon: 'success', title: 'Barang ditambahkan!', timer: 1000, showConfirmButton: false });
                }

                renderTabel();
                this.reset();
            });

            renderTabel();

            // === Checkout ===
            $('#checkoutForm').on('submit', function (e) {
                e.preventDefault();

                const nama = $('#namaPelanggan').val();
                const telp = $('#noTelp').val();
                const alamat = $('#alamatPelanggan').val();

                if (!nama || !telp) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data belum lengkap',
                        text: 'Nama dan Nomor Telepon pelanggan wajib diisi!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (keranjang.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Keranjang Kosong',
                        text: 'Tambahkan barang terlebih dahulu sebelum checkout!',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Kirim ke controller via AJAX
                fetch("{{ route('transaksi.checkout') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        Nama_Pelanggan: nama,
                        No_Telp: telp,
                        Alamat: alamat,
                        barang: keranjang
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Transaksi berhasil disimpan!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => window.location.href = '/transaksi');
                        } else {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error', 'Gagal menghubungi server.', 'error'));
            });

        });
    </script>
@endpush