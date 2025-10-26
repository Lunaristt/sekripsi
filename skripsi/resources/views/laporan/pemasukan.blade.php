<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pemasukan Bulanan - Toko Sumber Rejeki</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div class="col-md-10 content p-4">
        <h4 class="fw-bold mb-4">💰 Laporan Pemasukan Bulanan</h4>

        <!-- Filter Bulan -->
        <form method="GET" action="{{ route('laporan.pemasukan') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="bulan" class="form-label fw-semibold">Pilih Bulan</label>
                <input type="month" id="bulan" name="bulan" class="form-control"
                    value="{{ request('bulan', now()->format('Y-m')) }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary fw-semibold">Tampilkan</button>
            </div>
        </form>

        @if(isset($penjualan) && $penjualan->count() > 0)
            <div class="card shadow-sm p-3">
                <h5 class="fw-bold mb-3">
                    Periode: {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}
                </h5>

                <table class="table table-striped align-middle">
                    <thead class="table-dark text-start">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>No. Telepon</th>
                            <th>Total Penjualan (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="text-start">
                        @php $totalBulan = 0; @endphp
                        @foreach($penjualan as $p)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($p->Tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $p->pelanggan->Nama_Pelanggan ?? '-' }}</td>
                                <td>{{ $p->pelanggan->No_Telp ?? '-' }}</td>
                                <td>Rp {{ number_format($p->Harga_Keseluruhan, 0, ',', '.') }}</td>
                            </tr>
                            @php $totalBulan += $p->Harga_Keseluruhan; @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="fw-bold">
                        <tr class="table-light">
                            <td colspan="3" class="text-end">Total Pemasukan Bulan Ini:</td>
                            <td class="text-start">Rp {{ number_format($totalBulan, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>

                <!-- ✅ Tambahkan Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $penjualan->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <div class="alert alert-info mt-3 text-center">
                Tidak ada data penjualan pada bulan ini.
            </div>
        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>