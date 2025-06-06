<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('buku_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('ebook_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('jenis', ['fisik', 'digital']);
            $table->enum('status', ['menunggu', 'dipinjam', 'dikembalikan', 'terlambat', 'dibatalkan']);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_kembali')->nullable();
            $table->decimal('denda', 10, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
