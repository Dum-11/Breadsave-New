<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeranjangItem extends Model
{
    use HasFactory;

    // Sesuaikan nama tabel jika di database bernama 'keranjang_items' atau 'keranjangs'
    protected $table = 'keranjang_items'; 

    protected $fillable = [
        'id_user',
        'id_produk',
        'jumlah',
    ];

    // Relasi ke tabel Produk (pastikan foreign key sesuai dengan struktur tabel Anda)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk'); 
    }
}