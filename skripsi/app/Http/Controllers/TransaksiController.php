<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangPenjualan;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Tampilkan daftar transaksi.
     */
    public function index()
    {
        $transaksi = Penjualan::with('barang')->orderBy('Tanggal', 'desc')->get();
        return view('transaksi.index', compact('transaksi'));
    }

    /**
     * Buat transaksi baru atau lanjutkan yang masih pending.
     */
    public function create()
    {
        $barang = Barang::all();
        $pelanggan = Pelanggan::orderBy('Nama_Pelanggan')->get();

        $penjualanId = session('penjualan_id');
        $penjualan = null;

        if ($penjualanId) {
            $penjualan = Penjualan::find($penjualanId);
            if (!$penjualan || $penjualan->Status !== 'Pending') {
                $penjualan = Penjualan::create([
                    'Harga_Keseluruhan' => 0,
                    'Tanggal' => now(),
                    'Status' => 'Pending',
                ]);
                if (!$penjualan) {
                    abort(500, 'Gagal membuat transaksi baru');
                }
                session(['penjualan_id' => $penjualan->ID_Penjualan]);
            }
        } else {
            $penjualan = Penjualan::create([
                'Harga_Keseluruhan' => 0,
                'Tanggal' => now(),
                'Status' => 'Pending',
            ]);
            if (!$penjualan) {
                abort(500, 'Gagal membuat transaksi baru');
            }
            session(['penjualan_id' => $penjualan->ID_Penjualan]);
        }

        $transaksi = BarangPenjualan::with('barang')
            ->where('ID_Penjualan', $penjualan->ID_Penjualan)
            ->get();

        return view('transaksi', compact('barang', 'penjualan', 'transaksi', 'pelanggan'));
    }


    /**
     * Tambahkan barang ke transaksi (sementara, belum mengurangi stok).
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'ID_Barang' => 'required|exists:barang,ID_Barang',
            'Jumlah' => 'required|integer|min:1',
        ]);

        $penjualanId = session('penjualan_id');
        if (!$penjualanId) {
            return response()->json(['success' => false, 'message' => 'Tidak ada transaksi aktif']);
        }

        $barang = Barang::findOrFail($request->ID_Barang);

        // Cek stok tersedia
        if ($barang->Stok_Barang < $request->Jumlah) {
            return response()->json(['success' => false, 'message' => 'Stok barang tidak mencukupi!']);
        }

        $totalHarga = $barang->Harga_Barang * $request->Jumlah;

        // Simpan item ke pivot table
        $detail = BarangPenjualan::create([
            'ID_Penjualan' => $penjualanId,
            'ID_Barang' => $request->ID_Barang,
            'Jumlah' => $request->Jumlah,
            'Total_Harga' => $totalHarga,
        ]);

        // Update total harga keseluruhan di tabel penjualan
        $grandTotal = BarangPenjualan::where('ID_Penjualan', $penjualanId)->sum('Total_Harga');
        Penjualan::where('ID_Penjualan', $penjualanId)->update(['Harga_Keseluruhan' => $grandTotal]);

        return response()->json([
            'success' => true,
            'id' => $detail->id,
            'barang' => $barang->Nama_Barang,
            'deskripsi' => $barang->Deskripsi_Barang,
            'jumlah' => $detail->Jumlah,
            'harga' => $barang->Harga_Barang,
            'total' => $detail->Total_Harga,
            'grandTotal' => $grandTotal
        ]);
    }

    /**
     * Selesaikan transaksi dan kurangi stok barang.
     */
    public function checkout(Request $request)
    {
        if (!$request->Nama_Pelanggan || !$request->No_Telp) {
            return response()->json([
                'success' => false,
                'message' => 'Nama dan Nomor Telepon pelanggan wajib diisi!'
            ]);
        }

        if (empty($request->barang) || count($request->barang) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada barang dalam transaksi!'
            ]);
        }

        DB::beginTransaction();
        try {
            // 🔹 Cek pelanggan atau buat baru
            $pelanggan = Pelanggan::firstOrCreate(
                ['No_Telp' => $request->No_Telp],
                [
                    'Nama_Pelanggan' => $request->Nama_Pelanggan,
                    'Alamat' => $request->Alamat ?? '-'
                ]
            );

            // 🔹 Ambil transaksi pending
            $penjualan = Penjualan::where('Status', 'Pending')->latest('ID_Penjualan')->first();

            if (!$penjualan) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada transaksi yang sedang berjalan!'
                ]);
            }

            // 🔹 Update data transaksi
            $penjualan->update([
                'No_Telp' => $pelanggan->No_Telp,
                'Status' => 'Selesai',
                'Tanggal' => now(),
                'Harga_Keseluruhan' => collect($request->barang)->sum('total'),
            ]);

            // 🔹 Simpan barang-barang
            foreach ($request->barang as $item) {
                $barang = Barang::findOrFail($item['id']);
                if ($barang->Stok_Barang < $item['jumlah']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok ' . $barang->Nama_Barang . ' tidak mencukupi!'
                    ]);
                }

                $barang->decrement('Stok_Barang', $item['jumlah']);
                $penjualan->barang()->attach($barang->ID_Barang, [
                    'Jumlah' => $item['jumlah'],
                    'Total_Harga' => $item['total'],
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!',
                'penjualan_id' => $penjualan->ID_Penjualan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    /**
     * Tampilkan detail transaksi.
     */
    public function show($idPenjualan)
    {
        $penjualan = Penjualan::with('barang')->findOrFail($idPenjualan);
        $pelanggan = Pelanggan::all();
        $barang = Barang::all();
        return view('transaksi', compact('penjualan', 'pelanggan', 'barang'));
    }

    /**
     * Hapus item dari transaksi.
     */
    public function destroy($id_penjualan, $id_barang)
    {
        $deleted = BarangPenjualan::where('ID_Penjualan', $id_penjualan)
            ->where('ID_Barang', $id_barang)
            ->delete();

        if ($deleted) {
            return redirect()->route('transaksi.show', $id_penjualan)
                ->with('success', 'Barang berhasil dihapus dari transaksi.');
        }

        // return redirect()->back()->with('error', 'Barang gagal dihapus.');
    }



    /**
     * Batalkan transaksi (kembalikan stok & reset data).
     */
    public function cancel()
    {
        $penjualanId = session('penjualan_id');

        if (!$penjualanId) {
            return redirect()->back()->with('error', 'Tidak ada transaksi untuk dibatalkan!');
        }

        $items = BarangPenjualan::where('ID_Penjualan', $penjualanId)->get();

        // Kembalikan stok barang
        foreach ($items as $item) {
            $barang = Barang::find($item->ID_Barang);
            if ($barang) {
                $barang->Stok_Barang += $item->Jumlah;
                $barang->save();
            }
        }

        // Hapus item di pivot
        BarangPenjualan::where('ID_Penjualan', $penjualanId)->delete();

        // Reset transaksi di tabel penjualan
        Penjualan::where('ID_Penjualan', $penjualanId)->update([
            'Harga_Keseluruhan' => 0,
            'Status' => 'Pending',
            'Tanggal' => now(),
        ]);

        return redirect()->route('transaksi.create')->with('success', 'Transaksi dibatalkan dan stok dikembalikan!');
    }

    public function batalTransaksi($id)
    {
        $penjualan = Penjualan::with('barangpenjualan')->findOrFail($id);

        // Jika sudah batal, tidak perlu diproses ulang
        if ($penjualan->Status === 'Batal') {
            return redirect()->back()->with('error', 'Transaksi ini sudah dibatalkan.');
        }

        // Kembalikan stok semua barang di transaksi ini
        foreach ($penjualan->barangpenjualan as $item) {
            $barang = Barang::find($item->ID_Barang);
            if ($barang) {
                $barang->Stok_Barang += $item->Jumlah;
                $barang->save();
            }
        }

        // Ubah status transaksi jadi "Batal"
        $penjualan->update([
            'Status' => 'Batal'
        ]);

        // Redirect kembali ke list transaksi
        return redirect()->route('statustransaksi.index')->with('success', 'Transaksi berhasil dibatalkan dan stok dikembalikan!');
    }
}
