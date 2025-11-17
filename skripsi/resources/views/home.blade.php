@extends('layouts.app')

@section('title', 'Home - Toko Sumber Rejeki')

@section('content')
    <div class="p-4">

        <h5 class="fw-bold mb-3">📢 Informasi Terbaru:</h5>

        {{-- ⏰ Peringatan Jatuh Tempo Pembelian --}}
        @forelse ($jatuhTempo as $p)
            <div class="alert alert-danger mb-2 d-flex justify-content-between align-items-center">
                <div>
                    <strong>⏰ Jatuh Tempo Segera:</strong>
                    Pembelian dari <b>{{ $p->distributor->Nama_Distributor ?? 'Distributor Tidak Dikenal' }}</b>
                    akan jatuh tempo pada
                    <b>{{ \Carbon\Carbon::parse($p->Tanggal_Jatuh_Tempo)->format('d M Y') }}</b>.
                </div>
                <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-outline-light text-dark">
                    Lihat Detail
                </a>
            </div>
        @empty
            <div class="alert alert-info mb-2">
                🕓 Tidak ada pembelian yang mendekati tanggal jatuh tempo.
            </div>
        @endforelse

        {{-- 📊 Rekap Transaksi Kemarin --}}
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-center">
                    📅 Rekap Transaksi Tanggal {{ $kemarin->format('d M Y') }}
                </h5>

                <div class="row text-center">
                    <div class="col-md-6 border-end">
                        <h6 class="text-muted">Total Pembelian</h6>
                        <h4 class="text-danger fw-bold">
                            Rp {{ number_format($totalPembelianKemarin, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Total Penjualan</h6>
                        <h4 class="text-success fw-bold">
                            Rp {{ number_format($totalPenjualanKemarin, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    <a href="{{ route('pembelian.index') }}" class="btn btn-outline-danger btn-sm me-2">
                        📦 Detail Pembelian
                    </a>
                    <a href="{{ route('statustransaksi.index') }}" class="btn btn-outline-success btn-sm">
                        🛍️ Detail Penjualan
                    </a>
                </div>
            </div>
        </div>

        {{-- 🛒 Tombol Buat Pesanan --}}
        <div class="mt-3 mb-3 text-center">
            <a href="{{ route('transaksi.create') }}" class="btn btn-order btn-primary px-4 py-2 fw-bold">
                🛒 Buat Pesanan Baru
            </a>
        </div>

        {{-- ⚠️ Peringatan Stok Menipis --}}
        @forelse ($barangMenipis as $b)
            @php
                // Siapkan daftar info tambahan (bisa berisi satu atau dua data)
                $infoTambahan = [];
                if (!empty($b['barang']->Besar_Satuan)) {
                    $infoTambahan[] = $b['barang']->Besar_Satuan;
                }
                if (!empty($b['barang']->Deskripsi_Barang)) {
                    $infoTambahan[] = $b['barang']->Deskripsi_Barang;
                }
              @endphp

            <div class="alert alert-warning mb-2 d-flex justify-content-between align-items-center">
                <div>
                    <strong>⚠️ Stok Menipis:</strong>
                    {{ $b['barang']->Nama_Barang }}
                    @if(count($infoTambahan) > 0)
                        ({{ implode(' - ', $infoTambahan) }})
                    @endif
                    tersisa <b>{{ $b['barang']->Stok_Barang }}</b> unit.
                </div>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-dark">Lihat Barang</a>
            </div>
        @empty
            <div class="alert alert-success mb-2">
                ✅ Semua stok barang masih mencukupi.
            </div>
        @endforelse

    </div>
@endsection