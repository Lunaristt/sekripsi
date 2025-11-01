@extends('layouts.app')

@section('title', 'Status Penjualan - Toko Sumber Rejeki')

@section('content')
    <h4 class="mb-4 fw-bold">Status Penjualan</h4>

    {{-- ✅ Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ✅ Tabel Penjualan --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Nomor Telepon</th>
                    <th>Harga Keseluruhan</th>
                    <th>Tanggal</th>
                    <th>Status Transaksi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualan as $p)
                    <tr>
                        <td>{{ $p->Nama_Pelanggan }}</td>
                        <td>{{ $p->No_Telp }}</td>
                        <td>Rp. {{ number_format($p->Harga_Keseluruhan, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->Tanggal)->format('d M Y') }}</td>
                        <td>
                            @if($p->Status === 'Selesai')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($p->Status === 'Proses')
                                <span class="badge bg-warning text-dark">Proses</span>
                            @else
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- 🔍 Tombol Lihat --}}
                                <a href="{{ route('penjualan.show', $p->ID_Penjualan) }}"
                                    class="btn btn-info btn-sm text-white">
                                    🔍 Lihat
                                </a>

                                {{-- ❌ Tombol Batal --}}
                                @if($p->Status === 'Batal')
                                    <button class="btn btn-secondary btn-sm" disabled>Batal</button>
                                @else
                                    <form action="{{ route('transaksi.batal', $p->ID_Penjualan) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm text-white">Batal</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data penjualan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection