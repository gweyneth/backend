<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; 

    protected $fillable = [
        'no_transaksi',
        'pelanggan_id',
        'tanggal_order',
        'tanggal_selesai',
        'total',
        'uang_muka',
        'diskon',
        'sisa',
        'id_pelunasan',
        'status_pengerjaan',
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

    
    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
