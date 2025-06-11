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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->foreignId('kategori_id')->constrained()->onDelete('cascade');
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('set null');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('file_url');
            $table->string('gambar_sampul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('izin_unduh')->default(false);
            $table->foreign('penerbit_id')->references('id')->on('penerbits')->onDelete('set null');
            $table->unsignedBigInteger('penerbit_id')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
