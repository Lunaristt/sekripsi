<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'ID_Pembelian';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'ID_Distributor',
        'Tanggal',
        'Harga_Keseluruhan',
        'Tanggal_Jatuh_Tempo',
    ];

    /**
     * Relasi ke Distributor
     */
    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'ID_Distributor', 'ID_Distributor');
    }

    /**
     * Relasi ke BarangPembelian (pivot)
     */
    public function barangpembelian()
    {
        return $this->hasMany(BarangPembelian::class, 'ID_Pembelian', 'ID_Pembelian');
    }

    /**
     * Relasi ke Barang melalui pivot
     */
    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'BarangPembelian', 'ID_Pembelian', 'ID_Barang')
            ->withPivot('Jumlah', 'Harga_Beli');
    }
}
