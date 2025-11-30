<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     *Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     *Proses login pengguna
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required',
            'password' => 'required'
        ]);

        // Cari pengguna berdasarkan Nama
        $user = Pengguna::where('Nama', $request->nama)->first();

        // Cek keberadaan user dan password
        if ($user && Hash::check($request->password, $user->Password)) {

            //Cek status akun
            if ($user->Status !== 'approved') {
                // Arahkan ke halaman akses-terbatas tanpa sidebar
                return view('aksesterbatas', [
                    'nama' => $user->Nama,
                    'status' => $user->Status,
                ]);
            }

            //Jika sudah approved, simpan data ke session
            session([
                'user_id' => $user->ID_Pengguna,
                'nama' => $user->Nama,
                'role' => $user->Role,
            ]);

            //Redirect sesuai role
            if (strtolower($user->Role) === 'admin') {
                return redirect()->route('dashboard')->with('success', 'Selamat datang Admin!');
            } else {
                return redirect()->route('home')->with('success', 'Selamat datang, ' . $user->Nama . '!');
            }
        }

        //Jika gagal login
        return redirect()->route('login')->with('error', 'Username atau Password salah!');
    }

    /**
     *Logout pengguna & hapus session
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
