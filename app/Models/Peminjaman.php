<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_peminjaman', 'anggota_id', 'buku_id',
        'tanggal_pinjam', 'tanggal_jatuh_tempo',
        'tanggal_kembali', 'status', 'denda',
        'denda_dibayar', 'catatan', 'petugas_id',
    ];

    protected $casts = [
        'tanggal_pinjam'      => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_kembali'     => 'date',
        'denda_dibayar'       => 'boolean',
    ];

    // Relasi
    public function anggota() { return $this->belongsTo(Anggota::class); }
    public function buku()    { return $this->belongsTo(Buku::class); }
    public function petugas() { return $this->belongsTo(Anggota::class, 'petugas_id'); }

    // Generate kode peminjaman otomatis
    public static function generateKode(): string
    {
        $tahun = now()->year;
        $last  = static::whereYear('created_at', $tahun)->count() + 1;
        return sprintf('PJM-%d-%03d', $tahun, $last);
    }

    // Hitung denda (Rp 500/hari keterlambatan)
    public function hitungDenda(): int
    {
        if ($this->status === 'dikembalikan' || !$this->tanggal_jatuh_tempo) {
            return 0;
        }
        $today    = Carbon::today();
        $jatuhTempo = Carbon::parse($this->tanggal_jatuh_tempo);
        $selisih  = $today->diffInDays($jatuhTempo, false);
        return $selisih < 0 ? abs($selisih) * 500 : 0;
    }

    // Update status terlambat secara otomatis
    public function updateStatus(): void
    {
        if ($this->status === 'dipinjam' && Carbon::today()->gt($this->tanggal_jatuh_tempo)) {
            $this->update([
                'status' => 'terlambat',
                'denda'  => $this->hitungDenda(),
            ]);
        }
    }
}
