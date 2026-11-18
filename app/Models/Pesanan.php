<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';

    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'id_user',
        'tanggal_pesan',
        'status',
        'total_harga',
        'waktu_pengambilan',
        'kode_pickup',
    ];

    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'total_harga' => 'decimal:2',
        'waktu_pengambilan' => 'datetime',
    ];

    // Relasi ke pengguna
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
        );
    }

    // Relasi ke detail pesanan
    public function detailPesanans()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_pesanan',
            'id_pesanan'
        );
    }
}
