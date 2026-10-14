<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;

// Rute untuk halaman utama (katalog roti)
Route::get('/', [ProdukController::class, 'index']);

// Rute untuk halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');