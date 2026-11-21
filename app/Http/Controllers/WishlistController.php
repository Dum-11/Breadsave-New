<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function onlyBuyer(): int
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pembeli', 403);
        return (int) auth()->id();
    }

    public function index()
    {
        $userId = $this->onlyBuyer();
        $items = DB::table('wishlists as w')
            ->leftJoin('produks as p', 'p.id_produk', '=', 'w.id_produk')
            ->where('w.id_user', $userId)
            ->orderByDesc('w.created_at')
            ->orderByDesc('w.id')
            ->select(
                'w.id_produk', 'w.created_at as disimpan_pada',
                'p.nama_produk', 'p.harga_asli', 'p.harga_diskon',
                'p.stok', 'p.expired_at', 'p.foto', 'p.deskripsi'
            )->paginate(12);

        return view('pembeli.wishlist', compact('items'));
    }

    public function ids()
    {
        $userId = $this->onlyBuyer();
        $ids = DB::table('wishlists')->where('id_user', $userId)->pluck('id_produk');

        return response()->json(['success' => true, 'ids' => $ids]);
    }

    public function store(Request $request, $idProduk)
    {
        $userId = $this->onlyBuyer();
        abort_unless(ctype_digit((string) $idProduk) && (int) $idProduk > 0, 404);
        $idProduk = (int) $idProduk;
        abort_unless(DB::table('produks')->where('id_produk', $idProduk)->exists(), 404);

        DB::table('wishlists')->insertOrIgnore([
            'id_user' => $userId,
            'id_produk' => $idProduk,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'saved' => true, 'message' => 'Roti disimpan ke Wishlist.']);
        }
        return back()->with('success', 'Roti disimpan ke Wishlist.');
    }

    public function destroy(Request $request, $idProduk)
    {
        $userId = $this->onlyBuyer();
        abort_unless(ctype_digit((string) $idProduk) && (int) $idProduk > 0, 404);

        DB::table('wishlists')
            ->where('id_user', $userId)
            ->where('id_produk', (int) $idProduk)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'saved' => false, 'message' => 'Roti dihapus dari Wishlist.']);
        }
        return back()->with('success', 'Roti dihapus dari Wishlist.');
    }
}
