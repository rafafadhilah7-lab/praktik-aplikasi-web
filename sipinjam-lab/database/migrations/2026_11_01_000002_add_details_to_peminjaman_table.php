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
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            $table->text('catatan_petugas')->nullable()->after('status');
            $table->timestamp('tanggal_persetujuan')->nullable()->after('catatan_petugas');
            $table->timestamp('tanggal_pengambilan')->nullable()->after('tanggal_persetujuan');
            $table->timestamp('tanggal_pengembalian')->nullable()->after('tanggal_pengambilan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'catatan_petugas',
                'tanggal_persetujuan',
                'tanggal_pengambilan',
                'tanggal_pengembalian',
            ]);
        });
    }
};
