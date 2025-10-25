<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Distributor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
    <style>
        .toggle-btn {
            cursor: pointer;
            transition: 0.2s;
        }
    </style>
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">📦 Daftar Distributor</h4>

                    <!-- Tombol toggle mode -->
                    <button id="toggleMode" class="btn btn-outline-primary toggle-btn">
                        🔁 Ubah ke Mode Upload Excel
                    </button>
                </div>

                <!-- Notifikasi -->
                @if(session('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ✅ Mode Input Manual -->
                <div id="manualForm">
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
                                            <form action="{{ route('distributor.destroy', $d->ID_Distributor) }}"
                                                method="POST" class="d-inline"
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

                <!-- ✅ Mode Upload Excel -->
                <div id="excelForm" class="d-none mt-4">
                    <form action="{{ route('distributor.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload File Excel Distributor</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                            <div class="form-text">
                                Pastikan format kolom sesuai template Excel sistem:
                                <br>
                                <code>Nama_Distributor | Telp_CS | Nama_Salesman | Notelp_Salesman</code>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">📤 Upload Excel</button>
                        <a href="{{ route('distributor.downloadTemplate') }}" class="btn btn-secondary ms-2">
                            📄 Unduh Template Excel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleMode');
        const manualForm = document.getElementById('manualForm');
        const excelForm = document.getElementById('excelForm');

        toggleBtn.addEventListener('click', () => {
            const isManualVisible = !manualForm.classList.contains('d-none');
            manualForm.classList.toggle('d-none');
            excelForm.classList.toggle('d-none');

            toggleBtn.textContent = isManualVisible
                ? '✏️ Ubah ke Mode Input Manual'
                : '🔁 Ubah ke Mode Upload Excel';
        });
    </script>
</body>

</html>