<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel alat.
     */
    public function up(): void
    {
        Schema::create('alat', function (Blueprint $table) {
            // Primary key
            $table->id();

            // Informasi alat laboratorium
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->string('kategori', 50);
            $table->unsignedInteger('stok');
            $table->string('lokasi', 150);
            $table->text('deskripsi')->nullable();

            // Waktu pembuatan dan perubahan data
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migration dengan menghapus tabel alat.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
