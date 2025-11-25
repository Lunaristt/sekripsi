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
                    <option value="{{ $b->ID_Barang }}" data-harga="{{ $b->Harga_Barang }}"
                        data-deskripsi="{{ $b->Deskripsi_Barang }}" data-satuan="{{ $b->Besar_Satuan }} {{ $b->Satuan }}">

                        {{ $b->Nama_Barang }}

                        @if($b->Besar_Satuan)
                            ({{ $b->Besar_Satuan }} {{ $b->Satuan }})
                        @endif

                        @if($b->Deskripsi_Barang)
                            - {{ $b->Deskripsi_Barang }}
                        @endif
                    </option>
                @endforeach

            </select>
        </div>
        <div class="mb-3">
            <label for="Harga_Beli" class="form-label">Harga Beli Satuan (Rp)</label>
            <input type="number" name="Harga_Beli" id="Harga_Beli" class="form-control" min="0" required>
        </div>
        <div class="mb-3">
            <label for="Jumlah" class="form-label">Jumlah</label>
            <input type="number" name="Jumlah" id="Jumlah" class="form-control" min="1" required>
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
            <input type="hidden" name="Total_Harga" id="checkoutTotal">
            <input type="hidden" name="Nama_Salesman" id="checkoutSalesman">
            <input type="hidden" name="NoTelp_Salesman" id="checkoutTelpSalesman">
            <input type="hidden" name="Status" id="checkoutStatus" value="Diterima">
            <button type="submit" class="btn btn-success">Selesaikan Pembelian</button>
        </form>


    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {

            //VARIABEL GLOBAL
            let daftarBarang = [];
            let totalKeseluruhan = 0;

            //Select2 Distributor
            $('#namaDistributor').select2({
                placeholder: 'Pilih distributor...',
                width: '100%'
            });

            //Select2 Barang (Search)
            function initSelect2Barang() {
                $('#ID_Barang').select2({
                    placeholder: 'Cari barang...',
                    allowClear: true,
                    width: '100%'
                });
            }

            // Panggil pertama kali
            initSelect2Barang();

            //EVENT: Distributor dipilih
            $('#namaDistributor').on('change', function () {
                const distributorId = $(this).val();
                const salesman = $(this).find(':selected').data('salesman') || '';
                const telp = $(this).find(':selected').data('telp') || '';

                $('#namaSalesman').val(salesman);
                $('#noTelpSalesman').val(telp);
                $('#checkoutDistributor').val(distributorId);
                $('#checkoutSalesman').val(salesman);
                $('#checkoutTelpSalesman').val(telp);

                // Reset pilihan barang
                $('#ID_Barang').html('<option value="">-- Memuat data barang... --</option>');
                initSelect2Barang();

                // 🔵 Ambil barang distributor via AJAX
                if (distributorId) {
                    $.get(`/pembelian/barang-by-distributor/${distributorId}`, function (data) {

                        let options = '<option value="">-- Pilih Barang --</option>';

                        if (data.length > 0) {
                            data.forEach(function (b) {
                                options += `
                                                    <option value="${b.ID_Barang}">
                                                        ${b.Nama_Barang} - ${b.Deskripsi_Barang ?? ''}
                                                    </option>`;
                            });
                        } else {
                            options = '<option value="">Distributor ini belum memiliki barang.</option>';
                        }

                        // Update dropdown
                        $('#ID_Barang').html(options);

                        // 🔥 Re-init Select2 setelah isi dropdown diupdate
                        initSelect2Barang();

                    }).fail(function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal memuat data',
                            text: 'Terjadi kesalahan saat mengambil data barang dari server.'
                        });
                    });
                }
            });

            // 🟢 Autofill Harga Beli
            $('#ID_Barang').on('change', function () {
                const barangId = $(this).val();
                const distributorId = $('#namaDistributor').val();

                if (barangId && distributorId) {
                    $.get(`/pembelian/harga-beli/${distributorId}/${barangId}`, function (data) {
                        if (data.harga_beli && data.harga_beli > 0) {
                            $('#Harga_Beli').val(data.harga_beli);
                        } else {
                            $('#Harga_Beli').val('');
                            Swal.fire({
                                icon: 'info',
                                title: 'Harga beli belum terdaftar',
                                text: 'Distributor ini belum memiliki harga beli untuk barang tersebut.'
                            });
                        }
                    }).fail(function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal mengambil harga beli',
                            text: 'Terjadi kesalahan pada server.'
                        });
                    });
                } else {
                    $('#Harga_Beli').val('');
                }
            });

            // 🟢 Tambah Barang ke Tabel & Array
            const form = $('#formAddItem');
            const listBarang = $('#listBarang');
            const grandTotal = $('#grandTotal');

            form.on('submit', function (e) {
                e.preventDefault();

                const idBarang = $('#ID_Barang').val();
                const label = $('#ID_Barang option:selected').text().split('-');

                const namaBarang = label[0].trim();
                const deskripsi = label[1]?.trim() || '-';
                const jumlah = parseInt($('#Jumlah').val());
                const hargaBeli = parseInt($('#Harga_Beli').val());
                const totalHarga = jumlah * hargaBeli;

                if (!idBarang || jumlah <= 0 || hargaBeli <= 0) {
                    Swal.fire('Gagal', 'Mohon lengkapi semua data barang', 'error');
                    return;
                }

                daftarBarang.push({
                    ID_Barang: idBarang,
                    Nama_Barang: namaBarang,
                    Deskripsi: deskripsi,
                    Jumlah: jumlah,
                    Harga_Beli: hargaBeli,
                    Total_Harga: totalHarga
                });

                totalKeseluruhan += totalHarga;

                $('#checkoutBarang').val(JSON.stringify(daftarBarang));
                $('#checkoutTotal').val(totalKeseluruhan);

                $('#emptyRow').remove();

                const row = $(`
                                    <tr class="align-middle text-center">
                                        <td class="text-start fw-semibold">${namaBarang}</td>
                                        <td>${deskripsi}</td>
                                        <td>${jumlah}</td>
                                        <td class="text-end">Rp ${hargaBeli.toLocaleString()}</td>
                                        <td class="text-end fw-bold">Rp ${totalHarga.toLocaleString()}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btnHapus">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                `);

                listBarang.append(row);
                grandTotal.text(`Total: Rp ${totalKeseluruhan.toLocaleString()}`);

                form.trigger('reset');
                $('#ID_Barang').val(null).trigger('change');

                row.find('.btnHapus').on('click', function () {
                    row.remove();
                    totalKeseluruhan -= totalHarga;
                    daftarBarang = daftarBarang.filter(item => item.ID_Barang !== idBarang);

                    $('#checkoutBarang').val(JSON.stringify(daftarBarang));
                    $('#checkoutTotal').val(totalKeseluruhan);

                    grandTotal.text(`Total: Rp ${totalKeseluruhan.toLocaleString()}`);

                    if (listBarang.children('tr').length === 0) {
                        listBarang.html(`
                                            <tr id="emptyRow">
                                                <td colspan="6" class="text-center text-muted py-3">
                                                    Belum ada barang dalam pembelian
                                                </td>
                                            </tr>
                                        `);
                    }
                });
            });
            // 🟢 Checkout
            $('#checkoutForm').on('submit', function (e) {
                e.preventDefault();

                const distributorId = $('#namaDistributor').val();
                if (!distributorId) {
                    Swal.fire('Gagal', 'Pilih distributor terlebih dahulu!', 'warning');
                    return;
                }

                $('#checkoutDistributor').val(distributorId);
                $('#checkoutTempo').val($('#tanggalJatuhTempo').val());

                const form = $(this);
                const formData = form.serialize();

                $.post(form.attr('action'), formData, function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `${response.message} (Total: Rp ${response.total.toLocaleString()})`
                        }).then(() => {
                            window.location.href = '/pembelian/create';
                        });
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                }).fail(() => {
                    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan pembelian.', 'error');
                });
            });

        });
    </script>
@endpush