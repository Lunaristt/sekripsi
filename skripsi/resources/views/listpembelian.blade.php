@extends('layouts.app')

@section('title', 'Status Pembelian - Toko Sumber Rejeki')

@section('content')
    <h4 class="fw-bold mb-4">📦 List Pembelian</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID Pembelian</th>
                    <th>Nama Distributor</th>
                    <th>Tanggal Pembelian</th>
                    <th>Harga Keseluruhan</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelian as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->ID_Pembelian ?? '—' }}</td>
                        <td class="fw-semibold">{{ $p->distributor->Nama_Distributor ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->Tanggal)->format('d M Y') }}</td>
                        <td>Rp {{ number_format($p->Harga_Keseluruhan, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->Tanggal_Jatuh_Tempo)->format('d M Y') }}</td>
                        <td>
                            @if($p->Status === 'Diterima')
                                <span class="badge bg-success">Diterima</span>
                            @elseif($p->Status === 'Dikembalikan')
                                <span class="badge bg-danger">Dikembalikan</span>
                            @elseif($p->Status === 'Menunggu')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @else
                                <span class="badge bg-secondary">{{ $p->Status ?? 'Tidak Diketahui' }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pembelian.show', $p->ID_Pembelian) }}"
                                class="btn btn-info btn-sm justify-content-center">
                                🔍 Lihat
                            </a>
                            <form action="{{ route('pembelian.cancel') }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Batalkan pembelian ini?');">
                                @csrf
                                <input type="hidden" name="id" value="{{ $p->ID_Pembelian }}">
                                <button type="submit" class="btn btn-danger btn-sm">❌ Batalkan</button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Belum ada transaksi pembelian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection