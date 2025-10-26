<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangDistributor extends Model
{
    use HasFactory;

    protected $table = 'barangdistributor';
    public $incrementing = false; // karena pakai composite key
    public $timestamps = false;   // tidak ada created_at / updated_at

    protected $primaryKey = null; // composite PK
    protected $fillable = [
        'ID_Distributor',
        'ID_Barang',
        'Harga_Beli',
    ];

    /**
     * Relasi ke model Barang
     * BarangDistributor -> Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'ID_Barang', 'ID_Barang');
    }

    /**
     * Relasi ke model Distributor
     * BarangDistributor -> Distributor
     */
    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'ID_Distributor', 'ID_Distributor');
    }
}
