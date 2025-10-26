@extends('layouts.app')

@section('title', 'Tambah Satuan Barang')

@section('content')
    <h4 class="mb-4 fw-bold">Tambah Satuan</h4>

    <form action="{{ route('barang.tambahsatuan') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3">
                <label class="form-label">Nama Satuan Barang</label>
                <input type="text" class="form-control" name="Nama_Satuan" placeholder="Masukkan Nama Satuan Baru."
                    required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">💾 Simpan</button>
    </form>
@endsection