<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // GET /buku — Halaman katalog
    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->kategori($request->kategori);
        }

        if ($request->filled('q')) {
            $query->cari($request->q);
        }

        if ($request->boolean('tersedia')) {
            $query->tersedia();
        }

        $sort = $request->get('sort', 'rating');
        match ($sort) {
            'judul'  => $query->orderBy('judul'),
            'tahun'  => $query->orderByDesc('tahun_terbit'),
            default  => $query->orderByDesc('rating'),
        };

        $bukus = $query->paginate(12);

        return view('perpustakaan.katalog', compact('bukus'));
    }

    // GET /buku/{id} — Detail buku
    public function show(Buku $buku)
    {
        $buku->load('ulasan.anggota');
        $rekomendasi = Buku::kategori($buku->kategori)
            ->where('id', '!=', $buku->id)
            ->limit(4)
            ->get();

        return view('perpustakaan.detail', compact('buku', 'rekomendasi'));
    }

    // POST /buku/{id}/ulasan — Tambah ulasan
    public function tambahUlasan(Request $request, Buku $buku)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        $buku->ulasan()->updateOrCreate(
            ['anggota_id' => auth()->id()],
            [
                'rating'   => $request->rating,
                'komentar' => $request->komentar,
            ]
        );

        $buku->updateRating();

        return back()->with('success', 'Ulasan berhasil ditambahkan!');
    }

    // ── API ENDPOINTS (untuk fetch() dari JS) ──

    // GET /api/buku
    public function apiIndex(Request $request)
    {
        $query = Buku::query();

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->kategori($request->kategori);
        }
        if ($request->filled('q')) {
            $query->cari($request->q);
        }
        if ($request->boolean('tersedia')) {
            $query->tersedia();
        }

        $sort = $request->get('sort', 'rating');
        match ($sort) {
            'judul'  => $query->orderBy('judul'),
            'tahun'  => $query->orderByDesc('tahun_terbit'),
            default  => $query->orderByDesc('rating'),
        };

        return response()->json([
            'data' => $query->paginate(12),
        ]);
    }

    // GET /api/buku/{id}
    public function apiShow(Buku $buku)
    {
        $buku->load('ulasan.anggota');
        return response()->json(['data' => $buku]);
    }
}
