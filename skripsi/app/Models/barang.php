<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'ID_Barang';

    protected $fillable = [
        'ID_Kategori',
        'ID_Satuan',
        'Nama_Barang',
        'Harga_Barang',
        'Stok_Barang',
        'Besar_Satuan',
        'Merek_Barang',
        'Deskripsi_Barang'
    ];

    public $timestamps = false;

    // Relasi ke JenisBarang
    public function kategoribarang()
    {
        return $this->belongsTo(kategoribarang::class, 'ID_Kategori', 'ID_Kategori');
    }

    public function satuanbarang()
    {
        return $this->belongsTo(satuanbarang::class, 'ID_Satuan', 'ID_Satuan');
    }

    public function transaksi()
    {
        return $this->hasMany(BarangPenjualan::class, 'ID_Barang', 'ID_Barang');
    }

    public function penjualan()
    {
        return $this->belongsToMany(Penjualan::class, 'BarangPenjualan', 'ID_Barang', 'ID_Penjualan')
            ->withPivot('Jumlah', 'Total_Harga');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'ID_Distributor', 'ID_Distributor');
    }

}
