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
            'distributor',
            'barang.distributor'
        ])
            ->where('Status', 'Diterima') // 🟢 Hanya tampilkan pembelian berstatus "Diterima"
            ->orderBy('Tanggal', 'desc')
            ->get();

        return view('listpembelian', compact('pembelian'));
    }

    /**
     * Form pembelian baru / melanjutkan pembelian pending
     */
    public function create()
    {
        $barang = Barang::all();
        $distributor = Distributor::orderBy('Nama_Distributor')->get();

        $pembelianId = session('pembelian_id');

        if ($pembelianId) {
            $pembelian = Pembelian::find($pembelianId);
            if (!$pembelian) {
                $pembelian = Pembelian::create([
                    'Tanggal' => now(),
                    'Tanggal_Jatuh_Tempo' => now()->addDays(60),
                    'Harga_Keseluruhan' => 0,
                ]);
                session(['pembelian_id' => $pembelian->ID_Pembelian]);
            }
        } else {
            $pembelian = Pembelian::create([
                'Tanggal' => now(),
                'Tanggal_Jatuh_Tempo' => now()->addDays(60),
                'Harga_Keseluruhan' => 0,
            ]);
            session(['pembelian_id' => $pembelian->ID_Pembelian]);
        }

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
        if (!$request->ID_Distributor) {
            return response()->json([
                'success' => false,
                'message' => 'Distributor wajib dipilih!'
            ]);
        }

        $barangData = json_decode($request->barang, true);
        if (!$barangData || count($barangData) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada barang dalam pembelian!'
            ]);
        }

        DB::beginTransaction();
        try {
            $pembelianId = session('pembelian_id');
            $pembelian = Pembelian::find($pembelianId);

            if (!$pembelian) {
                $pembelian = Pembelian::create([
                    'Tanggal' => now(),
                    'Tanggal_Jatuh_Tempo' => $request->Tanggal_Jatuh_Tempo ?? now()->addDays(60),
                    'ID_Distributor' => $request->ID_Distributor,
                    'Harga_Keseluruhan' => 0,
                    'Status' => 'Pending',
                ]);
                session(['pembelian_id' => $pembelian->ID_Pembelian]);
            }

            BarangPembelian::where('ID_Pembelian', $pembelian->ID_Pembelian)->delete();

            $totalKeseluruhan = 0;

            foreach ($barangData as $item) {
                if (!isset($item['ID_Barang'], $item['Jumlah'], $item['Harga_Beli']))
                    continue;

                $barang = Barang::findOrFail($item['ID_Barang']);

                BarangPembelian::create([
                    'ID_Pembelian' => $pembelian->ID_Pembelian,
                    'ID_Barang' => $barang->ID_Barang,
                    'Jumlah' => $item['Jumlah'],
                    'Harga_Beli' => $item['Harga_Beli'],
                ]);

                $barang->increment('Stok_Barang', $item['Jumlah']);
                $totalKeseluruhan += ($item['Jumlah'] * $item['Harga_Beli']);
            }

            $pembelian->update([
                'Harga_Keseluruhan' => $totalKeseluruhan,
                'Status' => 'Diterima',
                'Tanggal' => now(),
                'Tanggal_Jatuh_Tempo' => $request->Tanggal_Jatuh_Tempo ?? now()->addDays(60),
                'ID_Distributor' => $request->ID_Distributor,
            ]);

            DB::commit();
            session()->forget('pembelian_id');

            return response()->json([
                'success' => true,
                'message' => 'Pembelian berhasil disimpan dan diterima!',
                'pembelian_id' => $pembelian->ID_Pembelian,
                'total' => $totalKeseluruhan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Pembelian Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function getBarangByDistributor($id)
    {
        try {
            $distributor = Distributor::findOrFail($id);

            $barang = $distributor->barang()
                ->select('barang.ID_Barang', 'barang.Nama_Barang', 'barang.Deskripsi_Barang')
                ->get();

            return response()->json($barang);
        } catch (\Exception $e) {
            \Log::error('Error getBarangByDistributor: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getHargaBeli($distributorId, $barangId)
    {
        $barangDistributor = \App\Models\BarangDistributor::where('ID_Distributor', $distributorId)
            ->where('ID_Barang', $barangId)
            ->first();

        return response()->json([
            'harga_beli' => $barangDistributor->Harga_Beli ?? 0
        ]);
    }
}
