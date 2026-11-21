<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotifikasiController;

Route::middleware(['auth'])
    ->prefix('notifikasi')
    ->name('notifikasi.')
    ->group(function () {
        Route::get('/feed', [NotifikasiController::class, 'feed'])
            ->middleware('throttle:60,1')
            ->name('feed');

        Route::post('/read-all', [NotifikasiController::class, 'readAll'])
            ->middleware('throttle:30,1')
            ->name('read-all');

        Route::post('/{id}/read', [NotifikasiController::class, 'read'])
            ->where('id', '[0-9]+')
            ->middleware('throttle:60,1')
            ->name('read');
    });
