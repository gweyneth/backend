<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('email')->unique(); 
            $table->string('alamat')->nullable();
            $table->string('alamat_tanggal')->nullable(); 
            $table->string('no_handphone')->nullable();
            $table->string('instagram')->nullable();
            $table->string('logo')->nullable(); 
            $table->string('favicon')->nullable(); 
            $table->string('logo_login')->nullable();
            $table->string('logo_lunas')->nullable(); 
            $table->string('logo_belum_lunas')->nullable(); 
            $table->string('qr_code')->nullable(); 
            $table->string('id_card_desain')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
