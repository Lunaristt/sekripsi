<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Baru - Toko Sumber Rejeki</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 (dropdown + search + add new) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 10px;
        }
    </style>

    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>

    @include('layouts.navbar')
    <div class="container-fluid">
        <div class="row">
            @include('layouts.sidebar')

            <div class="col-md-10 p-4">
                <h4 class="mb-4">Transaksi Baru (ID: {{ $penjualan->ID_Penjualan }})</h4>

                {{-- 🔹 Informasi Pelanggan --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6 class="fw-bold">Nama Pelanggan</h6>
                        <select id="namaPelanggan" class="form-control" style="width: 100%;">
                            <option value="">-- Pilih atau Ketik Pelanggan --</option>

                            @foreach($pelanggan as $plg)
                                <option value="{{ $plg->Nama_Pelanggan }}" data-telp="{{ $plg->No_Telp }}"
                                    data-alamat="{{ $plg->Alamat }}">
                                    {{ $plg->Nama_Pelanggan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-bold">Nomor Telepon</h6>
                        <input type="text" id="noTelp" name="No_Telp" class="form-control"
                            placeholder="Nomor telepon...">
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-bold">Alamat</h6>
                        <input type="text" id="alamatPelanggan" name="Alamat" class="form-control"
                            placeholder="Alamat pelanggan...">
                    </div>
                </div>

                {{-- 🔹 Tabel Barang --}}
                <table class="table table-bordered align-middle">
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
                        @forelse ($transaksi as $t)
                            <tr>
                                <td>{{ $t->barang->Nama_Barang }}</td>
                                <td>{{ $t->barang->Deskripsi_Barang }}</td>
                                <td>{{ $t->barang->Jumlah }}</td>
                                <td>Rp {{ number_format($t->barang->Harga_Barang, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($t->Total_Harga, 0, ',', '.') }}</td>

                                <td>
                                    <form
                                        action="{{ route('transaksi.destroy', ['id_barang' => $t->ID_Barang, 'id_penjualan' => $penjualan->ID_Penjualan]) }}"
                                        method="POST" onsubmit="return confirm('Hapus {{ $t->Nama_Barang }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center">Belum ada barang dalam transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- 🔹 Form Tambah Barang --}}
                <form id="formAddItem" action="{{ route('transaksi.addItem') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="ID_Barang" class="form-label">Pilih Barang</label>
                        <select name="ID_Barang" id="ID_Barang" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->ID_Barang }}">
                                    {{ $b->Nama_Barang }} - Rp{{ number_format($b->Harga_Barang, 0, ',', '.') }}
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

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h6 id="grandTotal" class="fw-bold">
                        Total: Rp {{ number_format($penjualan->Harga_Keseluruhan, 0, ',', '.') }}
                    </h6>
                </div>

                {{-- 🔹 Tombol --}}
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
                        <button type="submit" class="btn btn-success" @if($transaksi->count() == 0) @endif>
                            Selesaikan Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================== --}}
    <script>
        $(document).ready(function () {

            // ====================================================
            // 🔹 SETUP SELECT2 UNTUK PELANGGAN
            // ====================================================
            $('#namaPelanggan').select2({
                placeholder: 'Ketik atau pilih pelanggan...',
                tags: true,
                allowClear: true,
                width: '100%'
            });

            $('#namaPelanggan').on('change', function () {
                const selected = $(this).find(':selected');
                $('#noTelp').val(selected.data('telp') || '');
                $('#alamatPelanggan').val(selected.data('alamat') || '');
            });

            // ====================================================
            // 🔹 VARIABEL PENYIMPANAN VISUAL (KERANJANG)
            // ====================================================
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
                            <td>-</td>
                            <td>${item.jumlah}</td>
                            <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
                            <td>Rp ${item.total.toLocaleString('id-ID')}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="hapusBarang(${i})">Hapus</button>
                            </td>
                        </tr>
                    `);
                    });
                }

                // Update total
                const total = keranjang.reduce((sum, item) => sum + item.total, 0);
                $('#grandTotal').text('Total: Rp ' + total.toLocaleString('id-ID'));
            }

            // ====================================================
            // 🔹 FUNGSI HAPUS BARANG
            // ====================================================
            window.hapusBarang = function (index) {
                keranjang.splice(index, 1);
                renderTabel();
            }

            // ====================================================
            // 🔹 TAMBAH BARANG KE TABEL VISUAL (MERGE OTOMATIS)
            // ====================================================
            $('#formAddItem').on('submit', function (e) {
                e.preventDefault();

                const idBarang = $('#ID_Barang').val();
                const namaBarang = $('#ID_Barang option:selected').text().split('-')[0].trim();
                const hargaText = $('#ID_Barang option:selected').text().match(/\d[\d.]+/);
                const harga = hargaText ? parseInt(hargaText[0].replace(/\./g, '')) : 0;
                const jumlah = parseInt($('#Jumlah').val());

                if (!idBarang || jumlah <= 0) {
                    Swal.fire('Peringatan', 'Pilih barang dan masukkan jumlah yang valid.', 'warning');
                    return;
                }

                // 🔍 Cek apakah barang sudah ada di keranjang
                const existingItem = keranjang.find(item => item.id === idBarang);

                if (existingItem) {
                    // Jika sudah ada → tambahkan jumlah & total
                    existingItem.jumlah += jumlah;
                    existingItem.total = existingItem.harga * existingItem.jumlah;

                    Swal.fire({
                        icon: 'info',
                        title: 'Jumlah barang diperbarui!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else {
                    // Jika belum ada → push item baru
                    keranjang.push({
                        id: idBarang,
                        nama: namaBarang,
                        jumlah: jumlah,
                        harga: harga,
                        total: harga * jumlah
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Barang ditambahkan!',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }

                renderTabel();
                this.reset();
            });


            // ====================================================
            // 🔹 SAAT CHECKOUT -> KIRIM SEMUA DATA KE SERVER
            // ====================================================
            $('#checkoutForm').on('submit', function (e) {
                e.preventDefault();

                if (keranjang.length === 0) {
                    Swal.fire('Kosong', 'Belum ada barang di transaksi.', 'warning');
                    return;
                }

                const payload = {
                    _token: $('input[name=_token]').val(),
                    ID_Penjualan: {{ $penjualan->ID_Penjualan }},
                    Nama_Pelanggan: $('#namaPelanggan').val(),
                    No_Telp: $('#noTelp').val(),
                    Alamat: $('#alamatPelanggan').val(),
                    barang: keranjang
                };


                fetch("{{ route('transaksi.checkout') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': payload._token
                    },
                    body: JSON.stringify(payload)
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Transaksi berhasil disimpan!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = '/transaksi'; // redirect ke halaman utama
                            });
                        } else {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    });
            });

            // ====================================================
            // 🔹 CEGAH ENTER AUTO SUBMIT
            // ====================================================
            $('#Jumlah').on('keydown', function (e) {
                if (e.key === 'Enter') e.preventDefault();
            });

            renderTabel();
        });
    </script>



</body>

</html>