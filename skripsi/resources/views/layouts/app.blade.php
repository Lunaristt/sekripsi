<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Toko Sumber Rejeki')</title>

    <!-- ✅ Bootstrap -->

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ Vite (CSS & JS utama) -->
    @vite(['resources/css/style.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Tempat tambahan CSS per halaman -->
    @stack('styles')
</head>

<body class="bg-light">

    {{-- ✅ Navbar tampil di semua halaman --}}
    @include('layouts.navbar')

    {{-- ✅ Struktur utama --}}
    <div class="container-fluid">
        <div class="row">

            {{-- Sidebar selalu di kiri --}}
            @include('layouts.sidebar')

            {{-- Konten halaman --}}
            <div class="col-md-10 p-4">
                @yield('content')
            </div>

        </div>
    </div>

    <!-- ✅ Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ Tempat script tambahan per halaman -->
    @stack('scripts')
</body>

</html>