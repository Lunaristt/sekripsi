<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Toko Sumber Rejeki - Data Distributor</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div class="col-md-10 content">
        <h4 class="fw-bold mb-4">Form Data Distributor & Sales</h4>

        <!-- Pesan sukses -->
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('distributor.store') }}" method="POST">
            @csrf

            <!-- Nama Distributor -->
            <div class="mb-3">
                <label for="Nama_Distributor" class="form-label fw-semibold">Nama Distributor</label>
                <input type="text" class="form-control bg-secondary-subtle border-0" id="Nama_Distributor"
                    name="Nama_Distributor" placeholder="Masukkan nama distributor" required>
            </div>

            <!-- Nomor Telepon Customer Service -->
            <div class="mb-3">
                <label for="Telp_CS" class="form-label fw-semibold">Nomor Telepon CS</label>
                <input type="text" class="form-control bg-secondary-subtle border-0" id="Telp_CS" name="Telp_CS"
                    placeholder="Masukkan nomor telepon CS" required>
            </div>

            <!-- Nama Sales -->
            <div class="mb-3">
                <label for="Nama_Salesman" class="form-label fw-semibold">Nama Salesman</label>
                <input type="text" class="form-control bg-secondary-subtle border-0" id="Nama_Salesman"
                    name="Nama_Salesman" placeholder="Masukkan nama sales" required>
            </div>

            <!-- Nomor Telepon Sales -->
            <div class="mb-3">
                <label for="Notelp_Salesman" class="form-label fw-semibold">Nomor Telepon Salesman</label>
                <input type="text" class="form-control bg-secondary-subtle border-0" id="Notelp_Salesman"
                    name="Notelp_Salesman" placeholder="Masukkan nomor telepon sales" required>
            </div>

            <!-- Tombol Simpan -->
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-warning fw-semibold px-4">Save</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>