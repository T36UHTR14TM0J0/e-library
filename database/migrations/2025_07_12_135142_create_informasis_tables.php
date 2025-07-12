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
        Schema::create('informasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('icon');
            $table->integer('min_kapasitas');
            $table->integer('maks_kapasitas');
            $table->string('info');
            $table->string('warna');
            $table->time('waktu_buka');
            $table->time('waktu_tutup');
            $table->text('fasilitas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasis');
    }
};
