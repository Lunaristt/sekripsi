@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Tambah Barang</h4>

        <!-- Tombol toggle mode -->
        <button id="toggleMode" class="btn btn-outline-primary toggle-btn">
            🔁 Ubah ke Mode Upload Excel
        </button>
    </div>

    <!-- ✅ Form Input Manual -->
    <div id="manualForm">
        <form action="{{ route('tambahbarang.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" name="Nama_Barang"
                        placeholder="Masukkan Nama Barang, contoh: Keni, Semen, Baja Ringan" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori Barang*</label>
                    <select class="form-control" name="ID_Kategori" required>
                        <option value="">Pilih kategori</option>
                        @foreach($kategoribarang as $kategori)
                            <option value="{{ $kategori->ID_Kategori }}">{{ $kategori->Kategori_Barang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Merek Barang*</label>
                    <input type="text" class="form-control" name="Merek_Barang" placeholder="Masukkan Merek Barang"
                        required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Jual*</label>
                    <input type="number" class="form-control" name="Harga_Barang" placeholder="Masukkan Harga Jual Barang"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok Barang*</label>
                    <input type="number" class="form-control" name="Stok_Barang" placeholder="Masukkan Stok Barang"
                        required>
                </div>
            </div>

            <!-- 🔹 Tambahan Kolom Distributor dan Harga Beli -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Distributor*</label>
                    <select class="form-control" name="ID_Distributor" required>
                        <option value="">Pilih Distributor</option>
                        @foreach($distributor as $d)
                            <option value="{{ $d->ID_Distributor }}">{{ $d->Nama_Distributor }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Beli (Rp)*</label>
                    <input type="number" class="form-control" name="Harga_Beli"
                        placeholder="Masukkan Harga Beli dari Distributor" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Besar Satuan</label>
                    <input type="text" class="form-control" name="Besar_Satuan" placeholder="Contoh: 1, 1/2">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Satuan*</label>
                    <select class="form-control" name="ID_Satuan" required>
                        <option value="">Pilih satuan</option>
                        @foreach($satuanbarang as $satuan)
                            <option value="{{ $satuan->ID_Satuan }}">{{ $satuan->Nama_Satuan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi Barang</label>
                    <input type="text" class="form-control" name="Deskripsi_Barang"
                        placeholder="Masukkan deskripsi lebih spesifik, contoh: Warna, ukuran, bahan">
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">💾 Simpan Barang</button>
        </form>
    </div>

    <!-- ✅ Form Upload Excel -->
    <div id="excelForm" class="d-none">
        <form action="{{ route('barang.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Upload File Excel Barang</label>
                <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                <div class="form-text">
                    Pastikan format kolom sesuai template Excel sistem.<br>
                    Contoh header:
                    <code>Nama_Barang | Kategori | Merek | Harga_Jual | Stok | Distributor | Harga_Beli</code>
                </div>
            </div>
            <button type="submit" class="btn btn-success">📤 Upload Excel</button>
        </form>
    </div>
@endsection

@push('scripts')
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
@endpush