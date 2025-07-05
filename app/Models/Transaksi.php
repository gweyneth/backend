<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; // Menentukan nama tabel di database

    protected $fillable = [
        'no_transaksi',
        'pelanggan_id',
        'tanggal_order',
        'tanggal_selesai',
        'kasir_id',
        'desainer_id',
        'total',
        'uang_muka', // Kolom baru
        'diskon',    // Kolom baru
        'sisa',      // Kolom baru
        'id_pelunasan', // Kolom baru
        'status_pengerjaan', // Kolom baru
    ];

    protected $casts = [
        'tanggal_order' => 'date',
        'tanggal_selesai' => 'date',
        'total' => 'decimal:2',
        'uang_muka' => 'decimal:2',
        'diskon' => 'decimal:2',
        'sisa' => 'decimal:2',
    ];

    
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }


   

}
