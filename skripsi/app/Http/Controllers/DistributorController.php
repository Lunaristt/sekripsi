<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distributor;

class DistributorController extends Controller
{
    /**
     * Menampilkan semua data distributor.
     */
    public function index()
    {
        $distributors = Distributor::all();
        return view('distributor.distributor', compact('distributors'));
    }

    /**
     * Menampilkan form tambah distributor baru.
     */
    public function create()
    {
        return view('distributor.tambahdistributor');
    }

    /**
     * Menyimpan data distributor baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nama_Distributor' => 'required|string|max:100',
            'Telp_CS' => 'nullable|string|max:20',
            'Nama_Salesman' => 'nullable|string|max:100',
            'Notelp_Salesman' => 'nullable|string|max:20',
        ]);

        Distributor::create([
            'Nama_Distributor' => $request->Nama_Distributor,
            'Telp_CS' => $request->Telp_CS,
            'Nama_Salesman' => $request->Nama_Salesman,
            'Notelp_Salesman' => $request->Notelp_Salesman,
        ]);

        return redirect()->route('distributor.create')->with('success', 'Distributor berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit distributor.
     */
    public function edit($id)
    {
        $distributor = Distributor::findOrFail($id);
        return view('distributor.edit', compact('distributor'));
    }

    /**
     * Menyimpan perubahan data distributor.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama_Distributor' => 'required|string|max:100',
            'Telp_CS' => 'nullable|string|max:20',
            'Nama_Salesman' => 'nullable|string|max:100',
            'Notelp_Salesman' => 'nullable|string|max:20',
        ]);

        $distributor = Distributor::findOrFail($id);
        $distributor->update($request->all());

        return redirect()->route('distributor.index')->with('success', 'Data distributor berhasil diperbarui!');
    }

    /**
     * Menghapus data distributor.
     */
    public function destroy($id)
    {
        $distributor = Distributor::findOrFail($id);
        $distributor->delete();

        return redirect()->route('distributor.index')->with('success', 'Distributor berhasil dihapus!');
    }
}