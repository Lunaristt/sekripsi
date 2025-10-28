@extends('layouts.app')

@section('title', 'Toko Sumber Rejeki - Dashboard')

@push('styles')
  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
  <style>
    .pagination {
      justify-content: center;
    }

    .pagination .page-item.active .page-link {
      background-color: #8b0d18;
      border-color: #8b0d18;
    }

    .pagination .page-link {
      color: #8b0d18;
    }

    .pagination .page-link:hover {
      color: #a01520;
    }
  </style>
@endpush

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <!-- 🔍 Form Search -->
    <form action="{{ route('barang.index') }}" method="GET" class="d-flex flex-grow-1 me-2">
      <input type="text" name="search" class="form-control me-2" placeholder="Cari barang..."
        value="{{ request('search') }}">
      <button type="submit" class="btn btn-primary">Cari</button>
    </form>

    <!-- ➕ Tombol Tambah Barang -->
    <a href="{{ route('tambahbarang.create') }}" class="btn btn-success">Tambah Barang Baru</a>

    <!-- 🔽 Dropdown Sort -->
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

  <!-- 📋 Tabel Barang -->
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-dark text-left">
        <tr>
          <th>Nama Barang</th>
          <th>Merek Barang</th>
          <th>Berat/Ukuran</th>
          <th>Deskripsi</th>
          <th>Harga Jual</th>
          <th>Harga Beli</th>
          <th>QTY</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @php
          function sensorHarga($angka)
          {
            $map = [
              '1' => 'I',
              '2' => 'N',
              '3' => 'D',
              '4' => 'O',
              '5' => 'M',
              '6' => 'A',
              '7' => 'R',
              '8' => 'E',
              '9' => 'T',
              '0' => 'U'
            ];
            $angka = (string) $angka;
            $hasil = '';
            foreach (str_split($angka) as $digit) {
              $hasil .= $map[$digit] ?? $digit;
            }
            return $hasil;
          }
        @endphp

        @forelse($barang as $b)
          @php
            $hargaBeli = $b->distributor->first()->pivot->Harga_Beli ?? 0;
            $hargaSensor = sensorHarga($hargaBeli);
          @endphp

          <tr>
            <td>{{ $b->Nama_Barang }}</td>
            <td>{{ $b->Merek_Barang ?? '-' }}</td>
            <td>{{ $b->Besar_Satuan ?? '-' }}</td>
            <td>{{ $b->Deskripsi_Barang ?? '-' }}</td>
            <td>Rp. {{ number_format($b->Harga_Barang, 0, ',', '.') }},-</td>

            <!-- 👁️ Harga Beli + Tombol Toggle per Baris -->
            <td>
              <span class="harga-sensor" data-real="{{ $hargaBeli }}" data-sensor="{{ $hargaSensor }}">
                {{ $hargaSensor }}
              </span>
              <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-harga ms-2" title="Lihat harga asli">
                <i class="fa-solid fa-eye"></i>
              </button>
            </td>

            <td>{{ $b->Stok_Barang }} {{ $b->satuanbarang->Nama_Satuan }}</td>
            <td>
              <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                <!-- ➕ Tambah Stok -->
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                  data-bs-target="#modalStok{{ $b->ID_Barang }}">
                  ➕ Tambah Stok
                </button>

                <!-- ✏️ Edit -->
                <a href="{{ route('barang.edit', $b->ID_Barang) }}" class="btn btn-warning btn-sm text-black">
                  ✏️ Edit
                </a>

                <!-- ❌ Hapus -->
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
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-3">Tidak ada barang ditemukan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- 🔢 Pagination -->
  <div class="d-flex justify-content-center mt-4">
    {{ $barang->links('pagination::bootstrap-5') }}
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll('.btn-toggle-harga').forEach(button => {
        button.addEventListener('click', function () {
          const hargaSpan = this.previousElementSibling;
          const real = hargaSpan.dataset.real;
          const sensor = hargaSpan.dataset.sensor;
          const icon = this.querySelector('i');
          const showingReal = hargaSpan.textContent.includes('Rp.');

          if (showingReal) {
            hargaSpan.textContent = sensor;
            icon.classList.replace('fa-eye-slash', 'fa-eye');
            this.title = "Lihat harga asli";
          } else {
            hargaSpan.textContent = 'Rp. ' + parseInt(real).toLocaleString('id-ID') + ',-';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
            this.title = "Sembunyikan harga";
          }
        });
      });
    });
  </script>
@endpush