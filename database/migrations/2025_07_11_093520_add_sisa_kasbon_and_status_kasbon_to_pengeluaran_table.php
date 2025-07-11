<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom 'sisa_kasbon' dan 'status_kasbon' ke tabel 'pengeluaran'.
     */
    public function up(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            // Kolom untuk melacak sisa kasbon yang belum dibayar
            // Defaultnya akan sama dengan total saat kasbon pertama kali dibuat
            $table->decimal('sisa_kasbon', 15, 2)->default(0)->after('total');

            // Kolom untuk status pembayaran kasbon itu sendiri
            // 'belum_lunas': kasbon belum dibayar sama sekali atau masih ada sisa
            // 'lunas_sebagian': kasbon sudah dibayar sebagian
            // 'lunas': kasbon sudah lunas sepenuhnya
            $table->enum('status_kasbon', ['belum_lunas', 'lunas_sebagian', 'lunas'])->default('belum_lunas')->after('sisa_kasbon');
        });
    }

    /**
     * Reverse the migrations.
     * Menghapus kolom yang ditambahkan jika migrasi di-rollback.
     */
    public function down(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn(['sisa_kasbon', 'status_kasbon']);
        });
    }
};
