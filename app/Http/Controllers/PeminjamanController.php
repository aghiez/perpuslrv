<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // GET /peminjaman — Halaman peminjaman anggota
    public function index()
    {
        $anggota = auth()->user();

        $pending  = $anggota->peminjaman()
                        ->where('status', 'pending')
                        ->with('buku')->latest()->get();

        $aktif    = $anggota->peminjaman()
                        ->whereIn('status', ['dipinjam', 'terlambat'])
                        ->with('buku')->latest()->get();

        $riwayat  = $anggota->peminjaman()
                        ->whereIn('status', ['dikembalikan', 'ditolak'])
                        ->with('buku')->latest()->get();

        // Update status terlambat otomatis
        $aktif->each(fn ($p) => $p->updateStatus());

        return view('perpustakaan.peminjaman', compact('pending', 'aktif', 'riwayat'));
    }

    // POST /peminjaman — Kirim permintaan peminjaman (status: pending)
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        // Cek ketersediaan stok
        if ($buku->stok_tersedia <= 0) {
            return back()->with('error', 'Maaf, buku ini sedang tidak tersedia.');
        }

        // Cek apakah sudah ada permintaan aktif untuk buku yang sama
        $sudahAda = Peminjaman::where('anggota_id', auth()->id())
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Kamu sudah memiliki permintaan atau sedang meminjam buku ini.');
        }

        // Batas maksimal 3 buku aktif
        $jumlahAktif = auth()->user()->peminjaman()
            ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
            ->count();

        if ($jumlahAktif >= 3) {
            return back()->with('error', 'Maksimal 3 buku dapat dipinjam/diminta bersamaan.');
        }

        // Buat peminjaman dengan status PENDING
        Peminjaman::create([
            'kode_peminjaman'     => Peminjaman::generateKode(),
            'anggota_id'          => auth()->id(),
            'buku_id'             => $buku->id,
            'tanggal_pinjam'      => Carbon::today(),
            'tanggal_jatuh_tempo' => Carbon::today()->addDays(14),
            'status'              => 'pending',
            'petugas_id'          => null,
        ]);

        // Stok belum dikurangi — baru dikurangi saat admin approve

        return redirect()->route('peminjaman.index')
            ->with('success', "Permintaan peminjaman \"" . $buku->judul . "\" berhasil dikirim! Menunggu persetujuan petugas perpustakaan.");
    }

    // GET /api/peminjaman — API untuk anggota login
    public function apiIndex()
    {
        $anggota = auth()->user();
        $data = $anggota->peminjaman()
            ->with('buku:id,judul,pengarang,cover_color')
            ->latest()
            ->get()
            ->map(fn ($p) => [
                'id'            => $p->kode_peminjaman,
                'title'         => $p->buku->judul,
                'author'        => $p->buku->pengarang,
                'tanggalPinjam' => $p->tanggal_pinjam->format('d M Y'),
                'jatuhTempo'    => $p->tanggal_jatuh_tempo->format('d M Y'),
                'status'        => $p->status,
                'denda'         => $p->denda,
            ]);

        return response()->json(['data' => $data]);
    }
}
