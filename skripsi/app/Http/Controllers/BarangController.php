<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\kategoribarang;
use App\Models\satuanbarang;
use Illuminate\Http\Request;
use App\Imports\BarangImport;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    /**
     * Tampilkan semua barang.
     */
    public function index(Request $request)
    {
        $query = Barang::with('satuanbarang'); // tidak perlu kategoribarang lagi

        // 🔍 Fitur Search — cari berdasarkan nama, merek, dan deskripsi
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Nama_Barang', 'like', '%' . $search . '%')
                    ->orWhere('Merek_Barang', 'like', '%' . $search . '%')
                    ->orWhere('Deskripsi_Barang', 'like', '%' . $search . '%');
            });
        }

        // 🔽 Fitur Sort
        $sort = $request->get('sort', 'Nama_Barang'); // default: Nama_Barang
        $direction = $request->get('direction', 'asc'); // default: asc

        // Jika sort berdasarkan Merek_Barang, urutkan langsung kolomnya
        if ($sort === 'Merek_Barang') {
            $query->orderBy('Merek_Barang', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        // 🔹 Ambil data
        $barang = $query->get();
        $satuanbarang = satuanbarang::all();

        return view('barang.barang', compact('barang', 'satuanbarang', 'sort', 'direction'));
    }




    /**
     * Tampilkan form tambah barang.
     */
    public function create()
    {
        $satuanbarang = satuanbarang::all();
        $kategoribarang = kategoribarang::all();
        return view('barang/tambahbarang', compact('kategoribarang', 'satuanbarang'));
    }

    /**
     * Simpan barang baru.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file_excel')) {
            Excel::import(new BarangImport, $request->file('file_excel'));
            return redirect()->route('barang.index')->with('success', 'Data barang berhasil diimpor dari Excel!');
        }

        $request->validate([
            'Nama_Barang' => 'required|string|max:100',
            'Merek_Barang' => 'required|string|max:100',
            'Deskripsi_Barang' => 'nullable|string',
            'Harga_Barang' => 'required|numeric',
            'Stok_Barang' => 'required|integer',
            'ID_Kategori' => 'required|exists:kategoribarang,ID_Kategori',
            'ID_Satuan' => 'required|exists:satuanbarang,ID_Satuan',
            'Besar_Satuan' => 'nullable|string|max:50',
        ]);

        Barang::create([
            'ID_Kategori' => $request->ID_Kategori,
            'ID_Satuan' => $request->ID_Satuan,
            'Nama_Barang' => $request->Nama_Barang,
            'Harga_Barang' => $request->Harga_Barang,
            'Stok_Barang' => $request->Stok_Barang,
            'Besar_Satuan' => $request->Besar_Satuan,
            'Merek_Barang' => $request->Merek_Barang,
            'Deskripsi_Barang' => $request->Deskripsi_Barang,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoribarang = kategoribarang::all();
        $satuanbarang = satuanbarang::all();

        return view('barang/editbarang', compact('barang', 'kategoribarang', 'satuanbarang'));
    }

    /**
     * Update data barang.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama_Barang' => 'required|string|max:100',
            'Merek_Barang' => 'required|string|max:100',
            'Deskripsi_Barang' => 'nullable|string',
            'Harga_Barang' => 'required|numeric',
            'Stok_Barang' => 'required|integer',
            'ID_Kategori' => 'required|exists:kategoribarang,ID_Kategori',
            'ID_Satuan' => 'required|exists:satuanbarang,ID_Satuan',
            'Besar_Satuan' => 'required|string|max:50',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update([
            'Nama_Barang' => $request->Nama_Barang,
            'Merek_Barang' => $request->Merek_Barang,
            'Deskripsi_Barang' => $request->Deskripsi_Barang,
            'Harga_Barang' => $request->Harga_Barang,
            'Stok_Barang' => $request->Stok_Barang,
            'ID_Kategori' => $request->ID_Kategori,
            'ID_Satuan' => $request->ID_Satuan,
            'Besar_Satuan' => $request->Besar_Satuan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Hapus barang.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Tambah stok barang.
     */
    public function tambahStok(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->Stok_Barang += $request->jumlah;
        $barang->save();

        return redirect()->route('barang.index')->with('success', 'Stok berhasil ditambahkan!');
    }

    /**
     * Tambah kategori baru.
     */
    public function kategori(Request $request)
    {
        $request->validate([
            'Kategori_Barang' => 'required|string|max:100|unique:kategoribarang,Kategori_Barang',
        ]);

        kategoribarang::create([
            'Kategori_Barang' => $request->Kategori_Barang,
        ]);

        return redirect()->route('tambahkategori')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Tambah satuan baru.
     */
    public function satuan(Request $request)
    {
        $request->validate([
            'Nama_Satuan' => 'required|string|max:100|unique:satuanbarang,Nama_Satuan',
        ]);

        satuanbarang::create([
            'Nama_Satuan' => $request->Nama_Satuan,
        ]);

        return redirect()->route('tambahsatuan')->with('success', 'Satuan baru berhasil ditambahkan!');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new BarangImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data barang berhasil diimport!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

}
