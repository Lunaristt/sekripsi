@extends('layouts.app')

@section('title', 'Daftar Pengguna - Toko Sumber Rejeki')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">👥 Daftar Pengguna</h4>

        <div>
            <a href="{{ route('pengguna.create') }}" class="btn btn-success">➕ Tambah Pengguna</a>
            <a href="{{ route('pengguna.index') }}" class="btn btn-primary ms-2">🔄 Refresh</a>
        </div>
    </div>

    <!-- 🔍 Form Pencarian -->
    <form action="{{ route('pengguna.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..."
                value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
        </div>
    </form>

    <!-- 📋 Tabel Pengguna -->
    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Nomor Telepon</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengguna as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->Nama }}</td>
                        <td>{{ $user->No_Telp }}</td>
                        <td>
                            @if ($user->Role === 'Admin')
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-primary">User</span>
                            @endif
                        </td>
                        <td>
                            <a href="" class="btn btn-warning btn-sm text-black">
                                ✏️ Edit
                            </a>
                            <form action="" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada data pengguna.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection