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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama produk
            $table->string('kode')->unique(); // Kode produk, akan di-generate otomatis (misal: barcode/QR code) dan harus unik

            // Foreign key untuk Bahan
            // Pastikan tabel 'bahan' sudah ada sebelum menjalankan migrasi ini
            $table->foreignId('bahan_id')->constrained('bahan')->onDelete('cascade');

            $table->string('ukuran')->nullable(); // Ukuran produk (misal: S, M, L, atau 10x20cm)
            $table->integer('jumlah'); // Jumlah/stok produk

            $table->decimal('harga_beli', 10, 2); // Harga beli produk (total 10 digit, 2 di belakang koma)
            $table->decimal('harga_jual', 10, 2); // Harga jual produk

            // Foreign key untuk Kategori
            // Pastikan tabel 'kategori' sudah ada sebelum menjalankan migrasi ini
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');

            // Foreign key untuk Satuan
            // Pastikan tabel 'satuan' sudah ada sebelum menjalankan migrasi ini
            $table->foreignId('satuan_id')->constrained('satuan')->onDelete('cascade');

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};