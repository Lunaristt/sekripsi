@extends('layouts.auth')

@section('title', 'Toko Sumber Rejeki - Daftar')

@push('styles')
  <style>
    /* Gaya input & tombol sesuai tema merah */
    .login-card {
      background: #fff;
      padding: 2rem;
      border-radius: .75rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-login {
      background-color: #8b0d18;
      color: #fff;
      font-weight: 600;
      width: 100%;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      background-color: #a30f1c;
    }

    .form-control:focus {
      border-color: #8b0d18;
      box-shadow: 0 0 0 .25rem rgba(139, 13, 24, 0.25);
    }

    /* Tombol mata di input password */
    .input-group .btn-toggle-pass {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
      padding: .375rem .5rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-toggle-pass svg {
      width: 1.1rem;
      height: 1.1rem;
    }

    .btn-toggle-pass:focus {
      box-shadow: 0 0 0 .25rem rgba(139, 13, 24, .25);
    }

    .btn-outline-secondary {
      border-color: #8b0d18;
      color: #8b0d18;
    }

    .btn-outline-secondary:hover {
      background-color: #8b0d18;
      color: #fff;
    }
  </style>
@endpush

@section('content')
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 85vh;">
    <div class="col-lg-6 col-md-8 col-sm-10">
      <div class="login-card rounded">
        {{-- Pesan error --}}
        @if(session('error'))
          <div class="alert alert-danger text-center fw-semibold">
            {{ session('error') }}
          </div>
        @endif

        {{-- Form Registrasi --}}
        <form method="POST" action="{{ route('register.store') }}">
          @csrf

          <div class="mb-3">
            <label for="username" class="form-label fw-semibold">Username</label>
            <input type="text" class="form-control" id="username" name="Nama" placeholder="Masukkan username" required>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label fw-semibold">Password</label>
            <div class="input-group">
              <input type="password" class="form-control" id="password" name="Password" placeholder="Masukkan password"
                required aria-describedby="togglePassword">
              <button class="btn btn-outline-secondary btn-toggle-pass" type="button" id="togglePassword"
                aria-pressed="false" title="Tampilkan password">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" id="pwIcon">
                  <g id="eyeIcon">
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor" stroke-width="1.5"
                      stroke-linecap="round" stroke-linejoin="round" fill="none"></path>
                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5" fill="none"></circle>
                  </g>
                  <g id="eyeSlashIcon" style="display:none">
                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.8 21.8 0 0 1 4.12-5.36"
                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none">
                    </path>
                    <path d="M1 1l22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                      stroke-linejoin="round"></path>
                  </g>
                </svg>
              </button>
            </div>
          </div>

          <div class="mb-4">
            <label for="notelp" class="form-label fw-semibold">Nomor Telepon</label>
            <input type="text" class="form-control" id="notelp" name="No_Telp" placeholder="Masukkan nomor telepon aktif"
              required>
          </div>

          <button type="submit" class="btn btn-login">Daftar</button>

          <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="w-100">Sudah punya akun?</a>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    // ✅ Toggle show/hide password
    document.addEventListener("DOMContentLoaded", () => {
      const pwInput = document.getElementById('password');
      const toggleBtn = document.getElementById('togglePassword');
      const pwIcon = document.getElementById('pwIcon');
      const eye = pwIcon.querySelector('#eyeIcon');
      const eyeSlash = pwIcon.querySelector('#eyeSlashIcon');

      toggleBtn.addEventListener('click', function () {
        const isPassword = pwInput.type === 'password';
        pwInput.type = isPassword ? 'text' : 'password';
        toggleBtn.title = isPassword ? 'Sembunyikan password' : 'Tampilkan password';

        // Ganti ikon
        eye.style.display = isPassword ? 'none' : 'block';
        eyeSlash.style.display = isPassword ? 'block' : 'none';
      });
    });
  </script>
@endpush