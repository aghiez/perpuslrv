<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('pengarang');
            $table->string('kategori');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->string('isbn')->unique();
            $table->integer('jumlah_halaman');
            $table->text('deskripsi')->nullable();
            $table->string('cover_color')->default('oklch(60% .12 200)');
            $table->integer('stok')->default(1);
            $table->integer('stok_tersedia')->default(1);
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('rating_count')->default(0);
            $table->string('lokasi_rak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
