@extends('layouts.app')

@section('title', 'Detail Transaksi - Toko Sumber Rejeki')

@push('styles')
    <style>
        .readonly-input {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')
    <h4 class="mb-4">Transaksi Pelanggan (ID: {{ $penjualan->ID_Penjualan }})</h4>

    {{-- 🔹 Informasi Pelanggan --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <h6 class="fw-bold">Nama Pelanggan</h6>
            <input type="text" class="form-control readonly-input"
                value="{{ $penjualan->pelanggan->Nama_Pelanggan ?? '-' }}" readonly>
        </div>
        <div class="col-md-4">
            <h6 class="fw-bold">Nomor Telepon</h6>
            <input type="text" class="form-control readonly-input" value="{{ $penjualan->pelanggan->No_Telp ?? '-' }}"
                readonly>
        </div>
        <div class="col-md-4">
            <h6 class="fw-bold">Alamat</h6>
            <input type="text" class="form-control readonly-input" value="{{ $penjualan->pelanggan->Alamat ?? '-' }}"
                readonly>
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
            </tr>
        </thead>
        <tbody>
            @forelse($penjualan->barangpenjualan as $bp)
                <tr>
                    <td>{{ $bp->barang->Nama_Barang }}</td>
                    <td>{{ $bp->barang->Deskripsi_Barang ?? '-' }}</td>
                    <td>{{ $bp->Jumlah }} {{ $bp->barang->satuanbarang->Nama_Satuan ?? '' }}</td>

                    {{-- Harga dari tabel BARANG --}}
                    <td>Rp {{ number_format($bp->barang->Harga_Barang, 0, ',', '.') }}</td>

                    {{-- Total harga dihitung dari harga barang × jumlah --}}
                    <td>Rp {{ number_format($bp->Jumlah * $bp->barang->Harga_Barang, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada barang dalam transaksi ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 🔹 Total Keseluruhan --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h5 class="fw-bold">Total Keseluruhan:</h5>
        <h5 class="fw-bold text-success">
            Rp {{ number_format($penjualan->Harga_Keseluruhan, 0, ',', '.') }}
        </h5>
    </div>

    {{-- 🔹 Tombol Aksi --}}
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary me-2">Kembali</a>
        <form action="{{ route('penjualan.print', $penjualan->ID_Penjualan) }}" method="GET">
            <button type="submit" class="btn btn-primary">🖨️ Cetak Nota</button>
        </form>
    </div>
@endsection