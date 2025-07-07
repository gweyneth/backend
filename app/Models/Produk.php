<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini jika Anda menggunakan factory
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory; 
    protected $table = 'produk';
 
    protected $fillable = [
        'nama',
        'kode', 
        'bahan_id',
        'ukuran',
        'jumlah',
        'harga_beli',
        'harga_jual',
        'kategori_id',
        'satuan_id',
    ];

    
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id', 'id');
    }

   
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id', 'id');
    }


    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }
}