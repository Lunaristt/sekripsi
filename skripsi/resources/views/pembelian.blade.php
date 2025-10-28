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