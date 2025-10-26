<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Toko Sumber Rejeki - Home</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navbar')

    <!-- Struktur utama (Sidebar + Konten) -->
    <div class="container-fluid">
        <div class="row">

            <!-- ✅ Sidebar -->
            @include('layouts.sidebar')

            <!-- ✅ Konten Utama -->
            <div class="col-md-10 content p-4">
                <h5 class="fw-bold mb-3">📢 Informasi Terbaru:</h5>

                <div class="alert alert-warning mb-2">
                    <strong>⚠️ Peringatan Stok:</strong> Semen Merdeka tersisa <b>5 sak</b>.
                </div>
                <div class="alert alert-warning mb-2">
                    <strong>⚠️ Peringatan Stok:</strong> Cat Avian 5L tersisa <b>3 kaleng</b>.
                </div>

                <div class="mt-5 text-center">
                    <a href="{{ route('transaksi.create') }}" class="btn btn-order btn-primary px-4 py-2 fw-bold">
                        🛒 Buat Pesanan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>