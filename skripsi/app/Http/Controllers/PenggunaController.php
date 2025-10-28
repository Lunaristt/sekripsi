<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Pengguna::query();
        $pengguna = Pengguna::all();

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        // 🔹 arahkan ke listpengguna.blade.php
        return view('listpengguna', compact('pengguna'));
    }

    public function create()
    {
        return view('regis');
    }

    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return view('pengguna.edit', compact('pengguna'));
    }

    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
