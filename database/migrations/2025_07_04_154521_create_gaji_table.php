<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gaji_karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->onDelete('cascade');
            $table->decimal('jumlah_gaji', 15, 2); 
            $table->decimal('bonus_persen', 5, 2)->default(0); 
            $table->decimal('jumlah_bonus', 15, 2)->default(0); 
            $table->enum('status_pembayaran', ['belum dibayar', 'bayar sebagian', 'bayar setengah', 'lunas'])->default('belum dibayar');
            $table->foreignId('pengeluaran_kasbon_id')->nullable()->constrained('pengeluaran')->onDelete('set null');
            $table->decimal('sisa_gaji', 15, 2); 
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('gaji_karyawan');
    }
};
