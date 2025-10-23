<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangPembelian extends Model
{
    use HasFactory;

    protected $table = 'barangpembelian';
    public $incrementing = false; // karena primary key gabungan
    public $timestamps = false;

    protected $primaryKey = null; // composite key, tidak pakai single PK
    protected $fillable = [
        'ID_Pembelian',
        'ID_Barang',
        'Jumlah',
        'Harga_Beli',
    ];

    /**
     * Relasi ke Pembelian
     */
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'ID_Pembelian', 'ID_Pembelian');
    }

    /**
     * Relasi ke Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'ID_Barang', 'ID_Barang');
    }
}
