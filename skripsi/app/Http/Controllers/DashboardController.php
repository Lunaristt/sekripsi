<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardData(Request $request)
    {
        // Ambil filter dari dropdown (default = bulan)
        $filter = $request->get('filter', 'bulan');

        switch ($filter) {

            // ================== PER BULAN ==================
            case 'bulan':
                $data = Penjualan::select(
                    DB::raw('YEAR(Tanggal) as Tahun'),
                    DB::raw('MONTH(Tanggal) as Bulan'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Omzet')
                )
                    ->where('Status', 'Selesai')
                    ->whereYear('Tanggal', date('Y'))
                    ->groupBy(DB::raw('YEAR(Tanggal), MONTH(Tanggal)'))
                    ->orderByRaw('YEAR(Tanggal) ASC, MONTH(Tanggal) ASC')
                    ->get();

                // Nama bulan dalam format Indonesia
                $labels = $data->map(fn($item) => Carbon::create()->month($item->Bulan)->locale('id')->translatedFormat('F'));
                break;

            // ================== PER MINGGU ==================
            case 'minggu':
                $data = Penjualan::select(
                    DB::raw('YEAR(Tanggal) as Tahun'),
                    DB::raw('WEEK(Tanggal, 1) as MingguKe'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Omzet')
                )
                    ->where('Status', 'Selesai')
                    ->whereYear('Tanggal', date('Y'))
                    ->groupBy(DB::raw('YEAR(Tanggal), WEEK(Tanggal, 1)'))
                    ->orderByRaw('YEAR(Tanggal) ASC, WEEK(Tanggal, 1) ASC')
                    ->get();

                $labels = $data->map(fn($item) => "Minggu ke-" . $item->MingguKe);
                break;

            // ================== PER HARI ==================
            case 'hari':
                $data = Penjualan::select(
                    DB::raw('DATE(Tanggal) as Hari'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Omzet')
                )
                    ->where('Status', 'Selesai')
                    ->whereMonth('Tanggal', date('m'))
                    ->groupBy(DB::raw('DATE(Tanggal)'))
                    ->orderByRaw('DATE(Tanggal) ASC')
                    ->get();

                $labels = $data->map(fn($item) => Carbon::parse($item->Hari)->translatedFormat('d M Y'));
                break;

            // ================== DEFAULT: PER TAHUN ==================
            default:
                $data = Penjualan::select(
                    DB::raw('YEAR(Tanggal) as Tahun'),
                    DB::raw('SUM(Harga_Keseluruhan) as Total_Omzet')
                )
                    ->where('Status', 'Selesai')
                    ->groupBy(DB::raw('YEAR(Tanggal)'))
                    ->orderByRaw('YEAR(Tanggal) ASC')
                    ->get();

                $labels = $data->pluck('Tahun');
                break;
        }

        // Nilai omzet
        $values = $data->pluck('Total_Omzet');

        // Kembalikan ke frontend (Chart.js)
        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }
}
