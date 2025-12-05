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

    <!-- Form Pencarian -->
    <form action="{{ route('pengguna.index') }}" method="GET" class="mb-3">
        <div class="input-group" style="max-width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau role..."
                value="{{ request('search') }}">
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
        </div>
    </form>

    <!-- Tabel Pengguna -->
    <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Nomor Telepon</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengguna as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->Nama }}</td>
                        <td>{{ $user->No_Telp ?? '-' }}</td>
                        <td>
                            <!-- Dropdown ubah role -->
                            <form action="{{ route('pengguna.updateRole', $user->ID_Pengguna) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="Role" class="form-select form-select-sm d-inline-block w-auto"
                                    onchange="this.form.submit()">
                                    <option value="User" {{ $user->Role === 'User' ? 'selected' : '' }}>User</option>
                                    <option value="Admin" {{ $user->Role === 'Admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            @if ($user->Status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-secondary">Restricted</span>
                            @endif
                        </td>
                        <td>
                            <!-- Tombol Approve / Restrict -->
                            @if ($user->Status === 'restricted')
                                <form action="{{ route('pengguna.approve', $user->ID_Pengguna) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">✔️ Approve</button>
                                </form>
                            @else
                                <form action="{{ route('pengguna.restrict', $user->ID_Pengguna) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-warning btn-sm text-black">🚫 Restrict</button>
                                </form>
                            @endif

                            <!-- Edit -->
                            <a href="{{ route('pengguna.edit', $user->ID_Pengguna) }}" class="btn btn-outline-primary btn-sm">
                                ✏️ Edit
                            </a>

                            <!-- Hapus -->
                            <form action="{{ route('pengguna.destroy', $user->ID_Pengguna) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus pengguna ini?')">
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