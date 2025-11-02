@extends('layouts.plain')

@section('title', 'Akses Dibatasi - Toko Sumber Rejeki')

@push('styles')
    <style>
        body {
            background: linear-gradient(135deg, #8b0d18, #2b0004);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        /* Efek partikel lembut di latar */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 8s infinite ease-in-out;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            text-align: center;
            padding: 2.5rem;
            color: #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon {
            font-size: 3.5rem;
            color: #ff4d4d;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }

        h4 {
            font-weight: 700;
            color: #fff;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        p {
            color: #f2f2f2;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .btn-login {
            background-color: #ffffff;
            color: #8b0d18;
            font-weight: 600;
            border: none;
            padding: 0.7rem 2rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        .btn-login:hover {
            background-color: #8b0d18;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
        }
    </style>
@endpush

@section('content')
    <!-- Elemen partikel latar -->
    @for ($i = 0; $i < 15; $i++)
        <div class="particle" style="
                    width: {{ rand(10, 40) }}px;
                    height: {{ rand(10, 40) }}px;
                    left: {{ rand(0, 100) }}%;
                    top: {{ rand(0, 100) }}%;
                    animation-delay: {{ rand(0, 5) }}s;">
        </div>
    @endfor

    <div class="col-md-6 mx-auto position-relative">
        <div class="card">
            <div class="icon mb-3">🚫</div>
            <h4>Akses Dibatasi</h4>
            <p>Akun anda belum disetujui oleh admin.<br>Hubungi admin segera untuk mendapatkan akses penuh.</p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-login">Kembali ke Halaman Login</button>
            </form>

        </div>
    </div>
@endsection