<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'ID_Pembelian';
    public $incrementing = true;
    protected $keyType = 'int';
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
     * Relasi ke Barang melalui tabel pivot barangpembelian
     */
    public function barang()
    {
        return $this->belongsToMany(Barang::class, 'barangpembelian', 'ID_Pembelian', 'ID_Barang')
            ->withPivot('Jumlah', 'Harga_Beli');
    }

    /**
     * Relasi langsung ke model BarangPembelian (pivot detail)
     */
    public function detailBarang()
    {
        return $this->hasMany(BarangPembelian::class, 'ID_Pembelian', 'ID_Pembelian');
    }
}
