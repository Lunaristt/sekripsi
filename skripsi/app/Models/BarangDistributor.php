<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangDistributor extends Model
{
    use HasFactory;

    protected $table = 'barangdistributor';
    protected $primaryKey = null; // karena composite key
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ID_Distributor',
        'ID_Barang',
        'Harga_Beli',
    ];

    /**
     * Relasi ke model Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'ID_Barang', 'ID_Barang');
    }

    /**
     * Relasi ke model Distributor
     */
    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'ID_Distributor', 'ID_Distributor');
    }
}
