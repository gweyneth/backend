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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id(); // ID Karyawan (Primary Key)
            $table->string('id_karyawan')->unique(); // ID Karyawan (sesuai input di form, pastikan unik)
            $table->string('nama_karyawan'); // Nama Karyawan
            $table->string('nik')->nullable(); // NIK (opsional, bisa null)
            $table->string('jabatan'); // Jabatan
            $table->enum('status', ['Tetap', 'Kontrak', 'Magang']); // Status (Tetap, Kontrak, Magang)
            $table->text('alamat'); // Alamat
            $table->string('no_handphone'); // No Handphone
            $table->string('email')->unique(); // Email (pastikan unik)
            $table->decimal('gaji_pokok', 10, 2); // Gaji Pokok (misal: 10 digit total, 2 di belakang koma)
            $table->string('foto')->nullable(); // Path foto karyawan (opsional, bisa null)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
