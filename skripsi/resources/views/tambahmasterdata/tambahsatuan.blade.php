@extends('layouts.app')

@section('title', 'Tambah Satuan Barang')

@section('content')
    <h4 class="mb-4 fw-bold">Tambah Satuan</h4>

    {{-- Form Tambah Satuan --}}
    <form action="{{ route('barang.tambahsatuan') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3">
                <label class="form-label">Nama Satuan Barang</label>
                <input type="text" class="form-control" name="Nama_Satuan" placeholder="Masukkan Nama Satuan Baru" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">💾 Simpan</button>
    </form>

    <hr class="my-4">

    {{-- Tabel Data Satuan --}}
    <h5 class="mb-3">Daftar Satuan Barang</h5>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th width="60">No</th>
                    <th>Nama Satuan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($satuanBarang as $index => $satuan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $satuan->Nama_Satuan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted">
                            Belum ada data satuan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection