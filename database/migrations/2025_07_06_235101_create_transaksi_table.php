<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel 'transaksi' untuk menyimpan data transaksi.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id(); 
            $table->string('no_transaksi')->unique(); 
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggan')->onDelete('set null'); 
            $table->date('tanggal_order'); 
            $table->date('tanggal_selesai')->nullable(); 
            $table->decimal('total', 15, 2)->default(0); 
            $table->decimal('uang_muka', 15, 2)->default(0); 
            $table->decimal('diskon', 15, 2)->default(0); 
            $table->decimal('sisa', 15, 2)->default(0);
            $table->string('id_pelunasan')->nullable(); 
            $table->enum('status_pengerjaan', ['menunggu export', 'belum dikerjakan', 'proses desain', 'proses produksi', 'selesai'])->default('belum dikerjakan'); // Status pengerjaan

            $table->timestamps(); 
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
