<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    // MENAMPILKAN FORM RATING
    public function create($idPesanan)
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'pembeli',
            403
        );

        $pesanan = DB::table('pesanans as p')
            ->leftJoin(
                'pembayarans as b',
                'p.id_pesanan',
                '=',
                'b.id_pesanan'
            )
            ->where('p.id_pesanan', $idPesanan)
            ->where('p.id_user', Auth::id())
            ->select(
                'p.id_pesanan',
                'p.status',
                'b.status as status_pembayaran'
            )
            ->first();

        abort_if(!$pesanan, 404);

        if (
            $pesanan->status !== 'Selesai' ||
            $pesanan->status_pembayaran !== 'Berhasil'
        ) {
            return redirect()
                ->route('pembeli.pesanan')
                ->with(
                    'error',
                    'Rating hanya tersedia untuk pesanan selesai dan sudah dibayar.'
                );
        }

        // Produk yang sudah diberi rating
        $sudahDinilai = DB::table('ratings')
            ->where('id_pesanan', $idPesanan)
            ->pluck('id_produk')
            ->all();

        // Hanya tampilkan produk yang belum dinilai
        $produkBelumDinilai = DB::table('detail_pesanans as d')
            ->join(
                'produks as pr',
                'd.id_produk',
                '=',
                'pr.id_produk'
            )
            ->where('d.id_pesanan', $idPesanan)
            ->whereNotIn('pr.id_produk', $sudahDinilai)
            ->select(
                'pr.id_produk',
                'pr.nama_produk'
            )
            ->distinct()
            ->orderBy('pr.nama_produk')
            ->get();

        return view('pembeli.rating', compact(
            'pesanan',
            'produkBelumDinilai'
        ));
    }

    // MENYIMPAN RATING
    public function store(Request $request, $idPesanan)
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'pembeli',
            403
        );

        $data = $request->validate([
            'id_produk' => 'required|integer|exists:produks,id_produk',
            'bintang' => 'required|integer|between:1,5',
            'ulasan' => 'nullable|string|max:1000',
        ], [
            'id_produk.required' => 'Silakan pilih produk.',
            'id_produk.exists' => 'Produk tidak ditemukan.',
            'bintang.required' => 'Silakan pilih rating.',
            'bintang.between' => 'Rating harus antara 1 sampai 5.',
            'ulasan.max' => 'Ulasan maksimal 1000 karakter.',
        ]);

        $pesanan = DB::table('pesanans')
            ->where('id_pesanan', $idPesanan)
            ->where('id_user', Auth::id())
            ->first();

        abort_if(!$pesanan, 404);

        if ($pesanan->status !== 'Selesai') {
            return back()->with(
                'error',
                'Pesanan harus selesai sebelum diberi rating.'
            );
        }

        $pembayaran = DB::table('pembayarans')
            ->where('id_pesanan', $idPesanan)
            ->first();

        if (
            !$pembayaran ||
            $pembayaran->status !== 'Berhasil'
        ) {
            return back()->with(
                'error',
                'Pembayaran harus berhasil sebelum memberi rating.'
            );
        }

        $produkDibeli = DB::table('detail_pesanans')
            ->where('id_pesanan', $idPesanan)
            ->where('id_produk', $data['id_produk'])
            ->exists();

        if (!$produkDibeli) {
            return back()->with(
                'error',
                'Produk tidak ditemukan dalam pesanan ini.'
            );
        }

        $sudahDinilai = DB::table('ratings')
            ->where('id_pesanan', $idPesanan)
            ->where('id_produk', $data['id_produk'])
            ->exists();

        if ($sudahDinilai) {
            return back()->with(
                'error',
                'Produk ini sudah pernah diberi rating.'
            );
        }

        $tersimpan = DB::table('ratings')
            ->insertOrIgnore([
                'id_pesanan' => $idPesanan,
                'id_produk' => $data['id_produk'],
                'bintang' => $data['bintang'],
                'ulasan' => $data['ulasan'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        if (!$tersimpan) {
            return back()->with(
                'error',
                'Rating gagal disimpan atau sudah pernah diberikan.'
            );
        }

        return redirect()
            ->route('pembeli.rating.form', $idPesanan)
            ->with(
                'success',
                'Rating dan ulasan berhasil dikirim!'
            );
    }
}
