<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'judul', 'pengarang', 'kategori', 'penerbit',
        'tahun_terbit', 'isbn', 'jumlah_halaman',
        'deskripsi', 'cover_color', 'stok',
        'stok_tersedia', 'rating', 'rating_count', 'lokasi_rak',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'stok_tersedia' => 'integer',
    ];

    // Accessor: apakah buku tersedia
    public function getAvailableAttribute(): bool
    {
        return $this->stok_tersedia > 0;
    }

    // Relasi: buku punya banyak peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Relasi: buku punya banyak ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    // Scope: hanya buku tersedia
    public function scopeTersedia($query)
    {
        return $query->where('stok_tersedia', '>', 0);
    }

    // Scope: filter berdasarkan kategori
    public function scopeKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Scope: search judul atau pengarang
    public function scopeCari($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('judul', 'like', "%{$keyword}%")
              ->orWhere('pengarang', 'like', "%{$keyword}%")
              ->orWhere('isbn', 'like', "%{$keyword}%");
        });
    }

    // Update rating rata-rata setelah ada ulasan baru
    public function updateRating(): void
    {
        $avg = $this->ulasan()->avg('rating') ?? 0;
        $count = $this->ulasan()->count();
        $this->update([
            'rating' => round($avg, 1),
            'rating_count' => $count,
        ]);
    }
}
