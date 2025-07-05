<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini jika Anda menggunakan factory
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory; // Tambahkan ini jika Anda menggunakan factory

    // Karena migrasi Anda membuat tabel 'produk' (singular),
    // dan nama model adalah 'Produk', Laravel secara default akan mencari 'produks' (plural).
    // Jadi, kita perlu mendefinisikan nama tabel secara eksplisit:
    protected $table = 'produk';

    // Kolom-kolom yang boleh diisi secara mass assignment
    protected $fillable = [
        'nama',
        'kode', // Kode akan di-generate di controller/model, tapi tetap perlu di-fillable
        'bahan_id',
        'ukuran',
        'jumlah',
        'harga_beli',
        'harga_jual',
        'kategori_id',
        'satuan_id',
    ];

    // Jika Anda tidak menggunakan created_at dan updated_at, set ini menjadi false
    // public $timestamps = true; // Defaultnya true, tidak perlu ditulis jika memang true

    /**
     * Get the bahan that owns the Produk.
     */
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id', 'id');
    }

    /**
     * Get the kategori that owns the Produk.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id', 'id');
    }

    /**
     * Get the satuan that owns the Produk.
     */
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }
}