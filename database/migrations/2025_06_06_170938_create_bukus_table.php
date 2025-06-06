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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('isbn')->unique()->nullable();
            $table->string('penerbit');
            $table->integer('tahun_terbit');
            $table->integer('jumlah')->default(1);
            $table->text('deskripsi')->nullable();
            $table->string('gambar_sampul')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->foreign('prodi_id')->references('id')->on('prodis')->onDelete('set null');
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
