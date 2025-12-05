<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Toko Sumber Rejeki - Data Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navbar')

    <div class="container-fluid">
        <div class="row">
            @include('layouts.sidebar')
            <!--Content-->
            <div class="col-md-10 p-4">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama Orang</th>
                            <th>Tanggal</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Asep</td>
                            <td>28 Agustus 2025</td>
                            <td>Rp. 520.000</td>
                            <td>
                                <button class="btn btn-outline-dark btn-sm d-flex align-items-center">
                                    👁 <span class="ms-1">Lihat</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Aldho</td>
                            <td>16 Juli 2025</td>
                            <td>Rp. 88.000</td>
                            <td>
                                <button class="btn btn-outline-dark btn-sm d-flex align-items-center">
                                    👁 <span class="ms-1">Lihat</span>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Yudo</td>
                            <td>17 Mei 2025</td>
                            <td>Rp. 120.000</td>
                            <td>
                                <button class="btn btn-outline-dark btn-sm d-flex align-items-center">
                                    👁 <span class="ms-1">Lihat</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>