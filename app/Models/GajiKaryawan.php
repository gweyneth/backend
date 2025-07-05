<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiKaryawan extends Model
{
    use HasFactory;

    protected $table = 'gaji_karyawan';

    protected $fillable = [
        'karyawan_id',
        'jumlah_gaji',
        'bonus_persen',
        'jumlah_bonus',
        'status_pembayaran',
        'pengeluaran_kasbon_id',
        'sisa_gaji',
    ];

    protected $casts = [
        'jumlah_gaji' => 'decimal:2',
        'bonus_persen' => 'decimal:2',
        'jumlah_bonus' => 'decimal:2',
        'sisa_gaji' => 'decimal:2',
    ];

    /**
     * Get the karyawan that owns the GajiKaryawan.
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Get the pengeluaran (kasbon) associated with the GajiKaryawan.
     */
    public function kasbon()
    {
        return $this->belongsTo(Pengeluaran::class, 'pengeluaran_kasbon_id');
    }
}
