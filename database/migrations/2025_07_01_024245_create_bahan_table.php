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
        Schema::create('bahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); 
            
           
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
           

            $table->enum('stok', ['Ada', 'Kosong'])->default('Ada'); 

            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif'); 

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan');
    }
};