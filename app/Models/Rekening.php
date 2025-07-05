<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    protected $table = 'rekening'; // Menentukan nama tabel di database

    protected $fillable = [
        'nomor_rekening',
        'atas_nama',
        'bank',
        'kode_bank',
    ];

    
}
