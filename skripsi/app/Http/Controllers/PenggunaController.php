<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    /**
     * Tampilkan daftar pengguna (dengan fitur pencarian & status)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Pengguna::query();

        if (!empty($search)) {
            $query->where('Nama', 'like', "%{$search}%")
                ->orWhere('No_Telp', 'like', "%{$search}%")
                ->orWhere('Role', 'like', "%{$search}%");
        }

        // Ambil semua data hasil query
        $pengguna = $query->orderBy('ID_Pengguna', 'asc')->get();

        // Arahkan ke listpengguna.blade.php
        return view('listpengguna', compact('pengguna', 'search'));
    }

    /**
     *Tampilkan form registrasi pengguna baru
     */
    public function create()
    {
        return view('regis');
    }

    /**
     *Tampilkan form edit pengguna
     */
    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return view('pengguna.edit', compact('pengguna'));
    }

    /**
     *Hapus pengguna dari database
     */
    public function destroy($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        $pengguna->delete();

        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     *Approve akun pengguna (ubah status jadi 'approved')
     */
    public function approve($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        if ($pengguna->Status === 'restricted') {
            $pengguna->Status = 'approved';
            $pengguna->save();

            return redirect()->route('pengguna.index')->with('success', 'Akun berhasil di-approve.');
        }

        return redirect()->route('pengguna.index')->with('info', 'Akun sudah di-approve sebelumnya.');
    }

    /**
     *Batalkan approval (opsional) — ubah kembali ke restricted
     */
    public function restrict($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        if ($pengguna->Status === 'approved') {
            $pengguna->Status = 'restricted';
            $pengguna->save();

            return redirect()->route('pengguna.index')->with('warning', 'Akun berhasil dikembalikan ke restricted.');
        }

        return redirect()->route('pengguna.index')->with('info', 'Akun sudah restricted.');
    }

    /**
     * Update Role pengguna dari dropdown
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'Role' => 'required|in:Admin,User'
        ]);

        $pengguna = Pengguna::findOrFail($id);
        $pengguna->Role = $request->Role;
        $pengguna->save();

        return redirect()->route('pengguna.index')->with('success', 'Role pengguna berhasil diperbarui.');
    }

}
