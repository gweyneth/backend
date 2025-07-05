<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'kode_pelanggan',
        'nama',
        'email',
        'alamat',
        'no_hp',
    ];
}
