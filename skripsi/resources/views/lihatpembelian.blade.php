@extends('layouts.app')

@section('title', 'Detail Pembelian - Toko Sumber Rejeki')

@push('styles')
    <style>
        .readonly-input {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')
    <h4 class="mb-4">Detail Pembelian (ID: {{ $pembelian->ID_Pembelian }})</h4>

    {{-- Informasi Distributor --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <h6 class="fw-bold">Nama Distributor</h6>
            <input type="text" class="form-control readonly-input"
                value="{{ $pembelian->distributor->Nama_Distributor ?? '-' }}" readonly>
        </div>
        <div class="col-md-3">
            <h6 class="fw-bold">Nomor Telepon</h6>
            <input type="text" class="form-control readonly-input" value="{{ $pembelian->distributor->No_Telp ?? '-' }}"
                readonly>
        </div>
        <div class="col-md-3">
            <h6 class="fw-bold">Alamat</h6>
            <input type="text" class="form-control readonly-input" value="{{ $pembelian->distributor->Alamat ?? '-' }}"
                readonly>
        </div>
        <div class="col-md-3">
            <h6 class="fw-bold">Tanggal Transaksi</h6>
            <input type="text" class="form-control readonly-input" value="{{ $pembelian->Tanggal ?? '-' }}" readonly>
        </div>
    </div>

    {{-- Tabel Barang --}}
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Nama Barang</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Harga Beli Satuan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembelian->barangpembelian as $bp)
                <tr>
                    <td>{{ $bp->barang->Nama_Barang }}</td>
                    <td>{{ $bp->barang->Deskripsi_Barang ?? '-' }}</td>

                    {{-- Jumlah + Satuan --}}
                    <td>{{ $bp->Jumlah }} {{ $bp->barang->satuanbarang->Nama_Satuan ?? '' }}</td>

                    {{-- Harga beli dari tabel pivot / tabel barang sesuai struktur kamu --}}
                    <td>Rp {{ number_format($bp->Harga_Beli, 0, ',', '.') }}</td>

                    {{-- Total harga dihitung dari jumlah × harga beli --}}
                    <td>
                        Rp {{ number_format($bp->Jumlah * $bp->Harga_Beli, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada barang dalam pembelian ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Total Pembelian --}}
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h5 class="fw-bold">Total Pembelian:</h5>
        <h5 class="fw-bold text-primary">
            Rp {{ number_format($pembelian->Harga_Keseluruhan, 0, ',', '.') }}
        </h5>
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary me-2">Kembali</a>
    </div>
@endsection