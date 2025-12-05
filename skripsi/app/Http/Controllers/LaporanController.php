<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    //Laporan Pemasukan Bulanan
    public function pemasukan(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $awal = Carbon::parse($bulan . '-01')->startOfMonth();
        $akhir = Carbon::parse($bulan . '-01')->endOfMonth();

        //Ambil hanya penjualan dengan total harga lebih dari 0
        $penjualan = Penjualan::with('pelanggan')
            ->whereBetween('Tanggal', [$awal, $akhir])
            ->where('Status', 'Selesai')
            ->where('Harga_Keseluruhan', '>', 0)
            ->orderBy('Tanggal', 'asc')
            ->paginate(20) // <-- Tambah pagination 20 data per halaman
            ->appends(['bulan' => $bulan]); // <-- agar filter bulan tetap terbawa

        return view('laporan.pemasukan', compact('penjualan', 'bulan'));
    }

    public function pengeluaran(Request $request)
    {
        // Ambil bulan dari input, default bulan ini
        $bulan = $request->input('bulan', now()->format('Y-m'));

        // Konversi jadi tanggal awal & akhir bulan
        $awal = Carbon::parse($bulan . '-01')->startOfMonth();
        $akhir = Carbon::parse($bulan . '-01')->endOfMonth();

        // Ambil data pembelian yang statusnya Diterima dalam range tanggal
        $pembelian = Pembelian::with('distributor')
            ->where('Status', 'Diterima') // 🟢 Tambahan: hanya tampilkan yang diterima
            ->whereBetween('Tanggal', [$awal, $akhir])
            ->orderBy('Tanggal', 'asc')
            ->get();

        return view('laporan.pengeluaran', compact('pembelian', 'bulan'));
    }
    public function exportPemasukanPDF(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');

        $penjualan = Penjualan::with('pelanggan')
            ->where('Status', 'Selesai')
            ->whereRaw("DATE_FORMAT(Tanggal, '%Y-%m') = ?", [$bulan])
            ->orderBy('Tanggal', 'asc')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf.pemasukan', compact('penjualan', 'bulan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Pemasukan_' . $bulan . '.pdf');
    }
    public function exportPengeluaranPDF(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');

        $pembelian = Pembelian::with('distributor')
            ->where('Status', 'Diterima') // ⬅️ hanya ambil yang diterima
            ->whereRaw("DATE_FORMAT(Tanggal, '%Y-%m') = ?", [$bulan])
            ->orderBy('Tanggal', 'asc')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf.pengeluaran', compact('pembelian', 'bulan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Pengeluaran_' . $bulan . '.pdf');
    }

}
