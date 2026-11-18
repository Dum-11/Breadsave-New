<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';

    protected $primaryKey = 'id_produk';

    protected $fillable = [
    'nama_produk',
    'harga_asli',
    'harga_diskon',
    'stok',
    'deskripsi',
    'expired_at',
    'foto',
    ];

    protected $casts = [
        'harga_asli' => 'decimal:2',
        'harga_diskon' => 'decimal:2',
        'stok' => 'integer',
        'expired_at' => 'datetime',
    ];
}
