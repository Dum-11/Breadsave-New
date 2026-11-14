<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil sesuai role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('admin.profil', compact('user'));
        }

        if ($user->role === 'pegawai') {
            return view('pegawai.profil', compact('user'));
        }

        return view('pembeli.profil', compact('user'));
    }

    /**
     * Memperbarui data profil sesuai role.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'nama' => ['required', 'string', 'max:100'],
            'foto_profil' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];

        /*
         * Pembeli dan pegawai boleh mengubah email serta nomor HP.
         * Admin tidak mengubah kedua data tersebut dari halaman profil.
         */
        if ($user->role !== 'admin') {
            $rules['email'] = [
                'required',
                'email',
                'max:100',
                'unique:users,email,' . $user->id_user . ',id_user',
            ];

            $rules['no_hp'] = [
                'nullable',
                'string',
                'max:20',
            ];
        }

        $messages = [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
            'foto_profil.image' => 'File foto profil harus berupa gambar.',
            'foto_profil.mimes' => 'Foto profil harus berformat JPG, JPEG, PNG, atau WEBP.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 2 MB.',
        ];

        $validated = $request->validate($rules, $messages);

        $user->nama = $validated['nama'];

        if ($user->role !== 'admin') {
            $user->email = $validated['email'];
            $user->no_hp = $validated['no_hp'] ?? null;
        }

        /*
         * Upload foto profil
         */
        if ($request->hasFile('foto_profil')) {

            // Hapus foto lama jika ada
            if (!empty($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // Simpan foto baru
            $fotoProfil = $request->file('foto_profil')
                ->store('profil', 'public');

            // Simpan path ke database
            $user->foto_profil = $fotoProfil;
        }

        $user->save();

        return redirect()
            ->route($this->profileRoute($user))
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menghapus foto profil.
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        if (!empty($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->foto_profil = null;
        $user->save();

        return redirect()
            ->route($this->profileRoute($user))
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
         * Cek password lama
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
         * Simpan password baru
         */
        $user->password = Hash::make(
            $request->password_baru
        );

        $user->save();

        return redirect()
            ->route($this->profileRoute($user))
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Menentukan route profil berdasarkan role.
     */
    private function profileRoute($user)
    {
        if ($user->role === 'admin') {
            return 'admin.profil';
        }

        if ($user->role === 'pegawai') {
            return 'pegawai.profil';
        }

        return 'pembeli.profil';
    }
}