@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
    <h4 class="mb-4 fw-bold">Tambah Pelanggan</h4>

    {{-- Alert validasi jika ada error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah Pelanggan --}}
    <form action="{{ route('pelanggan.store') }}" method="POST" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input type="text" name="Nama_Pelanggan" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="No_Telp" class="form-control" maxlength="13" inputmode="numeric" pattern="[0-9]{10,13}"
                required>
        </div>

        <div class="col-12">
            <label class="form-label">Alamat</label>
            <textarea name="Alamat" class="form-control" maxlength="500" rows="3" required></textarea>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">💾 Simpan</button>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection