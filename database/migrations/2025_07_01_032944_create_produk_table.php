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
            $table->string('nama'); 
            $table->string('kode')->unique(); 
            $table->foreignId('bahan_id')->constrained('bahan')->onDelete('cascade');
            $table->string('ukuran')->nullable(); 
            $table->integer('jumlah'); 
            $table->decimal('harga_beli', 10, 2); 
            $table->decimal('harga_jual', 10, 2); 
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->foreignId('satuan_id')->constrained('satuan')->onDelete('cascade');
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};