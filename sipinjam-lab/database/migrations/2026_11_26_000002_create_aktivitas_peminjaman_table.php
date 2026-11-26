
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel aktivitas_peminjaman untuk jejak aktivitas transaksi.
     */
    public function up(): void
    {
        if (!Schema::hasTable('aktivitas_peminjaman')) {
            Schema::create('aktivitas_peminjaman', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->foreignId('peminjaman_id')
                    ->nullable()
                    ->constrained('peminjaman')
                    ->nullOnDelete();

                $table->string('aktivitas', 60);
                $table->text('keterangan')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index(['peminjaman_id', 'created_at']);
            });
        }
    }

    /**
     * Menghapus tabel aktivitas_peminjaman saat rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_peminjaman');
    }
};
