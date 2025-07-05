<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan'; // Nama tabel yang sesuai

    protected $fillable = [
        'nama_karyawan',
        'nik',
        'jabatan',
        'status',
        'alamat',
        'no_handphone',
        'email',
        'gaji_pokok',
        'foto', // Pastikan 'foto' ada di fillable jika ingin diisi massal
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2', // Pastikan gaji_pokok di-cast sebagai decimal
    ];
}
