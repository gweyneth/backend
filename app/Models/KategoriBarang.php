<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori';

    protected $fillable =[
        'nama',
        'status',
    ];
}
