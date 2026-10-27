<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Menampilkan halaman login
        return view('login');
    }

    /**
     * Memproses autentikasi login dan pengarahan berdasarkan role.
     */
    public function login(Request $request)
    {
        // Validasi input dari form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba lakukan autentikasi (login)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Pengecekan role dan pengarahan halaman sesuai permintaan
            if ($user->role === 'admin' || empty($user->role)) {
                // Admin Toko (atau jika role kosong)
                return redirect()->intended('/admin/beranda');
            } elseif ($user->role === 'pegawai') {
                // Pegawai Toko
                return redirect()->intended('/pegawai/beranda');
            } elseif ($user->role === 'pembeli') {
                // Pembeli
                return redirect()->intended('/pembeli/beranda');
            }

            // Default jika role tidak dikenali (diarahkan ke halaman utama /)
            return redirect()->intended('/');
        }

        // Jika login gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Fungsi untuk Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}