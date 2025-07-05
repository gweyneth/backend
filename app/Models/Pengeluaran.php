<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran'; // Nama tabel yang sesuai di database

    protected $fillable = [
        'jenis_pengeluaran',
        'karyawan_id', // Pastikan ini ada jika menggunakan relasi untuk Kasbon Karyawan
        'keterangan',
        'jumlah',
        'harga',
        'total',
    ];

    protected $casts = [
        'harga' => 'decimal:2', // Casting harga ke desimal dengan 2 angka di belakang koma
        'total' => 'decimal:2', // Casting total ke desimal dengan 2 angka di belakang koma
    ];

    /**
     * Mendefinisikan relasi Many-to-One dengan model Karyawan.
     * Ini digunakan untuk mengambil data karyawan jika jenis pengeluaran adalah 'Kasbon Karyawan'.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
