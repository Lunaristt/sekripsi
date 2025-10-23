<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'ID_Penjualan';
    public $timestamps = false;

    protected $fillable = [
        'No_Telp',
        'Harga_Keseluruhan',
        'Tanggal',
        'Status',
    ];

    /**
     * Relasi Many-to-Many dengan Barang melalui tabel pivot BarangPenjualan
     */
    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'BarangPenjualan', 'ID_Penjualan', 'ID_Barang')
            ->withPivot('Jumlah', 'Total_Harga');
    }

    public function barangpenjualan()
    {
        return $this->hasMany(BarangPenjualan::class, 'ID_Penjualan', 'ID_Penjualan');
    }

    public function pelanggan()
    {
        // Relasi: satu penjualan dimiliki oleh satu pelanggan
        return $this->belongsTo(Pelanggan::class, 'No_Telp', 'No_Telp');
    }
}
