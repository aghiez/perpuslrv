<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'judul', 'slug', 'kategori', 'isi',
        'gambar', 'penulis_id', 'published', 'published_at',
    ];

    protected $casts = [
        'published'    => 'boolean',
        'published_at' => 'datetime',
    ];

    // Auto-generate slug dari judul
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul) . '-' . uniqid();
            }
            if (empty($berita->published_at) && $berita->published) {
                $berita->published_at = now();
            }
        });
    }

    // Relasi: berita ditulis oleh anggota
    public function penulis()
    {
        return $this->belongsTo(Anggota::class, 'penulis_id');
    }

    // Scope: hanya yang published
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}
