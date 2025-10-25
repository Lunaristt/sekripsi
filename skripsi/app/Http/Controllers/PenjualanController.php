<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pelanggan;

class PenjualanController extends Controller
{
    public function index()
    {
        // Ambil data penjualan dan join dengan pelanggan
        $penjualan = Penjualan::join('pelanggan', 'penjualan.No_Telp', '=', 'pelanggan.No_Telp')
            ->select('penjualan.*', 'pelanggan.Nama_Pelanggan')
            ->orderBy('penjualan.Tanggal', 'desc')
            ->get();

        // Kirim data ke view listpenjualan
        return view('statustransaksi', compact('penjualan'));
    }

    public function show($idPenjualan)
    {
        // Ambil data penjualan lengkap dengan pelanggan dan daftar barang yang dibeli
        $penjualan = Penjualan::with([
            'pelanggan', // relasi ke tabel pelanggan
            'barangpenjualan.barang' // relasi pivot ke barang
        ])->findOrFail($idPenjualan);

        // Kirim data ke view khusus tampilan readonly
        return view('lihattransaksi', compact('penjualan'));
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('statustransaksi.index')->with('success', 'Pesanan berhasil dihapus!');
    }
}