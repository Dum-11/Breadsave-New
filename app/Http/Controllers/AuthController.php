<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Menampilkan halaman login
        return view('login');
    }

    /**
     * Menampilkan halaman register.
     */
    public function showRegister()
    {
        return view('register');
    }

    /**
     * Memproses pendaftaran akun pembeli.
     */
    public function register(Request $request)
    {
        // Validasi data yang dikirim dari form register
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Membuat akun baru dengan role pembeli secara otomatis
        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pembeli',
        ]);

        // Login otomatis setelah pendaftaran berhasil
        Auth::login($user);

        // Membuat ulang session untuk keamanan
        $request->session()->regenerate();

        // Mengarahkan pembeli ke halaman beranda
        return redirect('/pembeli/beranda')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di BreadSaver.');
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

            // Default jika role tidak dikenali
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
