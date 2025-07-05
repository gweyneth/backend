<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menjalankan migrasi untuk membuat tabel 'pengeluaran'.
     */
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id(); // Kolom ID auto-increment sebagai primary key
            $table->string('jenis_pengeluaran'); // Menyimpan jenis pengeluaran (misal: Kasbon Karyawan, Uang Makan, dll.)
            $table->foreignId('karyawan_id')->nullable()->constrained('karyawan')->onDelete('set null');
            $table->text('keterangan')->nullable(); // Keterangan tambahan, bisa kosong
            $table->integer('jumlah'); // Jumlah item atau unit
            $table->decimal('harga', 15, 2); // Harga per unit, dengan total 15 digit dan 2 di belakang koma
            $table->decimal('total', 15, 2); // Total pengeluaran (jumlah * harga), dengan total 15 digit dan 2 di belakang koma
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
