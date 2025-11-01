<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
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

    // 🔹 Relasi ke Kategori
    public function kategoribarang()
    {
        return $this->belongsTo(KategoriBarang::class, 'ID_Kategori', 'ID_Kategori');
    }

    // 🔹 Relasi ke Satuan
    public function satuanbarang()
    {
        return $this->belongsTo(SatuanBarang::class, 'ID_Satuan', 'ID_Satuan');
    }

    // 🔹 Relasi ke BarangPenjualan (One to Many)
    public function transaksi()
    {
        return $this->hasMany(BarangPenjualan::class, 'ID_Barang', 'ID_Barang');
    }

    // 🔹 Relasi ke Penjualan (Many to Many)
    public function penjualan()
    {
        return $this->belongsToMany(Penjualan::class, 'barangpenjualan', 'ID_Barang', 'ID_Penjualan')
            ->withPivot('Jumlah', 'Total_Harga');
    }

    // 🔹 Relasi ke Distributor (Many to Many)
    public function distributor()
    {
        return $this->belongsToMany(
            Distributor::class,
            'barangdistributor',
            'ID_Barang',
            'ID_Distributor'
        )->withPivot('Harga_Beli');
    }

    // 🔹 Relasi ke BarangDistributor (One to Many)
    public function barangdistributor()
    {
        return $this->hasMany(BarangDistributor::class, 'ID_Barang', 'ID_Barang');
    }
}
