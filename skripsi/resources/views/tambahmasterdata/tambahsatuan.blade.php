<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
    <!-- 
    {{-- CSS Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- JS Select2 --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

</head>

<body>
    <!-- Navbar -->
    @include('layouts.navbar')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Content -->
            <div class="col-md-10 p-4">
                <h4 class="mb-4">Tambah Satuan</h4>
                <form action="{{ route('barang.tambahsatuan') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">Nama Satuan Barang</label>
                            <input type="text" class="form-control" name="Nama_Satuan"
                                placeholder="Masukkan Nama Satuan Baru." required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-save mt-3">Save</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>