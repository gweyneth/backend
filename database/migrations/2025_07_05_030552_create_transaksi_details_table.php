<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade'); 
            $table->foreignId('produk_id')->nullable()->constrained('produk')->onDelete('set null'); 
            $table->string('nama_produk');
            $table->string('keterangan')->nullable();
            $table->string('bahan')->nullable(); 
            $table->integer('qty'); 
            $table->string('ukuran')->nullable(); 
            $table->string('satuan')->nullable(); 
            $table->decimal('harga', 15, 2); 
            $table->decimal('total', 15, 2); 
            $table->timestamps(); 
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
