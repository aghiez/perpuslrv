<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    \App\Models\Anggota::create([
        'nama'     => 'Admin Perpustakaan',
        'nis'      => 'ADMIN001',
        'email'    => 'admin@smkn2pkl.sch.id',
        'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        'role'     => 'admin',
    ]);

    \App\Models\Anggota::create([
        'nama'     => 'Riskiyanto',
        'nip'      => 'NIP001',
        'email'    => 'riskiyanto@smkn2pkl.sch.id',
        'password' => \Illuminate\Support\Facades\Hash::make('staff123'),
        'role'     => 'staff',
    ]);
    }
}
