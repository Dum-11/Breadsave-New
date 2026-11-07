<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // Halaman utama
    public function index()
    {
        $produks = Produk::latest()->get();

        return view('index', compact('produks'));
    }

    // Katalog pembeli
    public function katalog()
    {
        $produks = Produk::latest()->get();

        return view('pembeli.katalog', compact('produks'));
    }

    // Katalog pegawai
    public function katalogPegawai()
    {
        $produks = Produk::latest()->get();

        return view('pegawai.katalog', compact('produks'));
    }

    // Katalog admin
    public function katalogAdmin()
    {
        $produks = Produk::latest()->get();

        return view('admin.katalog', compact('produks'));
    }

    // Halaman tambah produk
    public function create()
    {
        return view('admin.tambah_produk');
    }

    // Menyimpan produk
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'harga_asli' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lte:harga_asli',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'expired_at' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                ->store('produk', 'public');
        }

        // Tidak memasukkan id_toko karena tidak ada di tabel produks.
        Produk::create($validated);

        return redirect()
            ->route('admin.katalog')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Halaman edit produk
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('admin.produk_edit', compact('produk'));
    }

    // Memperbarui produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'harga_asli' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lte:harga_asli',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'expired_at' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoLama = $produk->foto;

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                ->store('produk', 'public');
        }

        $produk->update($validated);

        if ($request->hasFile('foto') && $fotoLama) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()
            ->route('admin.katalog')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    // Menghapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()
            ->route('admin.katalog')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
