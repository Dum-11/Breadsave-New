<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pembeli.
     */
    public function index()
    {
        $user = Auth::user();

        return view('pembeli.profil', compact('user'));
    }

    /**
     * Memperbarui nama, email, nomor HP, dan foto profil.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email,' . $user->id_user . ',id_user',
            ],

            'no_hp' => [
                'nullable',
                'string',
                'max:20',
            ],

            'foto_profil' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
            'foto_profil.image' => 'File foto profil harus berupa gambar.',
            'foto_profil.mimes' => 'Foto profil harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        // Update data akun
        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->no_hp = $validated['no_hp'] ?? null;

        /*
        =========================================================
        UPLOAD FOTO PROFIL
        =========================================================
        */

        if ($request->hasFile('foto_profil')) {

            // Hapus foto lama jika ada
            if (!empty($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // Simpan foto baru
            $fotoProfil = $request->file('foto_profil')
                ->store('profil', 'public');

            // Simpan path foto baru ke database
            $user->foto_profil = $fotoProfil;
        }

        // Simpan perubahan
        $user->save();

        return redirect()
            ->route('pembeli.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menghapus foto profil.
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        /*
        =========================================================
        HAPUS FILE FOTO DARI STORAGE
        =========================================================
        */

        if (!empty($user->foto_profil)) {

            Storage::disk('public')->delete(
                $user->foto_profil
            );
        }

        /*
        =========================================================
        HAPUS PATH FOTO DARI DATABASE
        =========================================================
        */

        $user->foto_profil = null;

        $user->save();

        return redirect()
            ->route('pembeli.profil')
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Memperbarui password akun.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => [
                'required',
            ],

            'password_baru' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        /*
        =========================================================
        CEK PASSWORD LAMA
        =========================================================
        */

        if (!Hash::check(
            $request->password_lama,
            $user->password
        )) {

            return back()
                ->withErrors([
                    'password_lama' => 'Password lama yang Anda masukkan salah.',
                ])
                ->withInput();
        }

        /*
        =========================================================
        SIMPAN PASSWORD BARU
        =========================================================
        */

        $user->password = Hash::make(
            $request->password_baru
        );

        $user->save();

        return redirect()
            ->route('pembeli.profil')
            ->with('success', 'Password berhasil diperbarui.');
    }
}