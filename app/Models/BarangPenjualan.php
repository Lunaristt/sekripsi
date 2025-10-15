<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BarangPenjualan extends Pivot
{
    protected $table = 'BarangPenjualan';
    public $timestamps = false;

    protected $fillable = [
        'ID_Penjualan',
        'ID_Barang',
        'Jumlah',
        'Total_Harga',
    ];
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'ID_Barang', 'ID_Barang');
    }

    public function penjualan()
    {
        return $this->belongsTo(\App\Models\Penjualan::class, 'ID_Penjualan', 'ID_Penjualan');
    }
}
