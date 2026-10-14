<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pesanan';
    protected $fillable = [
        'id_user', 
        'tanggal_pesan', 
        'status', 
        'total_harga'
    ];
}