<?php

namespace App\Services;

use App\Models\BarangPenjualan;
use Illuminate\Support\Facades\DB;

class ROPService
{
    public function avgDailySales($barangId)
    {
        $data = BarangPenjualan::select(
            DB::raw("SUM(Jumlah) AS total_jual"),
            DB::raw("COUNT(DISTINCT DATE(penjualan.Tanggal)) AS total_hari")
        )
            ->join('penjualan', 'barangpenjualan.ID_Penjualan', '=', 'penjualan.ID_Penjualan')
            ->where('barangpenjualan.ID_Barang', $barangId)
            ->where('penjualan.Status', 'Selesai')
            ->first();

        if (!$data || $data->total_hari == 0) {
            return 0;
        }

        return $data->total_jual / $data->total_hari;
    }


    public function leadTime($barangId)
    {
        return 1;
    }

    public function hitungROP($barangId)
    {
        $avg = $this->avgDailySales($barangId);
        $lead = $this->leadTime($barangId);

        $safety = max(5, ceil($avg * 2));


        return ceil(($avg * $lead) + $safety);
    }
}
