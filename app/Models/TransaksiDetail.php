<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_details'; 

    protected $fillable = [
        'transaksi_id',
        'produk_id', 
        'nama_produk',
        'keterangan',
        'bahan',
        'qty',
        'ukuran',
        'satuan',
        'harga',
        'total',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga' => 'decimal:2',
        'total' => 'decimal:2',
    ];

   
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    
    public function produk() 
    {
        return $this->belongsTo(Produk::class); 
    }
}
