<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $table = 'distributor';
    protected $primaryKey = 'ID_Distributor';
    public $timestamps = false; // karena tidak ada created_at dan updated_at

    protected $fillable = [
        'Nama_Distributor',
        'Telp_CS',
        'Nama_Salesman',
        'Notelp_Salesman',
    ];

    // Relasi ke tabel Pembelian (satu distributor punya banyak transaksi pembelian)
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'ID_Distributor', 'ID_Distributor');
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'ID_Distributor', 'ID_Distributor');
    }

}
