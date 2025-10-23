<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

class PajakController extends Controller
{
    public function index()
    {
        // Hitung omzet dan pajak per bulan
        $data = Penjualan::select(
            DB::raw('YEAR(Tanggal) as Tahun'),
            DB::raw('MONTH(Tanggal) as Bulan'),
            DB::raw('SUM(Harga_Keseluruhan) as Total_Omzet')
        )
            ->where('Status', 'Selesai')
            ->groupBy(DB::raw('YEAR(Tanggal), MONTH(Tanggal)'))
            ->orderByRaw('YEAR(Tanggal) DESC, MONTH(Tanggal) DESC') // ✅ tidak ada “asc” tambahan
            ->get()
            ->map(function ($item) {
                $item->Nama_Bulan = date('F', mktime(0, 0, 0, $item->Bulan, 1));
                $item->PPh_Final = $item->Total_Omzet > 500000000 ? $item->Total_Omzet * 0.005 : 0;
                return $item;
            });

        return view('pajak', compact('data'));
    }
}
