<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique(); // NIS untuk siswa
            $table->string('nip')->nullable()->unique(); // NIP untuk guru
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['siswa', 'guru', 'staff', 'admin'])->default('siswa');
            $table->string('kelas')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('aktif')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
