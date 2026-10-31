<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel peminjaman.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel alat
            $table->foreignId('alat_id')
                ->constrained('alat')
                ->cascadeOnDelete();

            // Identitas peminjam
            $table->string('nama_peminjam', 100);
            $table->string('nim', 20);

            // Jadwal peminjaman
            $table->date('tanggal_pinjam');
            $table->date('tanggal_selesai');
            $table->unsignedSmallInteger('durasi');

            // Detail pengajuan
            $table->unsignedInteger('jumlah');
            $table->text('keperluan');
            $table->string('status', 20)->default('menunggu');

            // Indeks jadwal untuk pengecekan bentrok
            $table->index([
                'alat_id',
                'tanggal_pinjam',
                'tanggal_selesai',
            ]);

            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel peminjaman saat rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
