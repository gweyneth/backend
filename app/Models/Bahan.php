<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory; 

    
    protected $table = 'bahan';

    // Kolom-kolom yang boleh diisi secara mass assignment
    protected $fillable = [
        'nama',
        'kategori_id',
        'stok',
        'status',
    ];

  
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id', 'id');

    }
}