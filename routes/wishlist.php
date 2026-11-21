<?php

use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Tambahan terpisah agar route lama tidak perlu ditulis ulang.
Route::middleware('auth')->prefix('pembeli/wishlist')->name('pembeli.')->group(function () {
    Route::get('/', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/ids', [WishlistController::class, 'ids'])->name('wishlist.ids');
    Route::post('/{idProduk}', [WishlistController::class, 'store'])
        ->where('idProduk', '[0-9]+')->middleware('throttle:60,1')->name('wishlist.store');
    Route::delete('/{idProduk}', [WishlistController::class, 'destroy'])
        ->where('idProduk', '[0-9]+')->middleware('throttle:60,1')->name('wishlist.destroy');
});
