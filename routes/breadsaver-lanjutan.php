<?php

use App\Http\Controllers\RescueBoxController;
use App\Http\Controllers\PickupSlotController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\WasteController;
use App\Http\Middleware\AdvancedCheckoutMiddleware;
use App\Http\Middleware\TrackEmployeeActivity;
use Illuminate\Support\Facades\Route;

// Tidak menghapus, mengganti, atau menata ulang route lama BreadSaver.
Route::middleware('auth')->group(function () {
    Route::get('/pembeli/rescue-box', [RescueBoxController::class, 'index'])->name('pembeli.rescue-box');
    Route::post('/pembeli/rescue-box/checkout', [RescueBoxController::class, 'checkout'])
        ->middleware('throttle:10,1')->name('pembeli.rescue-box.checkout');
    Route::get('/pembeli/slot-pickup', [PickupSlotController::class, 'list'])->name('pembeli.slots');
    Route::get('/pembeli/loyalty', [LoyaltyController::class, 'index'])->name('pembeli.loyalty');
    Route::post('/pembeli/loyalty/tukar', [LoyaltyController::class, 'exchange'])
        ->middleware('throttle:10,1')->name('pembeli.loyalty.exchange');

    Route::get('/admin/slot-pickup', [PickupSlotController::class, 'index'])->name('admin.slots');
    Route::post('/admin/slot-pickup', [PickupSlotController::class, 'save'])->name('admin.slots.save');
    Route::get('/admin/batch-fefo', [BatchController::class, 'index'])->name('admin.batches');
    Route::post('/admin/batch-fefo', [BatchController::class, 'store'])->name('admin.batches.store');
    Route::get('/pegawai/batch-fefo', [BatchController::class, 'index'])->name('pegawai.batches');
    Route::get('/admin/aktivitas-pegawai', [ActivityController::class, 'index'])->name('admin.activity');
    Route::get('/admin/food-waste-aktual', [WasteController::class, 'index'])->name('admin.waste-actual');
    Route::get('/pegawai/food-waste-aktual', [WasteController::class, 'index'])->name('pegawai.waste-actual');
    Route::post('/food-waste/catat', [WasteController::class, 'record'])->name('breadsaver.waste.record');
    Route::get('/admin/food-waste-aktual/export/xlsx', [WasteController::class, 'exportXlsx'])->name('admin.waste-export.xlsx');
    Route::get('/admin/food-waste-aktual/export/pdf', [WasteController::class, 'exportPdf'])->name('admin.waste-export.pdf');
});

// Pada Laravel 8, getByName() bisa kosong saat route baru didaftarkan,
// karena indeks nama dibangun belakangan. Iterasi Route object yang asli.
foreach (Route::getRoutes()->getRoutes() as $existing) {
    $name = $existing->getName();
    $inject = [];
    if ($name === 'pembeli.checkout') {
        // FEFO melakukan validasi expiry per batch, lebih akurat daripada expiry produk agregat.
        $inject[] = AdvancedCheckoutMiddleware::class;
    } elseif (in_array($name, ['keranjang.tambah', 'keranjang.tambahQty'], true)) {
        $inject[] = \App\Http\Middleware\RejectExpiredProducts::class;
    } elseif ($name === 'admin.produk.update') {
        $inject[] = \App\Http\Middleware\SyncManualProductDiscount::class;
    }
    if (in_array($name, [
        'pegawai.pesanan.proses', 'pegawai.pesanan.siap', 'pegawai.pesanan.selesai',
        'pegawai.pesanan.batal', 'pegawai.pickup.confirm',
        'admin.produk.store', 'admin.produk.update', 'admin.produk.destroy',
    ], true)) {
        $inject[] = TrackEmployeeActivity::class;
    }
    $already = $existing->middleware();
    foreach ($inject as $middlewareClass) {
        if (!in_array($middlewareClass, $already, true)) {
            $existing->middleware($middlewareClass);
        }
    }
}
