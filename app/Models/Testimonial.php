<?php
// File: app/Models/Testimonial.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'name',
        'position',
        'content',
        'photo',
        'rating',
        'is_enabled',
    ];
    
    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
