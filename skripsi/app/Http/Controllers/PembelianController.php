<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\BarangPembelian;
use App\Models\Barang;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    /**
     * Tampilkan daftar pembelian
     */
    public function index()
    {
        $pembelian = Pembelian::with([
            'distributor',                    // relasi utama pembelian → distributor
            'barang.distributor'              // relasi barang → distributor (pivot barangdistributor)
        ])
            ->orderBy('Tanggal', 'desc')
            ->get();

        return view('pembelian', compact('pembelian'));
    }


    /**
     * Form pembelian baru / melanjutkan pembelian pending
     */
    public function create()
    {
        $barang = Barang::all();
        $distributor = Distributor::orderBy('Nama_Distributor')->get();

        // Cek apakah ada pembelian aktif (Pending)
        $pembelianId = session('pembelian_id');

        if ($pembelianId) {
            $pembelian = Pembelian::find($pembelianId);

            // Jika pembelian sudah dihapus atau tidak ditemukan → buat baru
            if (!$pembelian) {
                $pembelian = Pembelian::create([
                    'Tanggal' => now(),
                    'Tanggal_Jatuh_Tempo' => now()->addDays(60), // ✅ jatuh tempo 60 hari ke depan
                    'Harga_Keseluruhan' => 0,
                ]);
                session(['pembelian_id' => $pembelian->ID_Pembelian]);
            }
        } else {
            // Buat pembelian baru
            $pembelian = Pembelian::create([
                'Tanggal' => now(),
                'Tanggal_Jatuh_Tempo' => now()->addDays(60), // ✅ jatuh tempo 60 hari ke depan
                'Harga_Keseluruhan' => 0,
            ]);
            session(['pembelian_id' => $pembelian->ID_Pembelian]);
        }

        // Ambil daftar barang yang sudah dimasukkan
        $barangPembelian = BarangPembelian::where('ID_Pembelian', $pembelian->ID_Pembelian)
            ->with('barang')
            ->get();

        $totalHarga = $barangPembelian->sum(fn($bp) => $bp->Jumlah * $bp->Harga_Beli);

        return view('pembelian', compact('barang', 'distributor', 'pembelian', 'barangPembelian', 'totalHarga'));
    }

    /**
     * Tambah barang ke pembelian
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'ID_Barang' => 'required|exists:barang,ID_Barang',
            'Jumlah' => 'required|integer|min:1',
        ]);

        $pembelianId = session('pembelian_id');
        $barang = Barang::findOrFail($request->ID_Barang);

        BarangPembelian::updateOrCreate(
            [
                'ID_Pembelian' => $pembelianId,
                'ID_Barang' => $barang->ID_Barang,
            ],
            [
                'Jumlah' => DB::raw('Jumlah + ' . $request->Jumlah),
                'Harga_Beli' => $barang->Harga_Barang,
            ]
        );

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke pembelian.');
    }

    /**
     * Hapus item dari pembelian
     */
    public function removeItem($id)
    {
        $pembelianId = session('pembelian_id');

        BarangPembelian::where('ID_Pembelian', $pembelianId)
            ->where('ID_Barang', $id)
            ->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari daftar.');
    }

    /**
     * Batalkan transaksi pembelian
     */
    public function cancel()
    {
        $pembelianId = session('pembelian_id');

        if ($pembelianId) {
            BarangPembelian::where('ID_Pembelian', $pembelianId)->delete();
            Pembelian::where('ID_Pembelian', $pembelianId)->delete();
            session()->forget('pembelian_id');
        }

        return redirect()->route('pembelian.index')->with('error', 'Transaksi pembelian dibatalkan.');
    }

    /**
     * Selesaikan pembelian (checkout)
     */
    public function checkout(Request $request)
    {
        $pembelianId = session('pembelian_id');
        $pembelian = Pembelian::findOrFail($pembelianId);

        // 🟩 Ambil data keranjang dari input hidden (JSON)
        $barangData = json_decode($request->barang, true);

        // Validasi data
        if (!$barangData || !is_array($barangData)) {
            return back()->with('error', 'Data barang tidak valid atau kosong.');
        }

        // Hapus semua barang lama di pembelian ini (jika ada)
        BarangPembelian::where('ID_Pembelian', $pembelianId)->delete();

        $totalHarga = 0;

        // 🟩 Loop tiap item dari JSON dan simpan ke tabel barangpembelian
        foreach ($barangData as $item) {
            if (!isset($item['id'], $item['jumlah'], $item['harga']))
                continue;

            BarangPembelian::create([
                'ID_Pembelian' => $pembelianId,
                'ID_Barang' => $item['id'],
                'Jumlah' => $item['jumlah'],
                'Harga_Beli' => $item['harga'],
            ]);

            $totalHarga += $item['jumlah'] * $item['harga'];
        }

        // 🟩 Simpan total harga ke tabel pembelian
        $pembelian->update([
            'ID_Distributor' => $request->ID_Distributor,
            'Tanggal_Jatuh_Tempo' => $request->Tanggal_Jatuh_Tempo,
            'Harga_Keseluruhan' => $totalHarga,
        ]);

        // 🟩 Hapus session pembelian agar reset
        session()->forget('pembelian_id');

        return redirect()->route('pembelian.create')->with('success', 'Pembelian berhasil disimpan.');
    }

    public function getBarangByDistributor($id)
    {
        // Ambil barang yang berelasi dengan distributor tertentu melalui pivot 'barangdistributor'
        $barang = Barang::whereHas('distributor', function ($q) use ($id) {
            $q->where('distributor.ID_Distributor', $id);
        })
            ->with([
                'distributor' => function ($q) use ($id) {
                    $q->where('distributor.ID_Distributor', $id);
                }
            ])
            ->get();

        // Format data untuk dropdown (id, nama, dan harga beli dari pivot)
        $data = $barang->map(function ($b) {
            $hargaBeli = $b->distributor->first()->pivot->Harga_Beli ?? 0;
            return [
                'id' => $b->ID_Barang,
                'nama' => $b->Nama_Barang,
                'deskripsi' => $b->Deskripsi_Barang ?? '-', // tambahkan ini
                'harga_beli' => $hargaBeli,
            ];
        });


        return response()->json($data);
    }

}
