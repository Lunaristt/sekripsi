<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Toko Sumber Rejeki - Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>

<body>
  @include('layouts.navbar')
  @include('layouts.sidebar')

  <!-- Content -->
  <div class="col-md-10 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <!-- Form Search -->
      <form action="{{ route('barang.index') }}" method="GET" class="d-flex flex-grow-1 me-2">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari barang..."
          value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Cari</button>
      </form>

      <!-- Tombol Tambah Barang -->
      <a href="{{ route('tambahbarang.create') }}" class="btn btn-success">Tambah Barang Baru</a>

      <!-- Dropdown Sort -->
      <div class="dropdown ms-2">
        <button class="btn btn-info dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown"
          aria-expanded="false">
          Urutkan
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
          <li>
            <a class="dropdown-item"
              href="{{ route('barang.index', array_merge(request()->query(), ['sort' => 'Nama_Barang', 'direction' => ($sort === 'Nama_Barang' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
              Nama Barang
              @if($sort === 'Nama_Barang')
                {{ $direction === 'asc' ? '⬇️ A–Z' : '⬆️ Z–A' }}
              @endif
            </a>
          </li>
          <li>
            <a class="dropdown-item"
              href="{{ route('barang.index', array_merge(request()->query(), ['sort' => 'Merek_Barang', 'direction' => ($sort === 'Merek_Barang' && $direction === 'asc') ? 'desc' : 'asc'])) }}">
              Merek Barang
              @if($sort === 'Merek_Barang')
                {{ $direction === 'asc' ? '⬇️ A–Z' : '⬆️ Z–A' }}
              @endif
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Tabel Barang -->
    <table class="table table-hover align-middle">
      <thead class="table-secondary">
        <tr>
          <th>Nama Barang
            @if($sort === 'Nama_Barang')
              {!! $direction === 'asc' ? '⬇️' : '⬆️' !!}
            @endif
          </th>
          <th>Merek Barang
            @if($sort === 'Merek_Barang')
              {!! $direction === 'asc' ? '⬇️' : '⬆️' !!}
            @endif
          </th>
          <th>Berat/Ukuran</th>
          <th>Deskripsi</th>
          <th>Harga</th>
          <th>QTY</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($barang as $b)
          <tr>
            <td>{{ $b->Nama_Barang }}</td>
            <td>{{ $b->Merek_Barang ?? '-' }}</td>
            <td>{{ $b->Besar_Satuan ?? '-' }}</td>
            <td>{{ $b->Deskripsi_Barang ?? '-' }}</td>
            <td>Rp. {{ number_format($b->Harga_Barang, 0, ',', '.') }},-</td>
            <td>{{ $b->Stok_Barang }} {{ $b->satuanbarang->Nama_Satuan }}</td>
            <td>
              <div class="d-flex justify-content-center align-items-center gap-2">

                {{-- Tombol Tambah Stok (Modal) --}}
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                  data-bs-target="#modalStok{{ $b->ID_Barang }}">
                  ➕ Tambah Stok
                </button>

                {{-- Tombol Edit Barang --}}
                <a href="{{ route('barang.edit', $b->ID_Barang) }}" class="btn btn-warning btn-sm text-black">
                  ✏️ Edit
                </a>

                {{-- Tombol Hapus --}}
                <form action="{{ route('barang.destroy', $b->ID_Barang) }}" method="POST"
                  onsubmit="return confirm('Hapus {{ $b->Nama_Barang }}?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </div>

              <!-- Modal Tambah Stok -->
              <div class="modal fade" id="modalStok{{ $b->ID_Barang }}" tabindex="-1"
                aria-labelledby="modalLabel{{ $b->ID_Barang }}" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('barang.tambahStok', $b->ID_Barang) }}" method="POST">
                      @csrf
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel{{ $b->ID_Barang }}">
                          Tambah Stok - {{ $b->Nama_Barang }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <input type="number" name="jumlah" class="form-control" placeholder="Masukkan jumlah stok" min="1"
                          required>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>