<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = ['nama', 'program_keahlian_id'];
    
    public function programKeahlian()
    {
        return $this->belongsTo(ProgramKeahlian::class);
    }

    public function anggota()
    {
        return $this->hasMany(Anggota::class);
    }
}
