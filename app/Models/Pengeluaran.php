<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran'; 

    protected $fillable = [
        'jenis_pengeluaran',
        'karyawan_id', 
        'keterangan',
        'jumlah',
        'harga',
        'total',
        'sisa_kasbon',   
        'status_kasbon', 
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'total' => 'decimal:2',
        'sisa_kasbon' => 'decimal:2', 
    ];
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
    public function gajiKaryawan()
    {
        return $this->hasOne(GajiKaryawan::class, 'pengeluaran_kasbon_id');
    }
}
