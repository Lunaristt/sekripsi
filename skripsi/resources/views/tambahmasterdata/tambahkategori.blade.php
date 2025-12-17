@extends('layouts.app')

@section('title', 'Tambah Kategori Barang')

@section('content')
    <h4 class="mb-4">Tambah Kategori</h4>

    {{-- Form Tambah Kategori --}}
    <form action="{{ route('barang.tambahkategori') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3">
                <label class="form-label">Nama Kategori Barang</label>
                <input type="text" class="form-control" name="Kategori_Barang" placeholder="Masukkan Nama Kategori Barang"
                    required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">💾 Simpan</button>
    </form>

    <hr class="my-4">

    {{-- Tabel Data Kategori --}}
    <h5 class="mb-3">Daftar Kategori Barang</h5>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th width="60">No</th>
                    <th>Nama Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategoriBarang as $index => $kategori)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kategori->Kategori_Barang }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">
                            Belum ada data kategori
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection