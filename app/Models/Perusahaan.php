<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan'; // Menentukan nama tabel di database

    protected $fillable = [
        'nama_perusahaan',
        'email',
        'alamat',
        'alamat_tanggal',
        'no_handphone',
        'instagram',
        'facebook', 
        'twitter', 
        'youtube',
        'logo',
        'favicon',
        'logo_login',
        'logo_lunas',
        'logo_belum_lunas',
        'qr_code',
        'id_card_desain',
    ];

  
}
