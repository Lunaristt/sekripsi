@extends('layouts.app')

@section('title', 'Tambah Distributor - Toko Sumber Rejeki')

@section('content')
    <h4 class="fw-bold mb-4">Form Data Distributor & Sales</h4>

    {{-- ✅ Pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ Form Tambah Distributor --}}
    <form action="{{ route('distributor.store') }}" method="POST">
        @csrf

        {{-- Nama Distributor --}}
        <div class="mb-3">
            <label for="Nama_Distributor" class="form-label fw-semibold">Nama Distributor</label>
            <input type="text" class="form-control bg-secondary-subtle border-0" id="Nama_Distributor"
                name="Nama_Distributor" placeholder="Masukkan nama distributor" required>
        </div>

        {{-- Nomor Telepon Customer Service --}}
        <div class="mb-3">
            <label for="Telp_CS" class="form-label fw-semibold">Nomor Telepon CS</label>
            <input type="text" class="form-control bg-secondary-subtle border-0" id="Telp_CS" name="Telp_CS"
                placeholder="Masukkan nomor telepon CS">
        </div>

        {{-- Nama Sales --}}
        <div class="mb-3">
            <label for="Nama_Salesman" class="form-label fw-semibold">Nama Salesman</label>
            <input type="text" class="form-control bg-secondary-subtle border-0" id="Nama_Salesman" name="Nama_Salesman"
                placeholder="Masukkan nama sales">
        </div>

        {{-- Nomor Telepon Sales --}}
        <div class="mb-3">
            <label for="Notelp_Salesman" class="form-label fw-semibold">Nomor Telepon Salesman</label>
            <input type="text" class="form-control bg-secondary-subtle border-0" id="Notelp_Salesman" name="Notelp_Salesman"
                placeholder="Masukkan nomor telepon sales">
        </div>

        {{-- Tombol Simpan --}}
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-warning fw-semibold px-4">💾 Simpan</button>
            <a href="{{ route('distributor.index') }}" class="btn btn-secondary px-4">Kembali</a>
        </div>
    </form>
@endsection