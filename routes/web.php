<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── BERANDA ──────────────────────────────────────────────────────────────
Route::get('/', function () {
    $featured = \App\Models\Buku::orderByDesc('rating')->limit(4)->get();
    $topRated = \App\Models\Buku::orderByDesc('rating')->limit(8)->get();
    $berita   = \App\Models\Berita::where('published', true)->latest()->limit(3)->get();
    return view('perpustakaan.home', compact('featured', 'topRated', 'berita'));
})->name('home');

// ── DARK MODE TOGGLE ──────────────────────────────────────────────────────
Route::post('/toggle-dark', function () {
    session(['dark_mode' => !session('dark_mode', false)]);
    return back();
})->name('toggle.dark');

// ── AUTH ──────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [AuthController::class, 'login']);
    Route::get('/daftar',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── BUKU ──────────────────────────────────────────────────────────────────
Route::get('/katalog',                 [BukuController::class, 'index'])->name('katalog');
Route::get('/katalog/{buku}',          [BukuController::class, 'show'])->name('buku.detail');
Route::post('/katalog/{buku}/ulasan',  [BukuController::class, 'tambahUlasan'])
    ->name('buku.ulasan')->middleware('auth');

// ── PENCARIAN ─────────────────────────────────────────────────────────────
Route::get('/cari', function (\Illuminate\Http\Request $request) {
    $results = [];
    if ($request->filled('q')) {
        $results = \App\Models\Buku::cari($request->q)->paginate(12);
    }
    return view('perpustakaan.search', compact('results'));
})->name('cari');

// ── PEMINJAMAN (anggota) ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/peminjaman',   [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman',  [PeminjamanController::class, 'store'])->name('peminjaman.store');
});

// ── BERITA ────────────────────────────────────────────────────────────────
Route::get('/berita',                  [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{berita:slug}',    [BeritaController::class, 'show'])->name('berita.show');

// ── TENTANG ───────────────────────────────────────────────────────────────
Route::get('/tentang', fn () => view('perpustakaan.tentang'))->name('tentang');

// ── ADMIN ─────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/',          [AdminController::class, 'dashboard'])->name('dashboard');

    // CRUD Buku
    Route::get('/buku',                    [AdminController::class, 'bukuIndex'])->name('buku.index');
    Route::get('/buku/create',             [AdminController::class, 'bukuCreate'])->name('buku.create');
    Route::post('/buku',                   [AdminController::class, 'bukuStore'])->name('buku.store');
    Route::get('/buku/{buku}/edit',        [AdminController::class, 'bukuEdit'])->name('buku.edit');
    Route::put('/buku/{buku}',             [AdminController::class, 'bukuUpdate'])->name('buku.update');
    Route::delete('/buku/{buku}',          [AdminController::class, 'bukuDestroy'])->name('buku.destroy');

    // Manajemen Peminjaman
    Route::get('/peminjaman',                          [AdminController::class, 'peminjamanIndex'])->name('peminjaman.index');
    Route::post('/peminjaman/{peminjaman}/approve',    [AdminController::class, 'peminjamanApprove'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/tolak',      [AdminController::class, 'peminjamanTolak'])->name('peminjaman.tolak');
    Route::post('/peminjaman/{peminjaman}/kembalikan', [AdminController::class, 'peminjamanKembalikan'])->name('peminjaman.kembalikan');

    // Manajemen Anggota
    Route::get('/anggota', [AdminController::class, 'anggotaIndex'])->name('anggota.index');

    //Manajemen Progli dan Kelas
    Route::get('/program-keahlian', [ProgramKeahlianController::class, 'programKeahlianIndex'])->name('program-keahlian.index');
    Route::get('/kelas', [KelasController::class, 'kelasIndex'])->name('kelas.index');
});
