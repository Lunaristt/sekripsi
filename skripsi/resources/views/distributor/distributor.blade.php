<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Distributor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Navbar -->
    @include('layouts.navbar')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Konten -->
            <div class="col-md-10 p-4">
                <h4 class="mb-4 fw-bold">📦 Daftar Distributor</h4>

                <!-- Notifikasi -->
                @if(session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabel Distributor -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Distributor</th>
                                <th>Telepon CS</th>
                                <th>Nama Salesman</th>
                                <th>No. Telp Salesman</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($distributors as $d)
                                <tr>
                                    <td>{{ $d->Nama_Distributor }}</td>
                                    <td>{{ $d->Telp_CS ?? '-' }}</td>
                                    <td>{{ $d->Nama_Salesman ?? '-' }}</td>
                                    <td>{{ $d->Notelp_Salesman ?? '-' }}</td>
                                    <td class="text-center">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('distributor.edit', $d->ID_Distributor) }}"
                                            class="btn btn-warning btn-sm text-black">
                                            ✏️ Edit
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('distributor.destroy', $d->ID_Distributor) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Hapus distributor {{ $d->Nama_Distributor }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada data distributor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Tombol Tambah -->
                <a href="{{ route('distributor.create') }}" class="btn btn-primary mt-3">
                    ➕ Tambah Distributor
                </a>
            </div>
        </div>
    </div>
</body>

</html>