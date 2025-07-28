<?php
// File: database/migrations/..._create_testimonials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('position')->nullable();
            $table->text('content'); 
            $table->string('photo')->nullable(); 
            $table->unsignedTinyInteger('rating')->default(5); 
            $table->boolean('is_enabled')->default(true); 
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
