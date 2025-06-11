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
        Schema::create('penerbits', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penerbit', 20)->unique();
            $table->string('nama', 100);
            $table->text('alamat')->nullable();
            $table->string('kota', 50)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerbits');
    }
};
