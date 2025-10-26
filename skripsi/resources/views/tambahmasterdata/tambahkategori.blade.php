@extends('layouts.app')

@section('title', 'Tambah Kategori Barang')

@section('content')
    <h4 class="mb-4">Tambah Kategori</h4>

    <form action="{{ route('barang.tambahkategori') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3">
                <label class="form-label">Nama Kategori Barang</label>
                <input type="text" class="form-control" name="Kategori_Barang" placeholder="Masukkan Nama Kategori Barang."
                    required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">💾 Simpan</button>
    </form>
@endsection