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
        'total',
        'diskon',
        'sisa',
        'uang_muka',
        'id_pelunasan',
        'status_pengerjaan',
        'metode_pembayaran',
        'bukti_pembayaran',
        'rekening_id',
        'keterangan_pembayaran',
    ];

    protected $casts = [
        'tanggal_order' => 'date',
        'tanggal_selesai' => 'date',
        'total' => 'decimal:2',
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

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
}
