<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\Kategoribarang;
use App\Models\Satuanbarang;
use App\Models\Distributor;
use App\Models\BarangDistributor;
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
        $query = Barang::with(['satuanbarang', 'distributor']); // relasi satuan & distributor

        // 🔍 Fitur Search
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

        // 🔹 Urutkan sesuai kolom
        if ($sort === 'Merek_Barang') {
            $query->orderBy('Merek_Barang', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        // ✅ Pagination: tampilkan 30 item per halaman + simpan query string
        $barang = $query->paginate(30)->withQueryString();

        // Ambil data satuan untuk dropdown (jika ada)
        $satuanbarang = Satuanbarang::all();

        return view('barang.barang', compact('barang', 'satuanbarang', 'sort', 'direction'));
    }


    /**
     * Tampilkan form tambah barang.
     */
    public function create()
    {
        $satuanbarang = Satuanbarang::all();
        $kategoribarang = Kategoribarang::all();
        $distributor = Distributor::all(); // ambil semua distributor

        return view('barang.tambahbarang', compact('kategoribarang', 'satuanbarang', 'distributor'));
    }

    /**
     * Simpan barang baru + relasi distributor.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nama_Barang' => 'required',
            'ID_Kategori' => 'required',
            'Merek_Barang' => 'required',
            'Harga_Barang' => 'required|numeric',
            'Stok_Barang' => 'required|integer',
            'ID_Satuan' => 'required',
            'ID_Distributor' => 'required',
            'Harga_Beli' => 'required|numeric',
        ]);

        // Simpan barang baru
        $barang = Barang::create($request->only([
            'Nama_Barang',
            'ID_Kategori',
            'Merek_Barang',
            'Harga_Barang',
            'Stok_Barang',
            'ID_Satuan',
            'Deskripsi_Barang'
        ]));

        // Simpan relasi ke pivot barangdistributor
        BarangDistributor::create([
            'ID_Barang' => $barang->ID_Barang,
            'ID_Distributor' => $request->ID_Distributor,
            'Harga_Beli' => $request->Harga_Beli,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang dan relasi distributor berhasil disimpan!');
    }

    /**
     * Tampilkan form edit barang + distributor terkait.
     */
    public function edit($id)
    {
        $barang = Barang::with('distributor')->findOrFail($id);
        $kategoribarang = kategoribarang::all();
        $satuanbarang = satuanbarang::all();
        $distributor = Distributor::all();

        // Ambil data pivot (Harga_Beli) dari tabel barangdistributor
        $pivot = \DB::table('barangdistributor')
            ->where('ID_Barang', $barang->ID_Barang)
            ->first();

        return view('barang.editbarang', compact('barang', 'kategoribarang', 'satuanbarang', 'distributor', 'pivot'));
    }


    /**
     * Update data barang + relasi distributor.
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
            'Besar_Satuan' => 'nullable|string|max:50',
            'ID_Distributor' => 'required|exists:distributor,ID_Distributor',
            'Harga_Beli' => 'required|numeric',
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

        if ($request->filled('ID_Distributor') && $request->filled('Harga_Beli')) {
            $barang->distributor()->syncWithoutDetaching([
                $request->ID_Distributor => ['Harga_Beli' => $request->Harga_Beli]
            ]);
        }

        return redirect()->route('barang.index')->with('success', 'Barang dan relasi distributor berhasil diperbarui!');
    }

    /**
     * Hapus barang (otomatis hapus pivot karena foreign key cascade).
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
     * Import dari Excel.
     */
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
