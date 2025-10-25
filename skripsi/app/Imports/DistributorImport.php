<?php

namespace App\Imports;

use App\Models\Distributor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DistributorImport implements ToCollection, WithHeadingRow
{
    /**
     * Menangani import data dari file Excel.
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Cek apakah nama distributor ada
            if (!isset($row['nama_distributor']) || empty($row['nama_distributor'])) {
                continue; // lewati baris kosong / tidak valid
            }

            // Simpan atau update data distributor berdasarkan nama
            Distributor::updateOrCreate(
                ['Nama_Distributor' => $row['nama_distributor']],
                [
                    'Telp_CS' => $row['telp_cs'] ?? null,
                    'Nama_Salesman' => $row['nama_salesman'] ?? null,
                    'Notelp_Salesman' => $row['notelp_salesman'] ?? null,
                ]
            );
        }
    }

    /**
     * Pastikan header Excel dibaca dari baris pertama.
     *
     * Contoh header yang diterima:
     * | Nama Distributor | Telp CS | Nama Salesman | Notelp Salesman |
     */
    public function headingRow(): int
    {
        return 1; // baris pertama digunakan sebagai header
    }
}
