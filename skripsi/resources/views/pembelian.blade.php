@extends('layouts.app')

@section('title', 'Pembelian Baru - Toko Sumber Rejeki')

@push('styles')
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
@endpush

@section('content')
    <h4 class="mb-4">Pembelian Baru (ID: {{ $pembelian->ID_Pembelian }})</h4>

    {{-- 🔹 Informasi Distributor --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <h6 class="fw-bold">Distributor</h6>
            <select id="namaDistributor" class="form-control" style="width: 100%;">
                <option value="">-- Pilih Distributor --</option>
                @foreach($distributor as $d)
                    <option value="{{ $d->ID_Distributor }}" data-nama="{{ $d->Nama_Distributor }}"
                        data-salesman="{{ $d->Nama_Salesman }}" data-telp="{{ $d->Notelp_Salesman }}">
                        {{ $d->Nama_Distributor }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <h6 class="fw-bold">Nama Salesman</h6>
            <input type="text" id="namaSalesman" class="form-control" placeholder="Nama salesman..." readonly>
        </div>

        <div class="col-md-4">
            <h6 class="fw-bold">No. Telp Salesman</h6>
            <input type="text" id="noTelpSalesman" class="form-control" placeholder="Nomor telepon salesman..." readonly>
        </div>
    </div>

    {{-- 🔹 Informasi Tanggal --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="fw-bold">Tanggal Pembelian</h6>
            <input type="date" id="tanggalPembelian" class="form-control" value="{{ date('Y-m-d') }}" readonly>
        </div>
        <div class="col-md-6">
            <h6 class="fw-bold">Tanggal Jatuh Tempo</h6>
            <input type="date" id="tanggalJatuhTempo" class="form-control"
                value="{{ date('Y-m-d', strtotime('+60 days')) }}">
        </div>
    </div>

    {{-- 🔹 Tabel Barang --}}
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Nama Barang</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Harga Beli</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="listBarang">
            <tr id="emptyRow">
                <td colspan="6" class="text-center">Belum ada barang dalam pembelian</td>
            </tr>
        </tbody>
    </table>

    {{-- 🔹 Form Tambah Barang --}}
    <form id="formAddItem" action="{{ route('pembelian.addItem') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="ID_Barang" class="form-label">Pilih Barang</label>
            <select name="ID_Barang" id="ID_Barang" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barang as $b)
                    <option value="{{ $b->ID_Barang }}">{{ $b->Nama_Barang }} - {{ $b->Deskripsi_Barang }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="Jumlah" class="form-label">Jumlah</label>
            <input type="number" name="Jumlah" id="Jumlah" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label for="Harga_Beli" class="form-label">Harga Beli Satuan (Rp)</label>
            <input type="number" name="Harga_Beli" id="Harga_Beli" class="form-control" min="0" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah Barang</button>
    </form>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <h6 id="grandTotal" class="fw-bold">Total: Rp 0</h6>
    </div>

    {{-- 🔹 Tombol --}}
    <div class="d-flex justify-content-end mt-3">
        <form action="{{ route('pembelian.cancel') }}" method="POST" class="me-2">
            @csrf
            <button type="submit" class="btn btn-danger" onclick="return confirm('Batalkan pembelian ini?')">
                Batalkan Pembelian
            </button>
        </form>

        <form action="{{ route('pembelian.checkout') }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="ID_Distributor" id="checkoutDistributor">
            <input type="hidden" name="Tanggal_Jatuh_Tempo" id="checkoutTempo">
            <input type="hidden" name="barang" id="checkoutBarang">
            <input type="hidden" name="Nama_Salesman" id="checkoutSalesman">
            <input type="hidden" name="NoTelp_Salesman" id="checkoutTelpSalesman">
            <button type="submit" class="btn btn-success">Selesaikan Pembelian</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#namaDistributor').select2({ placeholder: 'Pilih distributor...', allowClear: true, width: '100%' });

            $('#namaDistributor').on('change', function () {
                const distributorId = $(this).val();
                const salesman = $(this).find(':selected').data('salesman') || '';
                const telp = $(this).find(':selected').data('telp') || '';

                $('#namaSalesman').val(salesman);
                $('#noTelpSalesman').val(telp);

                $('#ID_Barang').html('<option value="">Memuat data barang...</option>');

                if (distributorId) {
                    $.get(`/pembelian/barang-by-distributor/${distributorId}`, function (data) {
                        let options = '<option value="">-- Pilih Barang --</option>';
                        if (data.length > 0) {
                            data.forEach(item => {
                                options += `<option value="${item.id}" data-harga="${item.harga_beli}">
                                ${item.deskripsi} - Rp${item.harga_beli.toLocaleString('id-ID')}
                            </option>`;
                            });
                        } else {
                            options = '<option value="">Tidak ada barang untuk distributor ini</option>';
                        }
                        $('#ID_Barang').html(options);
                    }).fail(() => {
                        $('#ID_Barang').html('<option value="">Gagal memuat barang</option>');
                    });
                } else {
                    $('#ID_Barang').html('<option value="">-- Pilih Barang --</option>');
                }
            });

            $('#ID_Barang').on('change', function () {
                const harga = $(this).find(':selected').data('harga') || 0;
                $('#Harga_Beli').val(harga);
            });

            let keranjang = [];

            function renderTabel() {
                const tbody = $('#listBarang');
                tbody.empty();

                if (keranjang.length === 0) {
                    tbody.html('<tr><td colspan="6" class="text-center">Belum ada barang dalam pembelian</td></tr>');
                } else {
                    keranjang.forEach((item, i) => {
                        tbody.append(`
                        <tr>
                            <td>${item.deskripsi}</td>
                            <td>-</td>
                            <td>${item.jumlah}</td>
                            <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
                            <td>Rp ${item.total.toLocaleString('id-ID')}</td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusBarang(${i})">Hapus</button></td>
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
            };

            $('#formAddItem').on('submit', function (e) {
                e.preventDefault();

                const idBarang = $('#ID_Barang').val();
                const deskripsi = $('#ID_Barang option:selected').text().split('-')[0].trim();
                const harga = parseInt($('#Harga_Beli').val());
                const jumlah = parseInt($('#Jumlah').val());

                if (!idBarang || jumlah <= 0 || harga <= 0) {
                    Swal.fire('Peringatan', 'Pastikan semua data valid.', 'warning');
                    return;
                }

                const existing = keranjang.find(item => item.id === idBarang);
                if (existing) {
                    existing.jumlah += jumlah;
                    existing.total = existing.harga * existing.jumlah;
                    Swal.fire({ icon: 'info', title: 'Jumlah diperbarui!', timer: 800, showConfirmButton: false });
                } else {
                    keranjang.push({ id: idBarang, deskripsi, jumlah, harga, total: jumlah * harga });
                    Swal.fire({ icon: 'success', title: 'Barang ditambahkan!', timer: 800, showConfirmButton: false });
                }

                renderTabel();
                this.reset();
            });

            $('#checkoutForm').on('submit', function (e) {
                if (keranjang.length === 0) {
                    e.preventDefault();
                    Swal.fire('Kosong', 'Belum ada barang dalam pembelian.', 'warning');
                    return;
                }

                const selected = $('#namaDistributor option:selected');
                $('#checkoutDistributor').val(selected.val());
                $('#checkoutTempo').val($('#tanggalJatuhTempo').val());
                $('#checkoutBarang').val(JSON.stringify(keranjang));
                $('#checkoutSalesman').val($('#namaSalesman').val());
                $('#checkoutTelpSalesman').val($('#noTelpSalesman').val());
            });

            renderTabel();
        });
    </script>
@endpush