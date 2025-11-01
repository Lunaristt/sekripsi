<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardData(Request $request)
    {
        // Ambil filter dari dropdown (default = bulan)
        $filter = $request->get('filter', 'bulan');

        // ==============================
        // 🟩 DATA PENJUALAN (masih gunakan filter Status = Selesai)
        // ==============================
        switch ($filter) {
            case 'bulan':
                $penjualan = Penjualan::select(
                    DB::raw('YEAR(Tanggal) as Tahun'),
                    DB::raw('MONTH(Tanggal) as Bulan'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Omzet')
                )
                    ->where('Status', 'Selesai')
                    ->whereYear('Tanggal', date('Y'))
                    ->groupBy(DB::raw('YEAR(Tanggal), MONTH(Tanggal)'))
                    ->orderByRaw('YEAR(Tanggal) ASC, MONTH(Tanggal) ASC')
                    ->get();

                $labels = $penjualan->map(fn($item) => Carbon::create()->month($item->Bulan)->locale('id')->translatedFormat('F'));
                break;

            case 'minggu':
                $penjualan = Penjualan::select(
                    DB::raw('YEAR(Tanggal) as Tahun'),
                    DB::raw('WEEK(Tanggal, 1) as MingguKe'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Omzet')
                )
                    ->where('Status', 'Selesai')
                    ->whereYear('Tanggal', date('Y'))
                    ->groupBy(DB::raw('YEAR(Tanggal), WEEK(Tanggal, 1)'))
                    ->orderByRaw('YEAR(Tanggal) ASC, WEEK(Tanggal, 1) ASC')
                    ->get();

                $labels = $penjualan->map(fn($item) => "Minggu ke-" . $item->MingguKe);
                break;

            case 'hari':
                $penjualan = Penjualan::select(
                    DB::raw('DATE(Tanggal) as Hari'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Omzet')
                )
                    ->where('Status', 'Selesai')
                    ->whereMonth('Tanggal', date('m'))
                    ->groupBy(DB::raw('DATE(Tanggal)'))
                    ->orderByRaw('DATE(Tanggal) ASC')
                    ->get();

                $labels = $penjualan->map(fn($item) => Carbon::parse($item->Hari)->translatedFormat('d M Y'));
                break;

            default:
                $penjualan = Penjualan::select(
                    DB::raw('YEAR(Tanggal) as Tahun'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Omzet')
                )
                    ->where('Status', 'Selesai')
                    ->groupBy(DB::raw('YEAR(Tanggal)'))
                    ->orderByRaw('YEAR(Tanggal) ASC')
                    ->get();

                $labels = $penjualan->pluck('Tahun');
                break;
        }

        $penjualanValues = $penjualan->pluck('Total_Omzet');

        // ==============================
        // 🟦 DATA PEMBELIAN (❌ TANPA FILTER STATUS)
        // ==============================
        switch ($filter) {
            case 'bulan':
                $pembelian = Pembelian::select(
                    DB::raw('YEAR(Tanggal) as Tahun'),
                    DB::raw('MONTH(Tanggal) as Bulan'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Pembelian')
                )
                    ->whereYear('Tanggal', date('Y'))
                    ->groupBy(DB::raw('YEAR(Tanggal), MONTH(Tanggal)'))
                    ->orderByRaw('YEAR(Tanggal) ASC, MONTH(Tanggal) ASC')
                    ->get();
                break;

            case 'minggu':
                $pembelian = Pembelian::select(
                    DB::raw('YEAR(Tanggal) as Tahun'),
                    DB::raw('WEEK(Tanggal, 1) as MingguKe'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Pembelian')
                )
                    ->whereYear('Tanggal', date('Y'))
                    ->groupBy(DB::raw('YEAR(Tanggal), WEEK(Tanggal, 1)'))
                    ->orderByRaw('YEAR(Tanggal) ASC, WEEK(Tanggal, 1) ASC')
                    ->get();
                break;

            case 'hari':
                $pembelian = Pembelian::select(
                    DB::raw('DATE(Tanggal) as Hari'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Pembelian')
                )
                    ->whereMonth('Tanggal', date('m'))
                    ->groupBy(DB::raw('DATE(Tanggal)'))
                    ->orderByRaw('DATE(Tanggal) ASC')
                    ->get();
                break;

            default:
                $pembelian = Pembelian::select(
                    DB::raw('YEAR(Tanggal) as Tahun'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Pembelian')
                )
                    ->groupBy(DB::raw('YEAR(Tanggal)'))
                    ->orderByRaw('YEAR(Tanggal) ASC')
                    ->get();
                break;
        }

        $pembelianValues = $pembelian->pluck('Total_Pembelian');

        // ==============================
        // 🟢 RETURN JSON KE FRONTEND
        // ==============================
        return response()->json([
            'labels' => $labels,
            'penjualan' => $penjualanValues,
            'pembelian' => $pembelianValues,
        ]);
    }
}
