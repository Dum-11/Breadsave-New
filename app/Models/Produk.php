<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Menyesuaikan dengan nama tabel di database phpMyAdmin Anda
    protected $table = 'produks';

    // Menyesuaikan dengan primary key di database Anda
    protected $primaryKey = 'id_produk';

    // Jika primary key bertipe integer dan auto-increment
    public $incrementing = true;
    protected $keyType = 'int';

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'nama_produk',
        'harga_asli',
        'harga_diskon',
        'stok',
        'deskripsi',
        'expired_at',
        'foto',
    ];
}