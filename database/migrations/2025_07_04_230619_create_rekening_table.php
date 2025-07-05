<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menjalankan migrasi untuk membuat tabel 'rekening'.
     */
    public function up(): void
    {
        Schema::create('rekening', function (Blueprint $table) {
            $table->id(); // Kolom ID auto-increment sebagai primary key
            $table->string('nomor_rekening')->unique(); // Nomor rekening harus unik
            $table->string('atas_nama'); // Nama pemilik rekening
            $table->string('bank'); // Nama bank (misal: BCA, Mandiri, BRI)
            $table->string('kode_bank')->nullable(); // Kode bank (misal: 014 untuk BCA)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     * Mengembalikan migrasi (menghapus tabel 'rekening').
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening');
    }
};
