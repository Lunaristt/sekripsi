@extends('layouts.app')

@section('title', 'Edit Barang - Toko Sumber Rejeki')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Edit Barang</h4>

        <!-- Tombol kembali -->
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">
            ⬅️ Kembali
        </a>
    </div>

    {{-- ✅ Form Edit Barang --}}
    <form action="{{ route('barang.update', $barang->ID_Barang) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" class="form-control" name="Nama_Barang" value="{{ $barang->Nama_Barang }}"
                    placeholder="Masukkan Nama Barang, contoh: Keni, Semen, Baja Ringan" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Kategori Barang*</label>
                <select class="form-control" name="ID_Kategori" required>
                    <option value="">Pilih kategori</option>
                    @foreach($kategoribarang as $kategori)
                        <option value="{{ $kategori->ID_Kategori }}" {{ $barang->ID_Kategori == $kategori->ID_Kategori ? 'selected' : '' }}>
                            {{ $kategori->Kategori_Barang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Merek Barang*</label>
                <input type="text" class="form-control" name="Merek_Barang" value="{{ $barang->Merek_Barang ?? '' }}"
                    placeholder="Masukkan Merek Barang" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga Jual*</label>
                <input type="number" class="form-control" name="Harga_Barang" value="{{ $barang->Harga_Barang }}"
                    placeholder="Masukkan Harga Jual Barang" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Stok Barang*</label>
                <input type="number" class="form-control" name="Stok_Barang" value="{{ $barang->Stok_Barang }}"
                    placeholder="Masukkan Stok Barang" required>
            </div>
        </div>

        {{-- 🔹 Distributor & Harga Beli --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Distributor*</label>
                <select class="form-control" name="ID_Distributor" required>
                    <option value="">Pilih Distributor</option>
                    @foreach($distributor as $d)
                        <option value="{{ $d->ID_Distributor }}" {{ isset($pivot) && $pivot->ID_Distributor == $d->ID_Distributor ? 'selected' : '' }}>
                            {{ $d->Nama_Distributor }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Harga Beli (Rp)*</label>
                <input type="number" class="form-control" name="Harga_Beli" value="{{ $pivot->Harga_Beli ?? '' }}"
                    placeholder="Masukkan Harga Beli dari Distributor" required>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Besar Satuan</label>
                <input type="text" class="form-control" name="Besar_Satuan" value="{{ $barang->Besar_Satuan ?? '' }}"
                    placeholder="Contoh: 1, 1/2">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Satuan*</label>
                <select class="form-control" name="ID_Satuan" required>
                    <option value="">Pilih satuan</option>
                    @foreach($satuanbarang as $satuan)
                        <option value="{{ $satuan->ID_Satuan }}" {{ $barang->ID_Satuan == $satuan->ID_Satuan ? 'selected' : '' }}>
                            {{ $satuan->Nama_Satuan }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi Barang</label>
            <input type="text" class="form-control" name="Deskripsi_Barang" value="{{ $barang->Deskripsi_Barang ?? '' }}"
                placeholder="Masukkan deskripsi lebih spesifik, contoh: warna, ukuran, bahan">
        </div>

        <button type="submit" class="btn btn-primary mt-3">💾 Simpan Perubahan</button>
    </form>
@endsection