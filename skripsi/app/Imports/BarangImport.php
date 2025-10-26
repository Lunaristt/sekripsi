<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\kategoribarang;
use App\Models\satuanbarang;
use App\Models\Distributor;
use App\Models\BarangDistributor;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToModel, WithHeadingRow
{
    /**
     * Membaca setiap baris dari file Excel dan simpan ke tabel barang + relasi distributor.
     */
    public function model(array $row)
    {
        // ✅ Validasi kolom wajib
        if (
            !isset($row['nama_barang']) ||
            !isset($row['kategori_barang']) ||
            !isset($row['merek_barang'])
        ) {
            return null; // Lewati baris jika tidak lengkap
        }

        // 🔹 1. Cek / buat kategori
        $kategori = kategoribarang::firstOrCreate([
            'Kategori_Barang' => $row['kategori_barang'],
        ]);

        // 🔹 2. Cek / buat satuan
        $satuan = satuanbarang::firstOrCreate([
            'Nama_Satuan' => $row['nama_satuan'] ?? '-',
        ]);

        // 🔹 3. Selalu buat data barang baru (tidak cek duplikat)
        $barang = Barang::create([
            'ID_Kategori' => $kategori->ID_Kategori,
            'ID_Satuan' => $satuan->ID_Satuan,
            'Nama_Barang' => $row['nama_barang'],
            'Merek_Barang' => $row['merek_barang'],
            'Harga_Barang' => $row['harga_jual'] ?? ($row['harga_barang'] ?? 0),
            'Stok_Barang' => $row['stok_barang'] ?? 0,
            'Besar_Satuan' => $row['besar_satuan'] ?? null,
            'Deskripsi_Barang' => $row['deskripsi_barang'] ?? null,
        ]);

        // 🔹 4. Cek / buat distributor
        if (!empty($row['nama_distributor'])) {
            $distributor = Distributor::firstOrCreate([
                'Nama_Distributor' => $row['nama_distributor'],
            ]);


            // 🔹 5. Simpan relasi barang-distributor + harga beli
            BarangDistributor::create([
                'ID_Barang' => $barang->ID_Barang,
                'ID_Distributor' => $distributor->ID_Distributor,
                'Harga_Beli' => $row['harga_beli'] ?? 0,
            ]);
        }

        return $barang;
    }

    /**
     * Membaca baris header (row pertama).
     */
    public function headingRow(): int
    {
        return 1;
    }
}
