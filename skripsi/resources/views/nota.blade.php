<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nota Penjualan</title>

    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        th {
            background: #f4f4f4;
            text-align: left;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h2>TB. SUMBER REJEKI</h2>
        <p>Jl. RA Kartini No.28 Margahayu, Bekasi Timur<br>
            0813 1811 5566
        </p>
        <p><strong>Nota Penjualan #{{ $penjualan->ID_Penjualan }}</strong></p>
    </div>

    {{-- Info Pelanggan --}}
    <p>
        <strong>Nama Pelanggan:</strong> {{ $penjualan->pelanggan->Nama_Pelanggan ?? '-' }}<br>
        <strong>No. Telp:</strong> {{ $penjualan->pelanggan->No_Telp ?? '-' }}<br>
        <strong>Tanggal:</strong> {{ $penjualan->Tanggal ?? '-' }}
    </p>

    {{-- Tabel Barang --}}
    <table>
        <tr>
            <th>Qty</th>
            <th>Nama Barang</th>
            <th>Harga Satuan</th>
            <th>Total</th>
        </tr>

        @foreach($penjualan->barangpenjualan as $bp)
            <tr>
                <td>{{ $bp->Jumlah }}</td>
                <td>{{ $bp->barang->Nama_Barang }}</td>
                <td>Rp {{ number_format($bp->barang->Harga_Barang, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($bp->Jumlah * $bp->barang->Harga_Barang, 0, ',', '.') }}</td>
            </tr>
        @endforeach

        <tr class="total">
            <td colspan="3">Total Keseluruhan</td>
            <td>Rp {{ number_format($penjualan->Harga_Keseluruhan, 0, ',', '.') }}</td>
        </tr>
    </table>

</body>

</html>