<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Anggota extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'anggota';

    protected $fillable = [
        'nis', 'nip', 'nama', 'email', 'password',
        'role', 'kelas', 'jurusan', 'foto', 'aktif',
        'kelas_id', 'program_keahlian_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'aktif' => 'boolean',
        'password' => 'hashed',
    ];

    // Relasi: anggota punya banyak peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Relasi: anggota punya banyak ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    // Peminjaman yang sedang aktif
    public function peminjamanAktif()
    {
        return $this->peminjaman()->whereIn('status', ['dipinjam', 'terlambat']);
    }

    // Cek apakah admin/staff
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'staff']);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function programKeahlian()
    {
        return $this->belongsTo(ProgramKeahlian::class);
    }
}
