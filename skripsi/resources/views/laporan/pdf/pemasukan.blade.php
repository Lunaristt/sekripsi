<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>

    <h3 style="text-align:center">Laporan Pemasukan Bulanan</h3>
    <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Telepon</th>
                <th>Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($penjualan as $p)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($p->Tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $p->pelanggan->Nama_Pelanggan ?? '-' }}</td>
                    <td>{{ $p->pelanggan->No_Telp ?? '-' }}</td>
                    <td>Rp {{ number_format($p->Harga_Keseluruhan, 0, ',', '.') }}</td>
                </tr>
                @php $total += $p->Harga_Keseluruhan; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Total:</th>
                <th>Rp {{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

</body>

</html>