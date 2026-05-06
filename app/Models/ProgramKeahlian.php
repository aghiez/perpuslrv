<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramKeahlian extends Model
{
    protected $fillable = ['nama', 'singkatan'];
    
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
