<?php

use App\Http\Controllers\FoodWasteController;
use App\Http\Middleware\RejectExpiredProducts;
use App\Http\Middleware\SyncManualProductDiscount;
use Illuminate\Support\Facades\Route;

/*
 * Route tambahan; route lama TIDAK dihapus atau ditimpa.
 * Pemeriksaan peran dilakukan di tiap method controller, bukan hanya auth.
 */
Route::middleware('auth')->group(function () {
    Route::get('/admin/food-waste', [FoodWasteController::class, 'dashboard'])
        ->name('admin.foodwaste');
    Route::get('/admin/stok-prioritas', [FoodWasteController::class, 'stockPriority'])
        ->name('admin.stock-priority');
    Route::get('/pegawai/stok-prioritas', [FoodWasteController::class, 'stockPriority'])
        ->name('pegawai.stock-priority');
    Route::get('/admin/laporan/export/xlsx', [FoodWasteController::class, 'exportXlsx'])
        ->name('admin.report-export.xlsx');
    Route::get('/admin/laporan/export/pdf', [FoodWasteController::class, 'exportPdf'])
        ->name('admin.report-export.pdf');
});

// Decorate route lama dengan guard, tanpa mengubah PesananController/checkout.
foreach (['keranjang.tambah', 'keranjang.tambahQty', 'pembeli.checkout'] as $name) {
    $existing = Route::getRoutes()->getByName($name);
    if ($existing) {
        $existing->middleware(RejectExpiredProducts::class);
    }
}

$adminUpdate = Route::getRoutes()->getByName('admin.produk.update');
if ($adminUpdate) {
    $adminUpdate->middleware(SyncManualProductDiscount::class);
}
