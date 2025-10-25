<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Sumber Rejeki - Pajak</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <!-- Content -->
    <div class="col-md-10 content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Laporan Pajak</h4>

            <!-- Tombol Refresh -->
            <a href="{{ route('pajak.index') }}" class="btn btn-primary ms-2">🔄 Refresh</a>
        </div>

        <!-- Deskripsi -->
        <div class="alert alert-info mb-4">
            <strong>Keterangan:</strong> PPh Final sebesar <b>0,5%</b> dikenakan apabila total omzet tahunan melebihi
            <b>Rp 500.000.000</b>.
        </div>

        <!-- Tabel Pajak -->
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Tahun</th>
                        <th scope="col">Total Omzet</th>
                        <th scope="col">PPh Final (0,5%)</th>
                        <th scope="col">Status Pajak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            <td>{{ $row->Tahun }}</td>
                            <td class="text-end">Rp {{ number_format($row->Total_Omzet, 0, ',', '.') }}</td>
                            <td class="text-end">
                                @if($row->PPh_Final > 0)
                                    Rp {{ number_format($row->PPh_Final, 0, ',', '.') }}
                                @else
                                    Rp 0
                                @endif
                            </td>
                            <td>
                                @if($row->Total_Omzet > 500000000)
                                    <span class="badge bg-danger">Wajib Bayar</span>
                                @else
                                    <span class="badge bg-success">Tidak Kena Pajak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data penjualan untuk perhitungan
                                pajak.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>