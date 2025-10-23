<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembelian Barang - Toko Sumber Rejeki</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div class="col-md-10 content p-4">
        <h4 class="fw-bold mb-4">🧾 Form Pembelian Barang dari Distributor</h4>

        <!-- Pesan sukses / error -->
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <!-- FORM PEMBELIAN -->
        <form action="{{ route('pembelian.addItem') }}" method="POST" id="formPembelian">
            @csrf

            <!-- Informasi Distributor -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="ID_Distributor" class="form-label fw-semibold">Nama Distributor</label>
                    <select id="ID_Distributor" name="ID_Distributor" class="form-select bg-secondary-subtle border-0"
                        required>
                        <option value="">-- Pilih Distributor --</option>
                        @foreach ($distributor as $d)
                            <option value="{{ $d->ID_Distributor }}" data-telp="{{ $d->Notelp_Salesman }}"
                                data-sales="{{ $d->Nama_Salesman }}">
                                {{ $d->Nama_Distributor }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="Notelp_Salesman" class="form-label fw-semibold">Nomor Telepon Sales</label>
                    <input type="text" id="Notelp_Salesman" class="form-control bg-secondary-subtle border-0" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label for="Nama_Salesman" class="form-label fw-semibold">Nama Salesman</label>
                <input type="text" id="Nama_Salesman" class="form-control bg-secondary-subtle border-0" readonly>
            </div>

            <hr class="my-4">

            <!-- Tabel Barang -->
            <h5 class="fw-semibold mb-3">Daftar Barang yang Dibeli</h5>
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
                <tbody id="daftarBarang">
                    @forelse ($transaksi as $t)
                        <tr>
                            <td>{{ $t->barang->Nama_Barang }}</td>
                            <td>{{ $t->barang->Deskripsi_Barang ?? '-' }}</td>
                            <td>{{ $t->Jumlah_Pesanan }}</td>
                            <td>Rp {{ number_format($t->barang->Harga_Barang, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($t->Total_Harga, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('pembelian.destroy') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="ID_Pembelian" value="{{ $t->ID_Pembelian }}">
                                    <input type="hidden" name="ID_Barang" value="{{ $t->ID_Barang }}">
                                    <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted text-center">Belum ada barang dalam transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Input Barang -->
            <div class="row g-3 align-items-end mt-3">
                <div class="col-md-6">
                    <label for="ID_Barang" class="form-label fw-semibold">Pilih Barang</label>
                    <select id="ID_Barang" class="form-select bg-secondary-subtle border-0">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->ID_Barang }}" data-harga="{{ $b->Harga_Barang }}"
                                data-deskripsi="{{ $b->Deskripsi_Barang ?? '-' }}">
                                {{ $b->Nama_Barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="Jumlah_Pesanan" class="form-label fw-semibold">Jumlah</label>
                    <input type="number" id="Jumlah_Pesanan" min="1" class="form-control bg-secondary-subtle border-0"
                        placeholder="Masukkan jumlah">
                </div>

                <div class="col-md-3">
                    <button type="button" id="btnTambahBarang" class="btn btn-primary fw-semibold w-100">
                        Tambah Barang
                    </button>
                </div>
            </div>

            <div class="text-end mt-4">
                <h5 class="fw-bold">Total: <span id="totalHarga">Rp
                        {{ number_format($pembelian->Harga_Keseluruhan, 0, ',', '.') }}</span></h5>
            </div>

            <!-- Tombol Aksi -->
            <div class="text-end mt-3">
                <a href="{{ route('pembelian.cancel') }}" class="btn btn-danger fw-semibold px-4 me-2">Batalkan
                    Pembelian</a>
                <button type="submit" class="btn btn-success fw-semibold px-4">Selesaikan Pembelian</button>
            </div>
        </form>
    </div>

    <!-- SCRIPT -->
    <script>
        // 🔹 Autofill data distributor
        const distributorSelect = document.getElementById('ID_Distributor');
        const telpSalesInput = document.getElementById('Notelp_Salesman');
        const namaSalesInput = document.getElementById('Nama_Salesman');
        distributorSelect.addEventListener('change', function () {
            const sel = this.options[this.selectedIndex];
            telpSalesInput.value = sel.getAttribute('data-telp') || '';
            namaSalesInput.value = sel.getAttribute('data-sales') || '';
        });

        // 🔹 Tambah barang via AJAX
        const barangSelect = document.getElementById('ID_Barang');
        const jumlahInput = document.getElementById('Jumlah_Pesanan');
        const daftarBarang = document.getElementById('daftarBarang');
        const totalHargaDisplay = document.getElementById('totalHarga');

        document.getElementById('btnTambahBarang').addEventListener('click', function () {
            const idBarang = barangSelect.value;
            const jumlah = parseInt(jumlahInput.value) || 0;

            if (!idBarang || jumlah <= 0) {
                alert('Pilih barang dan masukkan jumlah yang valid!');
                return;
            }

            fetch("{{ route('pembelian.addItem') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    ID_Barang: idBarang,
                    Jumlah_Pesanan: jumlah
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${data.barang}</td>
                        <td>${data.deskripsi}</td>
                        <td>${data.jumlah}</td>
                        <td>Rp ${data.harga.toLocaleString('id-ID')}</td>
                        <td>Rp ${data.total.toLocaleString('id-ID')}</td>
                        <td><button class="btn btn-danger btn-sm btnHapus">Hapus</button></td>
                    `;
                        daftarBarang.appendChild(row);
                        totalHargaDisplay.textContent = 'Rp ' + data.grandTotal.toLocaleString('id-ID');
                        jumlahInput.value = '';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => alert('Terjadi kesalahan: ' + err.message));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>