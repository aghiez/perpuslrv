<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Ubah status enum untuk tambah 'pending' dan 'ditolak'
            $table->enum('status', ['pending', 'dipinjam', 'terlambat', 'dikembalikan', 'ditolak'])
                  ->default('pending')
                  ->change();
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->constrained('anggota')->after('approved_at');
            $table->text('alasan_tolak')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['approved_at', 'approved_by', 'alasan_tolak']);
            $table->enum('status', ['dipinjam', 'terlambat', 'dikembalikan'])
                  ->default('dipinjam')
                  ->change();
        });
    }
};
