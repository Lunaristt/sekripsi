<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Toko Sumber Rejeki')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Navbar warna utama */
        .navbar {
            background-color: #8b0d18;
        }

        .navbar-brand {
            color: #fff !important;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .btn-auth {
            background-color: #fff;
            color: #8b0d18;
            font-weight: 600;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            background-color: #fff3f4;
            border-color: #8b0d18;
        }

        .login-card {
            background: #fff;
            padding: 2rem;
            border-radius: .75rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #8b0d18 !important;
            border-color: #8b0d18 !important;
        }

        .btn-primary:hover {
            background-color: #a30f1c !important;
            border-color: #a30f1c !important;
        }

        .form-control:focus {
            border-color: #8b0d18 !important;
            box-shadow: 0 0 0 .25rem rgba(139, 13, 24, 0.25) !important;
        }
    </style>
</head>

<body>
    <!-- ✅ Navbar sederhana -->
    <nav class="navbar navbar-expand-lg px-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Toko Sumber Rejeki</a>

            <div class="ms-auto">
                @if(Request::is('login'))
                    <a href="{{ route('register') }}" class="btn btn-auth">Daftar</a>
                @elseif(Request::is('register'))
                    <a href="{{ route('login') }}" class="btn btn-auth">Login</a>
                @endif
            </div>
        </div>
    </nav>

    <!-- ✅ Konten utama -->
    <main class="py-5">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>