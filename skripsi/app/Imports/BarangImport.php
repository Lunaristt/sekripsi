<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\kategoribarang;
use App\Models\satuanbarang;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToModel, WithHeadingRow
{
    /**
     * Membaca setiap baris dari file Excel dan simpan ke tabel barang.
     */
    public function model(array $row)
    {
        // Validasi: pastikan kolom utama ada
        if (
            !isset($row['nama_barang']) ||
            !isset($row['kategori_barang']) ||
            !isset($row['merek_barang'])
        ) {
            return null; // skip baris tidak valid
        }

        // 🔹 Cek atau buat kategori (berdasarkan nama kategori)
        $kategori = kategoribarang::firstOrCreate([
            'Kategori_Barang' => $row['kategori_barang'],
        ]);

        // 🔹 Cek atau buat satuan (berdasarkan nama satuan)
        $satuan = satuanbarang::firstOrCreate([
            'Nama_Satuan' => $row['nama_satauan'] ?? '-', // typo: sesuai header Excel "Nama Satauan"
        ]);

        // 🔹 Buat objek Barang baru
        return new Barang([
            'ID_Kategori' => $kategori->ID_Kategori,
            'ID_Satuan' => $satuan->ID_Satuan,
            'Nama_Barang' => $row['nama_barang'],
            'Merek_Barang' => $row['merek_barang'],
            'Harga_Barang' => $row['harga_barang'] ?? 0,
            'Stok_Barang' => $row['stok_barang'] ?? 0,
            'Besar_Satuan' => $row['besar_satuan'] ?? null,
            'Deskripsi_Barang' => $row['deskripsi_barang'] ?? null,
        ]);
    }

    /**
     * Membuat heading Excel case-insensitive (optional tapi direkomendasikan)
     */
    public function headingRow(): int
    {
        return 1;
    }
}
