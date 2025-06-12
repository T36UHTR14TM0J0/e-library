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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Mengaitkan dengan tabel users
            $table->foreignId('buku_id')->nullable()->constrained()->onDelete('set null'); // Mengaitkan dengan tabel buku, bisa null
            $table->enum('status', ['menunggu', 'dipinjam', 'dikembalikan', 'dibatalkan']); // Status peminjaman
            $table->date('tanggal_pinjam'); // Tanggal peminjaman
            $table->date('tanggal_jatuh_tempo'); // Tanggal jatuh tempo
            $table->date('tanggal_kembali')->nullable(); // Tanggal pengembalian, bisa null
            $table->date('tanggal_setujui')->nullable(); // Tanggal persetujuan, bisa null
            $table->date('tanggal_batal')->nullable(); // Tanggal pembatalan, bisa null
            $table->decimal('denda', 10, 2)->default(0); // Denda yang dikenakan
            $table->text('catatan_pinjam')->nullable(); // Catatan saat peminjaman, bisa null
            $table->text('catatan_kembali')->nullable(); // Catatan saat pengembalian, bisa null
            $table->text('catatan_batal')->nullable(); // Catatan saat pembatalan, bisa null
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('dibatalkan_oleh')->nullable()->constrained('users')->onDelete('set null'); 
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
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
