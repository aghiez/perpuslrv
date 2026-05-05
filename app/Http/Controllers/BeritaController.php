<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    // GET /berita
    public function index(Request $request)
    {
        $query = Berita::where('published', true)->latest('published_at');

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        $berita = $query->paginate(10);
        return view('perpustakaan.berita', compact('berita'));
    }

    // GET /berita/{slug}
    public function show(Berita $berita)
    {
        abort_if(!$berita->published, 404);
        return view('perpustakaan.berita-detail', compact('berita'));
    }

    // GET /api/berita
    public function apiIndex(Request $request)
    {
        $query = Berita::where('published', true)->latest('published_at');
        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }
        return response()->json(['data' => $query->paginate(10)]);
    }
}
