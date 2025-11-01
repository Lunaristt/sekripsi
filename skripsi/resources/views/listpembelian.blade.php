@extends('layouts.app')

@section('title', 'Status Pembelian - Toko Sumber Rejeki')

@section('content')
    <h4 class="fw-bold mb-4">📦 Status Pembelian</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nama Distributor</th>
                    <th>Tanggal Pembelian</th>
                    <th>Harga Keseluruhan</th>
                    <th>Tanggal Jatuh Tempo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelian as $p)
                    <tr>
                        <td class="fw-semibold">{{ $p->distributor->Nama_Distributor ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->Tanggal)->format('d M Y') }}</td>
                        <td>Rp {{ number_format($p->Harga_Keseluruhan, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->Tanggal_Jatuh_Tempo)->format('d M Y') }}</td>

                        <td>
                            <a href="" class="btn btn-info btn-sm justify-content-center">
                                🔍 Lihat
                            </a>
                            <form action="{{ route('pembelian.cancel', $p->ID_Pembelian) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Batalkan pembelian ini?');">
                                @csrf
                                @method('DELETE')
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