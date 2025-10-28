@extends('layouts.app')

@section('title', 'Laporan Pengeluaran Bulanan - Toko Sumber Rejeki')

@section('content')
    <h4 class="fw-bold mb-4">📊 Laporan Pengeluaran Bulanan</h4>

    <!-- 🔹 Filter Bulan -->
    <form method="GET" action="{{ route('laporan.pengeluaran') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="bulan" class="form-label fw-semibold">Pilih Bulan</label>
            <input type="month" id="bulan" name="bulan" class="form-control"
                value="{{ request('bulan', now()->format('Y-m')) }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary fw-semibold">Tampilkan</button>
        </div>
    </form>

    <!-- 🔹 Tabel Laporan -->
    @if(isset($pembelian) && count($pembelian) > 0)
        <div class="card shadow-sm p-3">
            <h5 class="fw-bold mb-3">
                Periode: {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}
            </h5>

            <table class="table table-striped align-middle">
                <thead class="table-dark text-left">
                    <tr>
                        <th>Tanggal Jatuh Tempo</th>
                        <th>Nama Distributor</th>
                        <th>Salesman</th>
                        <th>Total Pembelian (Rp)</th>

                    </tr>
                </thead>
                <tbody>
                    @php $totalBulan = 0; @endphp
                    @foreach($pembelian as $p)
                        <tr>
                            <td class="text-center">{{ \Carbon\Carbon::parse($p->Tanggal_Jatuh_Tempo)->format('d/m/Y') }}</td>
                            <td>{{ $p->distributor->Nama_Distributor ?? '-' }}</td>
                            <td>{{ $p->distributor->Nama_Salesman ?? '-' }}</td>
                            <td class="text-end">{{ number_format($p->Harga_Keseluruhan, 0, ',', '.') }}</td>
                        </tr>
                        @php $totalBulan += $p->Harga_Keseluruhan; @endphp
                    @endforeach
                </tbody>

                <tfoot class="fw-bold">
                    <tr class="table-light">
                        <td colspan="3" class="text-end">Total Pengeluaran Bulan Ini:</td>
                        <td class="text-end">Rp {{ number_format($totalBulan, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="alert alert-info mt-3 text-center">
            Tidak ada data pembelian pada bulan ini.
        </div>
    @endif
@endsection