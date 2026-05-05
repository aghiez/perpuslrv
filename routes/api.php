<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BeritaController;

/*
|--------------------------------------------------------------------------
| API Routes — digunakan jika pakai fetch() dari frontend JS
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ── BUKU ──────────────────────────────────────────────────────────────
    Route::get('/buku',      [BukuController::class, 'apiIndex']);
    Route::get('/buku/{id}', [BukuController::class, 'apiShow']);

    // ── BERITA ────────────────────────────────────────────────────────────
    Route::get('/berita', [BeritaController::class, 'apiIndex']);

    // ── PEMINJAMAN (butuh auth) ───────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/peminjaman',  [PeminjamanController::class, 'apiIndex']);
        Route::post('/peminjaman', [PeminjamanController::class, 'store']);
    });

});
