<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Berita;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Middleware cek admin — tambahkan di route group
    private function cekAdmin()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }
    }

    // ── DASHBOARD ──────────────────────────────────────────────────────────
    public function dashboard()
    {
        $this->cekAdmin();
        return view('admin.dashboard', [
            'totalBuku'       => Buku::count(),
            'totalAnggota'    => Anggota::where('role', 'siswa')->count(),
            'peminjamanAktif' => Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'menungguApprove' => Peminjaman::where('status', 'pending')->count(),
            'totalDenda'      => Peminjaman::sum('denda'),
            'bukuTersedia'    => Buku::where('stok_tersedia', '>', 0)->count(),
            'recentPeminjaman'=> Peminjaman::with(['anggota', 'buku'])
                                    ->latest()->limit(5)->get(),
        ]);
    }

    // ── CRUD BUKU ──────────────────────────────────────────────────────────

    // GET /admin/buku
    public function bukuIndex(Request $request)
    {
        $this->cekAdmin();
        $query = Buku::query();
        if ($request->filled('q')) {
            $query->where('judul', 'like', "%{$request->q}%")
                  ->orWhere('pengarang', 'like', "%{$request->q}%");
        }
        $bukus = $query->latest()->paginate(15);
        return view('admin.buku.index', compact('bukus'));
    }

    // GET /admin/buku/create
    public function bukuCreate()
    {
        $this->cekAdmin();
        return view('admin.buku.create');
    }

    // POST /admin/buku
    public function bukuStore(Request $request)
    {
        $this->cekAdmin();
        $data = $request->validate([
            'judul'          => 'required|string|max:255',
            'pengarang'      => 'required|string|max:255',
            'kategori'       => 'required|string',
            'penerbit'       => 'required|string|max:255',
            'tahun_terbit'   => 'required|integer|min:1900|max:' . date('Y'),
            'isbn'           => 'required|string|unique:bukus,isbn',
            'jumlah_halaman' => 'required|integer|min:1',
            'deskripsi'      => 'nullable|string',
            'cover_color'    => 'nullable|string',
            'stok'           => 'required|integer|min:0',
            'lokasi_rak'     => 'nullable|string|max:50',
        ]);

        $data['stok_tersedia'] = $data['stok'];
        $data['cover_color'] = $data['cover_color'] ?? 'oklch(60% .12 200)';

        Buku::create($data);

        return redirect()->route('admin.buku.index')
            ->with('success', "Buku \"{$data['judul']}\" berhasil ditambahkan!");
    }

    // GET /admin/buku/{id}/edit
    public function bukuEdit(Buku $buku)
    {
        $this->cekAdmin();
        return view('admin.buku.edit', compact('buku'));
    }

    // PUT /admin/buku/{id}
    public function bukuUpdate(Request $request, Buku $buku)
    {
        $this->cekAdmin();
        $data = $request->validate([
            'judul'          => 'required|string|max:255',
            'pengarang'      => 'required|string|max:255',
            'kategori'       => 'required|string',
            'penerbit'       => 'required|string|max:255',
            'tahun_terbit'   => 'required|integer|min:1900|max:' . date('Y'),
            'isbn'           => 'required|string|unique:bukus,isbn,' . $buku->id,
            'jumlah_halaman' => 'required|integer|min:1',
            'deskripsi'      => 'nullable|string',
            'cover_color'    => 'nullable|string',
            'stok'           => 'required|integer|min:0',
            'lokasi_rak'     => 'nullable|string|max:50',
        ]);

        // Hitung ulang stok tersedia jika stok total berubah
        $selisih = $data['stok'] - $buku->stok;
        $data['stok_tersedia'] = max(0, $buku->stok_tersedia + $selisih);

        $buku->update($data);

        return redirect()->route('admin.buku.index')
            ->with('success', "Buku \"{$buku->judul}\" berhasil diperbarui!");
    }

    // DELETE /admin/buku/{id}
    public function bukuDestroy(Buku $buku)
    {
        $this->cekAdmin();

        // Cek apakah ada peminjaman aktif
        $aktif = $buku->peminjaman()->whereIn('status', ['pending', 'dipinjam', 'terlambat'])->count();
        if ($aktif > 0) {
            return back()->with('error', 'Buku tidak dapat dihapus karena masih ada peminjaman aktif.');
        }

        $judul = $buku->judul;
        $buku->delete();

        return redirect()->route('admin.buku.index')
            ->with('success', "Buku \"{$judul}\" berhasil dihapus.");
    }

    // ── MANAJEMEN PEMINJAMAN ───────────────────────────────────────────────

    // GET /admin/peminjaman
    public function peminjamanIndex(Request $request)
    {
        $this->cekAdmin();
        $tab    = $request->get('tab', 'pending');
        $query  = Peminjaman::with(['anggota', 'buku'])->latest();

        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'aktif') {
            $query->whereIn('status', ['dipinjam', 'terlambat']);
        } elseif ($tab === 'selesai') {
            $query->whereIn('status', ['dikembalikan', 'ditolak']);
        }

        $peminjaman = $query->paginate(15);
        $counts = [
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'aktif'   => Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count(),
            'selesai' => Peminjaman::whereIn('status', ['dikembalikan', 'ditolak'])->count(),
        ];

        return view('admin.peminjaman.index', compact('peminjaman', 'tab', 'counts'));
    }

    // POST /admin/peminjaman/{id}/approve
    public function peminjamanApprove(Peminjaman $peminjaman)
    {
        $this->cekAdmin();

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }

        // Cek stok
        if ($peminjaman->buku->stok_tersedia <= 0) {
            return back()->with('error', 'Stok buku tidak tersedia.');
        }

        $peminjaman->update([
            'status'      => 'dipinjam',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Kurangi stok
        $peminjaman->buku->decrement('stok_tersedia');

        return back()->with('success', "Peminjaman \"{$peminjaman->buku->judul}\" oleh {$peminjaman->anggota->nama} disetujui!");
    }

    // POST /admin/peminjaman/{id}/tolak
    public function peminjamanTolak(Request $request, Peminjaman $peminjaman)
    {
        $this->cekAdmin();

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }

        $request->validate([
            'alasan_tolak' => 'nullable|string|max:255',
        ]);

        $peminjaman->update([
            'status'       => 'ditolak',
            'alasan_tolak' => $request->alasan_tolak ?? 'Ditolak oleh admin.',
            'approved_by'  => auth()->id(),
        ]);

        return back()->with('success', "Peminjaman ditolak.");
    }

    // POST /admin/peminjaman/{id}/kembalikan
    public function peminjamanKembalikan(Peminjaman $peminjaman)
    {
        $this->cekAdmin();

        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Status peminjaman tidak valid.');
        }

        $denda = $peminjaman->hitungDenda();

        $peminjaman->update([
            'status'          => 'dikembalikan',
            'tanggal_kembali' => now(),
            'denda'           => $denda,
        ]);

        $peminjaman->buku->increment('stok_tersedia');

        $msg = $denda > 0
            ? "Buku dikembalikan. Denda: Rp " . number_format($denda, 0, ',', '.')
            : "Buku berhasil dikembalikan tepat waktu!";

        return back()->with('success', $msg);
    }

    // ── MANAJEMEN ANGGOTA ─────────────────────────────────────────────────

    // GET /admin/anggota
    public function anggotaIndex(Request $request)
    {
        $this->cekAdmin();
        $anggota = Anggota::latest()->paginate(20);
        return view('admin.anggota.index', compact('anggota'));
    }
}
