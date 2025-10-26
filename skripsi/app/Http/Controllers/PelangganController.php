<?php

namespace App\Http\Controllers;

use App\Models\pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = pelanggan::all();
        return view('pelanggan/listpelanggan', compact('pelanggan'));
    }

    public function create()
    {
        $pelanggan = pelanggan::all();
        return view('pelanggan.tambahpelanggan', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'No_Telp' => 'required|string|max:13|unique:pelanggan',
            'Nama_Pelanggan' => 'required|string|max:255',
            'Alamat' => 'required|string|max:500',
        ]);

        pelanggan::create($request->all());

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }
    public function getNoTelp(Request $request)
    {
        $pelanggan = Pelanggan::where('Nama_Pelanggan', $request->nama)->first();
        return response()->json(['no_telp' => $pelanggan ? $pelanggan->No_Telp : null]);
    }
    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan/editpelanggan', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nama_Pelanggan' => 'required|string|max:100',
            'NoTelp_Pelanggan' => 'required|string|max:20',
            'Alamat_Pelanggan' => 'required|string',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($request->all());

        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

}
