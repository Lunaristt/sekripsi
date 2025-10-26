@extends('layouts.app')

@section('title', 'Edit Pelanggan - Toko Sumber Rejeki')

@section('content')
    <h4 class="fw-bold mb-4">Edit Pelanggan</h4>

    {{-- ✅ Form Edit Pelanggan --}}
    <form action="{{ route('pelanggan.update', $pelanggan->No_Telp) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="Nama_Pelanggan" class="form-label fw-semibold">Nama</label>
                <input type="text" name="Nama_Pelanggan" id="Nama_Pelanggan" class="form-control"
                    value="{{ old('Nama_Pelanggan', $pelanggan->Nama_Pelanggan) }}" required>
            </div>

            <div class="col-md-6">
                <label for="NoTelp_Pelanggan" class="form-label fw-semibold">Nomor Telepon</label>
                <input type="text" name="NoTelp_Pelanggan" id="NoTelp_Pelanggan" class="form-control"
                    value="{{ old('NoTelp_Pelanggan', $pelanggan->No_Telp) }}" maxlength="13" inputmode="numeric"
                    pattern="[0-9]{10,13}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="Alamat_Pelanggan" class="form-label fw-semibold">Alamat</label>
            <textarea name="Alamat_Pelanggan" id="Alamat_Pelanggan" class="form-control" rows="3"
                required>{{ old('Alamat_Pelanggan', $pelanggan->Alamat) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
    </form>
@endsection