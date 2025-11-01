<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // 🟡 Barang dengan stok menipis
        $barangMenipis = Barang::where('Stok_Barang', '<', 10)->get();

        // 🔴 Pembelian mendekati jatuh tempo (dalam 7 hari)
        $jatuhTempo = Pembelian::with('distributor')
            ->where('Status', 'Diterima')
            ->whereBetween('Tanggal_Jatuh_Tempo', [Carbon::now(), Carbon::now()->addDays(7)])
            ->get();

        // 📆 Rekap pembelian & penjualan dari 1 hari yang lalu
        $kemarin = Carbon::yesterday(); // tanggal kemarin

        $totalPembelianKemarin = Pembelian::whereDate('Tanggal', $kemarin)
            ->where('Status', 'Diterima')
            ->sum('Harga_Keseluruhan');

        $totalPenjualanKemarin = Penjualan::whereDate('Tanggal', $kemarin)
            ->where('Status', 'Selesai')
            ->sum('Harga_Keseluruhan');

        return view('home', compact(
            'barangMenipis',
            'jatuhTempo',
            'totalPembelianKemarin',
            'totalPenjualanKemarin',
            'kemarin'
        ));
    }
}
